<?php

require_once 'header.php';

if(!$loggedIn) die();

if (isset($_GET['view']))
{
	$view = sanitizeString_L($_GET['view']);
}
else
{
	$view = $user;
}


if (isset($_POST['text']))
{
	$text = sanitizeString_L($_POST['text']);

	if ($text != "")
	{
		$privacy = substr(sanitizeString_L($_POST['privacy']), 0, 1);
		$time = currentDateTime_L();
		queryMysql_L("INSERT INTO messages (sender, receiver, privacy, time, message) VALUES ('$user', '$view', '$privacy', '$time', '$text')");
		echo "To: $view <br>";
		echo "From: $user <br>";
		if($privacy) echo "Privacy: On<br>";
		echo "Message was sent.<br>";
	}
}

if ($view != "")
{
	if ($view == $user)
	{
		$name1 = $name2 = "Your";
	}
	else
	{
		$name1 = "<a href='members.php?view=$view'>$view</a>'s";
		$name2 = "$view's";
	}

	echo "<div class='main'><h3>$name1 Messages</h3>";

	showProfile_L($view);

	echo <<<_END
      <form method='post' action='messages.php?view=$view'>
      Type here to leave a message:<br>
      <textarea name='text' cols='40' rows='3'></textarea><br>
      Public<input type='radio' name='privacy' value='0' checked='checked'>
      Private<input type='radio' name='privacy' value='1'>
      <input type='submit' value='Post Message'></form><br>
_END;

	if (isset($_GET['erase']))
	{
		$erase = sanitizeString_L($_GET['erase']);
		queryMysql_L("DELETE FROM messages WHERE id = $erase and receiver = '$user'");
		echo "Message was deleted<br>";
	}

	$result = queryMysql_L("SELECT * FROM messages WHERE receiver='$view' ORDER BY time DESC");
    $num    = $result->num_rows;

    for ($j = 0; $j < $num; $j++)
    {
    	$row = $result->fetch_array(MYSQLI_ASSOC);

    	if ($row['privacy'] == 0 || $row['sender'] == $user || $row['receiver'] ==$user)
    	{
    		echo $row['time'] . "> ";
    		echo "<a href='messages.php?view=" . $row['sender'] . "'>" . $row['sender']. "</a> ";

    		if ($row['privacy'] == 0)	//Public Message.
    		{
    			echo "wrote: &quot;" . $row['message'] . "&quot; ";
    		}
    		else 	//Private Message.
    		{
    			echo "whispered: <span class='whisper'>&quot;" . $row['message'] . "&quot;</span> ";
    		}

    		if ($row['receiver'] == $user)
    		{
    			echo "[<a href='messages.php?view=$view&erase=" . $row['id'] . "'>erase</a>]";
    		}

    		echo "<br>";

    	}
    }
}

if (!$num)
{
	echo "<br><span class='info'>No messages yet</span><br><br>";
}

echo "<br><a class='button' href='messages.php?view=$view'>Refresh messages</a>";

?>

</div><br>
</body>
</html>