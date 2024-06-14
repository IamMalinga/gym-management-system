<?php include('../includes/db_connect.php'); ?>
<?php
require('../libs/fpdf/fpdf.php');

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Admin Report', 0, 1, 'C');
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

$member_id = $_POST['member_id'];

// Fetch member information
$member_sql = "SELECT * FROM members WHERE id='$member_id'";
$member_result = $conn->query($member_sql);
$member = $member_result->fetch_assoc();

// Fetch progress tracking data
$progress_sql = "SELECT * FROM progress_tracking WHERE member_id='$member_id' ORDER BY date ASC";
$progress_result = $conn->query($progress_sql);

// Fetch payments data
$payments_sql = "SELECT * FROM payments WHERE member_id='$member_id' ORDER BY payment_date ASC";
$payments_result = $conn->query($payments_sql);

// Fetch milestones data
$milestones_sql = "SELECT * FROM milestones WHERE member_id='$member_id' ORDER BY date ASC";
$milestones_result = $conn->query($milestones_sql);

$pdf = new PDF();
$pdf->AddPage();

// Member Information
$pdf->ChapterTitle('Member Information');
$pdf->ChapterBody("Name: " . $member['name'] . "\nEmail: " . $member['email'] . "\nPhone: " . $member['phone'] . "\nAddress: " . $member['address'] . "\nDOB: " . $member['dob'] . "\nGender: " . $member['gender']);

// Progress Tracking
$pdf->ChapterTitle('Progress Tracking');
while ($row = $progress_result->fetch_assoc()) {
    $body = "Date: " . $row['date'] . "\nWeight: " . $row['weight'] . " kg\nHeight: " . $row['height'] . " cm\nBMI: " . $row['bmi'] . "\nBody Fat: " . $row['body_fat_percentage'] . " %\nMuscle Mass: " . $row['muscle_mass'] . " kg\nGoal Weight: " . $row['goal_weight'] . " kg\nGoal Body Fat: " . $row['goal_body_fat_percentage'] . " %\nNotes: " . $row['notes'];
    $pdf->ChapterBody($body);
}

// Payments
$pdf->ChapterTitle('Payments');
while ($row = $payments_result->fetch_assoc()) {
    $body = "Payment Date: " . $row['payment_date'] . "\nAmount: $" . $row['amount'] . "\nMethod: " . $row['payment_method'] . "\nPlan ID: " . $row['plan_id'] . "\nCourse ID: " . $row['course_id'];
    $pdf->ChapterBody($body);
}

// Milestones
$pdf->ChapterTitle('Milestones');
while ($row = $milestones_result->fetch_assoc()) {
    $achieved = $row['achieved_date'] ? 'Yes' : 'No';
    $body = "Milestone: " . $row['milestone_name'] . "\nDate: " . $row['date'] . "\nAchieved: " . $achieved . "\nReward: " . $row['reward'];
    $pdf->ChapterBody($body);
}

$pdf->Output('D', 'progress_report.pdf');
