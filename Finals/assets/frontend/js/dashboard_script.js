// Global state
let tables = {};
let activeSessions = {};
let timers = {};
let totalSales = 0;
let recentActivity = [];

// Initialize app
document.addEventListener('DOMContentLoaded', function() {
    initTables();
    initNavigation();
    initMobileMenu();
    updateStats();
    updateRecentActivity();
    updateAvailableTables();
});

// Navigation
function initNavigation() {
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const targetSection = link.dataset.section;
            
            // Update active nav
            document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
            link.classList.add('active');
            
            // Show target section
            document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
            document.getElementById(targetSection).classList.add('active');
        });
    });
}

// Mobile sidebar
function initMobileMenu() {
    document.getElementById('toggleSidebar').addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('show');
    });
}

// Initialize 12 tables
function initTables() {
    const tablesGrid = document.getElementById('tablesGrid');
    tablesGrid.innerHTML = '';
    
    for (let i = 1; i <= 12; i++) {
        tables[i] = {
            number: i,
            status: 'available',
            customer: null,
            startTime: null,
            duration: 0
        };
        
        const tableCard = createTableCard(i);
        tablesGrid.appendChild(tableCard);
    }
}

// Create table card HTML
function createTableCard(tableNum) {
    const div = document.createElement('div');
    div.className = 'col-xl-3 col-lg-4 col-md-6';
    div.dataset.table = tableNum;
    
    const tableData = tables[tableNum];
    const statusClass = tableData.status;
    const customerName = tableData.customer || '';
    const duration = formatDuration(tableData.duration);
    
    div.innerHTML = `
        <div class="table-card ${statusClass} h-100" onclick="toggleTable(${tableNum})">
            <div class="table-pool">
                <div class="table-pocket pocket-top-left"></div>
                <div class="table-pocket pocket-top-right"></div>
                <div class="table-pocket pocket-bottom"></div>
            </div>
            <div class="p-4 text-center">
                <div class="table-number">${tableNum}</div>
                <div class="status-badge ${getStatusBadgeClass(statusClass)}">${statusClass.toUpperCase()}</div>
                ${statusClass === 'occupied' ? `<div class="timer" data-timer="${tableNum}">${duration}</div>` : ''}
                ${customerName ? `<small class="d-block text-muted fw-semibold">${customerName}</small>` : '<small class="text-muted">Ready to play</small>'}
            </div>
        </div>
    `;
    
    return div;
}

// Toggle table status
function toggleTable(tableNum) {
    const tableData = tables[tableNum];
    
    if (tableData.status === 'available') {
        // Show customer modal instead of direct toggle
        document.getElementById('tableSelect').value = tableNum;
        new bootstrap.Modal(document.getElementById('customerModal')).show();
    } else {
        // End session for occupied tables
        endTableSession(tableNum);
    }
    
    updateTableDisplay(tableNum);
}

// Update table display
function updateTableDisplay(tableNum) {
    const tableCol = document.querySelector(`[data-table="${tableNum}"]`);
    const tableCard = tableCol.querySelector('.table-card');
    tableCard.className = `table-card ${tables[tableNum].status} h-100`;
    
    // Update content
    const tableData = tables[tableNum];
    const innerHTML = `
        <div class="table-pool">
            <div class="table-pocket pocket-top-left"></div>
            <div class="table-pocket pocket-top-right"></div>
            <div class="table-pocket pocket-bottom"></div>
        </div>
        <div class="p-4 text-center">
            <div class="table-number">${tableNum}</div>
            <div class="status-badge ${getStatusBadgeClass(tableData.status)}">${tableData.status.toUpperCase()}</div>
            ${tableData.status === 'occupied' ? `<div class="timer" data-timer="${tableNum}">${formatDuration(tableData.duration)}</div>` : ''}
            ${tableData.customer ? `<small class="d-block text-muted fw-semibold">${tableData.customer}</small>` : '<small class="text-muted">Ready to play</small>'}
        </div>
    `;
    
    tableCard.innerHTML = innerHTML;
}

// Start session
function startSession() {
    const customerName = document.getElementById('customerName').value.trim();
    const tableNum = parseInt(document.getElementById('tableSelect').value);
    
    if (!customerName || !tableNum) {
        alert('Please fill in all required fields');
        return;
    }
    
    // Update table data
    tables[tableNum] = {
        ...tables[tableNum],
        status: 'occupied',
        customer: customerName,
        startTime: Date.now(),
        duration: 0
    };
    
    // Start timer
    startTableTimer(tableNum);
    
    // Update billing display
    updateBillingDisplay(tableNum, customerName);
    
    // Add to recent activity
    recentActivity.unshift({
        table: tableNum,
        customer: customerName,
        duration: '00:00:00',
        amount: '0',
        status: 'Active'
    });
    
    // Close modal and update UI
    bootstrap.Modal.getInstance(document.getElementById('customerModal')).hide();
    document.getElementById('customerForm').reset();
    
    updateTableDisplay(tableNum);
    updateStats();
    updateRecentActivity();
    updateAvailableTables();
    
    // Switch to billing section
    showSection('billing');
}

