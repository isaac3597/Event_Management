<?php
session_start();
include '../config/db.php';

if(isset($_POST['register'])) {

    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $sql = "INSERT INTO users(fullname, email, password, role)
            VALUES('$fullname', '$email', '$password', '$role')";

    if(mysqli_query($conn, $sql)) {
        echo "Registration successful";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>

<h2>User Registration</h2>

<form method="POST">
    <input type="text" name="fullname" placeholder="Full Name" required><br><br>

    <input type="email" name="email" placeholder="Email" required><br><br>

    <input type="password" name="password" placeholder="Password" required><br><br>

    <select name="role">
        <option value="user">User</option>
        <option value="organizer">Organizer</option>
    </select><br><br>

    <button type="submit" name="register">Register</button>
</form>

</body>
</html>