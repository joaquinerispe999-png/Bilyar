<?php

require_once 'assets/config/auth.php';
require_once 'assets/config/db.php';

$username = $_SESSION['username'];

// Total Tables
$totalTables = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM billiard_tables"
    )
)['total'];

// Active Sessions
$activeSessions = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM sessions
         WHERE status='active'"
    )
)['total'];

// Daily Sales
$dailySales = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COALESCE(SUM(amount),0) AS total
         FROM payments
         WHERE DATE(payment_date)=CURDATE()"
    )
)['total'];

// Occupancy
$occupancy = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "
        SELECT
        ROUND(
            (
                COUNT(*) * 100 /
                (SELECT COUNT(*) FROM billiard_tables)
            ),
            0
        ) AS occupancy
        FROM billiard_tables
        WHERE status='occupied'
        "
    )
)['occupancy'];

$tablesQuery = mysqli_query(
    $conn,
    "SELECT * FROM billiard_tables
     ORDER BY table_number"
);
?>

<!DOCTYPE html>
    <html>
        <head>
            <meta >      
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Bilyaran ni Joaqs</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="assets/frontend/css/dashboard_style.css">
        </head>
        <body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header text-center">
            <h4 class="mb-0"><i class="fas fa-cue me-2"></i>Bilyaran ni Joaqs</h4>
            <small class="opacity-75">Management System</small>
        </div>
        <nav class="sidebar-nav mt-4">
            <a class="nav-link active" href="#dashboard" data-section="dashboard">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
            <a class="nav-link" href="#tables" data-section="tables">
                <i class="fas fa-table-cells me-2"></i>Table Management
            </a>
            <a class="nav-link" href="#billing" data-section="billing">
                <i class="fas fa-receipt me-2"></i>Billing
            </a>
            <a class="nav-link" href="#customers" data-section="customers">
                <i class="fas fa-users me-2"></i>Customers
            </a>
            <a class="nav-link" href="#reservations" data-section="reservations">
                <i class="fas fa-calendar-check me-2"></i>Reservations
            </a>
            <a class="nav-link" href="#reports" data-section="reports">
                <i class="fas fa-chart-bar me-2"></i>Reports
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg mb-4">
            <div class="container-fluid">
                <button class="btn btn-primary d-lg-none me-3" id="toggleSidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <span class="navbar-brand mb-0 h1">
                    <i class="fas fa-cue me-2"></i>Billiard Management
                </span>
                <div class="ms-auto">
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i><span id="userRole"> <?php echo htmlspecialchars($username); ?></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Dashboard Section -->
        <div id="dashboard" class="section active">
            <div class="row g-4 mb-5">
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card text-center" data-stat="tables">
                        <i class="fas fa-table-cells fa-2x mb-3"></i>
                        <h3 id="totalTables"><?php echo $totalTables; ?></h3>
                        <p class="mb-0">Total Tables</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card text-center" data-stat="active">
                        <i class="fas fa-play-circle fa-2x mb-3"></i>
                        <h3 id="activeSessions"><?php echo $activeSessions; ?></h3>
                        <p class="mb-0">Active Sessions</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card text-center" data-stat="sales">
                        <i class="fas fa-coins fa-2x mb-3"></i>
                        <h3 id="dailySales">₱<?php echo number_format($dailySales, 2); ?></h3>
                        <p class="mb-0">Daily Sales</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card text-center" data-stat="occupancy">
                        <i class="fas fa-chart-line fa-2x mb-3"></i>
                        <h3 id="occupancyRate"><?php echo $occupancy; ?>%</h3>
                        <p class="mb-0">Occupancy Rate(Today)</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4"><i class="fas fa-chart-pie me-2"></i>Recent Activity</h5>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Table</th>
                                            <th>Customer</th>
                                            <th>Duration</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="recentActivity">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-4"><i class="fas fa-clock me-2"></i>Quick Actions</h5>
                            <button class="btn btn-primary w-100 mb-3" data-bs-toggle="modal" data-bs-target="#customerModal">
                                <i class="fas fa-plus me-2"></i>New Session
                            </button>
                            <button class="btn btn-success w-100 mb-3" onclick="showSection('billing')">
                                <i class="fas fa-receipt me-2"></i>View Billing
                            </button>
                            <button class="btn btn-outline-primary w-100" onclick="showSection('reports')">
                                <i class="fas fa-chart-bar me-2"></i>Reports
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Section -->
        <div id="tables" class="section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4><i class="fas fa-table-cells me-2 text-primary"></i>Table Management</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#customerModal">
                    <i class="fas fa-plus me-2"></i>New Session
                </button>
            </div>
            <div class="row g-4" id="tablesGrid">
                <?php while ($table = mysqli_fetch_assoc($tablesQuery)): ?>
                    <div class="col-md-4">
                        <div
                            class="card shadow-sm table-card"

                            <?php
                            if(
                                $table['status'] == 'occupied'
                            )
                            {
                                ?>
                                ondblclick="loadSessionByTable(<?php echo $table['table_id']; ?> )"
                                style="cursor:pointer;"
                                <?php
                            }
                            ?>
                        >
                            <div class="card-body text-center">
                                <h5>
                                    Table <?php echo $table['table_number']; ?>
                                </h5>
                                <span class="badge
                                <?php
                                if ($table['status'] == 'available')
                                    echo 'bg-success';
                                elseif ($table['status'] == 'occupied')
                                    echo 'bg-danger';
                                else
                                    echo 'bg-warning';
                                ?>
                                ">
                                    <?php echo ucfirst($table['status']); ?>
                                </span>
                            </div>                
                        </div>                
                    </div>                
                <?php endwhile; ?>
            </div>
        </div>

        <!-- Billing Section -->
        <div id="billing" class="section">
            <div class="card p-5 text-center">
                <h3 class="mb-4"><i class="fas fa-receipt me-2 text-primary"></i>Billing System</h3>
                <div class="customer-info mb-4" id="billingInfo">
                    <h5>Current Session</h5>
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <div class="mb-3"><strong>Customer:</strong> <span id="billingCustomer"></span></div>
                            <div class="mb-3"><strong>Table:</strong> <span id="billingTable"></span></div>
                            <div class="mb-3"><strong>Rate:</strong> <span id="hourlyRate">₱120</span>/hour</div>
                        </div>
                        <div class="col-md-7">
                            <div class="timer fs-1 mb-3" id="billingTimer">00:00:00</div>
                            <div class="h2 mb-0 fw-bold text-primary" id="totalAmount">₱0</div>
                            <small class="text-muted">Hours Played × Rate Per Hour</small>
                        </div>
                    </div>
                </div>
                <div>
