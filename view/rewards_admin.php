<div class = "info"><?php if(isset($message_info)) 
    echo $message_info . "<br><br>";?></div>

<div class = "input_line">
        <div class = "error"><?php if(isset($message)) echo $message . "<br><br>";?></div>

        <form name="add_reward_form" action="chorez.php?rt=user/rewards" method="post">
            <input id="long" type="text" name="reward_name" placeholder="Nova nagrada" required>
            <input id="short" type="text" name="reward_price" placeholder="Bodovi" required>
            <input type="submit" name="add_reward" value="Dodaj">
        </form>
</div>
<br>

<! –– lista nagrada -->
<table class = "rewards">
<! –– ako nemamo dovoljno bodova za nagradu označimo -->
<! –– ili ako je nagrada kupljena maknemo            -->
    <?php if($rewards != NULL) foreach($rewards as $row): ?>
    <tr <?php if($user->points < $row->points_price && !$enter)
                echo "class=expensive";
            elseif($row->purchased) echo "class=green";?>>
    
        <td id="first_col">
            <text class="reward_left">
                <form action="" method="post">
                    <input id="x" type="submit" value="<?=$row->ID?>" name="remove_reward">
                </form>
            </text>
            <text class="reward_right">
                <?=$row->description;?>
            </text>
        </td>
        <td id="second_col" <?php if($row->purchased)
            echo "class=green"; ?>>
            <?=number_format( $row->points_price );?>
        </td>
        <td id="third_col">
            <?php if($user->points >= $row->points_price && !$row->purchased && !$enter): ?>
                <form action="" method="post">
                    <input id="bod" type=submit value="<?=$row->ID?>" name="buy_reward">
                </form>
            <?php endif; ?>
        <td>

    </tr>

<?php endforeach;?>
</table>

<?php if(!$enter): ?>
    <div class = "balance">
        <p id="left" class = "moji_bodovi"> Moji bodovi:</p>
        <table> <tr>
            <td><?=number_format( $user->points);?></td>
            <td><img src="app/bod.png"></td>
        </tr> </table>
    </div>
<?php endif; ?>

<img class = "box" src="./app/Mbox.png">