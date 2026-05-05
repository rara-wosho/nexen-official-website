<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$success = '';
$error   = '';

// ───────────────────────────────────────────
// ADD MEMBER
// ───────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_member') {
    $full_name = trim($_POST['full_name'] ?? '');
    $position  = trim($_POST['position'] ?? '');
    $avatar_filename = null;

    if (empty($full_name) || empty($position)) {
        $error = "Full name and position are required.";
    } else {
        if (!empty($_FILES['avatar']['name'])) {
            $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $mime    = mime_content_type($_FILES['avatar']['tmp_name']);
            if (!in_array($mime, $allowed)) {
                $error = "Invalid image type. Only JPG, PNG, WEBP, and GIF are allowed.";
            } elseif ($_FILES['avatar']['size'] > 2 * 1024 * 1024) {
                $error = "Image must be under 2MB.";
            } else {
                $ext             = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                $avatar_filename = uniqid('avatar_', true) . '.' . $ext;
                $upload_dir      = 'uploads/avatars/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_dir . $avatar_filename)) {
                    $error = "Failed to upload image.";
                    $avatar_filename = null;
                }
            }
        }

        if (empty($error)) {
            try {
                $connection->beginTransaction();

                $stmt = $connection->prepare("INSERT INTO team_members (full_name, position, avatar) VALUES (:full_name, :position, :avatar) RETURNING id");
                $stmt->execute([':full_name' => $full_name, ':position' => $position, ':avatar' => $avatar_filename]);
                $member_id = $stmt->fetchColumn();

                // Assign to departments if any
                $selected_depts = $_POST['departments'] ?? [];
                if (!empty($selected_depts)) {
                    $deptStmt = $connection->prepare("INSERT INTO department_members (department_id, member_id) VALUES (:dept_id, :member_id)");
                    foreach ($selected_depts as $dept_id) {
                        $deptStmt->execute([':dept_id' => (int)$dept_id, ':member_id' => $member_id]);
                    }
                }

                $connection->commit();
                $success = "Member added successfully.";
            } catch (PDOException $e) {
                $connection->rollBack();
                $error = $e->getCode() === '23505' ? "That position already exists." : "Database error: " . $e->getMessage();
            }
        }
    }
}

// ───────────────────────────────────────────
// EDIT MEMBER
// ───────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit_member') {
    $id        = (int) ($_POST['member_id'] ?? 0);
    $full_name = trim($_POST['full_name'] ?? '');
    $position  = trim($_POST['position'] ?? '');

    if (!$id || empty($full_name) || empty($position)) {
        $error = "All fields are required.";
    } else {
        // Fetch existing avatar
        $existing = $connection->prepare("SELECT avatar FROM team_members WHERE id = :id");
        $existing->execute([':id' => $id]);
        $current = $existing->fetch(PDO::FETCH_ASSOC);
        $avatar_filename = $current['avatar'];

        if (!empty($_FILES['avatar']['name'])) {
            $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $mime    = mime_content_type($_FILES['avatar']['tmp_name']);
            if (!in_array($mime, $allowed)) {
                $error = "Invalid image type.";
            } elseif ($_FILES['avatar']['size'] > 2 * 1024 * 1024) {
                $error = "Image must be under 2MB.";
            } else {
                $ext             = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                $new_filename    = uniqid('avatar_', true) . '.' . $ext;
                $upload_dir      = 'uploads/avatars/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_dir . $new_filename)) {
                    // Delete old avatar file
                    if ($avatar_filename && file_exists($upload_dir . $avatar_filename)) {
                        unlink($upload_dir . $avatar_filename);
                    }
                    $avatar_filename = $new_filename;
                } else {
                    $error = "Failed to upload image.";
                }
            }
        }

        if (empty($error)) {
            try {
                $connection->beginTransaction();
                $stmt = $connection->prepare("UPDATE team_members SET full_name = :full_name, position = :position, avatar = :avatar WHERE id = :id");
                $stmt->execute([':full_name' => $full_name, ':position' => $position, ':avatar' => $avatar_filename, ':id' => $id]);

                // Update department associations
                $delStmt = $connection->prepare("DELETE FROM department_members WHERE member_id = :id");
                $delStmt->execute([':id' => $id]);

                $selected_depts = $_POST['departments'] ?? [];
                if (!empty($selected_depts)) {
                    $insStmt = $connection->prepare("INSERT INTO department_members (department_id, member_id) VALUES (:dept_id, :member_id)");
                    foreach ($selected_depts as $dept_id) {
                        $insStmt->execute([':dept_id' => (int)$dept_id, ':member_id' => $id]);
                    }
                }

                $connection->commit();
                $success = "Member updated successfully.";
            } catch (PDOException $e) {
                $connection->rollBack();
                $error = $e->getCode() === '23505' ? "That position already exists." : "Database error: " . $e->getMessage();
            }
        }
    }
}

