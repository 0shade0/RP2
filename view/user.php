
<?php global $user_image; ?>

<img class = "user_Image" src = "./app/image/user<?php echo $user_menu->image; ?>.png">

<! –– ako je moja profilna, prikaži opciju za promjenu slike -->
<button class ="pick_Image" type=button></button>
<form action="chorez.php?rt=user/" method="post">
<table class ="pick_Image">
    <tr>
        <td> <input type=submit value="1" name="user" style="background-image: url('./app/image/user1.png')"> </td>
        <td> <input type=submit value="2" name="user" style="background-image: url('./app/image/user2.png')"> </td>
        <td> <input type=submit value="3" name="user" style="background-image: url('./app/image/user3.png')"> </td>
        <td> <input type=submit value="4" name="user" style="background-image: url('./app/image/user4.png')"> </td>
        <td> <input type=submit value="5" name="user" style="background-image: url('./app/image/user5.png')"> </td>
        <td> <input type=submit value="6" name="user" style="background-image: url('./app/image/user6.png')"> </td>
        <td> <input type=submit value="7" name="user" style="background-image: url('./app/image/user7.png')"> </td>
    </tr>
    <tr>
        <td> <input type=submit value="8" name="user" style="background-image: url('./app/image/user8.png')"> </td>
        <td> <input type=submit value="9" name="user" style="background-image: url('./app/image/user9.png')"> </td>
        <td> <input type=submit value="10" name="user" style="background-image: url('./app/image/user10.png')"> </td>
        <td> <input type=submit value="11" name="user" style="background-image: url('./app/image/user11.png')"> </td>
        <td> <input type=submit value="12" name="user" style="background-image: url('./app/image/user12.png')"> </td>
        <td> <input type=submit value="13" name="user" style="background-image: url('./app/image/user13.png')"> </td>
        <td> <input type=submit value="14" name="user" style="background-image: url('./app/image/user14.png')"> </td>
    </tr>
</table>
</form>

<div class = "pickImage"></div>