<?php

/**
 * NEXEN Admin Sign In
 * Redesigned with a premium SaaS aesthetic.
 * 
 * DESIGN NOTES:
 * - Layout: Split-screen (Left: Brand/Trust, Right: Form)
 * - Typography: Inter (UI) + DM Serif Display (Headlines)
 * - Palette: Deep Navy (#0A0F29), Primary Blue (#2563EB), Muted Slate
 * - Details: Glassmorphism accents, subtle borders, high-precision spacing
 */

session_start();
require_once "db_connect.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'] ?? '';
    $user_password = $_POST['password'] ?? '';

    // Check if all fields are filled
    if (empty($username) || empty($user_password)) {
        $message = "All fields are required.";
    } else {
        // Check for existing username
        $stmt = $connection->prepare("SELECT * FROM admin WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify password
            if (password_verify($user_password, $user['password'])) {
                $_SESSION['username'] = $username;
                header("Location: admin_editor.php");
                exit();
            } else {
                $message = "Invalid password.";
            }
        } else {
            $message = "User not found.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXEN — Sign In</title>
    <link href="assets/img/logo.jpg" rel="icon">

    <link rel="stylesheet" href="style.css">
    <!-- Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=DM+Serif+Display&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            /* Color Palette */
            --nx-bg-primary: #ffffff;
            --nx-bg-secondary: #f8fafc;
            --nx-brand-dark: rgb(4, 4, 4);
            --nx-brand-accent: #d92b3a;
            --nx-brand-hover: #d92b3a;
            --nx-text-main: #1E293B;
            --nx-text-muted: #bcc4ce;
            --nx-border: #E2E8F0;
            --nx-error: #EF4444;
            --nx-error-bg: #FEF2F2;

            /* Shadows & Effects */
            --nx-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --nx-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --nx-transition: 200ms cubic-bezier(0.4, 0, 0.2, 1);
            --nx-radius: 8px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--nx-bg-primary);
            color: var(--nx-text-main);
            min-height: 100vh;
            margin: 0;
            display: flex;
            overflow-x: hidden;
        }

        /* --- Layout --- */
        .nx-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* --- Brand Panel (Left) --- */
        .nx-brand-panel {
            flex: 1.2;
            background-color: var(--nx-brand-dark);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 60px;
            color: white;
            overflow: hidden;
        }

        /* Subtle grid background pattern */
        .nx-brand-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle at 2px 2px, rgba(255, 255, 255, 0.09) 1px, transparent 0);
            background-size: 32px 32px;
            pointer-events: none;
        }

        /* Abstract glowing orb */
        .nx-brand-panel::after {
            content: "";
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(235, 37, 37, 0.15) 0%, transparent 70%);
            top: -200px;
            left: -200px;
            pointer-events: none;
        }

        .nx-brand-header {
            position: relative;
            z-index: 10;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nx-logo-box {
            width: 75px;
            height: 75px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nx-brand-name {
            font-weight: 400;
            font-size: 16px;
            letter-spacing: -0.02em;
        }

        .nx-brand-content {
            position: relative;
            z-index: 10;
            /* max-width: 480px; */
        }

        .nx-headline {
            /* font-family: 'DM Serif Display', serif; */
            font-size: clamp(2.5rem, 5vw, 4rem);
            line-height: 1.1;
            max-width: 600px;
            margin-bottom: 24px;
        }

        .nx-headline span {
            color: var(--nx-brand-accent);
        }

        .nx-subtext {
            font-size: 1.125rem;
            color: rgba(255, 255, 255, 0.6);
            line-height: 1.6;
            font-weight: 300;
            max-width: 600px;
        }

        .nx-brand-footer {
            position: relative;
            z-index: 10;
            display: flex;
            gap: 40px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 40px;
        }

        .nx-metric {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .nx-metric-val {
            font-weight: 600;
            font-size: 18px;
        }

        .nx-metric-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: rgba(255, 255, 255, 0.4);
        }

        /* --- Form Panel (Right) --- */
        .nx-form-panel {
            flex: 1;
            background-color: rgba(25, 25, 25, .4);
            background: linear-gradient(to bottom, rgba(50, 50, 50, .5),
                    transparent);
            backdrop-filter: blur(20px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .nx-form-container {
            width: 100%;
            max-width: 400px;
        }

        .nx-form-header {
            margin-bottom: 40px;
        }

        .nx-form-title {
            font-size: 28px;
            font-weight: 600;
            letter-spacing: -0.02em;
            margin-bottom: 8px;

        }

        .nx-form-subtitle {
            color: var(--nx-text-muted);
            font-size: 15px;
        }

        /* --- Form Elements --- */
        .nx-form-group {
            margin-bottom: 24px;
        }

        .nx-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--nx-text-muted);
        }

        .nx-input-container {
            position: relative;
        }


        .nx-input {
            background: rgba(31, 31, 31, 1);
            border: 1px solid rgba(86, 86, 86, 0.5);
            border-radius: 10px;
            padding-block: 10px;
            padding-inline: 14px;
            color: rgb(240, 240, 240);
            font-size: 14px;
        }

        .nx-input:hover {
            border-color: #CBD5E1;
        }

        .nx-input:focus {
            border-color: var(--nx-brand-accent);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .nx-input.is-invalid {
            border-color: var(--nx-error);
        }

        .nx-input.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        .nx-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
        }

        .nx-checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            cursor: pointer;
            user-select: none;
        }

        .nx-checkbox {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            border: 1px solid var(--nx-border);
            cursor: pointer;
        }


        .back-button {
            font-size: 18px;
            outline: 0;
            border: none;
            background-color: rgba(50, 50, 50, .2);
            border-radius: 8px;
            padding: 8px 18px;
            color: rgba(230, 230, 230, 1);
            margin-bottom: 1rem;
            position: absolute;
            top: 2rem;
            left: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            text-decoration: none;
        }



        input[type="checkbox"] {
            accent-color: #ff0000;
            /* Replaces the default blue with red */
        }

        .nx-link {
            color: var(--nx-brand-accent);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: var(--nx-transition);
        }

        .nx-link:hover {
            color: var(--nx-brand-hover);
            text-decoration: underline;
        }

        .nx-btn-submit {
            width: 100%;
            height: 48px;
            background-color: var(--nx-brand-dark);
            color: white;
            border: none;
            border-radius: var(--nx-radius);
            font-weight: 600;
            font-size: 15px;
            transition: var(--nx-transition);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .nx-btn-submit:hover {
            background-color: #1a1f3d;
            transform: translateY(-1px);
            box-shadow: var(--nx-shadow-md);
        }

        .nx-btn-submit:active {
            transform: translateY(0);
        }

        /* --- Alert --- */
        .nx-alert {
            padding: 12px 16px;
            border-radius: var(--nx-radius);
            background-color: var(--nx-error-bg);
            border: 1px solid rgba(239, 68, 68, 0.1);
            color: var(--nx-error);
            font-size: 14px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* --- Responsive --- */
        @media (max-width: 992px) {
            .nx-brand-panel {
                display: none;
            }

            .nx-form-panel {
                flex: 1;
                /* background-color: var(--nx-bg-secondary); */
            }

            .nx-form-container {
                /* background: white; */
                padding: 40px;
                border-radius: 16px;
                box-shadow: var(--nx-shadow-md);
            }
        }

        @media (max-width: 576px) {
            .nx-form-panel {
                padding: 20px;
            }

            .nx-form-container {
                padding: 32px 24px;
            }
        }
    </style>
</head>

<body>

    <div class="nx-wrapper bg-background">
        <!-- Brand Section -->
        <aside class="nx-brand-panel">
            <div class="nx-brand-header">
                <div class="nx-logo-box">
                    <!-- <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="16 18 22 12 16 6"></polyline>
                        <polyline points="8 6 2 12 8 18"></polyline>
                    </svg> -->
                    <img src="assets/png/nexen-logo.png" alt="">
                </div>
                <span class="nx-brand-name">NEXEN INNOVATION TECHNOLOGIES</span>
            </div>

            <div class="nx-brand-content">
                <h1 class="nx-headline inter">Your Next Business Engine <span class="text-red-gradient">Solutions</span></h1>
                <p class="nx-subtext">Find everything you need in one place. From modern homes to valuable opportunities, NEXEN brings you closer to what matters most.</p>
            </div>

            <div class="nx-brand-footer">
                <div class="nx-metric">
                    <span class="nx-metric-val">99.99%</span>
                    <span class="nx-metric-label">Uptime</span>
                </div>
                <div class="nx-metric">
                    <span class="nx-metric-val">AES-256</span>
                    <span class="nx-metric-label">Security</span>
                </div>
                <div class="nx-metric">
                    <span class="nx-metric-val">24/7</span>
                    <span class="nx-metric-label">Support</span>
                </div>
            </div>
        </aside>

        <!-- Form Section -->
        <main class="nx-form-panel position-relative">
            <a href="/nexen-official-website" class="back-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0" />
                </svg>
                <span style="font-size: 14px;">
                    Back
                </span>
            </a>
            <div class="nx-form-container">

                <header class="nx-form-header">
                    <h2 class="nx-form-title text-secondary-foreground">Sign in</h2>
                    <p class="nx-form-subtitle text-muted-light">Enter your credentials to access the admin portal.</p>
                </header>

                <?php if (!empty($message)): ?>
                    <div class="nx-alert">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <span><?php echo htmlspecialchars($message); ?></span>
                    </div>
                <?php endif; ?>


                <form method="POST" action="">
                    <div class="nx-form-group">
                        <label for="username" class="nx-label">Username</label>
                        <div class="nx-input-container">
                            <input
                                type="text"
                                id="username"
                                name="username"
                                class="nx-input w-100 <?php echo !empty($message) ? 'is-invalid' : ''; ?>"
                                placeholder="name@company.com"
                                value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                                required>
                        </div>
                    </div>

                    <div class="nx-form-group">
                        <label for="password" class="nx-label">Password</label>
                        <div class="nx-input-container">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="nx-input w-100 <?php echo !empty($message) ? 'is-invalid' : ''; ?>"
                                placeholder="••••••••"
                                required>
                        </div>
                    </div>

                    <div class="nx-actions">
                        <label class="nx-checkbox-group">
                            <input type="checkbox" class="nx-checkbox" name="remember">
                            <span class="text-muted-light">Remember me</span>
                        </label>
                        <a href="#" class="nx-link">Forgot password?</a>
                    </div>

                    <button type="submit" style="padding-block: 12px;" class="bg-gradient-red text-white rounded-3 w-100 outline-0 border-0 px-5">
                        <span>Sign in to NEXEN</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>
                </form>

                <!-- <footer style="background:transparent !important; margin-top: 40px; text-align: center;">
                    <p style="font-size: 13px; color: var(--nx-text-muted);">
                        Don't have an account? <a href="#" class="nx-link">Contact your administrator</a>
                    </p>

                    <p class="text-secondary-foreground">Don't have an account? <a href="" class="nx-link">Contact your administrator</a></p>
                </footer> -->
            </div>
        </main>
    </div>

</body>

</html>