// ───────────────────────────────────────────
// ADD DEPARTMENT
// ───────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_department') {
    $dept_name = trim($_POST['department_name'] ?? '');
    $dept_desc = trim($_POST['department_description'] ?? '');
    $dept_image = null;

    if (empty($dept_name)) {
        $error = "Department name is required.";
    } else {
        if (!empty($_FILES['department_image']['name'])) {
            $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $mime    = mime_content_type($_FILES['department_image']['tmp_name']);
            if (!in_array($mime, $allowed)) {
                $error = "Invalid image type.";
            } elseif ($_FILES['department_image']['size'] > 2 * 1024 * 1024) {
                $error = "Image must be under 2MB.";
            } else {
                $ext        = pathinfo($_FILES['department_image']['name'], PATHINFO_EXTENSION);
                $dept_image = uniqid('dept_', true) . '.' . $ext;
                $upload_dir = 'uploads/departments/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                if (!move_uploaded_file($_FILES['department_image']['tmp_name'], $upload_dir . $dept_image)) {
                    $error = "Failed to upload image.";
                    $dept_image = null;
                }
            }
        }

        if (empty($error)) {
            try {
                $stmt = $connection->prepare("INSERT INTO departments (department_name, department_description, department_image) VALUES (:name, :desc, :image)");
                $stmt->execute([':name' => $dept_name, ':desc' => $dept_desc, ':image' => $dept_image]);
                $success = "Department added successfully.";
            } catch (PDOException $e) {
                $error = "Database error: " . $e->getMessage();
            }
        }
    }
}

// ───────────────────────────────────────────
// EDIT DEPARTMENT
// ───────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit_department') {
    $id        = (int) ($_POST['dept_id'] ?? 0);
    $dept_name = trim($_POST['department_name'] ?? '');
    $dept_desc = trim($_POST['department_description'] ?? '');

    if (!$id || empty($dept_name)) {
        $error = "Department name is required.";
    } else {
        // Fetch existing image
        $existing = $connection->prepare("SELECT department_image FROM departments WHERE id = :id");
        $existing->execute([':id' => $id]);
        $current = $existing->fetch(PDO::FETCH_ASSOC);
        $dept_image = $current['department_image'];

        if (!empty($_FILES['department_image']['name'])) {
            $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $mime    = mime_content_type($_FILES['department_image']['tmp_name']);
            if (!in_array($mime, $allowed)) {
                $error = "Invalid image type.";
            } elseif ($_FILES['department_image']['size'] > 2 * 1024 * 1024) {
                $error = "Image must be under 2MB.";
            } else {
                $ext          = pathinfo($_FILES['department_image']['name'], PATHINFO_EXTENSION);
                $new_filename = uniqid('dept_', true) . '.' . $ext;
                $upload_dir   = 'uploads/departments/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                if (move_uploaded_file($_FILES['department_image']['tmp_name'], $upload_dir . $new_filename)) {
                    if ($dept_image && file_exists($upload_dir . $dept_image)) unlink($upload_dir . $dept_image);
                    $dept_image = $new_filename;
                } else {
                    $error = "Failed to upload image.";
                }
            }
        }

        if (empty($error)) {
            try {
                $stmt = $connection->prepare("UPDATE departments SET department_name = :name, department_description = :desc, department_image = :image WHERE id = :id");
                $stmt->execute([':name' => $dept_name, ':desc' => $dept_desc, ':image' => $dept_image, ':id' => $id]);
                $success = "Department updated successfully.";
            } catch (PDOException $e) {
                $error = "Database error: " . $e->getMessage();
            }
        }
    }
}

