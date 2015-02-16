<?php

require_once 'header.php';

echo <<<_END
<div class='main'><h3>Please enter your details to sign up</h3>
_END;
// This must be written at the first column of the last row.

$error = $user = $pass = $passAgain = $email = "";

if (isset($_SESSION['user']))
{
    destroySession_L();
}

if (isset($_POST['user']))
{
    $user      = sanitizeString_L($_POST['user']);
    $pass      = sanitizeString_L($_POST['pass']);
    $passAgain = sanitizeString_L($_POST['passAgain']);
    $email     = sanitizeString_L($_POST['email']);

    if ($user == "" || $pass == "" || $passAgain == "" || $email == "")
    {
        $error = "Not all fields were entered<br><br>";
    }
    else
    {
        //DB Access
        $resultUser = queryMysql_L("SELECT * FROM members WHERE user='$user'");
        $resultEmail = queryMysql_L("SELECT * FROM members WHERE email='$email'");

        //Input Analysis
        if (!preg_match("/^[a-zA-Z0-9]+$/", $user))
        {
            $error = "User name must be half-width alphanumeric.<br><br>";
        }
        elseif ($resultUser->num_rows)
        {
            $error = "That username already exists.<br><br>";
        }
        elseif (strlen($user) < 5)
        {
            $error = "User Name must be more than 5 letters.<br><br>";
        }
        elseif (strlen($pass) < 8)
        {
            $error = "Password must be more than 8 letters.<br><br>";
        }
        elseif ($pass != $passAgain)
        {
            $error = "Password confirmation was failed.<br><br>";
        }
        //elseif (!preg_match("/[0-9a-z!#\$%\&'\*\+\/\=\?\^\|\-\{\}\.]+@[0-9a-z!#\$%\&'\*\+\/\=\?\^\|\-\{\}\.]+/" , $email))
        elseif (!preg_match('|^[0-9a-z_./?-]+@([0-9a-z-]+\.)+[0-9a-z-]+$|', $email))
        {
            $error = "Invalid e-mail address<br><br>";
        }
        elseif ($resultEmail->num_rows)
        {
            $error = "That e-mail address already exists.<br><br>";
        }
        else
        {
            echo "Passed several checks<br>";
            echo "Currently Sign-up is disabled";

            $pass = hashPass_L($pass);
            $dt = currentDateTime_L();

            //echo "$pass <br>";
            //echo "$dt <br>";
            exit(1);

            queryMysql_L("INSERT INTO members (user, pass, email, created) VALUE('$user', '$pass', '$email', '$dt')");
            die("<h4>Account created</h4>Please log in.<br><br>");
        }
    }
}


echo <<<_END
<form id='signup' method='post' action='signup.php'><font color=#ff0000> $error </font>

<span class='fieldname'>Username</span>
<input type ='text' maxlength='16' name='user' value='$user' onkeyup='checkUser_L(this)' onblur='checkUser_L(this)'/><span id='userInfo'></span><br>

<span class='fieldname'>Password</span>
<input type='password' maxlength='32' name='pass' value='$pass' onkeyup='checkPass_L(this)'/><span id='passInfo'></span><br>

<span class='fieldname'>Confirm Password</span>
<input type='password' maxlength='32' name='passAgain' value='$passAgain' onblur='confirmPass_L(this)'/><span id='passConf'></span><br>

<span class='fieldname'>E-mail Address</span>
<input type = 'text' maxlength='255' name='email' value='$email' onkeyup='checkEmail_L(this)' onblur='checkEmail_L(this)'/><span id='emailInfo'></span><br>
_END;

?>

    <span class='fieldname'>&nbsp;</span>
    <input id='submit_button' type='submit' value='Sign up'>
    </form></div><br>
  </body>
</html>
