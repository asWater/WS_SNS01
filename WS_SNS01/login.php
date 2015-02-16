<?php

require_once "header.php";

echo "<div class='main'><h3>Please enter User Name & Password to log in. </h3>";

$error = $user = $pass = "";

if (isset($_POST['user']))
{
	$user = sanitizeString_L($_POST['user']);
	$pass = sanitizeString_L($_POST['pass']);

	if ($user == "" || $pass == "")
	{
		$error = "Not all fields were entered <br><br>";
	}
	else
	{
		$pass = hashPass_L($pass);

		$result = queryMysql_L("SELECT user, pass FROM members WHERE user='$user' AND pass='$pass'");

		if ($result->num_rows == 0)
		{
			//echo "user = $user <br>";
			//echo "pass = $pass <br>";
			$error = "<span class='error'>Username/Password invalid</span><br><br>";
		}
		else
		{
			// Update DB with logon date
			$cdt = currentDateTime_L();
			$result = queryMysql_L("UPDATE members SET lastlogin = '$cdt' WHERE user='$user'");

			// Session data set 
			$_SESSION['user'] = $user;
			$_SESSION['pass'] = $pass;

			die("You are now logged in. Please <a href='members.php?view=$user'> Click Here <a/> to continue.<br><br>");
		}
	}
}

echo <<<_END
<form method='post' action='login.php'><font color=#ff0000> $error </font>
<span class='fieldname'>User Name</span><input type='text' maxlength='16' name='user' value='$user'><br>
<span class='fieldname'>Password</span><input type='password' maxlength='32' name='pass' value='$pass'><br>
_END
?>

<br>
<span class='fieldname'>&nbsp;</span>
<input type='submit' value='Log In'>
</form>
<br>
</div>
</body>
</html>
