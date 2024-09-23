<?php
    
    session_start();
    header('Content-Type: application/json');
    
    include('../models/leaves.php');
    $d['username'] = trim($_POST['username']);
    $d['password'] = trim($_POST['password']);
    $d['status'] = 1;
    
    if (empty($d['username']) || empty($d['password'])) {
        echo json_encode(['success' => false, 'error' => 'Please enter both Username and Password']);
        exit();
    } else {
        $d['password'] = md5($d['password']);
        $userInfo = find($d, '', '', '', 'Users');
       
        if (empty($userInfo) || $userInfo[0]['password'] != $d['password']) {
            echo json_encode(['success' => false, 'error' => 'Invalid Username/Password']);
            exit();
        } elseif ($userInfo[0]['status'] == 0) {
            echo json_encode(['success' => false, 'error' => 'You are not authorized to access this application']);
            exit();
        } else {
            $_SESSION['USER_ID'] = $userInfo[0]['id'];
            $_SESSION['name'] = $userInfo[0]['name'];
            $_SESSION['message'] = 'Successfully Logged In';
            echo json_encode(['success' => true, 'redirect' => '../../controllers/index.php']);
            exit();
        }
    }
    
?>