// ───────────────────────────────────────────
// DELETE DEPARTMENT
// ───────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_department') {
    $id = (int) ($_POST['dept_id'] ?? 0);
    if ($id) {
        try {
            $connection->beginTransaction();
            // Fetch image to delete
            $stmt = $connection->prepare("SELECT department_image FROM departments WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row && $row['department_image']) {
                $file = 'uploads/departments/' . $row['department_image'];
                if (file_exists($file)) unlink($file);
            }

            // Remove assignments first (if no cascade)
            $stmt = $connection->prepare("DELETE FROM department_members WHERE department_id = :id");
            $stmt->execute([':id' => $id]);

            $stmt = $connection->prepare("DELETE FROM departments WHERE id = :id");
            $stmt->execute([':id' => $id]);

            $connection->commit();
            $success = "Department removed successfully.";
        } catch (PDOException $e) {
            $connection->rollBack();
            $error = "Delete failed: " . $e->getMessage();
        }
    }
}

// ───────────────────────────────────────────
// DELETE MEMBER
// ───────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_member') {
    $id = (int) ($_POST['member_id'] ?? 0);
    if ($id) {
        try {
            // Fetch avatar to delete file
            $stmt = $connection->prepare("SELECT avatar FROM team_members WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row && $row['avatar']) {
                $file = 'uploads/avatars/' . $row['avatar'];
                if (file_exists($file)) unlink($file);
            }

            $stmt = $connection->prepare("DELETE FROM team_members WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $success = "Member removed successfully.";
        } catch (PDOException $e) {
            $error = "Delete failed: " . $e->getMessage();
        }
    }
}

