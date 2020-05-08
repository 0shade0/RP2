<div class = "login">

<div>
<form action= "main.php?rt=user/checkLogin" method="post" id="Login">
	<div><?php if(isset($message)) { echo $message; } ?></div>
	<div>
		<input placeholder="Username" name="member_name" type="text" value="<?php if(isset($_COOKIE["member_login"])) { echo $_COOKIE["member_login"]; } ?>" required>
	</div>
	<div>
		<input placeholder="Password" name="member_password" type="password" value="" required>
	</div>
	<div>
		<input type="checkbox" name="remember" id="remember" <?php if(isset($_COOKIE["member_login"])) { ?> checked <?php } ?> />
		<label for="remember-me">Remember me</label>
	</div>
	<div>
		<input type="submit" name="login" value="Login">
	</div>
</form>
</div>

<div>
<form action="<?php echo 'main.php?rt=user/register'; ?>" method="post" id="Register">
	<div><?php if(isset($message_name)) { echo $message_name; } ?></div>
	<div>
		<input placeholder="Username" name="member_name" type="text" value="" required>
	</div>
    <div><?php if(isset($message_email)) { echo $message_email; } ?></div>
    <div>
		<input placeholder="E-mail address" name="member_email" type="text" value="" required>
	</div>
    <div><?php if(isset($message_password)) { echo $message_password; } ?></div>
	<div>
		<input placeholder="Password" name="member_password" type="password" value="" required>
	</div>
    <div>
		<input placeholder="Repeat password" name="repeat_password" type="password" value="" required>
	</div>
	<div>
		<input type="submit" name="register" value="Register">
	</div>
</form>
</div>

</div>
