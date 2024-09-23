<?php

?>
<aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">
        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li>
                    <a href="./home.php?id=<?php echo $_SESSION['USER_ID']; ?>"><i class="menu-icon fa fa-laptop"></i>Home </a>
                </li>
                <li class="menu-title">Menus</li>
                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-cogs"></i>Employees</a>
                    <ul class="sub-menu children dropdown-menu">
                    <li><i class="fa fa-puzzle-piece"></i><a href="./leaves.php?id=<?php echo $_SESSION['USER_ID']; ?>">Leaves</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</aside>

