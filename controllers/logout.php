<?php
    include('../models/leaves.php');
    // include('../database/db.php');
    session_start();
    session_destroy();
	header('Location: ../views/pages/login.php' );
?>
