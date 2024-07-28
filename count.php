<?php

include './config/config.php';
$tableName = 'patient';
$sql = "SELECT COUNT(*) AS total FROM $tableName";

// Execute the query
$result = $conn->query($sql);

// Check if query execution was successful
if ($result) {
    // Fetch the result as an associative array
    $row = $result->fetch_assoc();
    // Get the count value
    $count = $row['total'];
    // Output the count using echo
    echo "Number of rows in '$tableName': $count";
} else {
    // Output an error message if the query fails
    echo "Error: " . $conn->error;
}

// Close the connection
$conn->close();
?>
