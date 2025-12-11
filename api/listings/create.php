<?php
/**
 * Create New Listing
 * POST /api/listings/create.php
 */

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once '../../includes/config.php';
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, null, 'Method not allowed', 405);
}

// Check authentication
if (!isLoggedIn()) {
    jsonResponse(false, null, 'Authentication required', 401);
}

$userId = getCurrentUserId();

// Validate inputs
$category = isset($_POST['category']) ? sanitize($_POST['category']) : '';
$title = isset($_POST['title']) ? sanitize($_POST['title']) : '';
$description = isset($_POST['description']) ? sanitize($_POST['description']) : '';
$condition = isset($_POST['condition']) ? sanitize($_POST['condition']) : '';
$price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
$city = isset($_POST['city']) ? sanitize($_POST['city']) : '';
$contactMethod = isset($_POST['contact_method']) ? sanitize($_POST['contact_method']) : '';
$contactValue = isset($_POST['contact_value']) ? sanitize($_POST['contact_value']) : '';

// Validation
$errors = [];

if (!in_array($category, ['bridal_lehenga', 'groom_sherwani', 'bridesmaid', 'accessories', 'jewelry', 'other'])) {
    $errors[] = 'Invalid category';
}

if (empty($title) || strlen($title) < 10) {
    $errors[] = 'Title must be at least 10 characters';
}

if (empty($description) || strlen($description) < 50) {
    $errors[] = 'Description must be at least 50 characters';
}

if (!in_array($condition, ['new', 'like_new', 'gently_used', 'used'])) {
    $errors[] = 'Invalid condition';
}

if ($price < 500 || $price > 1000000) {
    $errors[] = 'Price must be between ₹500 and ₹10,00,000';
}

if (empty($city)) {
    $errors[] = 'City is required';
}

if (!in_array($contactMethod, ['whatsapp', 'phone', 'email', 'app'])) {
    $errors[] = 'Invalid contact method';
}

if (empty($contactValue)) {
    $errors[] = 'Contact value is required';
}

// Validate images
if (!isset($_FILES['images']) || empty($_FILES['images']['name'][0])) {
    $errors[] = 'At least 3 images are required';
} else {
    $imageCount = count(array_filter($_FILES['images']['name']));
    if ($imageCount < 3 || $imageCount > 6) {
        $errors[] = 'Please upload 3-6 images';
    }
}

if (!empty($errors)) {
    jsonResponse(false, null, implode(', ', $errors));
}

try {
    $db = getDB();
    $db->beginTransaction();

    // Insert listing
    $stmt = $db->prepare("
        INSERT INTO listings
        (seller_id, title, description, category, price, city, condition_type, status, contact_method, contact_value)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', ?, ?)
    ");

    $stmt->execute([
        $userId,
        $title,
        $description,
        $category,
        $price,
        $city,
        $condition,
        $contactMethod,
        $contactValue
    ]);

    $listingId = $db->lastInsertId();

    // Create listing folder
    $yearMonth = date('Y/m');
    $uploadPath = UPLOAD_DIR . $yearMonth . '/' . $listingId;

    if (!file_exists($uploadPath)) {
        mkdir($uploadPath, 0755, true);
    }

    // Process and save images
    $imageFiles = $_FILES['images'];
    $uploadedImages = [];

    for ($i = 0; $i < count($imageFiles['name']); $i++) {
        if (empty($imageFiles['name'][$i])) {
            continue;
        }

        $file = [
            'name' => $imageFiles['name'][$i],
            'type' => $imageFiles['type'][$i],
            'tmp_name' => $imageFiles['tmp_name'][$i],
            'error' => $imageFiles['error'][$i],
            'size' => $imageFiles['size'][$i]
        ];

        // Validate file
        if ($file['error'] !== UPLOAD_ERR_OK) {
            continue;
        }

        if ($file['size'] > MAX_FILE_SIZE) {
            continue;
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, ALLOWED_EXTENSIONS)) {
            continue;
        }

        // Generate unique filename
        $fileName = uniqid() . '_' . time() . '.' . $extension;
        $filePath = $uploadPath . '/' . $fileName;
        $relativePath = $yearMonth . '/' . $listingId . '/' . $fileName;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            $uploadedImages[] = [
                'path' => $relativePath,
                'name' => $file['name']
            ];
        }
    }

    // Insert image records
    if (count($uploadedImages) < 3) {
        throw new Exception('Failed to upload minimum 3 images');
    }

    $stmt = $db->prepare("
        INSERT INTO listing_images (listing_id, file_path, file_name, sort_order)
        VALUES (?, ?, ?, ?)
    ");

    foreach ($uploadedImages as $index => $image) {
        $stmt->execute([
            $listingId,
            $image['path'],
            $image['name'],
            $index
        ]);
    }

    $db->commit();

    // Log analytics
    logEvent('listing_submitted', [
        'listing_id' => $listingId,
        'category' => $category,
        'price' => $price
    ], $userId);

    // TODO: Send email notification to admin for moderation

    jsonResponse(true, [
        'listing_id' => $listingId,
        'status' => 'pending',
        'message' => 'Listing submitted successfully. It will be reviewed and published within 24 hours.'
    ]);

} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    error_log("Listing Creation Error: " . $e->getMessage());
    jsonResponse(false, null, 'Failed to create listing: ' . $e->getMessage());
}
