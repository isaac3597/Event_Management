<?php
session_start();

include '../config/db.php';
require('../fpdf/fpdf.php');

if(!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$organizer_id = $_SESSION['user_id'];

$sql = "SELECT 
            tickets.id,
            tickets.quantity,
            tickets.total_price,
            tickets.purchase_date,
            users.fullname,
            users.email,
            events.title,
            events.event_date
        FROM tickets

        JOIN users
        ON tickets.user_id = users.id

        JOIN events
        ON tickets.event_id = events.id

        WHERE events.organizer_id = '$organizer_id'

        ORDER BY tickets.purchase_date DESC";

$result = mysqli_query($conn, $sql);

$pdf = new FPDF();

$pdf->AddPage();

$pdf->SetFont('Arial','B',18);

$pdf->Cell(190,10,'Event Ticket Holders Report',0,1,'C');

$pdf->Ln(10);

$pdf->SetFont('Arial','B',12);

$pdf->Cell(10,10,'#',1);
$pdf->Cell(40,10,'Name',1);
$pdf->Cell(55,10,'Email',1);
$pdf->Cell(30,10,'Tickets',1);
$pdf->Cell(25,10,'Amount',1);
$pdf->Cell(30,10,'Date',1);

$pdf->Ln();

$pdf->SetFont('Arial','',10);

$count = 1;

while($row = mysqli_fetch_assoc($result)) {

    $pdf->Cell(10,10,$count,1);

    $pdf->Cell(40,10,$row['fullname'],1);

    $pdf->Cell(55,10,$row['email'],1);

    $pdf->Cell(30,10,$row['quantity'],1);

    $pdf->Cell(25,10,'KSH '.$row['total_price'],1);

    $pdf->Cell(30,10,$row['purchase_date'],1);

    $pdf->Ln();

    $count++;
}

$pdf->Ln(10);

$pdf->SetFont('Arial','B',12);

$pdf->Cell(190,10,'Generated Successfully',0,1,'C');

$pdf->Output('D', 'ticket_holders_report.pdf');
?>