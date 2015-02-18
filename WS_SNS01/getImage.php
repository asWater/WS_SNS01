<?php

require_once 'functions.php';

if (!isset($_GET['user']))
{
	die("Cannot get GET variant");
}
else
{
	$user = $_GET['user'];
}

$result = queryMysql_L("SELECT * FROM profiles WHERE user = '$user'");

$row = $result->fetch_array(MYSQLI_ASSOC);

header('Content-Type: image/jpeg');

echo $row['image'];

?>