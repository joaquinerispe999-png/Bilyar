<?php

require_once '../config/db.php';

date_default_timezone_set('Asia/Manila');

$query = mysqli_query(
    $conn,
    "
    SELECT
        s.session_id,
        s.table_id,
        s.start_time,
        s.end_time,
        s.status,
        s.total_amount,
        c.fullname,
        b.table_number
    FROM sessions s

    INNER JOIN customers c
        ON s.customer_id = c.customer_id

    INNER JOIN billiard_tables b
        ON s.table_id = b.table_id

    ORDER BY s.session_id DESC

    LIMIT 10
    "
);

while($activity = mysqli_fetch_assoc($query))
{
    if($activity['status'] == 'active')
    {
        // In progress: show placeholder duration text.
        $duration = 'In progress';

        // In progress: show amount as blank (per requirement)
        $amount = null;

    }


    else
    {
        if(
            !empty(
                $activity['end_time']
            )
        )
        {
            $seconds = max(
                0,
                strtotime($activity['end_time'])
                -
                strtotime($activity['start_time'])
            );

            $hours = round($seconds / 3600, 2);
            $duration = $hours . ' hrs';
        }
        else
        {
            $duration = '-';
        }

        $amount =
            $activity['total_amount'];
    }

    ?>

    <tr
        ondblclick="loadSession(<?php echo $activity['session_id']; ?>, <?php echo $activity['table_id']; ?>)"
        style="cursor:pointer;"
    >

        <td>
            Table
            <?php echo $activity['table_number']; ?>
        </td>

        <td>
            <?php
            echo htmlspecialchars(
                $activity['fullname']
            );
            ?>
        </td>

        <td>
            <?php echo $duration; ?>
        </td>

        <td>
            <?php if($activity['status'] == 'active' || $amount === null): ?>
                
            <?php else: ?>
                ₱<?php echo number_format($amount, 2); ?>
            <?php endif; ?>
        </td>


        <td>

            <?php

            if(
                $activity['status']
                == 'active'
            )
            {
                ?>

                <span
                    class="badge bg-success"
                >
                    Active
                </span>

                <?php
            }
            else
            {
                ?>

                <span
                    class="badge bg-secondary"
                >
                    Completed
                </span>

                <?php
            }

            ?>

        </td>

    </tr>

    <?php
}
?>