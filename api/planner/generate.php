<?php
/**
 * Generate Wedding Budget Plan
 * POST /api/planner/generate.php
 */

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once '../../includes/config.php';
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, null, 'Method not allowed', 405);
}

// Get request body
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
$budget = isset($input['budget']) ? floatval($input['budget']) : 0;
$city = isset($input['city']) ? sanitize($input['city']) : '';
$type = isset($input['type']) ? sanitize($input['type']) : '';
$date = isset($input['date']) ? sanitize($input['date']) : null;

if ($budget < 50000 || $budget > 10000000) {
    jsonResponse(false, null, 'Budget must be between ₹50,000 and ₹1,00,00,000');
}

if (empty($city)) {
    jsonResponse(false, null, 'City is required');
}

if (!in_array($type, ['engagement', 'haldi', 'wedding', 'micro-wedding'])) {
    jsonResponse(false, null, 'Invalid event type');
}

// Budget allocation templates (percentage-based)
$allocationTemplates = [
    'engagement' => [
        ['name' => 'Venue', 'percent' => 25],
        ['name' => 'Catering', 'percent' => 30],
        ['name' => 'Outfits', 'percent' => 15],
        ['name' => 'Photography', 'percent' => 10],
        ['name' => 'Decoration', 'percent' => 10],
        ['name' => 'Gifts & Favors', 'percent' => 5],
        ['name' => 'Miscellaneous', 'percent' => 5],
    ],
    'haldi' => [
        ['name' => 'Venue', 'percent' => 20],
        ['name' => 'Catering', 'percent' => 25],
        ['name' => 'Outfits', 'percent' => 15],
        ['name' => 'Decoration', 'percent' => 20],
        ['name' => 'Photography', 'percent' => 10],
        ['name' => 'Mehendi Artist', 'percent' => 5],
        ['name' => 'Miscellaneous', 'percent' => 5],
    ],
    'wedding' => [
        ['name' => 'Venue', 'percent' => 30],
        ['name' => 'Catering', 'percent' => 30],
        ['name' => 'Outfits', 'percent' => 12],
        ['name' => 'Photography & Videography', 'percent' => 10],
        ['name' => 'Decoration & Flowers', 'percent' => 8],
        ['name' => 'Music & Entertainment', 'percent' => 5],
        ['name' => 'Invitations', 'percent' => 2],
        ['name' => 'Miscellaneous', 'percent' => 3],
    ],
    'micro-wedding' => [
        ['name' => 'Venue', 'percent' => 25],
        ['name' => 'Catering', 'percent' => 30],
        ['name' => 'Outfits', 'percent' => 20],
        ['name' => 'Photography', 'percent' => 12],
        ['name' => 'Decoration', 'percent' => 8],
        ['name' => 'Miscellaneous', 'percent' => 5],
    ],
];

// Calculate breakdown
$template = $allocationTemplates[$type];
$breakdown = [];
$totalAllocated = 0;

foreach ($template as $item) {
    $amount = round(($budget * $item['percent']) / 100);
    $breakdown[] = [
        'name' => $item['name'],
        'amount' => $amount,
        'percent' => $item['percent']
    ];
    $totalAllocated += $amount;
}

// Adjust for rounding errors
$difference = $budget - $totalAllocated;
if ($difference != 0 && !empty($breakdown)) {
    $breakdown[0]['amount'] += $difference;
}

// Generate checklist based on event type
$checklistTemplates = [
    'engagement' => [
        'Book venue (3-6 months before)',
        'Finalize guest list',
        'Order engagement outfits',
        'Book photographer',
        'Send invitations (1 month before)',
        'Arrange catering and menu tasting',
        'Plan decoration theme',
        'Book makeup artist',
        'Arrange gifts and favors',
        'Plan engagement ceremony schedule',
    ],
    'haldi' => [
        'Book venue or outdoor space',
        'Order haldi outfit (yellow attire)',
        'Arrange fresh turmeric and flowers',
        'Book photographer',
        'Plan decoration (marigold flowers, drapes)',
        'Arrange seating and shamiana',
        'Book mehendi artist if combined',
        'Plan music playlist',
        'Arrange refreshments',
        'Coordinate with family members',
    ],
    'wedding' => [
        'Book wedding venue (6-12 months before)',
        'Finalize guest list and send save-the-dates',
        'Order bridal lehenga and groom\'s sherwani',
        'Book photographer and videographer',
        'Arrange catering and finalize menu',
        'Book makeup artist and hair stylist',
        'Plan decoration and floral arrangements',
        'Book music band or DJ',
        'Order wedding invitations',
        'Book wedding car/transport',
        'Arrange accommodation for guests',
        'Plan wedding ceremony schedule',
        'Get marriage license',
        'Plan honeymoon',
        'Arrange gifts for family members',
    ],
    'micro-wedding' => [
        'Choose intimate venue',
        'Finalize small guest list (under 50)',
        'Order wedding outfits',
        'Book photographer',
        'Plan simple catering (buffet or sit-down)',
        'Arrange minimal decoration',
        'Send personalized invitations',
        'Book makeup artist',
        'Plan ceremony schedule',
        'Arrange return gifts',
    ],
];

$checklist = $checklistTemplates[$type];

// Get recommended listings based on budget
try {
    $db = getDB();

    // Calculate outfit budget from breakdown
    $outfitBudget = 0;
    foreach ($breakdown as $item) {
        if (stripos($item['name'], 'outfit') !== false) {
            $outfitBudget = $item['amount'];
            break;
        }
    }

    // Get listings within budget range
    $minPrice = $outfitBudget * 0.3; // 30% of outfit budget
    $maxPrice = $outfitBudget * 1.2; // 120% of outfit budget

    $stmt = $db->prepare("
        SELECT l.id, l.title, l.price, l.city,
               (SELECT file_path FROM listing_images WHERE listing_id = l.id ORDER BY sort_order LIMIT 1) as image_path
        FROM listings l
        WHERE l.status = 'published'
          AND l.price BETWEEN :min_price AND :max_price
          AND (l.city = :city OR l.city IS NOT NULL)
        ORDER BY
            CASE WHEN l.city = :city THEN 0 ELSE 1 END,
            l.featured DESC,
            RAND()
        LIMIT 8
    ");

    $stmt->execute([
        'min_price' => $minPrice,
        'max_price' => $maxPrice,
        'city' => $city
    ]);

    $recommendedListings = $stmt->fetchAll();

    // Format listing data
    $formattedListings = [];
    foreach ($recommendedListings as $listing) {
        $formattedListings[] = [
            'id' => $listing['id'],
            'title' => $listing['title'],
            'price' => $listing['price'],
            'city' => $listing['city'],
            'image_url' => $listing['image_path'] ? UPLOAD_URL . basename($listing['image_path']) : null,
        ];
    }

} catch (Exception $e) {
    error_log("Error fetching recommended listings: " . $e->getMessage());
    $formattedListings = [];
}

// Prepare response
$response = [
    'budget' => $budget,
    'city' => $city,
    'type' => $type,
    'date' => $date,
    'breakdown' => $breakdown,
    'checklist' => $checklist,
    'recommended_listings' => $formattedListings,
];

// Log analytics
logEvent('planner_completed', [
    'budget' => $budget,
    'city' => $city,
    'type' => $type
]);

jsonResponse(true, $response);
