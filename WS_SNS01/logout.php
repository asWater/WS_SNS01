<?php

require_once('header.php');

if(isset($_SESSION['user']))
{
	destroySession_L();
	//echo "<div class='main'>You have been logged out. Please <a href='index.php'>Click here</a> to refresh the screen.";
	header("location: login.php");
}
else
{
	echo "<div class='main'><br>You cannot logout because you are not logged in";
}

?>

<br>
<br>
</div>
</body>
</html>

