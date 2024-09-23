<?php
    session_start();
    include('../database/db.php');

    $eT = getET($_GET['id']);
    $dets = getIdName('', 1, '', '', YEAR, $eT['emptypeid']);
    
    
    $response = array();

    foreach ((array)$dets as $ic) {
        $taken = getLeavesTaken($_GET['id'], YEAR, $ic['id']);
        if ( !$taken ) $t = 0;
        else $t = $taken['taken'];
        $obj=null;
        $response['text'] =$ic['name'];
        $response['id'] = $ic['id'];
        $response['total'] = $ic['days'];
        $response['taken'] = (int)$t;
        $response['bal']= (int)$ic['days'] - (int)$t;
    }
    
    $_SESSION['results'] = $response;
	header('Location: ./' );
?>
