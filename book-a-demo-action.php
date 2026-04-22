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

if (empty($organization)) $errors[] = "Organization is required.";
if (empty($employees)) $errors[] = "Number of employees is required.";
if (empty($address)) $errors[] = "Address is required.";
if (empty($contact_person)) $errors[] = "Contact person is required.";
if (empty($contact_number)) $errors[] = "Contact number is required.";
if (empty($email)) $errors[] = "Email is required.";

if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
}

if (!empty($employees) && !is_numeric($employees)) {
    $errors[] = "Number of employees must be a number.";
}

if (!empty($contact_number) && !preg_match('/^[0-9+\-\s]{10,15}$/', $contact_number)) {
    $errors[] = "Invalid contact number.";
}

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

//
// ✅ EMAIL NOTIFICATION (ADMIN)
//
$to = "91ig815q@gmail.com"; // CHANGE THIS
$subject = "New Demo Booking Request";

$headers = "From: Nexen Website <no-reply@nexen.com>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

$message = "
<html>
<body>
    <h2>New Demo Booking Received</h2>
    <p><strong>Organization:</strong> {$organization}</p>
    <p><strong>Employees:</strong> {$employees}</p>
    <p><strong>Contact Person:</strong> {$contact_person}</p>
    <p><strong>Email:</strong> {$email}</p>
    <p><strong>Contact Number:</strong> {$contact_number}</p>
    <p><strong>Address:</strong> {$address}</p>
    <p><strong>Priority Code:</strong> {$priority_code}</p>
</body>
</html>
";

// Send email (non-blocking behavior)
@mail($to, $subject, $message, $headers); // @ prevents breaking response if email fails


//
// ✅ FINAL RESPONSE
//
echo json_encode([
    "success" => true,
    "message" => "Form submitted successfully!"
]);
