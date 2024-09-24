<?php
session_start();
    include('../models/leaves.php');
    real_delete($_REQUEST['id'], "Leaverecords");
    echo json_encode(['success' => true, 'details' => $response]);

?>
