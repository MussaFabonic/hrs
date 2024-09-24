<?php
session_start();
    include('../models/leaves.php');
    $eT = getET($_REQUEST['userid']); 
    $d['leaveid'] = $_REQUEST['leaveType'];
    
    if(isset( $_REQUEST['lrid'])){
        $id = $_REQUEST['lrid'];
    }else{
        $id = 0;
    }

    $dets = getIdName('', 1,  $d['leaveid'], '', YEAR, $eT['emptypeid']);
    
    $response = array();

    foreach ((array)$dets as $ic) {
        $taken = getLeavesTaken($_POST['userid'], YEAR, $ic['id'], $id);
        $t = $taken ? $taken['taken'] : 0;
        
        $response[] = array(
            'text' => $ic['name'],
            'id' => $ic['id'],
            'total' => $ic['days'],
            'taken' => (int)$t,
            'bal' => (int)$ic['days'] - (int)$t,
        );
    }
    
    echo json_encode(['success' => true, 'details' => $response]);

?>
