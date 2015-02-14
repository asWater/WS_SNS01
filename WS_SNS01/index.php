<?php

	require_once 'header.php';

	echo "<br><span class='main'>Welcome to the backwoods, ";

	if ($loggedIn)
	{
		echo "$user, you are logged in.";
	}
	else
	{
		echo "please sign up and/or log in to join in.";
	}

?>

<!--
Following parts are closures of corresponding parts written in "header.php".
-->
	</span><br><br>
	</body>
</html>
