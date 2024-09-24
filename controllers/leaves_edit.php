<?php
session_start();
include '../models/leaves.php';

$results = getAllDetsEdit('', YEAR, $_REQUEST['lid']);
$_SESSION['dets'] = $results;
$content = '../views/pages/leaves_edit.php';
$page = 'Leaves Edit';
include_once '../index.php';
?>
