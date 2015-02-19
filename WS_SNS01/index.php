<?php

ini_set("display_errors", "On");
error_reporting(E_ALL);


require_once 'header.php';

echo "<br><span class='main'>Welcome to the backwoods, ";

if ($loggedIn)
{
	echo "$user, you are logged in.";

	$result = queryMysql_L("SELECT * FROM profiles WHERE user = '$user'");
	if (!$result->num_rows)
	{
		echo "<p><span class='main'>&#9758; You have no profile information. What about creating your <a href='profile.php'>profile</a>?</span></p>";
	}

	$result = queryMysql_L("SELECT * FROM friends WHERE user = '$user'");
	if (!$result->num_rows)
	{
		echo "<p><span class='main'>&#9758; You have no friend. What about finding <a href='members.php'>friends</a>?</span></p>";
	}
}
else
{
	echo "please <strong><a href='signup.php'>Sign Up</a></strong> and/or <strong><a href='login.php'>Log In</a></strong> to join in.";
}

?>

<!--
Following parts are closures of corresponding parts written in "header.php".
-->
	</span><br><br>
	</body>
</html>
