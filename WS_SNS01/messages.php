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
		if ($privacy) echo "Privacy: On<br>"; else echo "Privacy: Off<br>";
		echo "Message was sent.<br><br>";
	}
}

if ($view != "")
{
	echo "<div class='main'>";

	if ($view === $user)
	{
		$name1 = $name2 = "Your";
	}
	else
	{
		$name1 = "<a href='members.php?view=$view'>$view</a>'s";
		$name2 = "$view's";

		showProfile_L($view);

		echo <<<_END
	      <form method='post' action='messages.php?view=$view'>
	      Type here to leave a message:<br>
	      <textarea name='text' cols='40' rows='3'></textarea><br>
	      Public<input type='radio' name='privacy' value='0' checked='checked'>
	      Private<input type='radio' name='privacy' value='1'>
	      <input id='submit_button' type='submit' value='Post Message'></form><br>
_END;
	}

	echo "<h3>$name1 Messages</h3>";

	if ($view === $user)
	{
		echo "<table class='messageTab maintMessageTab'>";
	}
	else
	{
		echo "<table class='messageTab'>";
	}

	echo <<<_END
			<thead class='scrollHead'>
				<tr> <th class='sender'>Sender</th>
					 <th class='receiver'>Reciever</th>
					 <th class='privacy'>Privacy</th>
					 <th class='dateTime'>Date-Time</th>
					 <th class='message'>Message</th>
_END;

	if ($view === $user)
	{
		echo "<th class='erase'>Erase</th>";
	}

	echo <<<_END
				</tr>
			</thead>
			<tbody class='scrollBody'>
_END;


	if (isset($_GET['erase']))
	{
		$erase = sanitizeString_L($_GET['erase']);
		queryMysql_L("DELETE FROM messages WHERE id = $erase and sender = '$user'");
		echo "Message was deleted<br>";
	}

	$result = queryMysql_L("SELECT * FROM messages WHERE sender='$view' ORDER BY time DESC");
    $num    = $result->num_rows;

	if (!$num)
	{
		echo "<br><span class='info'>No messages yet</span><br><br>";
	}
	else
	{
		for ($j = 0; $j < $num; $j++)
	    {
	    	$row = $result->fetch_array(MYSQLI_ASSOC);
	    	$trHTML = "<tr>";

	    	//If you use strict comparison "===" for privacy, it does not return anything.
	    	if ($row['privacy'] == 0 || $row['receiver'] === $user || $row['sender'] === $user)
	    	{
	    		if ($row['privacy'] == 1)
	    		{
	    			$trHTML = "<tr class='privRow'>";
	    		}
	    		elseif ($row['receiver'] === NA)
	    		{
	    			$trHTML = "<tr class='monoLog'>";
	    		}

	    		echo <<<_END
					$trHTML
						<td class='sender'>{$row['sender']}</td>
					   	<td class='receiver'>{$row['receiver']}</td>
						<td class='privacy'>{$row['privacy']}</td>
						<td class='dateTime'>{$row['time']}</td>
						<td class='message'>{$row['message']}</td>
_END;

	    		if ($row['sender'] === $user)
	    		{
	    			echo "<td class='erase'><a href='messages.php?view=$view&erase=" . $row['id'] . "'>Erase</a></td>";
	    		}

	    		echo "</tr>";

	    	}
	    }
	}

    echo "</tbody></table>";
}

echo "<br><a class='button' href='messages.php?view=$view'>Refresh messages</a>";

?>

</div><br>
</body>
</html>