<?php

require_once 'header.php';

if (!$loggedIn) die();

if (isset($_POST['updUsr']) && isset($_POST['pass']))
{
    $updUsr    = sanitizeString_L($_POST['updUsr']);
    $curPass   = sanitizeString_L($_POST['curPass']);
    $updPass   = sanitizeString_L($_POST['pass']);
    $passAgain = sanitizeString_L($_POST['passAgain']);
	
	if ($updPass == "" || $curPass == "" || $updPass == "" || $passAgain == "")
    {
        echo "<span class='error main'>Not all fields were entered.</span>";
    }
    else
    {
    	$curPass = hashPass_L($curPass);

    	$res = queryMysql_L("SELECT * FROM members WHERE user='$updUsr' AND pass='$curPass'");

    	if ($res->num_rows)
    	{
	        if (strlen($updPass) < 8)
	        {
	             echo "<span class='error main'>Password must be more than 8 letters.</span>";
	        }
	        elseif ($updPass != $passAgain)
	        {
	             echo "<span class='error main'>Password confirmation was failed.</span>";;
	        }
	        else
	        {
	        	updatePassword_L($updUsr, $updPass);
	        }    		
    	}
    	else
    	{
    		echo "<span class='error main'>Current User/Password are not valid!</span>";
    	}


	}
}
else
{
	echo "<br><span class='main warning'>User or Pass to be updated should be specifiled.</span>";
}


echo <<<_END
	<div class='main'>
		<br>
		<span class='messageTitle'><p>Update Your Password</p></span>

		<form id='signup' method='post' action='changePass.php'>
			<span class='fieldname'>User Name</span><input type='text' class='inputfield readonly' maxlength='16' name='updUsr' value='$user' readonly="readonly">
			<br>
			<span class='fieldname'>Current Password</span><input type='password' class='inputfield' maxlength='32' name='curPass'>
			<br>
			<span class='fieldname'>New Password</span><input type='password' class='inputfield' maxlength='32' name='pass' onkeyup='checkPass_L(this)'><span id='passInfo'></span>
			<br>
			<span class='fieldname'>Confirm Password</span><input type='password' class='inputfield' maxlength='32' name='passAgain' onblur='confirmPass_L(this)'><span id='passConf'></span>
			<br>
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
