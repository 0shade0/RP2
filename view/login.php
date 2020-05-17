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

	<p> Moji podaci: </p>
	<div>
		<input placeholder="Korisničko ime" name="reg_name" type="text" value="<?php if(isset($_POST['reg_name'])) echo $_POST['reg_name'];?>" required>
	</div>
	<div class = "error"><?php if(isset($message_name)) { echo $message_name; } ?></div>
    <div>
		<input placeholder="E-mail adresa" name="reg_email" type="text" value="<?php if(isset($_POST['reg_email'])) echo $_POST['reg_email'];?>" required>
	</div>
	<div class = "error"><?php if(isset($message_email)) { echo $message_email; } ?></div>
	<div>
		<input placeholder="Lozinka" name="reg_password" type="password" value="" required>
	</div>
    <div>
		<input placeholder="Ponovljena lozinka" name="reg_repeat" type="password" value="" required>
	</div>
	<div class = "error"><?php if(isset($message_password)) { echo $message_password; } ?></div>



	<br>



	<p> Podaci kućanstva: </p>
	<div>
		<input placeholder="Ime" name="house_name" type="text" value="<?php if(isset($_POST['house_name']) && isset($_POST['register_new'])) echo $_POST['house_name'];?>">
	</div>
	<div class = "error"><?php if(isset($message_hname)) { echo $message_hname; } ?></div>
	<div>
		<input placeholder="Lozinka" name="house_newpassword" type="password" value="">
	</div>
	<div class = "error"><?php if(isset($message_hpassword_new)) { echo $message_hpassword_new; } ?></div>
	<div>
		<input type="submit" name="register_new" value="Stvori kućanstvo"/>
	</div>




	<div>
		<input placeholder="#ID" name="house_id" type="text" value="<?php if(isset($_POST['house_id']) && isset($_POST['register'])) echo $_POST['house_id'];?>">
	</div>
	<div class = "error"><?php if(isset($message_hid)) { echo $message_hid; } ?></div>
	<div>
		<input placeholder="Lozinka" name="house_password" type="password" value="" >
	</div>
	<div class = "error"><?php if(isset($message_hpassword)) { echo $message_hpassword; } ?></div>
	<div>
		<input type="submit" name="register" value="Uđi u kućanstvo"/>
	</div>


</form>
</div>

</div>
