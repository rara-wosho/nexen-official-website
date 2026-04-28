<?php
session_start();
require_once 'db_connect.php';

// Check if user is logged in as admin
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

function getFileConfig($section_name)
{
    $configs = [
        'hero' => [
            'dir' => 'video',
            'types' => ['mp4', 'webm', 'ogg'],
            'max_size' => 50000000, // 50MB
            'message' => 'MP4, WebM & OGG files'
        ],
        'about' => [
            'dir' => 'img/v2',
            'types' => ['jpg', 'jpeg', 'png'],
            'max_size' => 5000000, // 5MB
            'message' => 'JPG, JPEG, PNG & GIF files'
        ],
        'blog' => [
            'dir' => 'img',
            'types' => ['jpg', 'jpeg', 'png', 'gif'],
            'max_size' => 5000000, // 5MB
            'message' => 'JPG, JPEG, PNG & GIF files'
        ],
        'why-us' => [
            'dir' => 'img',
            'types' => ['jpg', 'jpeg', 'png', 'gif'],
            'max_size' => 5000000, // 5MB
            'message' => 'JPG, JPEG, PNG & GIF files'
        ],
        'official-logo' => [
            'dir' => 'img/logo',
            'types' => ['jpg', 'jpeg', 'png'],
            'max_size' => 5000000, // 5MB
            'message' => 'JPG, JPEG, and PNG files'
        ],
        'partners_logo' => [
            'dir' => 'img/v2',
            'types' => ['jpg', 'jpeg', 'png', 'gif'],
            'max_size' => 5000000, // 5MB
            'message' => 'JPG, JPEG, PNG & GIF files'
        ],
        'projects' => [
            'dir' => 'img',
            'types' => ['jpg', 'jpeg', 'png', 'gif'],
            'max_size' => 5000000, // 5MB
            'message' => 'JPG, JPEG, PNG & GIF files'
        ]
    ];

    return isset($configs[$section_name]) ? $configs[$section_name] : null;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['section_name']) && isset($_POST['content_key'])) {
        $section_name = $_POST['section_name'];
        $content_key = $_POST['content_key'];

        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            // Get configuration for this section
            $config = getFileConfig($section_name);

            if (!$config) {
                $_SESSION['error'] = "Invalid section name.";
                header("Location: admin_editor.php");
                exit();
            }
            $target_dir = "assets/" . $config['dir'] . "/";

            // Create directory if it doesn't exist
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_extension = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;

            // Check file type
            if (!in_array($file_extension, $config['types'])) {
                $_SESSION['error'] = "Sorry, only " . $config['message'] . " are allowed.";
                header("Location: admin_editor.php");
                exit();
            }

            // Check file size
            if ($_FILES["file"]["size"] > $config['max_size']) {
                $_SESSION['error'] = "Sorry, your file is too large. Maximum size is " . ($config['max_size'] / 1000000) . "MB.";
                header("Location: admin_editor.php");
                exit();
            }

            // Delete old file if exists
            if (isset($_POST['old_file']) && file_exists($_POST['old_file'])) {
                unlink($_POST['old_file']);
            }

            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                // Update database with new file path
                try {
                    $query = "UPDATE content SET content = :content 
                             WHERE section_name = :section_name AND content_key = :content_key";
                    $stmt = $connection->prepare($query);
                    $stmt->bindParam(':content', $target_file);
                    $stmt->bindParam(':section_name', $section_name);
                    $stmt->bindParam(':content_key', $content_key);

                    if ($stmt->execute()) {
                        $_SESSION['success'] = "The file has been uploaded and database updated successfully.";
                    } else {
                        $_SESSION['error'] = "Failed to update database.";
                        unlink($target_file); // Delete uploaded file if database update fails
                    }
                } catch (PDOException $e) {
                    $_SESSION['error'] = "Database error: " . $e->getMessage();
                    unlink($target_file); // Delete uploaded file if database update fails
                }
            } else {
                $_SESSION['error'] = "Sorry, there was an error uploading your file.";
            }
        } else {
            // If no file was uploaded but content was provided, update the content
            if (isset($_POST['content'])) {
                try {
                    $query = "UPDATE content SET content = :content 
                             WHERE section_name = :section_name AND content_key = :content_key";
                    $stmt = $connection->prepare($query);
                    $stmt->bindParam(':content', $_POST['content']);
                    $stmt->bindParam(':section_name', $section_name);
                    $stmt->bindParam(':content_key', $content_key);

                    if ($stmt->execute()) {
                        $_SESSION['success'] = "Content updated successfully.";
                    } else {
                        $_SESSION['error'] = "Failed to update content.";
                    }
                } catch (PDOException $e) {
                    $_SESSION['error'] = "Database error: " . $e->getMessage();
                }
            } else {
                $_SESSION['error'] = "No file was uploaded or content provided.";
            }
        }
    } else {
        $_SESSION['error'] = "Missing required parameters.";
    }

    header("Location: admin_editor.php");
    exit();
}
