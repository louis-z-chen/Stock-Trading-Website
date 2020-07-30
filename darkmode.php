<?php
session_start();

$d = $_POST['darkmode'];

echo $d;

if($d == 'true') {
	$_SESSION['darkmode'] = true;
} else {
	$_SESSION['darkmode'] = false;
}

?>