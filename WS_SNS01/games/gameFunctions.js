function scoreUpdate(user, gameName, score)
{
	request = new ajaxRequest_L();
	var params = "user=" + user +"&game=" + gameName + "&score=" + score;

	request.open("POST", "../updateScore.php", true)
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.setRequestHeader("Content-length", params.length);
	request.setRequestHeader("Connection", "close");

	request.onreadystatechange = function()
	{
		if (this.readyState == 4)	// 1: Reading, 2: Finish reading, 3: Analyzing data, 4: Finish analyzing data
		{
			if (this.status == 200)	// HTTP status: 200 = OK.
			{
				if (this.responseText != null)	// Text data replied by the server.
				{
					alert(this.responseText);
				}
			}
		}
	};

	request.send(params);

}