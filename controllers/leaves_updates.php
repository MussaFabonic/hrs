<?php
session_start();
    include('../models/leaves.php');
    $d['leaveid'] = $_REQUEST['leaveType'];
    $id = $_REQUEST['id'];
    $d['fromdte'] = $_REQUEST['fromDate'];
    $d['todte'] = $_REQUEST['toDate'];

    $leaveFromDateTime = new DateTime($_REQUEST['fromDate']);
    $leaveToDateTime = new DateTime($_REQUEST['toDate']);
    $dateDifference = $leaveFromDateTime->diff($leaveToDateTime);
    $daysDifference = $dateDifference->days;

    $d['noofdays'] = $daysDifference;
    // $d['noofdays'] = $daysDifference +  1;

    $details = update($id, $d,'Leaverecords');
    if($details != ''){
    echo json_encode(['success' => true]);
        
    }

?>
