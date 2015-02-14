<?php

require_once 'functions.php';

if (isset($_POST['user']))
{
	$user = sanitizeString_L($_POST[$user]);

	if (mysql_num_rows(queryMysql_L("SELECT * FROM members WHERE user = '$user'")))
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