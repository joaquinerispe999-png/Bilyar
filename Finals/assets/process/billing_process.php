<?php

require_once '../config/db.php';

date_default_timezone_set('Asia/Manila');

if($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    header('Location: ../../dashboard.php');
    exit();
}

$session_id = (int)$_POST['session_id'];

$getSession = mysqli_query(
    $conn,
    "
    SELECT *
    FROM sessions
    WHERE session_id = $session_id
    AND status = 'active'
    "
);

$session = mysqli_fetch_assoc($getSession);

if(!$session)
{
    die("Session not found.");
}

$start = strtotime($session['start_time']);
$end   = time();

$hours = ($end - $start) / 3600;

// Total hours played (store this value even if rate/amount changes later)
$total_hours = round($hours, 2);

$rate = 120;

$total_amount = round($total_hours * $rate, 2);


mysqli_query(
    $conn,
    "
    UPDATE sessions
    SET
        end_time = NOW(),
        total_amount = $total_amount,
        total_hours = $total_hours,
        status = 'completed'
    WHERE session_id = $session_id
    "
);

mysqli_query(
    $conn,
    "
    INSERT INTO payments
    (
        session_id,
        amount
    )
    VALUES
    (
        $session_id,
        $total_amount
    )
    "
);

mysqli_query(
    $conn,
    "
    UPDATE billiard_tables
    SET status='available'
    WHERE table_id = {$session['table_id']}
    "
);

// Return JSON so the billing page can stay on-screen and update UI in-place
header('Content-Type: application/json; charset=utf-8');

mysqli_close($conn);

echo json_encode([
    'success' => true,
    'session_id' => $session_id,
    'total_hours' => $total_hours,
    'total_amount' => $total_amount
]);

exit();


?>