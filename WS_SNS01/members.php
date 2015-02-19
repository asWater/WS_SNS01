<?php

require_once 'header.php';

if (!$loggedIn) die();

echo "<div class = 'main'>";

if (isset($_GET['view']))
{
	$view = sanitizeString_L($_GET['view']);

	if ($view == $user)
	{
		$name = "Your";
		echo "<p><a class='button' href='profile.php'>Edit Profile</a></p><br>";
	}
	else
	{
		$name = "$view's";
	}

	echo "<h3>$name Profile</h3>";

	showProfile_L($view);

	echo "<p><a class='button' href='messages.php?view=$view'>View $name messages</a></p><br><br>";

	die("</div></body></html>");
}

if (isset($_GET['add']))
{
	$add = sanitizeString_L($_GET['add']);

	$result = queryMysql_L("SELECT * FROM friends WHERE user='$add' AND friend='$user'");
    
    if (!$result->num_rows)
    {
      queryMysql_L("INSERT INTO friends (user, friend) VALUES ('$add', '$user')");
    }
}
elseif (isset($_GET['remove']))
{
	$remove = sanitizeString_L($_GET['remove']);
	queryMysql_L("DELETE FROM friends WHERE user='$remove' AND friend='$user'");
}

$result = queryMysql_L("SELECT user FROM members ORDER BY user");
$num = $result->num_rows;

echo "<h3>Other Members</h3><ul>";

for ($j = 0; $j < $num; $j++)
{
	$row = $result->fetch_array(MYSQLI_ASSOC);

	if ($row['user'] == $user) continue;

	echo "<li><a href='members.php?view=" . $row['user'] . "'>" . $row['user'] . "</a>";

	$follow = "follow";

	$result1 = queryMysql_L("SELECT * FROM friends WHERE user='" . $row['user'] . "' AND friend='$user'");
	$following = $result1->num_rows;

	$result1 = queryMysql_L("SELECT * FROM friends WHERE user='$user' AND friend='" . $row['user'] . "'");
	$followed = $result1->num_rows;

	if (($following + $followed) > 1)
	{
		echo " &harr; is a mutual friend";
	}
	elseif ($following)
	{
		echo " &larr; you are following";
	}
	elseif ($followed)
	{
		echo " &rarr; is following you";
	}

	if (!$following)
	{
		echo "[<a href='members.php?add=" . $row['user'] . "'>$follow</a>]";
	}
	else
	{
		echo "[<a href='members.php?remove=" . $row['user'] . "'>drop</a>]";
	}

}

?>

<br>
    </ul></div>
  </body>
</html>
