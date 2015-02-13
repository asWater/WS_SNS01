
<!DOCTYPE html>
<html>
	<head>
		<title> Setting up database (create tables)</title>
	</head>
	<body>

	<h3> Creating tables ... </h3>

	<?php
	require_once "functions.php";

	createTable_L('members',
				  'id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				   user VARCHAR(16) NOT NULL,
				   pass VARCHAR(64) NOT NULL,
				   email VARCHAR(255) NOT NULL,
				   modified DATETIME,
				   created DATETIME NOT NULL,
				   lastlogin DATETIME,
				   INDEX(user(10)),
				   INDEX(email(18))');

	createTable_L('messages',
			      'id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			       sender VARCHAR(16),
			       receiver VARCHAR(16),
			       privacy CHAR(1),
			       time INT UNSIGNED,
			       message VARCHAR(4096),
			       INDEX(sender(10)),
			       INDEX(receiver(10))');

	createTable_L('friends',
				  'user VARCHAR(16),
				   friend VARCHAR(16),
				   INDEX(user(10)),
				   INDEX(friend(10))');

	createTable_L('profiles',
				  'user VARCHAR(16),
				   intro VARCHAR(4096),
				   image MEDIUMBLOB,
				   INDEX(user(10))');

	?>

	<br> ...Done.
	</body>
</html>


