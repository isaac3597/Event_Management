<?php
session_start();
echo $_SESSION['role'];

include '../config/db.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

if($_SESSION['role'] != 'admin') {
    exit('Access Denied');
}

$sql = "SELECT * FROM users
        WHERE role='organizer'
        AND status='pending'";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>

<head>

    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="../assets/style.css">

</head>

<body>

<div class="container">

    <div class="navbar">

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="../auth/logout.php">
            Logout
        </a>

    </div>

    <h1>Pending Organizers</h1>

    <?php while($row = mysqli_fetch_assoc($result)) { ?>

        <div class="event-card">

            <h2>
                <?php echo $row['fullname']; ?>
            </h2>

            <p>
                <?php echo $row['email']; ?>
            </p>

            <a
                href="approve.php?id=<?php echo $row['id']; ?>"
            >

                <button>
                    Approve Organizer
                </button>

            </a>

        </div>

    <?php } ?>

</div>

</body>
</html>