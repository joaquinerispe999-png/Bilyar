<?php

require_once '../config/db.php';

date_default_timezone_set('Asia/Manila');

$table_id =
(int)$_GET['table_id'];

$query = mysqli_query(
    $conn,
    "
    SELECT *
    FROM sessions
    WHERE table_id = $table_id
    AND status='active'
    LIMIT 1
    "
);

echo json_encode(
    mysqli_fetch_assoc($query)
);