
function checkUser_L(user)
{
	spanID = "userInfo"

	if (emptyProc_L(user.value, spanID))
	{
		return
	}

	requestSetSend_L("user=", user.value, "checkuser.php", spanID)
}

function checkPass_L(pass)
{
	spanID = "passInfo"

	if (emptyProc_L(pass.value, spanID))
	{
		return
	}

	//alert(/^[a-zA-Z0-9 -\/:-@\[-\`\{-\~]+$/.test(pass.value))

	if (pass.value.length < 8)
	{
		O(spanID).innerHTML = "<span class='error'>&nbsp;&#x2718; Password must be more than 8 letters </span>"
	}
	else
	{
		O(spanID).innerHTML = "<span class='available'>&nbsp;&#x2714; Password length is OK </span>"
	}
}

function confirmPass_L(passCheck)
{
	spanID = "passConf"

	if (emptyProc_L(passCheck.value, spanID))
	{
		return
	}

	if (signup.pass.value == passCheck.value)
	{
		O(spanID).innerHTML = "<span class='available'>&nbsp;&#x2714; Password Confirmed </span>"
	}
	else
	{
		O(spanID).innerHTML = "<span class='error'>&nbsp;&#x2718; Password not same </span>"
	}		
}

function checkEmail_L(email)
{
	spanID = "emailInfo"

	if (emptyProc_L(email.value, spanID))
	{
		return
	}

	requestSetSend_L("email=", email.value, "checkEmail.php", spanID)

}

function emptyProc_L(val, spanID)
{
	if (val == '')
	{
		O(spanID).innerHTML = ''
		return true
	}
	else
	{
		return false
	}
}

function requestSetSend_L(para, paraValue, target, spanID)
{
	params = para + paraValue
	request = new ajaxRequest_L()
	
	request.open("POST", target, true)
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
	request.setRequestHeader("Content-length", params.length)
	request.setRequestHeader("Connection", "close")

	request.onreadystatechange = function()
	{
		if (this.readyState == 4)	// 1: Reading, 2: Finish reading, 3: Analyzing data, 4: Finish analyzing data 
		{
			if (this.status == 200)	// HTTP status: 200 = OK.
			{
				if (this.responseText != null)	// Text data replied by the server.
				{
					O(spanID).innerHTML = this.responseText
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

