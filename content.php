<?php

// Handle logout
if (isset($_POST['logout'])) {
    session_start();
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: index.php"); // Redirect to the home page or login page
    exit();
}

include('db_config.php');
include('header.php');

// Check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to login page if not logged in
    exit();
}

// Get the user ID of the logged-in user
$user_id = $_SESSION['user_id'];

// Retrieve and display user data from the database
$query = "SELECT * FROM users WHERE user_id = $user_id";
$result = mysqli_query($db, $query);

if ($result) {
    $user = mysqli_fetch_assoc($result);

    // Handle email update form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $newEmail = mysqli_real_escape_string($db, $_POST['new_email']);

        // Update the email in the database
        $updateEmailQuery = "UPDATE users SET email = '$newEmail' WHERE user_id = $user_id";
        $updateEmailResult = mysqli_query($db, $updateEmailQuery);

        if ($updateEmailResult) {
            // Refresh user data after updating email
            $result = mysqli_query($db, $query);
            $user = mysqli_fetch_assoc($result);
            echo '<p>Email updated successfully!</p>';
        } else {
            echo "Error updating email: " . mysqli_error($db);
        }
    }

    // Handle user deletion
    if (isset($_GET['delete_user_id'])) {
        $deleteUserId = mysqli_real_escape_string($db, $_GET['delete_user_id']);

        // Delete the user from the database
        $deleteUserQuery = "DELETE FROM users WHERE user_id = $deleteUserId";
        $deleteUserResult = mysqli_query($db, $deleteUserQuery);

        if ($deleteUserResult) {
            echo '<p>User deleted successfully!</p>';
        } else {
            echo "Error deleting user: " . mysqli_error($db);
        }
    }
?>

    <h2>user details</h2>


    <!-- Display User Data -->
    <h2>current user <?php echo $user['name']; ?>!</h2>
    <p>Email: <?php echo $user['email']; ?></p>
    <p>Phone: <?php echo $user['phone']; ?></p>
    <p><img src="<?php echo $user['image_path']; ?>" alt="Profile Image" style="max-width: 200px; align-items: center;"></p>
    <form method="post" action="">
        <input class="button" type="submit" name="logout" value="Logout">
      </form>

    <!-- Update Email Section -->
    <form method="post" action="">
    <h3>Update Email</h3>
        <label for="new_email">New Email:</label>
        <input type="email" name="new_email" required>
        <input class="button" type="submit" value="Update Email">
    </form> 

    <!-- View Users Section -->
    <h3>Registered Users</h3>
    <?php
    // Retrieve all users from the database
    $allUsersQuery = "SELECT * FROM users";
    $allUsersResult = mysqli_query($db, $allUsersQuery);

    if ($allUsersResult) {
        // Display a table of all registered users
        echo '<table border="1">';
        echo '<tr><th>User ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Actions</th></tr>';
        while ($row = mysqli_fetch_assoc($allUsersResult)) {
            echo '<tr>';
            echo '<td>' . $row['user_id'] . '</td>';
            echo '<td>' . $row['name'] . '</td>';
            echo '<td>' . $row['email'] . '</td>';
            echo '<td>' . $row['phone'] . '</td>';
            echo '<td><a href="?delete_user_id=' . $row['user_id'] . '" onclick="return confirm(\'Are you sure you want to delete this user?\');">Delete</a></td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo "Error fetching users: " . mysqli_error($db);
    }

    // Close the result set
    mysqli_free_result($allUsersResult);
    ?>

<?php
} else {
    echo "Error fetching user data: " . mysqli_error($db);
}

include('footer.php');
?>
