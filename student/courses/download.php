<?php

    include_once('../../includes/config.php');

    if (file_exists($imagePath)) {
        // Set the appropriate headers for file download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($imagePath) . '"');
        header('Content-Length: ' . filesize($imagePath));

        // Read the file and output it to the browser
        readfile($imagePath);
        exit;
    } else {
        // Handle the case where the file doesn't exist
        echo 'File not found.';
    }
    
?>