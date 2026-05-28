<?php
session_start();
include '../config/db.php';

if(isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0) {

        $user = mysqli_fetch_assoc($result);

        if(password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['fullname'] = $user['fullname'];

            if($user['role'] == 'organizer') {
                header('Location: ../organizer/dashboard.php');
            } else {
                header('Location: ../user/events.php');
            }
        } else {
            echo "Invalid password";
        }
    } else {
        echo "User not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login</h2>
 <div class="container">

    <div class="navbar">
        <a href="../index.php">Home</a>
        <a href="events.php">Events</a>
        <a href="my_tickets.php">My Tickets</a>
        <a href="../auth/logout.php">Logout</a>
    </div>

<form method="POST">
    <input type="email" name="email" placeholder="Email" required><br><br>

    <input type="password" name="password" placeholder="Password" required><br><br>

    <button type="submit" name="login">Login</button>
</form>
<p style="text-align:center; margin-top:15px;">

    Don't have an account?

    <a href="register.php" style="color:#00c3ff; font-weight:bold;">
        Register Here
    </a>

</p>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
   

    <!-- PAGE CONTENT HERE -->
    <title>Event Management System</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>



</div>

</body>
</html>