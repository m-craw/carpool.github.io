<html>
<head>
	<title>Message Page</title>
</head>
<body>

<?php
	if ($nextAction == "error")
	{
		print "<h2>Your password was not updated.  Please try again.</h2><br>";
		$nextAction = "reset";
		$action = "request";
	}

	if ($action == "request")
	{
		print "Enter your email address and we will send you a code to reset your password.<br>";
	}
	elseif ($action == "reset")
	{
		print "We have sent you an email with a temporary code.  Enter the code and the new password below and click Submit to reset your password.<br>";
	}
?>

<hr>
Reset Password<br>
<?php
	if ($nextAction == "login")
	{
		print "<h2>Your password was successfully updated.</h2><br>";
		print "<a href=\"/login.php\">Click Here to Login</a><br>";
	}
	else
	{
?>
<form>
	<input type="hidden" name="target" value="<?php print "email"; ?>">
	<input type="hidden" name="action" value="<?php print $nextAction; ?>">
	<table>
	<tr><td>Email:</td><td><input type="text" name="email" value="<?php print $email; ?>"></td><tr>
<?php
	if ($action == "reset")
	{
	print	"<tr><td>Emailed Code:</td><td><input type=\"text\" name=\"code\">$passwordCode</></td><tr>";
	print "<tr><td>New Password:</td><td><input type=\"text\" name=\"p\">$newPassword</></td><tr>";
	}
?>
	<tr><td></td><td><input type=submit value="Submit"></td><tr>
	</table>
</form>

<?php
	}
?>

<hr>

</body>
</html>
