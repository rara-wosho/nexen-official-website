<?php

header("Content-Type: application/json");
session_start();
require_once 'db_connect.php';

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Method not allowed"]);
    exit;
}

// Helper: sanitize input
function sanitize($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Collect & sanitize inputs
$organization = sanitize($_POST['organization'] ?? '');
$employees = sanitize($_POST['number_of_employees'] ?? '');
$address = sanitize($_POST['address'] ?? '');
$contact_person = sanitize($_POST['contact_person'] ?? '');
$contact_number = sanitize($_POST['contact_number'] ?? '');
$email = sanitize($_POST['email'] ?? '');
$priority_code = sanitize($_POST['priority_reference_code'] ?? '');

// Validation
$errors = [];

// Required fields
if (empty($organization)) $errors[] = "Organization is required.";
if (empty($employees)) $errors[] = "Number of employees is required.";
if (empty($address)) $errors[] = "Address is required.";
if (empty($contact_person)) $errors[] = "Contact person is required.";
if (empty($contact_number)) $errors[] = "Contact number is required.";
if (empty($email)) $errors[] = "Email is required.";

// Email validation
if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
}

// Employees must be numeric
if (!empty($employees) && !is_numeric($employees)) {
    $errors[] = "Number of employees must be a number.";
}

// Contact number basic validation (PH format flexible)
if (!empty($contact_number) && !preg_match('/^[0-9+\-\s]{10,15}$/', $contact_number)) {
    $errors[] = "Invalid contact number.";
}

// If errors exist
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "errors" => $errors
    ]);
    exit;
}

// Insert into database
try {
    $query = "INSERT INTO demo_bookings 
        (organization, number_of_employees, address, contact_person, contact_number, email, priority_reference_code)
        VALUES 
        (:organization, :employees, :address, :contact_person, :contact_number, :email, :priority_code)";

    $stmt = $connection->prepare($query);

    $stmt->execute([
        ':organization' => $organization,
        ':employees' => (int)$employees,
        ':address' => $address,
        ':contact_person' => $contact_person,
        ':contact_number' => $contact_number,
        ':email' => $email,
        ':priority_code' => $priority_code ?: null
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Database error",
        "error" => $e->getMessage()
    ]);
    exit;
}

echo json_encode([
    "success" => true,
    "message" => "Form submitted successfully!",
    "data" => [
        "organization" => $organization,
        "employees" => $employees,
        "address" => $address,
        "contact_person" => $contact_person,
        "contact_number" => $contact_number,
        "email" => $email,
        "priority_code" => $priority_code
    ]
]);

// Check if the request method is POST
// if ($_SERVER["REQUEST_METHOD"] == "POST") {

//     echo $_POST;
    
//     // Get form data
//     $name = trim($_POST["name"]);
//     $email = trim($_POST["email"]);
//     $subject = trim($_POST["subject"]);
//     $message = trim($_POST["message"]);

//     // Validate input
//     $errors = [];

//     if (empty($name)) {
//         $errors[] = "Name is required";
//     }

//     if (empty($email)) {
//         $errors[] = "Email is required";
//     } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//         $errors[] = "Invalid email format";
//     }

//     if (empty($subject)) {
//         $errors[] = "Subject is required";
//     }

//     if (empty($message)) {
//         $errors[] = "Message is required";
//     }

//     // If there are no errors, proceed with sending email
//     if (empty($errors)) {
//         // Set recipient email address
//         $to = "info@nexen.com.ph"; // Replace with your actual email

//         // Set email headers
//         $headers = "From: " . $email . "\r\n";
//         $headers .= "Reply-To: " . $email . "\r\n";
//         $headers .= "MIME-Version: 1.0\r\n";
//         $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

//         // Email content
//         $email_content = "
//             <html>
//             <head>
//                 <title>New Contact Form Submission</title>
//             </head>
//             <body>
//                 <h2>New Contact Form Submission</h2>
//                 <p><strong>Name:</strong> {$name}</p>
//                 <p><strong>Email:</strong> {$email}</p>
//                 <p><strong>Subject:</strong> {$subject}</p>
//                 <p><strong>Message:</strong></p>
//                 <p>{$message}</p>
//             </body>
//             </html>
//         ";

//         // Send email
//         if (mail($to, $subject, $email_content, $headers)) {
//             // Return success response
//             echo json_encode([
//                 'status' => 'success',
//                 'message' => 'Your message has been sent successfully.'
//             ]);
//         } else {
//             // Return error response
//             echo json_encode([
//                 'status' => 'error',
//                 'message' => 'Failed to send message. Please try again later.'
//             ]);
//         }
//     } else {
//         // Return validation errors
//         echo json_encode([
//             'status' => 'error',
//             'message' => implode('<br>', $errors)
//         ]);
//     }
// } else {
//     // Return error if not POST request
//     echo json_encode([
//         'status' => 'error',
//         'message' => 'Invalid request method.'
//     ]);
// }
