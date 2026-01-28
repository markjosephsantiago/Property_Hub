<?php
$back = $_SERVER['HTTP_REFERER'] ?? '../../home.php';
?>

<a href="<?php echo htmlspecialchars($back); ?>" 
   class="btn btn-secondary" 
   data-toggle="tooltip" 
   title="Go back">
    <i class="fas fa-arrow-left"></i> Back
</a>
