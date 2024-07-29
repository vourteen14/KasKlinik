<?php
include './config/config.php';
$tableName = 'patient';

try {
    // SQL query to count rows
    $sql = "SELECT COUNT(*) AS total FROM $tableName";

    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Fetch the result as an associative array
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the count value
    $count = $row['total'];

    // Output the count using echo
    return "$count";
} catch (PDOException $e) {
    // Output an error message if the query fails
    echo "Error: " . $e->getMessage();
}
?>
