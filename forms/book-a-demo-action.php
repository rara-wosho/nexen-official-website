<?php
// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $subject = trim($_POST["subject"]);
    $message = trim($_POST["message"]);

    // Validate input
    $errors = [];

    if (empty($name)) {
        $errors[] = "Name is required";
    }

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($subject)) {
        $errors[] = "Subject is required";
    }

    if (empty($message)) {
        $errors[] = "Message is required";
    }

    // If there are no errors, proceed with sending email
    if (empty($errors)) {
        // Set recipient email address
        $to = "info@nexen.com.ph"; // Replace with your actual email

        // Set email headers
        $headers = "From: " . $email . "\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        // Email content
        $email_content = "
            <html>
            <head>
                <title>New Contact Form Submission</title>
            </head>
            <body>
                <h2>New Contact Form Submission</h2>
                <p><strong>Name:</strong> {$name}</p>
                <p><strong>Email:</strong> {$email}</p>
                <p><strong>Subject:</strong> {$subject}</p>
                <p><strong>Message:</strong></p>
                <p>{$message}</p>
            </body>
            </html>
        ";

        // Send email
        if (mail($to, $subject, $email_content, $headers)) {
            // Return success response
            echo json_encode([
                'status' => 'success',
                'message' => 'Your message has been sent successfully.'
            ]);
        } else {
            // Return error response
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to send message. Please try again later.'
            ]);
        }
    } else {
        // Return validation errors
        echo json_encode([
            'status' => 'error',
            'message' => implode('<br>', $errors)
        ]);
    }
} else {
    // Return error if not POST request
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}
?>