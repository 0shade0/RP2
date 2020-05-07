<div>
<form action="<?php platformSlashes($dir . '/main.php'); ?>" method="post" id="Register">
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