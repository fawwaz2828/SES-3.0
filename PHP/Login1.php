<?php
session_start();

// Enable detailed error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Include Google Client Library (make sure it is installed via Composer)
require_once 'vendor/autoload.php';

// Include the database connection
include('db.php');

// Check database connection
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Initialize Google Client
$googleClient = new Google_Client();
$googleClient->setClientId('244799922805-h9aqi2g52l4fnj0tvsrl5mlgji74hdr8.apps.googleusercontent.com'); // Replace with your Client ID
$googleClient->setClientSecret('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'); // Replace with your Client Secret
$googleClient->setRedirectUri('http://localhost/Sustain-Energy-Solution-main/'); // Replace with your Redirect URI
$googleClient->addScope(['email', 'profile']);

// Check if Google returned a code (after login)
if (isset($_GET['code'])) {
    try {
        // Fetch access token
        $token = $googleClient->fetchAccessTokenWithAuthCode($_GET['code']);
        
        // Check for token errors
        if (isset($token['error'])) {
            throw new Exception("Error fetching Google token: " . $token['error']);
        }

        // Set the access token
        $googleClient->setAccessToken($token['access_token']);

        // Fetch user information from Google
        $google_service = new Google_Service_Oauth2($googleClient);
        $google_user = $google_service->userinfo->get();

        // Debug: Output the user data
        // echo '<pre>';
        // var_dump($google_user);
        // echo '</pre>';
        // exit();

        // Database: Check if Google user already exists
        $query = "SELECT * FROM user WHERE google_id = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param('s', $google_user->id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User exists - Update their details
            $query = "UPDATE user SET google_token = ?, google_avatar = ? WHERE google_id = ?";
            $stmt = $conn->prepare($query);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            $stmt->bind_param('sss', $token['access_token'], $google_user->picture, $google_user->id);
            $stmt->execute();
        } else {
            // New user - Insert into database
            // Assuming Google provides "name" as full name, split into first name and last name.
            $fullName = explode(' ', $google_user->name);  // Split by space to get first name and last name
            $firstName = $fullName[0];  // Assuming first part is the first name
            $lastName = isset($fullName[1]) ? $fullName[1] : ''; // Assuming second part is the last name (if available)

            $query = "INSERT INTO user (name, last_name, email, google_id, google_token, google_avatar, phone_number, password) 
                      VALUES (?, ?, ?, ?, ?, ?, NULL, NULL)";
            $stmt = $conn->prepare($query);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            $stmt->bind_param(
                'ssssss',
                $firstName,      // First name
                $lastName,       // Last name
                $google_user->email, // Email
                $google_user->id,    // Google ID
                $token['access_token'],  // Google Token
                $google_user->picture     // Google Avatar
            );
            $stmt->execute();
        }

        // Start session and store user details
        $_SESSION['user_id'] = $google_user->id;
        $_SESSION['user_name'] = $firstName;  // Store first name
        $_SESSION['user_email'] = $google_user->email;

        // Redirect to dashboard
        header("Location: dashboard.php");
        exit();

    } catch (Exception $e) {
        // Display any errors
        echo "Error: " . $e->getMessage();
    }
}

// Standard login with email and password
$error_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Successful login
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                header("Location: dashboard.php");
                exit();
            } else {
                // Password is incorrect
                $error_message = 'Password is incorrect.';
            }
        } else {
            // No user found
            $error_message = 'No user found with that email.';
        }
    } else {
        die("Query failed: " . $conn->error);
    }
}

// Generate Google Login URL
if (!isset($_SESSION['user_id'])) {
    $loginUrl = $googleClient->createAuthUrl();
} else {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login1.css"> <!-- Link to your CSS file -->
    <script>
        <?php if ($error_message): ?>
            alert('<?php echo $error_message; ?>');
        <?php endif; ?>
    </script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <img src="imageLogin/Frame 2.svg" alt="Logo">
        </div>
        <ul class="nav-links">
            <li><a href="#">Home</a></li>
            <li><a href="#">Explore</a></li>
            <li><a href="#">Blog</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="left">
            <h1>Welcome back!</h1>
            <h2>Your next step is just a login away</h2>
            <p>Registering is the first step. Let’s get started!</p>
        </div>
        <div class="right">
            <!-- Standard Login Form -->
            <form method="POST" action="Login1.php">
                <input type="email" name="email" required placeholder="Email">
                <input type="password" name="password" required placeholder="Password">
                <button type="submit">Login</button>
            </form>

            <!-- Google Login Button -->
            <a href="<?php echo $loginUrl; ?>" style="text-decoration: none;">
                <button>
                    <img src="Image/Google_Icons-09-512.webp" alt="Google Icon" width="30" height="30">
                    Continue with Google
                </button>
            </a>
            <!-- Register Button -->
            <button class="register-btn" onclick="window.location.href='Register1.php'">Create Account</button>
            <div class="login">
                <p>Forgot password? <a href="Register1.php">Reset here</a></p>
            </div>
        </div>
    </div>
</body>
</html>
