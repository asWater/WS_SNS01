<?php

require_once 'header.php';

if(!$loggedIn)
{
	die();
}

echo "<div class='main'><h3>Your Profile</h3>";

$result = queryMysql_L("SELECT * FROM profiles WHERE user='$user'");

if (isset($_POST['text']) && !empty($_POST['text']))
{
	$profText = sanitizeString_L($_POST['text']);
	$profText = preg_replace('/\s\s+/', ' ', $profText); // Replace consecutive spaces to 1 space. "\s" means space.

echo "proftext = $profText<br>";

	if ($result->num_rows)
	{
		queryMysql_L("UPDATE profiles SET intro='$profText' WHERE user='$user'");
		echo "Profile text was updated.<br>";
	}
	else
	{
		queryMysql_L("INSERT INTO profiles (user, intro) VALUES ('$user', '$profText')");
		echo "Profile text was inserted.<br>";
	}
}
else
{
	if ($result->num_rows)
	{
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$profText = stripslashes($row['intro']);
	}
	else
	{
		$profText = "";
	}
}

$profText = stripslashes(preg_replace('/\s\s+/', ' ', $profText));

if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name']))
{
	$imageName = $_FILES['image']['name'];
echo "image & name was set in FILES: $imageName<br>";

	$saveTo = "$user.jpg";

	$imageTmp = $_FILES['image']['tmp_name'];
	$gottenFile = file_get_contents($imageTmp);
	//echo "gottenFile = $gottenFile<br>";

	move_uploaded_file($_FILES['image']['tmp_name'], $saveTo);
echo "imageTmp = $imageTmp<br>";

	$typeOK = TRUE;

	$mimeType = (string)$_FILES['image']['type'];

echo "filetype = $mimeType<br>";
echo "saveTo = $saveTo<br>";

	switch($_FILES['image']['type'])
	{
		case "image/gif":
			$src = imagecreatefromgif($saveTo);
			break;
		case "image/jpeg": // Both regular and progressive jpegs.
		case "image/pjpeg":
			$src = imagecreatefromjpeg($saveTo);
			echo "jpeg was selected<br>";
			break;
		case "image/png":
			$src = imagecreatefrompng($saveTo);
			break;
		default:
			$typeOK = FALSE;
			break;
	}

echo "typeOK = $typeOK<br>";

	if ($typeOK)
	{
		list($w, $h) = getimagesize($saveTo);

		$max = 100;
		$tw = $w;
		$th = $h;

		// Image size adjustment.
		if ($w > $h && $max* $w)
		{
			//"w" is largest and it will be 100($th = $max), so "h" should be shorten with same rate ($max / $w).
			$th = $max / $w * $h;
			$tw = $max;
		}
		elseif ($h > $w && $max < $h)
		{
			$tw = $max / $h * $w;
			$th = $max;
		}
		elseif ($max < $w)
		{
			$tw = $th = $max;
		}

		//imagecreatetruecolor() は、指定した大きさの黒い画像を表す画像 ID を返します。
		$tmp = imagecreatetruecolor($tw, $th);

		//imagecopyresampled() は、イメージの矩形の部分 を別のイメージにコピーします。同時にピクセル値を滑らかに補間を行い、
		//このため、特にサイズを小さくした場合には鮮明さが維持されます。
		//言い換えると、imagecopyresampled() は src_image の座標 (src_x,src_y) にある 幅 src_w、高さ src_h の矩形領域を受け取って、
		//それを dst_image の座標 (dst_x,dst_y) にある幅 dst_w、高さ dst_h の矩形領域に配置します。
		imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);

		//ImageConvolutionに渡す3x3のコンボリューション行列(畳み込み配列)を調整して画像をシャープにする方法です。
		imageconvolution($tmp, array(array(-1, -1, -1), array(-1, 16, -1), array(-1, -1, -1)), 8, 0);

	echo "src = $src<br>";
	echo "tmpImage = $tmp<br>";
		//画像をブラウザあるいはファイルに出力する。
		//imagejpeg($tmp, $saveTo);

		//Saving image data to DB.
		//$imgBin = mysqli_real_escape_string($connection, $tmp);
		$imgBin = mysqli_real_escape_string($connection, $gottenFile);

	echo "mysqli_real_escape_string was finished.<br>";
		//echo "imgBin = $imgBin<br>";

		$result2 = queryMysql_L("SELECT * FROM profiles WHERE user='$user'");

		if ($result2->num_rows)
		{
			queryMysql_L("UPDATE profiles SET image='$imgBin' WHERE user='$user'");
			echo "Image file was updated.<br>";
		}
		else
		{
			queryMysql_L("INSERT INTO profiles (user, image) VALUES ('$user', '$imgBin')");
			echo "Image file was inserted.<br>";
		}

		//imagedestroy() は画像 image を保持するメモリを解放します。
		imagedestroy($tmp);
		imagedestroy($src);

	}
}

showProfile_L($user);

echo <<<_END
<form method='post' action='profile.php' enctype='multipart/form-data'>
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