// Start table timer
function startTableTimer(tableNum) {
    timers[tableNum] = setInterval(() => {
        if (tables[tableNum]?.status === 'occupied') {
            tables[tableNum].duration += 1000;
            updateTableDisplay(tableNum);
            updateBillingDisplay(tableNum);
            updateStats();
        }
    }, 1000);
}

// Update billing display
function updateBillingDisplay(tableNum, customerName = null) {
    const tableData = tables[tableNum];
    if (tableData?.status === 'occupied') {
        document.getElementById('billingCustomer').textContent = customerName || tableData.customer;
        document.getElementById('billingTable').textContent = `Table ${tableNum}`;
        document.getElementById('billingTimer').textContent = formatDuration(tableData.duration);
        document.getElementById('totalAmount').textContent = `₱${calculateBill(tableData.duration)}`;
    }
}

// End table session
function endTableSession(tableNum) {
    if (confirm(`End session for ${tables[tableNum].customer} on Table ${tableNum}?`)) {
        const tableData = tables[tableNum];
        const bill = calculateBill(tableData.duration);
        
        // Add final amount to sales
        totalSales += parseInt(bill.replace('₱', '').replace(',', ''));
        
        // Update recent activity
        const activityIndex = recentActivity.findIndex(a => a.table === tableNum);
        if (activityIndex !== -1) {
            recentActivity[activityIndex] = {
                table: tableNum,
                customer: tableData.customer,
                duration: formatDuration(tableData.duration),
                amount: bill,
                status: 'Paid'
            };
        }
        
        // Reset table
        tables[tableNum] = {
            number: tableNum,
            status: 'available',
            customer: null,
            startTime: null,
            duration: 0
        };
        
        // Stop timer
        if (timers[tableNum]) {
            clearInterval(timers[tableNum]);
            delete timers[tableNum];
        }
        
        // Reset billing display
        document.getElementById('billingCustomer').textContent = '--';
        document.getElementById('billingTable').textContent = '--';
        document.getElementById('billingTimer').textContent = '00:00:00';
        document.getElementById('totalAmount').textContent = '0';
        
        updateTableDisplay(tableNum);
        updateStats();
        updateRecentActivity();
        updateAvailableTables();
        
        alert(`Session ended!\nBill: ${bill}\n\nReceipt ready for printing.`);
    }
}

// End current billing session
function endSession() {
    const activeTable = Object.keys(timers)[0];
    if (activeTable) {
        endTableSession(parseInt(activeTable));
    } else {
        alert('No active session to end.');
    }
}

// Calculate bill (hours × ₱120)
function calculateBill(durationMs) {
    const hours = Math.ceil(durationMs / (1000 * 60 * 60));
    const amount = hours * 120;
    return `${amount.toLocaleString()}`;
}

// Format duration
function formatDuration(durationMs) {
    const seconds = Math.floor((durationMs / 1000) % 60);
    const minutes = Math.floor((durationMs / (1000 * 60)) % 60);
    const hours = Math.floor((durationMs / (1000 * 60 * 60)) % 24);
    
    return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
}

// Update stats
function updateStats() {
    const activeCount = Object.values(tables).filter(t => t.status === 'occupied').length;
    const occupancy = Math.round((activeCount / 12) * 100);
    
    document.getElementById('activeSessions').textContent = activeCount;
    document.getElementById('occupancyRate').textContent = `${occupancy}%`;
    document.getElementById('dailySales').textContent = `₱${totalSales.toLocaleString()}`;
}

// Update recent activity table
function updateRecentActivity() {
    const tbody = document.getElementById('recentActivity');
    tbody.innerHTML = '';
    
    recentActivity.slice(0, 5).forEach(activity => {
        const row = tbody.insertRow();
        row.innerHTML = `
            <td><strong>Table ${activity.table}</strong></td>
            <td>${activity.customer}</td>
            <td>${activity.duration}</td>
            <td><strong>${activity.amount}</strong></td>
            <td><span class="badge ${activity.status === 'Paid' ? 'bg-success' : 'bg-warning'}">${activity.status}</span></td>
        `;
    });
}

// Update available tables in modal
function updateAvailableTables() {
    const select = document.getElementById('tableSelect');
    select.innerHTML = '<option value="">Choose available table...</option>';
    
    Object.values(tables).forEach(table => {
        if (table.status === 'available') {
            const option = document.createElement('option');
            option.value = table.number;
            option.textContent = `Table ${table.number} - Available`;
            select.appendChild(option);
        }
    });
}

// Show specific section
function showSection(sectionId) {
    document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
    const link = document.querySelector(`[data-section="${sectionId}"]`);
    if (link) link.classList.add('active');
    
    document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
    document.getElementById(sectionId).classList.add('active');
}

// Print receipt
function printReceipt() {
    window.print();
}

// Status badge classes
function getStatusBadgeClass(status) {
    const classes = {
        'available': 'bg-success text-white',
        'occupied': 'bg-danger text-white',
        'reserved': 'bg-warning text-dark'
    };
    return classes[status] || 'bg-secondary text-white';
}