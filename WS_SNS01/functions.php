<?php //functions.php

define("SALTWORD", "!john#paul%george&ringo");
define("NA", "N/A");

$dbHost  = 'localhost';
$dbName  = 'WSDB';
$dbUser  = 'root';
$dbPass  = 'test1234';	//Need to do something
$appName = "Tiny Network";

/* mysql is Obsolete. msyqli should be used.
mysql_connect($dbHost, $dbUser, $dbPass) or die(mysql_error());
mysql_select_db($dbName) or die(mysql_error());
*/

$connection = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($connection->connect_error) die($connection->connect_error);

/**
 * [createTable_L description]
 * @param  [type] $tabName [description]
 * @param  [type] $query   [description]
 * @return [type]          [description]
 * 
 * <<Example Parameters>>
 * $tabName = 'members'
 * $query = 'user VARCHAR(16), pass VARCHAR(16), INDEX(user(6))'
 */
function createTable_L($tabName, $query)
{
	queryMysql_L("CREATE TABLE IF NOT EXISTS $tabName($query)");
	echo "Table '$tabName' was created or already exists. <br>";
}


function queryMysql_L($query)
{
	/* Obsolete usage with mysql* functions
	$result = mysql_query($query) or die(mysql_error());
	return $result;
	*/

	global $connection;
    $result = $connection->query($query);
    if (!$result) die($connection->error);
    return $result;
}

function destroySession_L()
{
	$_SESSION = array();

	if (session_id() != "" | isset($_COOKIE[session_name()]))
	{
		setcookie(session_name(), '', time()-2592000, '/');	// 2592000 = 60(sec) * 60(min) * 24(hrs) * 30(days) i.e. 1 Month
	}
	session_destroy();
}

function sanitizeString_L($var)
{
	global $connection;
	$var = strip_tags($var);	//指定した文字列 (str) から全ての NUL バイトと HTML および PHP タグを取り除きます
	$var = htmlentities($var);	//この関数はhtmlspecialchars()と同じですが、 HTML エンティティと等価な意味を有する文字をHTMLエンティティに変換します。
								// e.g. <b>bold</b> -> &lt;b&gt;bold&lt;/b&gt
	$var = stripslashes($var);	//バックスラッシュでクォートされた文字列を元に戻す. "It\'s mine." -> It's mine.

	return $connection->real_escape_string($var);	//例えばINSERTする値にシングルクォーテーション(')などが含まれていた場合SQL文がおかしくなってしまいますが、この関数を通すことでSQL文の中で直接記述できないような値に対して「¥」を使ってエスケープ処理を行ってくれます。
													// e.g. "book's" -> "book¥'s"
}

function showProfile_L($user)
{
	$result = queryMysql_L("SELECT intro, image FROM profiles WHERE user = '$user'");

	if ($result->num_rows)
	{
		echo "<img src='getImage.php?user=$user' style='float:left;'>";
		$row = $result->fetch_array(MYSQLI_ASSOC);
		echo stripslashes($row['intro']) . "<br style='clear:left;'><br>";
	}
}

function hashPass_L($pass)
{
	$pass = hash('sha256', $pass.SALTWORD);
	return $pass;
}

