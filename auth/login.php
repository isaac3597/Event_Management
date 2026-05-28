<?php
session_start();

include '../config/db.php';

$error = "";

if(isset($_POST['login'])) {

    $email = trim($_POST['email']);

    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users
            WHERE email='$email'
            AND password='$password'";

    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0) {

        $user = mysqli_fetch_assoc($result);

        // Organizer approval check
        if(
            $user['role'] == 'organizer'
            &&
            $user['status'] != 'approved'
        ) {

            $error =
                "Organizer account pending approval";

        } else {

            // Save sessions
            $_SESSION['user_id'] = $user['id'];

            $_SESSION['role'] = $user['role'];

            // REDIRECTS
            if($user['role'] == 'admin') {

                header(
                    "Location: ../admin/dashboard.php"
                );
                exit();

            } elseif($user['role'] == 'organizer') {

                header(
                    "Location: ../organizer/dashboard.php"
                );
                exit();

            } else {

                header(
                    "Location: ../user/events.php"
                );
                exit();
            }
        }

    } else {

        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Login</title>

    <link rel="stylesheet" href="../assets/style.css">

</head>

<body>

<div class="container">

    <h2>Login</h2>

    <?php if($error != "") { ?>

        <p style="color:red;">
            <?php echo $error; ?>
        </p>

    <?php } ?>

    <form method="POST">

        <input
            type="email"
            name="email"
            placeholder="Email"
            required
        ><br><br>

        <input
            type="password"
            name="password"
            placeholder="Password"
            required
        ><br><br>

        <button type="submit" name="login">
            Login
        </button>

    </form>

    <p style="margin-top:15px;">

        Don't have an account?

        <a href="register.php">
            Register Here
        </a>

    </p>

</div>

</body>
</html>