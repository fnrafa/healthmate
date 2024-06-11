<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link {{($sidebar == 'dashboard')? '': 'collapsed'}}" href="/">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{($sidebar == 'request')? '': 'collapsed'}}" href="/request">
                <i class="bi bi-envelope"></i>
                <span>Request</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{($sidebar == 'consultation')? '': 'collapsed'}}" href="/consultation">
                <i class="bi bi-chat"></i>
                <span>Consultation</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{($sidebar == 'history')? '': 'collapsed'}}" href="/history">
                <i class="bi bi-journal-text"></i>
                <span>History</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="/logout">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</aside>