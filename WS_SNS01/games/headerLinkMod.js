$(document).ready(function()
{
	$("#HomeLink1").prop("href", "../index.php");
	$("#MemberLink").prop("href", "../members.php");
	$("#FriendLink").prop("href", "../friends.php");
	$("#ProfLink").prop("href", "../members.php?view=" + user);
	//$("#EditProfLink").prop("href", "../profile.php");
	$("#GameLink").prop("href", "gameindex.html");
	$("#adminLink").prop("href", "../adminTask.php");
	$("#LogoutLink").prop("href", "../logout.php");
	$("#HomeLink2").prop("href", "../index.php");
	$("#SignupLink").prop("href", "../signup.php");
	$("#LoginLink").prop("href", "../login.php");

});
