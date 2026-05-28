<!DOCTYPE html>
<html>
<head>
    <title>Event Management System</title>
    <link rel="icon" type="image/x-icon" href="../assets/favicon.ico">
</head>
<body>

<h1>Welcome to Event Management System</h1>

<div class="navbar">
    <link rel="stylesheet" href="assets/style.css">
    <a href="index.php">Home</a>
    <a href="auth/login.php">Login</a>
    <a href="auth/register.php">Register</a>
    <a href="user/events.php">Events</a>
    <a href="user/my_tickets.php">My Tickets</a>

    
</div>
<script>

const darkBtn =
    document.getElementById('darkModeBtn');

// Load saved mode
if(localStorage.getItem('darkMode') === 'enabled') {

    document.body.classList.add('dark-mode');
}

darkBtn.addEventListener('click', () => {

    document.body.classList.toggle('dark-mode');

    // Save mode
    if(document.body.classList.contains('dark-mode')) {

        localStorage.setItem(
            'darkMode',
            'enabled'
        );

    } else {

        localStorage.setItem(
            'darkMode',
            'disabled'
        );
    }
});

</script>
</body>
</html>

