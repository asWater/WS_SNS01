<?php

echo "<!DOCTYPE html>\n<html><head>";

require_once 'functions.php';

$profText = "Test profile text.";

$imageFile = file_get_contents($_FILES['image']['tmp_name']);

/*
	Table Information
	[Table name] testimage
	[Columns]
		- id (int unsigned, autoincreament)
		- image (mediumblob)
 */

if($imageFile)
{
	$imageBin = mysqli_real_escape_string($connection, $imageFile);

	$result = queryMysql_L('INSERT INTO testimage (image) VALUES ( "'.$imageBin.'" )');

	if($result)
	{
		echo "Image was saved to DB.<br>";
	}
	else
	{
		echo "Saving image to DB was FAILED.<br>";
	}
}

$result = queryMysql_L("SELECT * FROM testimage WHERE id = 1");

if($result)
{
	echo "<input type='button' value='Show image' onclick='showimage.php'>";
}


echo <<<_END
<title>Image Test</title>
</head>
<body>
<form method='post' action='setimage.php' enctype='multipart/form-data'>
<h3>Enter or edit your details and/or upload an image</h3>
<textarea name='text' cols='50' rows='3'>$profText</textarea><br>
_END;
?>

Image: <input type='file' name='image' size='14'>
<input type='submit' value='Save Profile'>
</form></div>
<br>
</body>
</html>