function currentDateTime_L()
{
	// If [date.timezone = "Asia/Tokyo"] is not set in "php.ini" file at the server side, this will issue the error.
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

function showGameRanking($gameName, $sortDesc)
{
	$r = 0;
	$tmpScore = 0;

	if($sortDesc)
	{
		$result = queryMysql_L("SELECT * FROM gamescores WHERE game = '$gameName' ORDER BY score DESC LIMIT 5");
	}
	else
	{
		$result = queryMysql_L("SELECT * FROM gamescores WHERE game = '$gameName' ORDER BY score LIMIT 5");
	}


	if ($result->num_rows)
	{
		while ($row = $result->fetch_array(MYSQLI_ASSOC))
		{
			if ($tmpScore !== $row['score'])
			{
				$tmpScore = $row['score'];
				$r++;
			}

			echo "<tr><td>$r</td><td>" . $row['user'] . "</td><td>" . $row['score'] . "</td></tr>";
		}
	}
	else
	{
		echo "<h4>No score data yet</h4>";
	}
}

function showInformation_L($user)
{
	$result = queryMysql_L("SELECT * FROM profiles WHERE user = '$user'");
	if (!$result->num_rows)
	{
		echo "<p><span class='main'>&#9758; You have no profile information. What about creating your <strong><a href='profile.php'>profile</a></strong>?</span></p>";
	}

	$result = queryMysql_L("SELECT * FROM friends WHERE user = '$user'");
	if (!$result->num_rows)
	{
		echo "<p><span class='main'>&#9758; You have no friends. What about finding <strong><a href='members.php'>friends</a></strong>?</span></p>";
	}

	echo "<p><span class='main'>&#9758; What about enjoying <strong><a href='games/gameindex.html'>Games</a></strong>?</p></span>";
}

function showRelatedMessages_L($user)
{
	// Get only username whom the user is following.
	$result = queryMysql_L("SELECT user FROM friends WHERE friend = '$user'");

	// The user is following someone.
	if ($result->num_rows)
	{
		while ($row = $result->fetch_array(MYSQLI_ASSOC))
		{
			$usrRows[] = $row['user'];
		}

		//$myName = array('user' => $user);
		array_push($usrRows, $user);	// Add myself to sender list.

		$queryParam = "";

		for ($i = 0; $i < count($usrRows); $i++)
		{
			if ($i === (count($usrRows) - 1))
			{
				$queryParam = $queryParam . "'" . $usrRows[$i] . "'";
			}
			else
			{
				$queryParam = $queryParam . "'" . $usrRows[$i] . "',";
			}
		}

		$result2 = queryMysql_L("SELECT * FROM messages WHERE sender IN ($queryParam) OR receiver = '$user' ORDER BY time DESC");

	}
	else
	{
		$result2 = queryMysql_L("SELECT * FROM messages WHERE sender = '$user' OR receiver = '$user' ORDER BY time DESC");
	}

	// The user has relevant messages
	if($result2->num_rows)
	{
		$num = $result2->num_rows;

		while ($row2 = $result2->fetch_array(MYSQLI_ASSOC))
		{
			$showOK = false;
			// Message was sent to the user or user sent
			if (($row2['sender'] === $user) || ($row2['receiver'] === $user))
			{
				$showOK = true;
			}
			// Public message
			if ($row2['privacy'] == 0)
			{
				$showOK = true;
			}


			if ($showOK)
			{
				$trHTML = "<tr>";

				switch ($row2['privacy'])
				{
					case 0:
						$row2['privacy'] = "Public";
						break;
					case 1:
						$row2['privacy'] = "Private";
						$trHTML = "<tr class='privRow'>";
						break;
					default:
						break;
				}

				if ($row2['receiver'] === NA)
				{
					$trHTML = "<tr class='monoLog'>";
				}

				$senderLinkStartHTML = "";
				$receiverLinkStartHTML = "";
				$linkCloseHTML = "";

				if ($row2['sender'] !== $user)
				{
					$senderLinkStartHTML = "<a href='messages.php?view=" . $row2['sender'] . "'>";
					$linkCloseHTML = "</a>";
				}
				
				if (($row2['receiver'] !== $user) && ($row2['receiver'] !== "N/A"))
				{
					$receiverLinkStartHTML = "<a href='messages.php?view=" . $row2['receiver'] . "'>";
					$linkCloseHTML = "</a>";
				}

				echo <<<_END
				$trHTML
					<td class='sender'>$senderLinkStartHTML{$row2['sender']}$linkCloseHTML</td>
				   	<td class='receiver'>$receiverLinkStartHTML{$row2['receiver']}$linkCloseHTML</td>
					<td class='privacy'>{$row2['privacy']}</td>
					<td class='dateTime'>{$row2['time']}</td>
					<td class='message'>{$row2['message']}</td>
				</tr>			
_END;
			}

		}

	}
	else
	{
		echo "You don't have any relevant messages.";
	}

}

?>
