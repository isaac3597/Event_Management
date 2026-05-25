<?php
session_start();
include '../config/db.php';

if(isset($_POST['create'])) {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $event_date = $_POST['event_date'];

    // ADD THESE NEW LINES
    $ticket_price = $_POST['ticket_price'];
    $available_tickets = $_POST['available_tickets'];

    $organizer_id = $_SESSION['user_id'];

    // REPLACE OLD SQL WITH THIS
    $sql = "INSERT INTO events(
                organizer_id,
                title,
                description,
                location,
                event_date,
                ticket_price,
                available_tickets
            )
            VALUES(
                '$organizer_id',
                '$title',
                '$description',
                '$location',
                '$event_date',
                '$ticket_price',
                '$available_tickets'
            )";

    if(mysqli_query($conn, $sql)) {
        echo "Event created successfully";
    } else {
        echo "Error creating event";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Event</title>
</head>
<body>

<h2>Create Event</h2>

<form method="POST">

    <input 
        type="text" 
        name="title" 
        placeholder="Event Title" 
        required
    ><br><br>

    <textarea 
        name="description" 
        placeholder="Description"
    ></textarea><br><br>

    <input 
        type="text" 
        name="location" 
        placeholder="Location"
    >

    <input 
        type="number" 
        step="0.01"
        name="ticket_price" 
        placeholder="Ticket Price"
        required
    >

    <input 
        type="number" 
        name="available_tickets" 
        placeholder="Number of Tickets"
        required
    ><br><br>

    <input 
        type="date" 
        name="event_date" 
        required
    ><br><br>

    <button type="submit" name="create">
        Create Event
    </button>

</form>

</body>
</html>


