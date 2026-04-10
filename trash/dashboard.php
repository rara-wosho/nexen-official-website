<!-- TEST SAMPLE -->

<?php
// Start the session
session_start();
require_once "db_connect.php";

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXEN - Admin</title>
    <link href="assets/img/logo.jpg" rel="icon">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body { display: flex; height: 100vh; overflow: hidden; }
        .sidebar { width: 250px; background: #343a40; color: white; padding: 20px; }
        .content { flex-grow: 1; display: flex; flex-direction: column; }
        .editor-container { flex: 1; display: flex; flex-direction: column; }
        .preview-container { flex: 1; border: 1px solid #ddd; overflow: hidden; }
        .header { background:rgb(218, 11, 11); padding: 10px; color: white; text-align: center; }
        textarea { width: 100%; height: 300px; font-family: monospace; }
        iframe { width: 100%; height: 100%; border: none; }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4>Welcome <?php echo $_SESSION['username']; ?></h4>
        <ul style="list-style-type: none; padding: 0;">
            <li><a href="#" class="text-white">---------</a></li>
            <li><a href="#" class="text-white" onclick="document.getElementById('imageUpload').click();">---------</a></li>
            <li><a href="logout.php" class="text-white">Logout</a></li>
        </ul>
       
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="header">
            <h2>Editor</h2>
        </div>
            
        <!-- Live Preview -->
        <div class="preview-container">
            <iframe id="preview-frame" src="index.html"></iframe>
        </div>
    </div>

    <script>
        // Live Preview Update
        $("#editor").on("input", function() {
            var updatedHtml = $(this).val();
            document.getElementById('preview-frame').contentDocument.open();
            document.getElementById('preview-frame').contentDocument.write(updatedHtml);
            document.getElementById('preview-frame').contentDocument.close();
        });

        // Handle Save
        $("#editor-form").on("submit", function(e) {
            e.preventDefault();
            var updatedHtml = $("#editor").val();
            
            $.post("save.php", { html_content: updatedHtml }, function(response) {
                alert(response);
                $("#preview-frame").attr("src", "index.html");  // Reload preview
            });
        });

        // Handle Image Upload
        function uploadImage() {
            var formData = new FormData(document.getElementById('uploadForm'));
            $.ajax({
                url: 'upload.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.includes("uploads/")) {
                        var imageTag = '<img src="' + response + '" style="max-width:100%;">';
                        $("#editor").val($("#editor").val() + imageTag);
                    } else {
                        alert("Upload failed: " + response);
                    }
                }
            });
        }
    </script>

</body>
</html>