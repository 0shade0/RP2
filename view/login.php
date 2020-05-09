<br> <div class = "title"> <?php echo $title; ?> </div>
<div class = "info"><?php if(isset($message_info)) { echo $message_info . "<br><br>"; } ?></div>


<div class = "login">

<div>
<form action= "chorez.php?rt=account/" method="post" id="Login">
	<div class = "error"><?php if(isset($message)) { echo $message; } ?></div>
	<div>
		<input placeholder="Korisničko ime" name="log_name" type="text" value="<?php if(isset($_COOKIE["member_login"])) { echo $_COOKIE["member_login"]; } ?>" required>
	</div>
	<div>
		<input placeholder="Lozinka" name="log_password" type="password" value="" required>
	</div>
	<div>
		<input type="checkbox" name="remember" id="remember" <?php if(isset($_COOKIE["member_login"])) { ?> checked <?php } ?> />
		<label for="remember">Zapamti me</label>
	</div>
	<div>
		<input type="submit" name="login" value="Prijava">
	</div>
</form>
</div>

<br>

<p style="text-align: center;"> ili </p>

<div class = "register">
<form action="chorez.php?rt=account/register" method="post" id="Register">
	<div>
		<input placeholder="Korisničko ime" name="reg_name" type="text" value="" required>
	</div>
	<div class = "error"><?php if(isset($message_name)) { echo $message_name; } ?></div>
    <div>
		<input placeholder="E-mail adresa" name="reg_email" type="text" value="" required>
	</div>
	<div class = "error"><?php if(isset($message_email)) { echo $message_email; } ?></div>
	<div>
		<input placeholder="Lozinka" name="reg_password" type="password" value="" required>
	</div>
    <div>
		<input placeholder="Ponovljena lozinka" name="reg_repeat" type="password" value="" required>
	</div>
	<div class = "error"><?php if(isset($message_password)) { echo $message_password; } ?></div>
	<div>
		<input type="submit" name="register" value="Registracija">
	</div>
</form>
</div>

</div>
