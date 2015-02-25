<?php

session_start();	// This sencentece is need to be callded before HTML output.

echo "<!DOCTYPE html>\n<html><head>";
//echo "<!DOCTYPE html>\n<html><head><script src='OSC.js'></script>";

require_once 'functions.php';

$userStr = ' (Guest)';

if (isset($_SESSION['user']))
{
	$user = $_SESSION['user'];
	$loggedIn = TRUE;
	$userStr = " ($user)";
}
else
{
	$loggedIn = FALSE;
}

echo <<<_END
	<title>$appName$userStr</title>
	<link rel='stylesheet' href='styles.css' type='text/css'>
	</head>
	<body>
	<div class='appname'>$appName$userStr</div>
	<script src='https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js'></script>
	<script src='OSC.js'></script>
	<script src='checkSignup.js'></script>
_END;

if ($loggedIn)
{
	echo "<br><ul class='menu'>" .
		 "<li><a id='HomeLink1' href='index.php'>Home</a></li>" .
		 "<li><a id='MemberLink' href='members.php'>Members</a></li>" .
		 "<li><a id='FriendLink' href='friends.php'>Friends</a></li>" .
		 "<li><a id='ProfLink' href='members.php?view=$user'>Profile</a></li>" .
		 "<li><a id='EditProfLink' href='profile.php'>Edit Profile</a></li>" .
		 "<li><a id='GameLink' href='games/gameindex.html'>Games</a></li>" .
		 "<li><a id='LogoutLink' href='logout.php'>Log out</a></li>" .
		 "</ul><br>";
}
else
{
	echo ("<br /><ul class='menu'>" .
		  "<li><a id='HomeLink2' href='index.php'>Home</a></li>" .
		  "<li><a id='SignupLink' href='signup.php'>Sign up</a></li>" .
		  "<li><a id='LoginLink' href='login.php'>Log in</a></li>" .
		  "</ul><br>");
		  //"<span class='info'>&#8658; You must be logged in to View this page.</span><br><br>");
}

?>
