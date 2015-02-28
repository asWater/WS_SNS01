<?php

ini_set("display_errors", "On");
error_reporting(E_ALL);


require_once 'header.php';

echo "<br><span class='main'>Welcome to the backwoods, ";

if ($loggedIn)
{
	echo "$user, you are logged in.</span>";
	showInformation_L($user);

	if (isset($_POST['text']))
	{
		$text = sanitizeString_L($_POST['text']);

		if ($text != "")
		{
			$privacy = 0;
			$view = NA;
			$time = currentDateTime_L();

			queryMysql_L("INSERT INTO messages (sender, receiver, privacy, time, message) VALUES ('$user', '$view', '$privacy', '$time', '$text')");

		}
	}

echo <<<_END
	<div class='main'>

		<br>
		<span class='messageTitle'><p>Relevant Messages</p></span>

		<div class='form-horizontal filterArea'>
			<label class='col-sm-0 control-label'>Sender:</label>
			<span class='col-sm-0 sender-filter-area'></span>
			
			<label class='col-sm-1 control-label'>Receiver:</label>
			<span class='col-sm-1 receiver-filter-area'></span>
			
			<label class='col-sm-2 control-label'>Privacy:</label>
			<span class='col-sm-2 privacy-filter-area'></span>

			<label class='col-sm-3 control-label'>Message:</label>
			<span class='col-sm-3 message-filter-area'></span>
		</div>
		
		<table id='msgTbl' class='messageTab'>
			<thead class='scrollHead'>
				<tr> <th class='sender'>Sender</th>
					 <th class='receiver'>Reciever</th>
					 <th class='privacy'>Privacy</th>
					 <th class='dateTime'>Date-Time</th>
					 <th class='message'>Message</th>
				</tr>
			</thead>
			<tbody class='scrollBody'>
_END;
	
	showRelatedMessages_L($user);
	
	echo <<<_END
			</tbody>
		</table>
		
		<br>
		<span class='messageTitle'><p>Monology</p></span>
		<form method='post' action='index.php'>
			<textarea class='messageText' name='text' cols='40' rows='3'></textarea>
			<br>
		    <input id='submit_button' type='submit' value='Post Message'>
	    </form>
	    <br>
	</div>
_END;
}
else
{
	echo "please <strong><a href='signup.php'>Sign Up</a></strong> and/or <strong><a href='login.php'>Log In</a></strong> to join in.</span>";
}

?>

<!--
Following parts are closures of corresponding parts written in "header.php".
-->
	</span><br><br>
	</body>
</html>
