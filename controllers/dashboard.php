<?php
session_start();
include('../database/db.php');

if (isset($_POST['userid'])) {
    $eT = getET($_POST['userid']); 
    $dets = getIdName('', 1, '', '', YEAR, $eT['emptypeid']);
    
    $response = array();

    foreach ((array)$dets as $ic) {
        $taken = getLeavesTaken($_POST['userid'], YEAR, $ic['id']);
        $t = $taken ? $taken['taken'] : 0;
        
        $response[] = array(
            'text' => $ic['name'],
            'id' => $ic['id'],
            'total' => $ic['days'],
            'taken' => (int)$t,
            'bal' => (int)$ic['days'] - (int)$t,
        );
    }
    
    $_SESSION['results'] = $response;
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit(); 
} else {
    echo json_encode(['error' => 'User ID not provided']);
    exit();
}
