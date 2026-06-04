document.addEventListener(
    'dblclick',
    async function(e)
    {
        const tableCard =
            e.target.closest('.table-card');

        if(tableCard)
        {
            if(
                tableCard.dataset.status !==
                'occupied'
            )
            {
                return;
            }

            const tableId =
                tableCard.dataset.tableId;

            loadSessionByTable(
                tableId
            );
        }

        const activityRow =
            e.target.closest('.activity-row');

        if(activityRow)
        {
            loadSession(
                activityRow.dataset.sessionId
            );
        }
    }
);         

let billingInterval = null;

document.addEventListener('DOMContentLoaded', function ()
{
    initNavigation();
    initMobileMenu();
});

function initNavigation()
{
    document.querySelectorAll('.nav-link').forEach(link =>
    {
        link.addEventListener('click', function(e)
        {
            e.preventDefault();

            const section =
                this.dataset.section;

            showSection(section);
        });
    });
}

function initMobileMenu()
{
    const btn = document.getElementById('toggleSidebar');

    if(btn)
    {
        btn.addEventListener('click', () =>
        {
            document
                .getElementById('sidebar')
                .classList.toggle('show');
        });
    }
}

function showSection(sectionId)
{
    // Hide all sections
    document
        .querySelectorAll('.section')
        .forEach(section =>
        {
            section.classList.remove('active');
        });

    // Show selected section
    const selectedSection =
        document.getElementById(sectionId);

    if(selectedSection)
    {
        selectedSection.classList.add('active');
    }

    // Remove active class from sidebar links
    document
        .querySelectorAll('.nav-link')
        .forEach(link =>
        {
            link.classList.remove('active');
        });

    // Highlight matching sidebar link
    const activeLink =
        document.querySelector(
            `.nav-link[data-section="${sectionId}"]`
        );

    if(activeLink)
    {
        activeLink.classList.add('active');
    }
    
    history.replaceState(
        null,
        null,
        '#' + sectionId
    );
}

async function loadSessionByTable(
    tableId
)
{
    const response =
        await fetch(
            `assets/includes/get_active_session.php?table_id=${tableId}`
        );

    const session =
        await response.json();

    if(session)
    {
        loadSession(
            session.session_id
        );
    }
}

async function loadSession(
    sessionId
)
{
    const response =
        await fetch(
            `assets/includes/get_session.php?session_id=${sessionId}`
        );

    const data =
        await response.json();

    document
        .getElementById(
            'billingCustomer'
        )
        .innerText =
        data.fullname;

    document
        .getElementById(
            'billingTable'
        )
        .innerText =
        'Table ' +
        data.table_number;

    document
        .getElementById(
            'selectedSessionId'
        )
        .value =
        data.session_id;

    showSection('billing');

    startBillingTimer(
        data.start_time
    );
}

function startBillingTimer(
    startTime
)
{
    if(billingInterval)
    {
        clearInterval(
            billingInterval
        );
    }

    billingInterval =
    setInterval(
        () =>
        {
            const start =
                new Date(startTime);

            const now =
                new Date();

            const diff =
                Math.floor(
                    (
                        now - start
                    ) / 1000
                );

            const hours =
                Math.floor(
                    diff / 3600
                );

            const mins =
                Math.floor(
                    (
                        diff % 3600
                    ) / 60
                );

            const secs =
                diff % 60;

            document
                .getElementById(
                    'billingTimer'
                )
                .innerText =
                `${hours
                    .toString()
                    .padStart(2,'0')}:${
                    mins
                    .toString()
                    .padStart(2,'0')}:${
                    secs
                    .toString()
                    .padStart(2,'0')
                }`;

            const amount =
                (
                    diff
                    /
                    3600
                )
                *
                120;

            document
                .getElementById(
                    'totalAmount'
                )
                .innerText =
                '₱' +
                amount.toFixed(2);

        },
        1000
    );
}

function printReceipt()
{
    window.print();
}

async function refreshRecentActivity()
{
    const response =
        await fetch(
            'assets/includes/recent_activity.php'
        );

    const html =
        await response.text();

    document
        .getElementById(
            'recentActivity'
        )
        .innerHTML =
        html;
}

refreshRecentActivity();

setInterval(
    refreshRecentActivity,
    5000
);

async function loadSessionByTable(tableId)
{
    const response =
        await fetch(
            'assets/process/get_active_session.php?table_id='
            +
            tableId
        );

    const session =
        await response.json();

    if(session && session.session_id)
    {
        loadSession(
            session.session_id
        );
    }
}

async function loadSession(sessionId)
{
    const response =
        await fetch(
            'assets/process/get_session.php?session_id='
            +
            sessionId
        );

    const data =
        await response.json();

    if(!data)
    {
        return;
    }

    document
        .getElementById(
            'billingCustomer'
        )
        .innerHTML =
        data.fullname;

    document
        .getElementById(
            'billingTable'
        )
        .innerHTML =
        "Table " +
        data.table_number;

    document
        .getElementById(
            'selectedSessionId'
        )
        .value =
        data.session_id;

    startBillingTimer(
        data.start_time
    );

    showSection(
        'billing'
    );
}