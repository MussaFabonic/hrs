<?php
session_start();
// error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
// ERROR_REPORTING(0);
    include('../models/leaves.php');

    if(isset($_GET['id'])){
        $userId = $_GET['id'];
        $staffId = getField($userId );
        if(isset($staffId)){
            $staffId = $staffId['id']; 
        }else{
            $staffId = 0;
        }
    }else{
        $userId = 0;
        $staffId = 0;
    }

     
    
    if(isset($_REQUEST['leaveType'])){
		$leaveFromDateTime = new DateTime($_REQUEST['fromDate']);
		$leaveToDateTime = new DateTime($_REQUEST['toDate']);
		$dateDifference = $leaveFromDateTime->diff($leaveToDateTime);
		$daysDifference = $dateDifference->days;

        $staffId = getField($_REQUEST['userid'] );
        $staffId = $staffId['id'];  

        $d['staffid'] =  $staffId;
        $d['leaveid'] = $_REQUEST['leaveType'];
        $d['fromdte'] = $_REQUEST['fromDate'];
        $d['todte'] = $_REQUEST['toDate'];
        $d['noofdays'] = $daysDifference + 1;

        $LeaveRecords = insert($d,'LeaveRecords');
        setNotifForPendingLeaveRequests($staffId);
    }

    $leaveTypes = getAllLeaves();
    $_SESSION['leaveTypes'] = $leaveTypes;
    
    $results = getAllDets($staffId, YEAR);
    $_SESSION['leavesHistory'] = $results;
    $content = '../views/pages/leaves.php';
    $page = 'Leaves';
    include_once('../index.php');
?>
