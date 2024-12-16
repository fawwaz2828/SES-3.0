<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If user is logged in, redirect them to the dashboard
    header("Location: dashboard.php");
    exit(); // Ensure no further code is executed
}
include('db.php'); // Include the database connection

// Message for success or error
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $name = $_POST['name'];
    $last_name = $_POST['last_name'];
    $business_name = $_POST['business_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate if email is already in use
    $checkEmail = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($checkEmail);
    if (!$stmt) {
        // If prepare failed, show an error message
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email is already taken
        $message = "Email already in use. Please choose another one.";
    } elseif ($password === $confirm_password) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Google-related fields can be set to NULL as we don't use them in this form
        $google_id = NULL;
        $google_token = NULL;
        $google_avatar = NULL;

        // Insert data into the database using prepared statements
        $sql = "INSERT INTO user (name, last_name, business_name, email, phone_number, password, google_id, google_token, google_avatar) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            // If prepare failed, show an error message
            die("Prepare failed: " . $conn->error);
        }

        // Bind the parameters and execute the query
        $stmt->bind_param("sssssssss", $name, $last_name, $business_name, $email, $phone_number, $hashed_password, $google_id, $google_token, $google_avatar);

        if ($stmt->execute()) {
            // Success message, redirect to login
            $message = "Registration successful! You can now login.";
            header("Location: Login1.php?message=" . urlencode($message));
            exit(); // Ensure no further code is executed after redirection
        } else {
            // Log error if query execution fails
            die("Error executing query: " . $stmt->error); // Capture query execution errors
        }
    } else {
        // Passwords do not match
        $message = "Passwords do not match!";
    }
}
?>

<!-- Registration Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register</title>
    <link rel="stylesheet" href="css/register1.css" />
    <script>
        window.onload = function() {
            <?php if ($message): ?>
                alert("<?php echo $message; ?>");
            <?php endif; ?>
        };
    </script>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <img src="imageLogin/Frame 2.svg" alt="Logo"> <!-- Change path/logo as needed -->
        </div>
        <ul class="nav-links">
            <li><a href="#">Home</a></li>
            <li><a href="#">Explore</a></li>
            <li><a href="#">Blog</a></li>
        </ul>
    </nav>

    <!-- Registration Form -->
    <div class="container">
        <div class="left">
            <h1>Join Us Today!</h1>
            <h2>Welcome to the Smart Lighting Revolution</h2>
            <p>Join us to transform your lighting experience with intelligent control and seamless integration.</p>
        </div>
        <div class="right">
            <form method="POST" action="register1.php">
                <div class="name-container">
                    <input type="text" name="name" placeholder="First Name" required />
                    <input type="text" name="last_name" placeholder="Last Name" required />
                </div>
                <input type="text" name="business_name" placeholder="Business Name" required />
                <input type="email" name="email" placeholder="Email" required />
                <input type="tel" name="phone_number" placeholder="Phone Number" required />
                <input type="password" id="password" name="password" required placeholder="Password" />
                <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm Password" />
                
                <button type="submit">Register</button>
            </form>

            <div class="login">
                <p>Already have an account? <a href="login1.php">Login here</a></p>
            </div>
        </div>
    </div>

</body>
</html>
