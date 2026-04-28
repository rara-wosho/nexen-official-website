<?php
session_start();
require_once 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$keyword = '';
if (isset($_GET['keyword'])) {
    if ($_GET['keyword'] !== "") {
        $keyword = $_GET['keyword'];
    }
}

// Fetch bookings
try {
    if (!empty($keyword)) {
        $query = "SELECT * FROM demo_bookings 
                  WHERE organization ILIKE :keyword
                     OR address ILIKE :keyword
                     OR contact_person ILIKE :keyword
                  ORDER BY created_at DESC";

        $stmt = $connection->prepare($query);
        $stmt->execute([
            ':keyword' => '%' . $keyword . '%'
        ]);
    } else {
        $query = "SELECT * FROM demo_bookings ORDER BY created_at DESC";
        $stmt = $connection->prepare($query);
        $stmt->execute();
    }

    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching bookings: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Bookings</title>

    <!-- Favicons -->
    <link href="assets/img/logo.jpg" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bookings.css">
</head>

<body>

    <header class="nav">
        <div class="max-w-wrapper mx-auto w-100 py-3">
            <div class="d-flex align-items-center gap-3">
                <button onclick="window.location.href='admin_editor'" class="btn btn-outline-ghost border btn-sm"><i class="bi bi-house-door"></i></button>
                <h3 class="mb-0">Demo Bookings</h3>
            </div>
        </div>
    </header>
    <div class="mx-auto w-100 max-w-wrapper pt-3 pb-5">
        <div class="rounded-3 w-100 table-wrapper">
            <div class="d-flex align-items-center mb-3">
                <form action="" class="w-100 d-flex align-items-center justify-content-end gap-2">
                    <div class="position-relative">
                        <i class="bi bi-search position-absolute" style="left: 14px; top:9px; opacity:.5"></i>
                        <input
                            name="keyword"
                            type="text"
                            value="<?= htmlspecialchars($keyword) ?>"
                            class="modern-input-white rounded-3"
                            placeholder="Search organization, address, or contact person">
                    </div>
                    <button class="btn btn-primary"><i class="bi bi-search me-2"></i> Search</button>
                </form>
            </div>
            <table class="table align-middle w-100 rounded-3">
                <thead class="table-secondary">
                    <tr class="py-4">
                        <th class="py-3 px-4">#</th>
                        <th class="py-3">Organization</th>
                        <th class="py-3">Employees</th>
                        <th class="py-3">Contact Person</th>
                        <th class="py-3">Contact Number</th>
                        <th class="py-3">Email</th>
                        <th class="py-3">Address</th>
                        <th class="py-3">Priority Code</th>
                        <th class="py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($bookings)): ?>
                        <?php foreach ($bookings as $index => $booking): ?>
                            <tr>
                                <td>
                                    <p class="px-3 mb-0 fs-6">
                                        <?= $index + 1 ?>
                                    </p>
                                </td>
                                <td class="">
                                    <div class="d-flex align-items-center gap-3">
                                        <?php
                                        $firstLetter = strtoupper(substr(trim($booking['organization']), 0, 1));
                                        ?>
                                        <span class="org-avatar rounded-circle d-center border" style="<?= $booking['is_read'] ? '' : 'border:2px solid rgb(31, 105, 232) !important' ?>">
                                            <span class="avatar-circle fw-bold"><?= htmlspecialchars($firstLetter) ?></span>
                                        </span>
                                        <div class="d-flex flex-column">
                                            <span class="<?= $booking['is_read'] ? 'text-muted' : 'fw-bold' ?>">
                                                <?= htmlspecialchars($booking['organization']) ?>
                                            </span>
                                            <span class="fs-7 <?= $booking['is_read'] ? 'text-muted' : 'fw-bold' ?>">Sent at: <?= date("M d, Y", strtotime($booking['created_at'])) ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($booking['number_of_employees']) ?></td>
                                <td><?= htmlspecialchars($booking['contact_person']) ?></td>
                                <td>
                                    <?= htmlspecialchars($booking['contact_number']) ?>
                                </td>
                                <td><?= htmlspecialchars($booking['email']) ?></td>
                                <td><?= htmlspecialchars($booking['address']) ?></td>
                                <td><?= htmlspecialchars($booking['priority_reference_code']) ?></td>
                                <td>
                                    <form method="POST" action="mark-as-read.php" class="position-relative">
                                        <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                                        <button type="button" class="btn btn-sm mark-read-btn">
                                            <i class="bi bi-three-dots"></i>
                                        </button>

                                        <div class="mark-read-pop bg-white p-3 rounded-3">
                                            <button class="btn btn-sm btn-success">Mark as Read</button>
                                        </div>
                                    </form>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">No bookings found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>