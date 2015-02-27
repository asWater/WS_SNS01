<?php

ini_set("display_errors", "On");
error_reporting(E_ALL);


require_once 'header.php';

echo "<br><span class='main'>Welcome to the backwoods, ";

if ($loggedIn)
{
	showInformation_L($user);

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
	echo "</tbody>";
	echo "</table>";
	echo "</div>";
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
