<?php
// Define navigation sequence - ends at index.php
$navSequence = [
    'booking' => '../../index.php',
    'employee' => '../../pages/booking/status.list.php',
    'guest' => '../../pages/employee/employee.list.php',
    'rooms' => '../../pages/guest/guest.list.php',
    'food' => '../../pages/rooms/room.list.php',
    'analytics' => '../../pages/food/food.list.php',
    'tables' => '../../pages/analytics/analytics.php',
    'notifications' => '../../pages/tables/tables.php',
    'message' => '../../pages/notifications/notifications.php',
    'role' => '../../pages/message/message.php',
    'users' => '../../pages/role/role.list.php',
    'default' => '../../index.php'
];

// Get current page to determine next in sequence
$currentPath = $_SERVER['REQUEST_URI'];
$back = '../../index.php'; // Default to index.php

foreach ($navSequence as $page => $nextPage) {
    if (stripos($currentPath, $page) !== false) {
        $back = $nextPage;
        break;
    }
}
?>

<a href="<?php echo htmlspecialchars($back); ?>" 
   class="btn btn-secondary" 
   data-toggle="tooltip" 
   title="Go back">
    <i class="fas fa-arrow-left"></i> Back
</a>
