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
                $stmt = $connection->prepare("INSERT INTO team_members (full_name, position, avatar) VALUES (:full_name, :position, :avatar)");
                $stmt->execute([':full_name' => $full_name, ':position' => $position, ':avatar' => $avatar_filename]);
                $success = "Member added successfully.";
            } catch (PDOException $e) {
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
                $stmt = $connection->prepare("UPDATE team_members SET full_name = :full_name, position = :position, avatar = :avatar WHERE id = :id");
                $stmt->execute([':full_name' => $full_name, ':position' => $position, ':avatar' => $avatar_filename, ':id' => $id]);
                $success = "Member updated successfully.";
            } catch (PDOException $e) {
                $error = $e->getCode() === '23505' ? "That position already exists." : "Database error: " . $e->getMessage();
            }
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
// FETCH MEMBERS
// ───────────────────────────────────────────
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
try {
    if (!empty($keyword)) {
        $query = "SELECT * FROM team_members WHERE full_name ILIKE :keyword OR position ILIKE :keyword ORDER BY added_at asc";
        $stmt  = $connection->prepare($query);
        $stmt->execute([':keyword' => '%' . $keyword . '%']);
    } else {
        $stmt = $connection->prepare("SELECT * FROM team_members ORDER BY added_at asc");
        $stmt->execute();
    }
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching team members: " . $e->getMessage());
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
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                <i class="bi bi-person-plus-fill me-2"></i>Add Member
            </button>
        </div>

        <!-- Cards -->
        <?php if (!empty($members)): ?>
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3">
                <?php foreach ($members as $member): ?>
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
                                    <?= strtoupper(substr(trim($member['full_name']), 0, 1)) ?>
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
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-people"></i>
                <p class="fw-semibold">No team members found.</p>
                <?php if (!empty($keyword)): ?>
                    <a href="?" class="btn btn-sm btn-outline-secondary mt-2">Clear search</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
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

        // ── Edit modal: populate fields
        function openEditModal(member) {
            document.getElementById('editMemberId').value = member.id;
            document.getElementById('editFullName').value = member.full_name;
            document.getElementById('editPosition').value = member.position;

            const preview = document.getElementById('editAvatarPreview');
            preview.src = member.avatar ?
                'uploads/avatars/' + member.avatar :
                'assets/img/default-avatar.png'; // fallback placeholder image

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