let billingInterval = null;

document.addEventListener(
    "DOMContentLoaded",
    function ()
    {
        initNavigation();
        initMobileMenu();
        refreshRecentActivity();

        setInterval(
            refreshRecentActivity,
            5000
        );
    }
);

function initNavigation()
{
    document
        .querySelectorAll(".nav-link")
        .forEach(link =>
        {
            link.addEventListener(
                "click",
                function(e)
                {
                    e.preventDefault();

                    showSection(
                        this.dataset.section
                    );
                }
            );
        });
}

function initMobileMenu()
{
    const btn =
        document.getElementById(
            "toggleSidebar"
        );

    if(btn)
    {
        btn.addEventListener(
            "click",
            () =>
            {
                document
                    .getElementById(
                        "sidebar"
                    )
                    .classList.toggle(
                        "show"
                    );
            }
        );
    }
}

function showSection(
    sectionId
)
{
    document
        .querySelectorAll(
            ".section"
        )
        .forEach(section =>
        {
            section.classList.remove(
                "active"
            );
        });

    document
        .getElementById(
            sectionId
        )
        .classList.add(
            "active"
        );

    document
        .querySelectorAll(
            ".nav-link"
        )
        .forEach(link =>
        {
            link.classList.remove(
                "active"
            );
        });

    const activeLink =
        document.querySelector(
            `.nav-link[data-section="${sectionId}"]`
        );

    if(activeLink)
    {
        activeLink.classList.add(
            "active"
        );
    }
}

async function refreshTablesUI(tableId = null)
{
    // Do not reload; reload caused the UI to jump back to Dashboard.
    // (Table status will be updated after a session ends, since billing_process.php redirects.)
    return;
}



async function refreshRecentActivity()
{
    const response =
        await fetch(
            "assets/includes/recent_activity.php"
        );

    document
        .getElementById(
            "recentActivity"
        )
        .innerHTML =
        await response.text();
}

async function loadSessionByTable(
    tableId
)
{
    const response =
        await fetch(
            "assets/includes/get_active_session.php?table_id="
            +
            tableId
        );

    const session =
        await response.json();

    if(
        session &&
        session.session_id
    )
    {
        loadSession(
            session.session_id
        );
    }
}

async function loadSession(sessionId, tableId = null)
{
    const response = await fetch(
        "assets/includes/get_session.php?session_id=" + sessionId
    );

    const data = await response.json();

    if(!data)
    {
        return;
    }

    document.getElementById("billingCustomer").innerText = data.fullname;

    document.getElementById("billingTable").innerText =
        "Table " + data.table_number;

    document.getElementById("selectedSessionId").value =
    data.session_id;

    showSection("billing");

    if(data.status === "active")
    {
        startBillingTimer(data.start_time);
    }
    else
    {
        clearInterval(billingInterval);
        billingInterval = null;
    }

    refreshTablesUI(tableId);

}

function startBillingTimer(startTime)
{
    if (!startTime)
        return;

    const start = new Date(
        startTime.replace(" ", "T")
    );

    if (isNaN(start.getTime()))
    {
        console.log("Invalid start time:", startTime);
        return;
    }

    billingInterval = setInterval(function ()
    {
        const selectedSession =
            document.getElementById("selectedSessionId").value;

        if(!selectedSession)
        {
            clearInterval(billingInterval);
            billingInterval = null;
            return;
}
        const sessionId =
            document.getElementById("selectedSessionId").value;

        if(sessionId === "")
        {
            clearInterval(billingInterval);
            return;
        }

        // existing timer code...
    }, 1000);

    clearInterval(billingInterval);

    billingInterval = setInterval(function ()
    {
        const now = new Date();

        const diff = Math.max(
            0,
            Math.floor((now - start) / 1000)
        );

        const hrs = Math.floor(diff / 3600);
        const mins = Math.floor((diff % 3600) / 60);
        const secs = diff % 60;

        document.getElementById("billingTimer").innerText =
            String(hrs).padStart(2, "0") + ":" +
            String(mins).padStart(2, "0") + ":" +
            String(secs).padStart(2, "0");

        const amount = (diff / 3600) * 120;

        document.getElementById("totalAmount").innerText =
            "₱" + amount.toFixed(2);

    }, 1000);
}

async function endSession(e)
{
    if(e && e.preventDefault) e.preventDefault();

    const form = document.getElementById('billingEndSessionForm');
    if(!form) return;

    const sessionId = document.getElementById('selectedSessionId')?.value;
    if(!sessionId) return;

    const response = await fetch(form.action, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
        body: new URLSearchParams({ session_id: sessionId }).toString()
    });

    const data = await response.json().catch(() => null);

    if(data && data.success)
    {
        clearInterval(billingInterval);
        billingInterval = null;

        // Update only the final amount
        document.getElementById('totalAmount').innerText =
            '₱' + Number(data.total_amount).toFixed(2);

        refreshRecentActivity();
        refreshTablesUI();
    }
}

function printReceipt()
{
    window.print();
}

