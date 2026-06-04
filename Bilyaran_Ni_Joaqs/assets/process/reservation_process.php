<?php

require_once '../config/db.php';

$fullname = $_POST['fullname'];
$contact = $_POST['contact_number'];
$table_id = $_POST['table_id'];
$date = $_POST['reservation_date'];
$time = $_POST['reservation_time'];

mysqli_query(
    $conn,
    "
    INSERT INTO customers
    (
        fullname,
        contact_number
    )
    VALUES
    (
        '$fullname',
        '$contact'
    )
    "
);

$customer_id =
mysqli_insert_id($conn);

mysqli_query(
    $conn,
    "
    INSERT INTO reservations
    (
        customer_id,
        table_id,
        reservation_date,
        reservation_time,
        status
    )
    VALUES
    (
        '$customer_id',
        '$table_id',
        '$date',
        '$time',
        'pending'
    )
    "
);

header(
    "Location: ../../dashboard.php#reservations"
);

exit();

?>