// ───────────────────────────────────────────
// FETCH DEPARTMENTS with corresponding members
// ───────────────────────────────────────────
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
try {
    $query = "SELECT 
                d.department_name,
                d.id AS dept_id, d.department_description, d.department_image,
                tm.id, tm.full_name, tm.position, tm.avatar, tm.added_at
            FROM departments d
            LEFT JOIN department_members dm ON d.id = dm.department_id
            LEFT JOIN team_members tm ON dm.member_id = tm.id";
    
    $params = [];
    if (!empty($keyword)) {
        $query .= " WHERE tm.full_name ILIKE :keyword 
                    OR tm.position ILIKE :keyword 
                    OR d.department_name ILIKE :keyword";
        $params[':keyword'] = '%' . $keyword . '%';
    }
    
    $query .= " ORDER BY d.department_name, tm.added_at ASC";
    
    $stmt = $connection->prepare($query);
    $stmt->execute($params);

    // FETCH_GROUP organizes the data by department_name automatically
    $departmentsWithMembers = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC);

    // Also fetch ALL departments for the checkboxes
    $stmt = $connection->query("SELECT id, department_name FROM departments ORDER BY department_name ASC");
    $allDepartments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch member-department mapping for JS
    $stmt = $connection->query("SELECT member_id, department_id FROM department_members");
    $memberDepartments = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $memberDepartments[$row['member_id']][] = $row['department_id'];
    }

} catch (PDOException $e) {
    die("Error fetching team departments: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Teams</title>
    <link href="assets/img/logo.jpg" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/teams.css">

</head>

<body>
    <header class="nav">
        <div class="max-w-wrapper mx-auto w-100 py-3">
            <div class="d-flex align-items-center gap-3">
                <button onclick="window.location.href='admin_editor'" class="btn btn-outline-ghost border btn-sm">
                    <i class="bi bi-house-door"></i>
                </button>
                <h3 class="mb-0">Team Members</h3>
            </div>
        </div>
    </header>

    <div class="mx-auto w-100 max-w-wrapper pt-3 pb-5">

        <!-- Alerts -->
        <?php if (!empty($success)): ?>
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
                <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($success) ?>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
                <i class="bi bi-exclamation-circle-fill"></i> <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Toolbar -->
        <div class="d-flex align-items-center justify-content-between mb-4 gap-2 flex-wrap">
            <form action="" method="GET" class="d-flex align-items-center gap-2">
                <div class="position-relative">
                    <i class="bi bi-search position-absolute" style="left:14px;top:9px;opacity:.5"></i>
                    <input name="keyword" type="text" value="<?= htmlspecialchars($keyword) ?>"
                        class="modern-input-white rounded-3" placeholder="Search name or position">
                </div>
                <button class="btn btn-primary"><i class="bi bi-search me-2"></i>Search</button>
            </form>
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-white border" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                    <i class="bi bi-plus-circle me-2"></i>Add Department
                </button>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                    <i class="bi bi-person-plus-fill me-2"></i>Add Member
                </button>
            </div>
        </div>


        <?php if (!empty($departmentsWithMembers)): ?>
            <?php foreach ($departmentsWithMembers as $deptName => $deptMembers): 
                $firstRow = $deptMembers[0];
                $deptInfo = [
                    'id' => $firstRow['dept_id'],
                    'department_name' => $deptName,
                    'department_description' => $firstRow['department_description'],
                    'department_image' => $firstRow['department_image']
                ];
            ?>
                <div class="mb-5">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <h4 class="mb-0 fw-bold text-dark"><?= htmlspecialchars($deptName) ?></h4>
                        <div class="flex-grow-1 border-bottom" style="opacity: 0.1;"></div>
                        
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-light text-dark border fw-normal py-2 px-3">
                                <?= count(array_filter($deptMembers, function($m) { return !empty($m['id']); })) ?> members
                            </span>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm border rounded-3" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                                    <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="openEditDeptModal(<?= htmlspecialchars(json_encode($deptInfo)) ?>)">
                                        <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Department</a></li>
                                    <li><a class="dropdown-item py-2 text-danger" href="javascript:void(0)" onclick="openDeleteDeptModal(<?= $deptInfo['id'] ?>, '<?= htmlspecialchars(addslashes($deptName)) ?>')">
                                        <i class="bi bi-trash-fill me-2"></i>Delete Department</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3">
                        <?php 
                        $hasMembers = false;
                        foreach ($deptMembers as $member): 
                            if (empty($member['id'])) continue; 
                            $hasMembers = true;
                        ?>
                            <div class="col">
                                <div class="member-card h-100">
                                    <!-- Action buttons (visible on hover) -->
                                    <div class="card-actions">
                                        <button
                                            class="btn btn-light border"
                                            title="Edit"
                                            onclick="openEditModal(<?= htmlspecialchars(json_encode($member)) ?>)">
                                            <i class="bi bi-pencil-fill text-primary"></i>
                                        </button>
                                        <button
                                            class="btn btn-light border"
                                            title="Delete"
                                            onclick="openDeleteModal(<?= $member['id'] ?>, '<?= htmlspecialchars(addslashes($member['full_name'])) ?>')">
                                            <i class="bi bi-trash-fill text-danger"></i>
                                        </button>
                                    </div>

                                    <?php if (!empty($member['avatar'])): ?>
                                        <img src="uploads/avatars/<?= htmlspecialchars($member['avatar']) ?>"
                                            alt="<?= htmlspecialchars($member['full_name']) ?>"
                                            class="member-avatar">
                                    <?php else: ?>
                                        <div class="member-avatar-fallback">
                                            <?= strtoupper(substr(trim($member['full_name'] ?? 'U'), 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="member-name"><?= htmlspecialchars($member['full_name']) ?></div>
                                    <div class="member-position"><?= htmlspecialchars($member['position']) ?></div>
                                    <div class="member-date">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        <?= date("M d, Y", strtotime($member['added_at'])) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <?php if (!$hasMembers): ?>
                            <div class="col-12">
                                <p class="text-muted small italic">No members in this department.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-people"></i>
                <p class="fw-semibold">No team members or departments found.</p>
                <?php if (!empty($keyword)): ?>
                    <a href="?" class="btn btn-sm btn-outline-secondary mt-2">Clear search</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- ───────── ADD DEPARTMENT MODAL ───────── -->
    <div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle me-2 text-primary"></i>Add Department
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-3">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="add_department">

                        <div class="d-flex flex-column align-items-center mb-4">
                            <input type="file" name="department_image" id="deptImageInput" accept="image/*" class="d-none">
                            <div id="deptImagePreviewWrapper" onclick="document.getElementById('deptImageInput').click()" 
                                style="width: 100%; height: 150px; border: 2px dashed #d1d5db; border-radius: 12px; overflow: hidden; cursor: pointer; display: flex; align-items: center; justify-content: center; background: #f9fafb; position: relative;">
                                <img id="deptImagePreview" src="" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                <i class="bi bi-image placeholder-icon" id="deptImagePlaceholder" style="font-size: 3rem; color: #9ca3af;"></i>
                                <div class="hover-overlay" style="position: absolute; inset: 0; background: rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; opacity: 0; transition: 0.2s;">
                                    <i class="bi bi-camera-fill text-white" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                            <small class="text-muted mt-2">Click to upload department cover <span class="text-secondary">(optional)</span></small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Department Name <span class="text-danger">*</span></label>
                            <input type="text" name="department_name" class="form-control rounded-3" placeholder="e.g. Engineering" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="department_description" class="form-control rounded-3" rows="3" placeholder="Describe the department's role..."></textarea>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary rounded-3 px-4">
                                <i class="bi bi-plus-lg me-1"></i> Add Department
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- ───────── EDIT DEPARTMENT MODAL ───────── -->
    <div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Department
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-3">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="edit_department">
                        <input type="hidden" name="dept_id" id="editDeptId">

                        <div class="d-flex flex-column align-items-center mb-4">
                            <input type="file" name="department_image" id="editDeptImageInput" accept="image/*" class="d-none">
                            <div id="editDeptImagePreviewWrapper" onclick="document.getElementById('editDeptImageInput').click()" 
                                style="width: 100%; height: 150px; border: 2px dashed #d1d5db; border-radius: 12px; overflow: hidden; cursor: pointer; display: flex; align-items: center; justify-content: center; background: #f9fafb; position: relative;">
                                <img id="editDeptImagePreview" src="" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
                                <div class="hover-overlay" style="position: absolute; inset: 0; background: rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; opacity: 0; transition: 0.2s;">
                                    <i class="bi bi-camera-fill text-white" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                            <small class="text-muted mt-2">Click to change department cover</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Department Name <span class="text-danger">*</span></label>
                            <input type="text" name="department_name" id="editDeptName" class="form-control rounded-3" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="department_description" id="editDeptDesc" class="form-control rounded-3" rows="3"></textarea>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary rounded-3 px-4">
                                <i class="bi bi-check-lg me-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- ───────── DELETE DEPARTMENT MODAL ───────── -->
    <div class="modal fade" id="deleteDepartmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-body text-center p-4">
                    <div class="mb-3">
                        <span style="font-size:3rem;">🏢</span>
                    </div>
                    <h5 class="fw-bold mb-1">Delete Department?</h5>
                    <p class="text-muted mb-4 small">
                        Are you sure you want to delete <strong id="deleteDeptName"></strong>? Members will be unassigned but not deleted.
                    </p>
                    <form action="" method="POST">
                        <input type="hidden" name="action" value="delete_department">
                        <input type="hidden" name="dept_id" id="deleteDeptId">
                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger rounded-3 px-4">
                                <i class="bi bi-trash-fill me-1"></i> Delete
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- ───────── ADD MODAL ───────── -->
    <div class="modal fade" id="addMemberModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-person-plus-fill me-2 text-success"></i>Add Team Member
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-3">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="add_member">

                        <div class="d-flex flex-column align-items-center mb-4">
                            <input type="file" name="avatar" id="avatarInput" accept="image/*" class="d-none">
                            <div id="avatarPreviewWrapper" onclick="document.getElementById('avatarInput').click()">
                                <img id="avatarPreview" src="" alt="Preview">
                                <i class="bi bi-person-circle placeholder-icon" id="avatarPlaceholder"></i>
                                <div class="hover-overlay"><i class="bi bi-camera-fill"></i></div>
                            </div>
                            <small class="text-muted mt-2">Click to upload photo <span class="text-secondary">(optional)</span></small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control rounded-3"
                                placeholder="e.g. Juan dela Cruz"
                                value="<?= isset($_POST['full_name']) && !empty($error) && ($_POST['action'] ?? '') === 'add_member' ? htmlspecialchars($_POST['full_name']) : '' ?>"
                                required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Position <span class="text-danger">*</span></label>
                            <input type="text" name="position" class="form-control rounded-3"
                                placeholder="e.g. Lead Developer"
                                value="<?= isset($_POST['position']) && !empty($error) && ($_POST['action'] ?? '') === 'add_member' ? htmlspecialchars($_POST['position']) : '' ?>"
                                required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold d-block mb-2">Departments</label>
                            <div class="row g-2">
                                <?php if (!empty($allDepartments)): ?>
                                    <?php foreach ($allDepartments as $dept): ?>
                                        <div class="col-6">
                                            <label class="form-check-label border w-100 px-3 py-2 rounded-3 fw-medium mb-0" for="dept_<?= $dept['id'] ?>" style="cursor:pointer">
                                                <input class="form-check-input ms-0 me-2" type="checkbox" name="departments[]" 
                                                    value="<?= $dept['id'] ?>" id="dept_<?= $dept['id'] ?>">
                                                    <span><?= htmlspecialchars($dept['department_name']) ?></span>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="col-12">
                                        <p class="text-muted small italic">No departments available.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success rounded-3 px-4">
                                <i class="bi bi-plus-lg me-1"></i> Add Member
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ───────── EDIT MODAL ───────── -->
    <div class="modal fade" id="editMemberModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-fill me-2 text-primary"></i>Edit Team Member
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-3">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="edit_member">
                        <input type="hidden" name="member_id" id="editMemberId">

                        <div class="d-flex flex-column align-items-center mb-4">
                            <input type="file" name="avatar" id="editAvatarInput" accept="image/*" class="d-none">
                            <div id="editAvatarPreviewWrapper" onclick="document.getElementById('editAvatarInput').click()">
                                <img id="editAvatarPreview" src="" alt="Preview">
                                <div class="hover-overlay"><i class="bi bi-camera-fill"></i></div>
                            </div>
                            <small class="text-muted mt-2">Click to change photo <span class="text-secondary">(optional)</span></small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" id="editFullName" class="form-control rounded-3" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Position <span class="text-danger">*</span></label>
                            <input type="text" name="position" id="editPosition" class="form-control rounded-3" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold d-block mb-2">Departments</label>
                            <div class="row g-2">
                                <?php if (!empty($allDepartments)): ?>
                                    <?php foreach ($allDepartments as $dept): ?>
                                        <div class="col-6">
                                            <div class="form-check border rounded-3 p-2 px-3 h-100 d-flex align-items-center">
                                                <input class="form-check-input ms-0 me-2 edit-dept-checkbox" type="checkbox" name="departments[]" 
                                                    value="<?= $dept['id'] ?>" id="edit_dept_<?= $dept['id'] ?>">
                                                <label class="form-check-label small fw-medium mb-0" for="edit_dept_<?= $dept['id'] ?>" style="cursor:pointer">
                                                    <?= htmlspecialchars($dept['department_name']) ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="col-12">
                                        <p class="text-muted small italic">No departments available.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary rounded-3 px-4">
                                <i class="bi bi-check-lg me-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- ───────── DELETE MODAL ───────── -->
    <div class="modal fade" id="deleteMemberModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-body text-center p-4">
                    <div class="mb-3">
                        <span style="font-size:3rem;">🗑️</span>
                    </div>
                    <h5 class="fw-bold mb-1">Remove Member?</h5>
                    <p class="text-muted mb-4">
                        You are about to remove <strong id="deleteMemberName"></strong>. This action cannot be undone.
                    </p>
                    <form action="" method="POST">
                        <input type="hidden" name="action" value="delete_member">
                        <input type="hidden" name="member_id" id="deleteMemberId">
                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger rounded-3 px-4">
                                <i class="bi bi-trash-fill me-1"></i> Remove
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        // ── Edit department modal
        function openEditDeptModal(dept) {
            document.getElementById('editDeptId').value = dept.id;
            document.getElementById('editDeptName').value = dept.department_name;
            document.getElementById('editDeptDesc').value = dept.department_description || '';
            
            const preview = document.getElementById('editDeptImagePreview');
            preview.src = dept.department_image ? 'uploads/departments/' + dept.department_image : '';
            preview.style.display = dept.department_image ? 'block' : 'none';

            new bootstrap.Modal(document.getElementById('editDepartmentModal')).show();
        }

        document.getElementById('editDeptImageInput')?.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                const preview = document.getElementById('editDeptImagePreview');
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });

        // ── Delete department modal
        function openDeleteDeptModal(id, name) {
            document.getElementById('deleteDeptId').value = id;
            document.getElementById('deleteDeptName').textContent = name;
            new bootstrap.Modal(document.getElementById('deleteDepartmentModal')).show();
        }

        // ── Add department modal: image preview
        document.getElementById('deptImageInput')?.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                const preview = document.getElementById('deptImagePreview');
                preview.src = e.target.result;
                preview.style.display = 'block';
                document.getElementById('deptImagePlaceholder').style.display = 'none';
            };
            reader.readAsDataURL(file);
        });

        // ── Add modal: avatar preview
        document.getElementById('avatarInput').addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                const preview = document.getElementById('avatarPreview');
                preview.src = e.target.result;
                preview.style.display = 'block';
                document.getElementById('avatarPlaceholder').style.display = 'none';
            };
            reader.readAsDataURL(file);
        });

        const memberDepartments = <?= json_encode($memberDepartments) ?>;

        // ── Edit modal: populate fields
        function openEditModal(member) {
            document.getElementById('editMemberId').value = member.id;
            document.getElementById('editFullName').value = member.full_name;
            document.getElementById('editPosition').value = member.position;

            const preview = document.getElementById('editAvatarPreview');
            preview.src = member.avatar ?
                'uploads/avatars/' + member.avatar :
                'assets/img/default-avatar.png'; // fallback placeholder image

            // Clear and set checkboxes
            document.querySelectorAll('.edit-dept-checkbox').forEach(cb => cb.checked = false);
            const depts = memberDepartments[member.id] || [];
            depts.forEach(deptId => {
                const cb = document.getElementById('edit_dept_' + deptId);
                if (cb) cb.checked = true;
            });

            new bootstrap.Modal(document.getElementById('editMemberModal')).show();
        }

        // ── Edit modal: live avatar preview on new file select
        document.getElementById('editAvatarInput').addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('editAvatarPreview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        });

        // ── Delete modal: populate name + id
        function openDeleteModal(id, name) {
            document.getElementById('deleteMemberId').value = id;
            document.getElementById('deleteMemberName').textContent = name;
            new bootstrap.Modal(document.getElementById('deleteMemberModal')).show();
        }

        // ── Re-open add modal on POST error
        <?php if (!empty($error) && $_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_member'): ?>
            document.addEventListener('DOMContentLoaded', () => {
                new bootstrap.Modal(document.getElementById('addMemberModal')).show();
            });
        <?php endif; ?>

        // ── Re-open edit modal on POST error
        <?php if (!empty($error) && $_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit_member'): ?>
            document.addEventListener('DOMContentLoaded', () => {
                new bootstrap.Modal(document.getElementById('editMemberModal')).show();
            });
        <?php endif; ?>
    </script>
</body>

</html>