<?php

ini_set("display_errors", "On");
error_reporting(E_ALL);


require_once 'header.php';

echo "<br><span class='main'>Welcome to the backwoods, ";

if ($loggedIn)
{
	showInformation_L($user);
	echo "<div class='main'>";
	echo "<br>";
	echo "<span class='messageTitle'><p>Relevant Messages</p></span>";
	echo "<table class='messageTab'>";
	echo "<tr> <th>Sender</th><th>Reciever</th><th>Privacy</th><th>Date-Time</th><th>Message</th> </tr>";
	showRelatedMessages_L($user);
	echo "</table>";
	echo "</div>";
}
else
{
	echo "please <strong><a href='signup.php'>Sign Up</a></strong> and/or <strong><a href='login.php'>Log In</a></strong> to join in.</span>";
}

?>

<!--
Following parts are closures of corresponding parts written in "header.php".
-->
	</span><br><br>
	</body>
</html>
