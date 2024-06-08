<?php
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the PDF path is provided in the form submission
    if (isset($_POST["pdf_path"])) {
        // Get the PDF path from the form data
        $pdf_path = $_POST["pdf_path"];

        // Validate the PDF path
        if (file_exists($pdf_path)) {
            // Set Content-Type header
            header("Content-Type: application/pdf");

            // Set Content-Disposition header to force download with specified filename
            header("Content-Disposition: attachment; filename=\"" . basename($pdf_path) . "\"");

            // Read the PDF file and output its contents
            readfile($pdf_path);

            // Exit to prevent further output
            exit;
        } else {
            // If the PDF file does not exist, display an error message
            echo "Error: PDF file not found.";
        }
    } else {
        // If the PDF path is not provided, display an error message
        echo "Error: PDF path not provided.";
    }
} else {
    // If the form has not been submitted via POST method, display an error message
    echo "Error: Invalid request.";
}
?>
