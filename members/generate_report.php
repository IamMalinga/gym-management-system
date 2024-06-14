<?php include('../includes/db_connect.php'); ?>
<?php
   if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username'])) {
    header("Location: ../public/login.php");
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT id, name FROM members WHERE email='$username'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$member_id = $row['id'];
$member_name = $row['name'];

$progress_sql = "SELECT * FROM progress_tracking WHERE member_id='$member_id' ORDER BY date ASC";
$progress_result = $conn->query($progress_sql);

require('../libs/fpdf/fpdf.php');

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Progress Report', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function ChapterTitle($label)
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, $label, 0, 1, 'L');
        $this->Ln(4);
    }

    function ChapterBody($body)
    {
        $this->SetFont('Arial', '', 12);
        $this->MultiCell(0, 10, $body);
        $this->Ln();
    }
}

$pdf = new PDF();
$pdf->AddPage();

$pdf->ChapterTitle('Member Information');
$pdf->ChapterBody("Name: " . $member_name);

$pdf->ChapterTitle('Progress Tracking');
while ($row = $progress_result->fetch_assoc()) {
    $body = "Date: " . $row['date'] . "\nWeight: " . $row['weight'] . " kg\nHeight: " . $row['height'] . " cm\nBMI: " . $row['bmi'] . "\nBody Fat: " . $row['body_fat_percentage'] . " %\nMuscle Mass: " . $row['muscle_mass'] . " kg\nGoal Weight: " . $row['goal_weight'] . " kg\nGoal Body Fat: " . $row['goal_body_fat_percentage'] . " %\nNotes: " . $row['notes'];
    $pdf->ChapterBody($body);
}

$pdf->Output('I', 'progress_report.pdf');
?>
