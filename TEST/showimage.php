<?php

require_once 'functions.php';

$result = queryMysql_L("SELECT * FROM testimage WHERE id = 1");

$row = mysqli_fetch_array($result);

header('Content-Type: image/jpeg');

//echo '<img src="'.$row[image].'">';
//echo "<img src='$row[image]'>";
echo $row['image'];

?>
