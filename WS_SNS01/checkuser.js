function checkUser_L(user)
{
	if (user.value == '')
	{
		O('info').innerHTML = ''
		return
	}

	params = "user" + user.value
	request = new ajaxRequest_L()
	request.open("POST", "checkuser.php", true)
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
	request.setRequestHeader("Content-length", params.length)
	reqeust.setRequestHeader("Connection", "close")

	reqeust.onreadystatechange = function()
	{
		if (this.readyState == 4)
		{
			if (this.status == 200)
			{
				if (this.responseText != null)
				{
					O('info').innerHTML = this.responseText
				}
			}
		}
	}

	request.send(params)
}

function ajaxRequest_L()
{
	try 
	{
		var request = new XMLHttpRequest()
	}
	catch (e1)
	{
		try 
		{
			request = new ActiveXObject("Msxml2.XMLHTTP")
		}
		catch (e2)
		{
			try
			{
				reqeust = new ActiveXObject("Microsoft.XMLHTTP")
			}
			catch (e3)
			{
				request = false
			}
		}
	}
	return request
}