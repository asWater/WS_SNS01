<?php

require_once 'header.php';

if (!$loggedIn) die();

if (isset($_POST['updUsr']) && isset($_POST['updPass']))
{
	updatePassword_L($_POST['updUsr'], $_POST['updPass']);
}
else
{
	echo "<br><span class='error'>User or Pass to be updated should be specifiled.</span>";
}


echo <<<_END
	<div class='main'>
		<br>
		<span class='messageTitle'><p>Update Your Password</p></span>

		<form method='post' action='changePass.php'>
			<span class='fieldname'>User Name</span><input type='text' class='inputfield' maxlength='16' name='updUsr' value='$user' readonly="readonly">
			<br>
			<span class='fieldname'>Password</span><input type='password' class='inputfield' maxlength='32' name='updPass'>
			<br>
			<span class='fieldname'>&nbsp;</span>
			<input id='submit_button' type='submit' value='Update'>
		</form>
		<br>
		<br>
	</div>
</body>
</html>
_END;
?>
