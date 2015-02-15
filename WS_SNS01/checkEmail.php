<?php

require_once 'functions.php';

if (isset($_POST['email']))
{
	$email = sanitizeString_L($_POST['email']);

	// Check whether valid e-mail address or not 
	//if (!preg_match("/[0-9a-z!#\$%\&'\*\+\/\=\?\^\|\-\{\}\.]+@[0-9a-z!#\$%\&'\*\+\/\=\?\^\|\-\{\}\.]+/" , $email)) 
	if (!preg_match('|^[0-9a-z_./?-]+@([0-9a-z-]+\.)+[0-9a-z-]+$|', $email))
	{
		echo "<span class='error'>&nbsp;&#x2718; " .
			 "Invalid E-mail Address</span>";
	}
	else
	{
    	//echo "<span class='available'>&nbsp;&#x2714; " .
		//	 "Syntax of E-mail address is OK </span>";

		$resultUser = queryMysql_L("SELECT * FROM members WHERE email='$email'");

		// Existence Check
		if ($resultUser->num_rows)
		{
			echo "<span class='taken'>&nbsp;&#x2718; " .
				 "Sorry, this E-mail address is already used</span>";
		}
		else
		{
			echo "<span class='available'>&nbsp;&#x2714; " .
				 "This E-mail address is available </span>";
		}
	}
}

?>