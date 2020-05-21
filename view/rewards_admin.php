<div class = "info"><?php if(isset($message_info)) 
    echo $message_info . "<br><br>";?></div>

<!-- Ubaci traku za dodavanje nagrada samo ako nisi u svom pofilu -->
<div class = "input_line">
        <div class = "error"><?php if(isset($message)) echo $message . "<br><br>";?></div>

        <form name="add_reward_form" action="chorez.php?rt=user/rewards<?php if($enter) echo "&id=".$ID; ?>" method="post">
            <input id="long" type="text" name="reward_name" placeholder="Nova nagrada" required>
            <input id="short" type="text" name="reward_price" placeholder="Bodovi" required>
            <input type="submit" name="add_reward" value="Dodaj">
        </form>
</div>
<br>


<!–– lista nagrada -->
<table class = "rewards">
<!–– ako nemamo dovoljno bodova za nagradu označimo -->
<!–– ili ako je nagrada kupljena maknemo            -->
    <?php if($rewards != NULL) foreach($rewards as $row): ?>
    <tr <?php if($row->purchased) echo "class=green";
            elseif($user->points < $row->points_price && !$enter)
                echo "class=expensive";?>>
    
        <td id="first_col">
            <text class="reward_left">
                <form action="chorez.php?rt=user/rewards<?php if($enter) echo "&id=".$ID; ?>" method="post">
                    <input id="<?php if($row->purchased) echo 'check'; else echo 'x';?>"
                        type="submit" value="<?=$row->ID?>" name="remove_reward">
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
                <form action="chorez.php?rt=user/rewards<?php if($enter) echo "&id=".$ID; ?>" method="post">
                    <input id="bod" type=submit value="<?=$row->ID?>" name="buy_reward">
                </form>
            <?php endif; ?>
        <td>

    </tr>

<?php endforeach;?>
</table>

<div class = "balance">
    <p id="left" class = "moji_bodovi">
        <?php if(!$enter) echo 'Moji bodovi:';
            else echo 'Bodovi';?>   </p>
    <table> <tr>
        <td><?=number_format( $user->points);?></td>
        <td><img src="./app/image/bod.png"></td>
    </tr> </table>
</div>

<img class = "box" src="./app/image/Mbox.png">