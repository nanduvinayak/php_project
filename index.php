<?php
include('db_config.php');
include('header.php');

// Check if the user is already logged in
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: content.php"); // Redirect to the content page if logged in
    exit();
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate login credentials
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query the database to check if the email exists
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($db, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Verify the password
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            // Password is correct, set session variables
            $_SESSION['user_id'] = $user['user_id'];

            // Redirect to the content page
            header("Location: content.php");
            exit();
        } else {
            // Display an error message for incorrect password
            $error_message = "Invalid password";
        }
    } else {
        // Display an error message for invalid email
        $error_message = "Invalid email";
    }
}
?>

<!-- Your Home Page Content Here -->
<h2>Login to your account</h2>

<!-- Login Form -->
<form method="post" action="">
    <input type="text" name="email" required placeholder="Username"><br>

    <input type="password" name="password" required placeholder="Password"><br>

    <input class="button" type="submit" value="Login">
    <p>To register your account <a href="register.php">Click here</a> </p>
</form>

<!-- Display error message if any -->
<?php if (isset($error_message)) { ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
<?php } ?>

<?php
include('footer.php');
?>
