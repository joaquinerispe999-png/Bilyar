<?php

require_once '../config/db.php';

date_default_timezone_set('Asia/Manila');

// Prevent any warnings/notices from corrupting JSON output
mysqli_report(MYSQLI_REPORT_OFF);

$session_id = isset($_GET['session_id']) ? (int)$_GET['session_id'] : 0;

$query = mysqli_query(
    $conn,
    "
    SELECT
        s.*,
        c.fullname,
        b.table_number
    FROM sessions s
    JOIN customers c
        ON s.customer_id = c.customer_id
    JOIN billiard_tables b
        ON s.table_id = b.table_id
    WHERE s.session_id = $session_id
    "
);

$data = mysqli_fetch_assoc($query);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);

exit();