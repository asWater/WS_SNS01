<?php

require_once 'functions.php';

if (isset($_POST['user']))
{
	$user = sanitizeString_L($_POST['user']);

	$resultUser = queryMysql_L("SELECT * FROM members WHERE user='$user'");

	if ($resultUser->num_rows)
	{
		echo "<span class='taken'>&nbsp;&#x2718; " .
			 "Sorry, this user name is taken</span>";
	}
	else
	{
		echo "<span class='available'>&nbsp;&#x2714; " .
			 "This user name is available </span>";
	}
}

?>