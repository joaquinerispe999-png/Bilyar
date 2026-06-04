<?php

require_once '../config/db.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    header('Location: ../../dashboard.php');
    exit();
}

$fullname =
trim($_POST['fullname']);

$contact =
trim($_POST['contact_number']);

$table_id =
(int)$_POST['table_id'];

if(empty($fullname))
{
    header('Location: ../../dashboard.php');
    exit();
}

$stmt = mysqli_prepare(
    $conn,
    "
    INSERT INTO customers
    (
        fullname,
        contact_number
    )
    VALUES
    (
        ?,
        ?
    )
    "
);

mysqli_stmt_bind_param(
    $stmt,
    "ss",
    $fullname,
    $contact
);

mysqli_stmt_execute($stmt);

$customer_id =
mysqli_insert_id($conn);

$sessionStmt = mysqli_prepare(
    $conn,
    "
    INSERT INTO sessions
    (
        customer_id,
        table_id,
        start_time,
        status
    )
    VALUES
    (
        ?,
        ?,
        NOW(),
        'active'
    )
    "
);

mysqli_stmt_bind_param(
    $sessionStmt,
    "ii",
    $customer_id,
    $table_id
);

mysqli_stmt_execute($sessionStmt);

$updateTable = mysqli_prepare(
    $conn,
    "
    UPDATE billiard_tables
    SET status='occupied'
    WHERE table_id=?
    "
);

mysqli_stmt_bind_param(
    $updateTable,
    "i",
    $table_id
);

mysqli_stmt_execute($updateTable);

header(
    "Location: ../../dashboard.php"
);

exit();

?>