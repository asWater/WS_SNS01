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
		echo "<p><span class='main'>&#9758; You have no profile information. What about creating your <strong><a href='profile.php'>profile</a></strong>?</span></p>";
	}

	$result = queryMysql_L("SELECT * FROM friends WHERE user = '$user'");
	if (!$result->num_rows)
	{
		echo "<p><span class='main'>&#9758; You have no friends. What about finding <strong><a href='members.php'>friends</a></strong>?</span></p>";
	}

	echo "<p><span class='main'>&#9758; What about enjoying <strong><a href='games/gameindex.html'>Games</a></strong>?</p></span>";

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
