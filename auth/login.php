<?php
session_start();

include '../config/db.php';

if(isset($_POST['login'])) {

    $email = $_POST['email'];

    $password = $_POST['password'];

    $sql = "SELECT * FROM users
            WHERE email='$email'
            AND password='$password'";

    // EXECUTE QUERY
    $result = mysqli_query($conn, $sql);

    // CHECK USER
    if(mysqli_num_rows($result) > 0) {

        $user = mysqli_fetch_assoc($result);

        // CHECK ORGANIZER APPROVAL
        if(
            $user['role'] == 'organizer'
            &&
            $user['status'] != 'approved'
        ) {

            $error =
                "Organizer account pending approval";

        } else {

            // SAVE SESSION
            $_SESSION['user_id'] = $user['id'];

            $_SESSION['role'] = $user['role'];

            // REDIRECT BASED ON ROLE
            if($user['role'] == 'admin') {

                header(
                    'Location: ../admin/dashboard.php'
                );

            } elseif($user['role'] == 'organizer') {

                header(
                    'Location: ../organizer/dashboard.php'
                );

            } else {

                header(
                    'Location: ../user/events.php'
                );
            }

            exit();
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

    <?php if(isset($error)) { ?>

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

    <p style="text-align:center; margin-top:15px;">

        Don't have an account?

        <a
            href="register.php"
            style="color:#00c3ff; font-weight:bold;"
        >
            Register Here
        </a>

    </p>

</div>

</body>
</html>