<?php

?>
<header id="header" class="header">
    <div class="top-left">
        <div class="navbar-header" style="height: 55px;">
            <a class="navbar-brand" href="./"><img src="<?php echo BASE_URL; ?>images/logo.png" alt="Logo"></a>
            <a class="navbar-brand hidden" href="./"><img src="<?php echo BASE_URL; ?>images/logo2.png" alt="Logo"></a>
            <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
        </div>
    </div>
    <div class="top-right">
        <div class="header-menu">
            <div class="header-left">
                <button class="search-trigger"><i class="fa fa-search"></i></button>
                <div class="form-inline">
                    <form class="search-form">
                        <input class="form-control mr-sm-2" type="text" placeholder="Search ..." aria-label="Search">
                        <button class="search-close" type="submit"><i class="fa fa-close"></i></button>
                    </form>
                </div>

                <div class="dropdown for-notification">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="notification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bell"></i>
                        <span class="count bg-danger">0</span>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="notification">
                        <p class="red">No New Notification</p>
                    </div>
                </div>

               
            </div>

            <div class="user-area dropdown float-right">
                <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="user-avatar rounded-circle" src="<?php echo BASE_URL; ?>images/admin1.jpg" alt="">
                </a>

                <div class="user-menu dropdown-menu">
                    <a class="nav-link" href="#"><i class="fa fa- user"></i><?php echo $_SESSION['name']?></a>

                    <!-- <a class="nav-link" href="#"><i class="fa fa- user"></i>Notifications <span class="count">13</span></a>

                    <a class="nav-link" href="#"><i class="fa fa -cog"></i>Settings</a> -->

                    <a class="nav-link" href="./logout.php"><i class="fa fa-power -off"></i>Logout</a>
                </div>
            </div>

        </div>
    </div>
</header>