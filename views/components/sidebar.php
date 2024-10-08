<?php

?>
<aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">
        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="nav-item">
                    <a href="./home.php?id=<?php echo $_SESSION['USER_ID']; ?>" class="nav-link">
                        <i class="menu-icon fa fa-laptop"></i> Home
                    </a>
                </li>
                <li class="menu-title">Menus</li>
                <li class="nav-item dropdown menu-item-has-children">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="menu-icon fa fa-cogs"></i> Employees
                    </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li>
                            <i class="fa fa-puzzle-piece"></i>
                            <a href="./leaves.php?id=<?php echo $_SESSION['USER_ID']; ?>" class="nav-link">Leaves</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</aside>


