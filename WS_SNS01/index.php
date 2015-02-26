<?php

ini_set("display_errors", "On");
error_reporting(E_ALL);


require_once 'header.php';

echo "<br><span class='main'>Welcome to the backwoods, ";

if ($loggedIn)
{
	showInformation_L($user);
	echo "<p> Relevant Messages </p>";
	showRelatedMessages_L($user);
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
