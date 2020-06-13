<div class = "info"><?php if(isset($message_info)) 
    echo $message_info . "<br><br>";?></div>

<table class="household">
<?php if($users != NULL) foreach($users as $row):?>
    <tr <?php if($row->ID === $_SESSION['user']) echo 'class=me'?>>
        <td id="first_col"> <img class="household_user" src='./app/image/user<?=$row->image?>.png'> </td>
        <td id="second_col"><?=$row->username;?>
            <?php if(!$_SESSION['boss'] && $row->admin) echo '<img src="./app/image/star.png">';
                else if ($_SESSION['boss'] && $row->ID === $_SESSION['user']) echo '<img src="./app/image/star_double.png">';
            //Boss može mijenjati admin status korsnicima
                else if($_SESSION['boss'] && $row->admin && $row->ID !== $_SESSION['user'])
                    echo '<form action="" method=post class="is_admin"> <input type=submit name="admin" value='.$row->ID.'> </form>';
                else if($_SESSION['boss'] && !$row->admin && $row->ID !== $_SESSION['user'])
                    echo '<form action="" method=post class="isnot_admin"> <input type=submit name="admin" value='.$row->ID.'> </form>';?>
        </td>

        <?php if($user->admin): ?>
            <td id="admin_col">
                <?php if($row->ID !== $user->ID)
                    echo '<a href="chorez.php?rt=chore/show&id='.$row->ID.'"> <img src="./app/image/pencil.png"> </a>';?>
            </td>
            <td id="admin_col">
                <?php if($row->ID !== $user->ID)
                    echo '<a href="chorez.php?rt=user/rewards&id='.$row->ID.'"> <img src="./app/image/box.png"> </a>';?>
            </td>

        <?php endif; if(!$user->admin): ?>
            <td id="third_col"><?=$row->points;?> <img src="./app/image/bod.png"> </td>
        <?php endif; ?>

    </tr>

<?php endforeach; ?>
</table>

<img class = "house" src="./app/image/Mhouse.png">