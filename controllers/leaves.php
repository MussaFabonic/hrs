<?php
session_start();
// error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
// ERROR_REPORTING(0);
    include('../models/leaves.php');
    if(isset($_GET['id'])){
        $userId = $_GET['id'];
    }else{
        $userId = 0;
    }
    if(isset($_REQUEST['leaveType'])){
		$leaveFromDateTime = new DateTime($_REQUEST['fromDate']);
		$leaveToDateTime = new DateTime($_REQUEST['toDate']);
		$dateDifference = $leaveFromDateTime->diff($leaveToDateTime);
		$daysDifference = $dateDifference->days;

        $d['staffid'] =  $_REQUEST['userid'];
        $d['leaveid'] = $_REQUEST['leaveType'];
        $d['fromdte'] = $_REQUEST['fromDate'];
        $d['todte'] = $_REQUEST['toDate'];
        $d['noofdays'] = $daysDifference;
        // $d['noofdays'] = $daysDifference +  1;

        $LeaveRecords = insert($d,'LeaveRecords');
    }

   $leaveTypes = getAllLeaves();
    $_SESSION['leaveTypes'] = $leaveTypes;
    
    $results = getAllDets($userId, YEAR);
    $_SESSION['leavesHistory'] = $results;
    // print_r($_SESSION['leavesHistory']);die();
    $content = '../views/pages/leaves.php';
    $page = 'Leaves';
    include_once('../index.php');
?>
