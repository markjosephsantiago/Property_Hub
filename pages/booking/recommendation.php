<?php
include '../../includes/conn.php';

$room_type = $_POST['room_type'] ?? '';

if (!$room_type) {
    echo "<div class='alert alert-warning mt-3'>No room type selected for recommendation.</div>";
    exit();
}

// Get clusters that have rooms of this type
$cluster_query = "
    SELECT DISTINCT cluster_id 
    FROM tbl_recommendation_clusters rc
    JOIN tbl_rooms r ON rc.room_id = r.room_id
    WHERE r.room_type = '$room_type'
";
$cluster_result = mysqli_query($conn, $cluster_query);

$cluster_ids = [];
while ($row = mysqli_fetch_assoc($cluster_result)) {
    $cluster_ids[] = $row['cluster_id'];
}

if (!empty($cluster_ids)) {
    $ids_str = implode(',', $cluster_ids);

    // Fetch recommended rooms from these clusters
    $rec_query = "
        SELECT DISTINCT r.*
        FROM tbl_recommendation_clusters rc
        JOIN tbl_rooms r ON rc.room_id = r.room_id
        WHERE rc.cluster_id IN ($ids_str)
        ORDER BY RAND()
        LIMIT 5
    ";
    $rec_result = mysqli_query($conn, $rec_query);

    if (mysqli_num_rows($rec_result) > 0) {
        echo "<div class='alert alert-info mt-3'><strong>Recommended Rooms:</strong></div>";
        echo "<ul class='list-group mb-3'>";

        while ($room = mysqli_fetch_assoc($rec_result)) {

            // Skip if missing or invalid data
            if (empty($room['room_type']) || ($room['price'] ?? 0) <= 0 || ($room['capacity'] ?? 0) <= 0) {
                continue;
            }

            // Add class 'recommend-room' and data attribute
            echo "
            <li class='list-group-item recommend-room' data-room-id='{$room['room_id']}' style='cursor:pointer'>
                <strong>Room " . htmlspecialchars($room['room_number']) . "</strong><br>
                <span>Type: " . htmlspecialchars($room['room_type']) . "</span><br>
                <span>💰 Price: ₱" . number_format($room['price']) . "</span><br>
                <span>🧍 Capacity: " . htmlspecialchars($room['capacity']) . " person(s)</span>
            </li>
            ";
        }

        echo "</ul>";

        // Add JavaScript for click handling
        echo "
        <script>
        $(document).ready(function(){
            // Click on recommended room
            $(document).on('click', '.recommend-room', function(){
                var roomId = $(this).data('room-id');
                $('#room_id').val(roomId); // Set hidden input

                // Highlight selected room
                $('.recommend-room').removeClass('bg-primary text-white');
                $(this).addClass('bg-primary text-white');
            });
        });
        </script>
        ";

    } else {
        echo "<div class='alert alert-secondary mt-3'>No recommended rooms found for this type.</div>";
    }

} else {
    echo "<div class='alert alert-warning mt-3'>No cluster data found for this room type.</div>";
}
