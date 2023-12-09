<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('db_config.php');
include('header.php');

// Initialize variables for form validation
$nameErr = $emailErr = $phoneErr = $passwordErr = $imageErr = "";
$name = $email = $phone = $password = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and process registration data
    $name = test_input($_POST['name']);
    $email = test_input($_POST['email']);
    $phone = test_input($_POST['phone']);
    $password = test_input($_POST['password']);

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    // Validate Name
    if (empty($name)) {
        $nameErr = "Name is required";
    }

    // Validate Email
    if (empty($email)) {
        $emailErr = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    }

    // Validate Phone
    if (empty($phone)) {
        $phoneErr = "Phone is required";
    }

    // Validate Password
    if (empty($password)) {
        $passwordErr = "Password is required";
    }

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($db, $query);
    if (mysqli_num_rows($result) > 0) {
        $emailErr = "Email address is already taken";
    }

    // Handle image upload
    $targetDir = "uploads/"; 
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));


    // If there are no validation errors, proceed with registration
    if (empty($nameErr) && empty($emailErr) && empty($phoneErr) && empty($passwordErr) && empty($imageErr)) {
        // Hash the password before storing it in the database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        // Save the user information to the database (you should use prepared statements)
        $query = "INSERT INTO users (name, email, phone, password, image_path) VALUES ('$name', '$email', '$phone', '$hashedPassword', '$targetFile')";
    
        // Check for SQL query execution errors
        if (!mysqli_query($db, $query)) {
            echo "Error: " . mysqli_error($db);
        } else {
            // Redirect to a success page or login page
            header("Location: index.php");
            exit();
        }
    }
}
// Function to sanitize and validate input
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>


<h2>Register</h2>
<form method="post" action="register.php" enctype="multipart/form-data">
    
    <input type="text" name="name" value="<?php echo $name; ?>" placeholder="Name">
    <span class="error"><?php echo $nameErr; ?></span>
    <br>
    <input type="text" name="email" value="<?php echo $email; ?>" placeholder="e-mail">
    <span class="error"><?php echo $emailErr; ?></span>
    <br>
    <input type="text" name="phone" value="<?php echo $phone; ?>" placeholder="Phone">
    <span class="error"><?php echo $phoneErr; ?></span>
    <br>

    <input type="password" name="password" placeholder="Password">
    <span class="error"><?php echo $passwordErr; ?></span>
    <br>
    <label for="image">Profile Image:</label>
    <input type="file" name="image">
    <span class="error"><?php echo $imageErr; ?></span>

    <input class="button" type="submit" value="Register">
    <p>Click to return <a href="index.php">Home</a></p>
</form>

<?php
include('footer.php');
?>
