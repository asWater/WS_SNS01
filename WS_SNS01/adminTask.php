<?php

require_once 'header.php';

if (!$loggedIn || !$usrAdmin) die();

echo <<<_END
	<div class='main'>
_END;

if (isset($_POST['updUsr']) && isset($_POST['updPass']))
{
	updatePassword_L($_POST['updUsr'], $_POST['updPass']);
}
else
{
	echo "<br><span class='warning'>User or Pass to be updated should be specifiled.</span>";
}

echo <<<_END
<span class='messageTitle'><p>Password Update</p></span>
<form method='post' action='adminTask.php'>
	<span class='fieldname'>User Name</span><input type='text' class='inputfield' maxlength='16' name='updUsr'>
	<br>
	<span class='fieldname'>Password</span><input type='password' class='inputfield' maxlength='32' name='updPass'>
	<br>
	<span class='fieldname'>&nbsp;</span>
	<input id='submit_button' type='submit' value='Update'>
</form>
<br>
<br>
<span class='messageTitle'><p>User Information</p></span>
_END;


$res = queryMysql_L("SELECT user, email, modified, created, lastlogin, admin FROM members");

if ($res->num_rows)
{
	echo <<<_END
		<table id='msgTbl' class='messageTab usrTab'>
			<thead class='scrollHead'>
				<tr> <th class='user'>User</th>
					 <th class='email'>E-mail</th>
					 <th class='modified'>Modified</th>
					 <th class='created'>Created</th>
					 <th class='lastlogin'>Last Login</th>
					 <th class='admin'>Admin</th>
				</tr>
			</thead>
			<tbody class='scrollBody'>
_END;
	while($row = $res->fetch_array(MYSQLI_ASSOC))
	{
		echo <<<_END
			<tr>
				<td class='user'>{$row['user']}</td>
				<td class='email'>{$row{'email'}}</td>
				<td class='modified'>{$row['modified']}</td>
				<td class='created'>{$row['created']}</td>
				<td class='lastlogin'>{$row['lastlogin']}</td>
				<td class='admin'>{$row['admin']}</td>
			</tr>
_END;
	}
	echo "</tbody></table>";
}
else
{
	echo "No user data found in the table 'members'!";
}

echo "</div></body></html>";

?>