<?php

require_once 'functions.php';

if (isset($_POST['user']))
{
	$user = sanitizeString_L($_POST['user']);

	// Half-Width Alphanumeric Check
	if (!preg_match("/^[a-zA-Z0-9]+$/", $user))
	{
		echo "<span class='error'>&nbsp;&#x2718; " .
			 "User name must be half-width alphanumeric</span>";
	}
	else
	{
		//Length Check
		if (strlen($user) < 5)
		{
			echo "<span class='error'>&nbsp;&#x2718; " .
				 "User name must be more than 5 letters</span>";
		}
		else
		{
			$resultUser = queryMysql_L("SELECT * FROM members WHERE user='$user'");

			//Existence Check
			if ($resultUser->num_rows)
			{
				echo "<span class='taken'>&nbsp;&#x2718; " .
					 "Sorry, this user name is already used</span>";
			}
			else
			{
				echo "<span class='available'>&nbsp;&#x2714; " .
					 "This user name is available </span>";
			}
		}
	}

}

?>