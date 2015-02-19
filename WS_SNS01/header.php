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
	<link rel='stylesheet' href='styles.css' type='text/css' />
	</head>
	<body>
	<div class='appname'>$appName$userStr</div>
	<script src='OSC.js'></script>
	<script src='checkSignup.js'></script>
_END;

if ($loggedIn)
{
	echo "<br><ul class='menu'>" .
		 "<li><a href='index.php?view=$user'>Home</a></li>" .
		 "<li><a href='members.php'>Members</a></li>" .
		 "<li><a href='friends.php'>Friends</a></li>" .
		 "<li><a href='members.php?view=$user'>Profile</a></li>" .
		 "<li><a href='profile.php'>Edit Profile</a></li>" .
		 "<li><a href='logout.php'>Log out</a></li>" .
		 "</ul><br>";
}
else
{
	echo ("<br /><ul class='menu'>" .
		  "<li><a href='index.php'>Home</a></li>" .
		  "<li><a href='signup.php'>Sign up</a></li>" .
		  "<li><a href='login.php'>Log in</a></li>" .
		  "</ul><br>");
		  //"<span class='info'>&#8658; You must be logged in to View this page.</span><br><br>");
}

?>
