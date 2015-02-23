<?php

require_once '../functions.php';

$shouldUpdate = false;
$initialScore = false;

if (isset($_POST['user']) && isset($_POST['game']) && isset($_POST['score']))
{
	$user = sanitizeString_L($_POST['user']);
	$game = $_POST['game'];
	$score = $_POST['score'];

	$result = queryMysql_L("SELECT * FROM gamescores WHERE user='$user' AND game='$game'");

	// The user alreay has score for that game.
	if ($result->num_rows)
	{
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$oldScore = $row['score'];

		if ($game === "SpaceBowling")
		{
			if ($score < $oldScore)
			{
				$shouldUpdate = true;
			}
		}
		elseif ($game === "AsteroidAvoidance")
		{
			if ($score > $oldScore)
			{
				$shouldUpdate = true;
			}
		}
		else
		{
			echo "No game found in the table: $game";
		}

	}
	// The user has no entry in gamescore.
	else
	{
		queryMysql_L("INSERT INTO gamescores (user, game, score) VALUES ('$user', '$game', '$score')");
		$initialScore = true;
		echo "Initial Score";
	}

	// Update table
	if ($shouldUpdate == true && $initialScore ==false)
	{
		queryMysql_L("UPDATE gamescores SET score='$score' WHERE user='$user' AND game='$game'");
		echo "New Record!";
	}
	elseif ($shouldUpdate == false && $initialScore == false)
	{
		echo "No new record, Try again.";
	}


}
else
{
	echo "POST variants are not perfect!";
}

?>