<form
                        id="billingEndSessionForm"
                        action="assets/process/billing_process.php"
                        method="POST"
                        onsubmit="return endSession(event);"
                    >

                        <input
                            type="hidden"
                            id="selectedSessionId"
                            name="session_id"
                            value=""
                        >
                        
                        <button
                            type="submit"
                            class="btn btn-success btn-lg"
                        >
                            <i class="fas fa-stop me-2"></i>
                            End Session
                        </button>

                    </form>

                    <button class="btn btn-primary btn-lg" onclick="printReceipt()">
                        <i class="fas fa-print me-2"></i>Print Receipt
                    </button>
                </div>
            </div>
        </div>

        <!-- Customer Section -->
        <div id="customers" class="section">
            <div class="card p-5">
                <h3 class="text-center mb-4">Customer Records</h3>
                <p class="text-center text-muted">
                    <?php

                    $customers = mysqli_query(
                        $conn,
                        "SELECT *
                        FROM customers
                        ORDER BY customer_id DESC"
                    );

                    ?>
                    
                    <div class="table-responsive">
                    
                        <table class="table table-striped">
                    
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Contact</th>
                                </tr>
                            </thead>
                    
                            <tbody>
                    
                                <?php while ($customer = mysqli_fetch_assoc($customers)): ?>
                    
                                    <tr>
                                        <td><?php echo $customer['customer_id']; ?></td>
                                        <td><?php echo htmlspecialchars($customer['fullname']); ?></td>
                                        <td><?php echo htmlspecialchars($customer['contact_number']); ?></td>
                                    </tr>
                    
                                <?php endwhile; ?>
                    
                            </tbody>
                    
                        </table>
                    
                    </div>
                </p>
            </div>
        </div>
        <!-- Reservation Section -->
        <div id="reservations" class="section">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>
                    <i class="fas fa-calendar-check me-2 text-primary"></i>
                    Reservation Management
                </h4>

                <button
                    class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#reservationModal"
                >
                    <i class="fas fa-plus me-2"></i>
                    New Reservation
                </button>
            </div>

            <?php

            $reservations = mysqli_query(
                $conn,
                "
                SELECT
                    r.*,
                    c.fullname,
                    b.table_number
                FROM reservations r
                LEFT JOIN customers c
                ON r.customer_id = c.customer_id
                LEFT JOIN billiard_tables b
                ON r.table_id = b.table_id
                ORDER BY r.reservation_date DESC,
                        r.reservation_time DESC
                "
            );

            ?>

            <div class="card">
                <div class="card-body">

                    <table class="table table-hover">

                        <thead>

                            <tr>
                                <th>Customer</th>
                                <th>Table</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>

                        </thead>

                        <tbody>

                        <?php while($reservation = mysqli_fetch_assoc($reservations)): ?>

                            <tr>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $reservation['fullname']
                                    );
                                    ?>
                                </td>

                                <td>
                                    Table
                                    <?php
                                    echo $reservation['table_number'];
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo $reservation['reservation_date'];
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo $reservation['reservation_time'];
                                    ?>
                                </td>

                                <td>

                                    <span class="badge bg-primary">

                                    <?php
                                    echo ucfirst(
                                        $reservation['status']
                                    );
                                    ?>

                                    </span>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                        </tbody>

                    </table>

                </div>
            </div>

        </div>
        <!-- Reports Section -->
        <div id="reports" class="section">
            <div class="card p-5">
                <h3 class="text-center mb-4">Reports</h3>
                <p class="text-center text-muted">
                    <div class="row">

                        <div class="col-md-4">

                        <div class="card p-4 text-center">

                        <h5>Daily Sales</h5>

                        <h2>
                        ₱<?php echo number_format($dailySales, 2); ?>
                                </h2>
                    
                            </div>
                    
                        </div>
                    
                        <div class="col-md-4">
                    
                            <div class="card p-4 text-center">
                    
                                <h5>Occupancy Rate</h5>
                    
                                <h2>
                                    <?php echo $occupancy; ?>%
                                </h2>
                    
                            </div>
                    
                        </div>
                    
                        <div class="col-md-4">
                    
                            <div class="card p-4 text-center">
                    
                                <h5>Total Tables</h5>
                    
                                <h2>
                                    <?php echo $totalTables; ?>
                                </h2>
                    
                            </div>
                    
                        </div>
                    
                    </div>
                </p>
            </div>
        </div>
    </div>

    <!-- Customer Modal -->
    <div class="modal fade" id="customerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-plus me-2 text-primary"></i>Start New Session</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="customerForm" action="assets/process/customer_process.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="customerName" placeholder="Enter customer name" name="fullname"
                            required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="customerContact" name="contact_number" placeholder="09xxxxxxxxx">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Select Table <span class="text-danger">*</span></label>
                            <select
                                class="form-select"
                                id="tableSelect"
                                name="table_id"
                                required
                            >
                                <option value="">
                                    Choose available table...
                                </option>

                                <?php
                                $availableTables = mysqli_query(
                                    $conn, "SELECT * FROM billiard_tables WHERE status = 'available' ORDER BY table_number ASC");

                                while($table = mysqli_fetch_assoc($availableTables))
                                {
                                    ?>

                                    <option
                                        value="<?php echo $table['table_id']; ?>"
                                    >
                                        Table <?php echo $table['table_number']; ?>
                                    </option>

                                    <?php
                                }

                                ?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button
                        type="submit"
                        form="customerForm"
                        class="btn btn-primary"
                    >
                        <i class="fas fa-play me-2"></i>
                        Start Session
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reservation Modal -->

    <div
        class="modal fade"
        id="reservationModal"
        tabindex="-1"
    >

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        New Reservation
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>

                <div class="modal-body">

                    <form
                        id="reservationForm"
                        action="assets/process/reservation_process.php"
                        method="POST"
                    >

                        <div class="mb-3">

                            <label class="form-label">
                            Customer Name
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                name="fullname"
                                required
                            >

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                            Contact Number
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                name="contact_number"
                            >

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                            Table
                            </label>

                            <select
                                class="form-select"
                                name="table_id"
                                required
                            >
                            <option value="">
                                Select Table
                            </option>

                            <?php

                                $tables =
                                    mysqli_query(
                                        $conn,
                                        "
                                        SELECT *
                                        FROM billiard_tables
                                        ORDER BY table_number
                                        "
                                    );

                                while (
                                    $table =
                                    mysqli_fetch_assoc($tables)
                                ) {
                                    ?>
    
                                    <option value="<?php
                                    echo $table['table_id'];
                                    ?>">
    
                                        Table
                                        <?php
                                        echo $table['table_number'];
                                        ?>
    
                                    </option>
    
                                    <?php
                                }
                            ?>
    
                            </select>
    
                        </div>
    
                        <div class="mb-3">
    
                            <label class="form-label">
                                Reservation Date
                            </label>
    
                            <input type="date" class="form-control" name="reservation_date" required>
    
                        </div>
    
                        <div class="mb-3">
    
                            <label class="form-label">
                                Reservation Time
                            </label>
    
                            <input type="time" class="form-control" name="reservation_time" required>
    
                        </div>
    
                    </form>
    
                </div>
    
                <div class="modal-footer">
    
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
    
                    <button type="submit" form="reservationForm" class="btn btn-primary">
                        Save Reservation
                    </button>
    
                </div>
    
            </div>
    
        </div>
    
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/frontend/js/dashboard_script.js"></script>
</body>
    </html>