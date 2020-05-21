<div class = "info"><?php if(isset($message_info)) 
    echo $message_info . "<br><br>";?></div>

<table class="household">
<?php if($users != NULL) foreach($users as $row):?>
    <tr <?php if($row->ID === $_SESSION['user']) echo 'class=me'?>>
        <td id="first_col"> <img class="household_user" src='./app/image/user<?=$row->image?>.png'> </td>
        <td id="second_col"><?=$row->username;?>  <?php if($row->admin) echo '<img src="./app/image/star.png">';?></td>
        <td id="admin_col">
            <?php if($user->admin && $row->ID !== $user->ID)
                echo '<a href="chorez.php?rt=chore/show&id='.$row->ID.'"> <img src="./app/image/pencil.png"> </a>';?>
        </td>
        <td id="admin_col">
            <?php if($user->admin && $row->ID !== $user->ID)
                echo '<a href="chorez.php?rt=user/rewards&id='.$row->ID.'"> <img src="./app/image/box.png"> </a>';?>
        </td>
    </tr>

<?php endforeach; ?>
</table>

<img class = "house" src="./app/image/Mhouse.png">