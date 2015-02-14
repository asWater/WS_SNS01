<?php

require_once 'header.php';

echo <<<_END
<div class='main'><h3>Please enter your details to sign up</h3>
_END;
// This must be written at the first column of the last row.

$error = $user = $pass = $email = "";

if (isset($_SESSION['user']))
{
    destroySession_L();
}

if (isset($_POST['user']))
{
    $user = sanitizeString_L($_POST['user']);
    $pass = sanitizeString_L($_POST['pass']);
    $email = sanitizeString_L($_POST['email']);

    if ($user == "" || $pass == "" || $email == "")
    {
        $error = "Not all fields were entered<br><br>";
    }
    else
    {
        echo "passed field check<br>";
        echo "user = $user<br>";
        echo "email = $email<br>";
        $resultUser = queryMysql_L("SELECT * FROM members WHERE user='$user'");
        $resultEmail = queryMysql_L("SELECT * FROM members WHERE email='$email'");
        $numrow = $resultUser->num_rows;
        $numrow2 = $resultEmail->num_rows;
        echo "user row = $numrow<br>";
        echo "email row = $numrow2<br>";
        if ($resultUser->num_rows)
        {
            $error = "That username already exists<br><br>";
        }
        elseif ($resultEmail->num_rows)
        {
            $error = "That e-mail address already exists<br><br>";
        }
        else
        {
            echo "passed duplicate check<br>";
            $pass = hash('sha256', $pass.SALTWORD);
            echo $pass;
            exit(1);
            $dt = currentDateTime_L();
            queryMysql_L("INSERT INTO members (user, pass, email, created) VALUE('$user', '$pass', '$email', '$dt')");
            die("<h4>Account created</h4>Please log in.<br><br>");
        }
    }
}

function currentDateTime_L()
{

    try 
    {
        $date = new DateTime();
    } 
    catch (Exception $e) 
    {
        echo $e->getMessage();
        exit(1);
    }

    return $date->format('Y-m-d H:i:s');
}


echo <<<_END
<form method='post' action='signup.php'>$error

<span class='fieldname'>Username</span>
<input type ='text' maxlength='16' name='user' value='$user' onblur='checkUser_L(this)'/><span id='info'></span><br>

<span class='fieldname'>Password</span>
<input type='password' maxlength='16' name='pass' value='$pass'/><br>

<span class='fieldname'>E-mail Address</span>
<input type = 'text' maxlength='255' name='email' value='$email'/><br>
_END;

?>

    <span class='fieldname'>&nbsp;</span>
    <input type='submit' value='Sign up'>
    </form></div><br>
  </body>
</html>