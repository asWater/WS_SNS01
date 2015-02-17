<?php //functions.php

define("SALTWORD", "!john#paul%george&ringo");

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
		$row = $result->fetch_array(MYSQLI_ASSOC);
		echo '<img src="'.$row['image'].'">';
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

?>
