<?php

require_once 'config/auth.php';

$username = $_SESSION['username'];

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
            <h4 class="mb-0"><i class="fas fa-cue me-2"></i>BilliardPro</h4>
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
                        <h3 id="totalTables">12</h3>
                        <p class="mb-0">Total Tables</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card text-center" data-stat="active">
                        <i class="fas fa-play-circle fa-2x mb-3"></i>
                        <h3 id="activeSessions">4</h3>
                        <p class="mb-0">Active Sessions</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card text-center" data-stat="sales">
                        <i class="fas fa-coins fa-2x mb-3"></i>
                        <h3 id="dailySales">₱18,450</h3>
                        <p class="mb-0">Daily Sales</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card text-center" data-stat="occupancy">
                        <i class="fas fa-chart-line fa-2x mb-3"></i>
                        <h3 id="occupancyRate">67%</h3>
                        <p class="mb-0">Occupancy Rate</p>
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
                                        <!-- Dynamic content -->
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
                <!-- Tables will be generated by JavaScript -->
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
                            <div class="mb-3"><strong>Customer:</strong> <span id="billingCustomer">--</span></div>
                            <div class="mb-3"><strong>Table:</strong> <span id="billingTable">--</span></div>
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
                    <button class="btn btn-success btn-lg me-3" id="endSessionBtn" onclick="endSession()">
                        <i class="fas fa-stop me-2"></i>End Session
                    </button>
                    <button class="btn btn-primary btn-lg" onclick="printReceipt()">
                        <i class="fas fa-print me-2"></i>Print Receipt
                    </button>
                </div>
            </div>
        </div>

        <!-- Other sections (placeholders) -->
        <div id="customers" class="section">
            <div class="card p-5">
                <h3 class="text-center mb-4">Customer Records</h3>
                <p class="text-center text-muted">Customer management coming soon...</p>
            </div>
        </div>

        <div id="reservations" class="section">
            <div class="card p-5">
                <h3 class="text-center mb-4">Reservation System</h3>
                <p class="text-center text-muted">Reservation system coming soon...</p>
            </div>
        </div>

        <div id="reports" class="section">
            <div class="card p-5">
                <h3 class="text-center mb-4">Reports</h3>
                <p class="text-center text-muted">Daily sales, table usage reports coming soon...</p>
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
                    <form id="customerForm">
                        <div class="mb-3">
                            <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="customerName" placeholder="Enter customer name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="customerContact" placeholder="09xxxxxxxxx">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Select Table <span class="text-danger">*</span></label>
                            <select class="form-select" id="tableSelect" required>
                                <option value="">Choose available table...</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="startSession()">
                        <i class="fas fa-play me-2"></i>Start Session
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/frontend/js/dashboard_script.js"></script>
</body>
    </html>