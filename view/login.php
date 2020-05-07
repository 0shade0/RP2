<div>
<form action= "<?php platformSlashes($dir . '/main.php'); ?>" method="post" id="Login">
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
<div>
	<form action="<?php platformSlashes($dir . '/main.php?rt=register'); ?>" method="post">
		<input type="submit" name="null" value="Register">
	</form>
</div>
</div>