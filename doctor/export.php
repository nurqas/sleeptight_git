<?php
session_start(); // Start the session if it's not already started

// Check if the username is submitted
if(isset($_POST['html_username'])) {
    $html_username = $_POST['html_username']; // Username from HTML form
} else {
    // If the username is not available, you can use a default filename or handle the situation accordingly
    $html_username = "default_user";
}

// Database connection parameters
$servername = "localhost";
$db_username = "root"; // MySQL database username
$db_password = ""; // MySQL database password
$database = "sleeppattern";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from MySQL database
$sql = "SELECT * FROM sleeppattern";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Set headers for Excel file
    header("Content-Type: application/vnd.ms-excel");
    
    // Append user's name to the filename
    $filename = "database_export_" . $html_username . ".xls";
    header("Content-Disposition: attachment; filename=" . $filename);

    // Output Excel data
    $output = fopen("php://output", "w");
    
    // Add column headers
    $row = $result->fetch_assoc();
    fputcsv($output, array_keys($row));

    // Add data rows
    fputcsv($output, $row);
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    // Close file pointer
    fclose($output);
    exit;
} else {
    echo "No data found in the database.";
}

$conn->close();
?>
