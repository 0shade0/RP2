<div class = "info"><?php if(isset($message_info)) 
    echo $message_info . "<br><br>";?></div>


<! –– lista nagrada -->
<table class = "rewards">
<! –– ako nemamo dovoljno bodova za nagradu označimo -->
<! –– ili ako je nagrada kupljena maknemo            -->
    <?php if($rewards != NULL) foreach($rewards as $row): ?>
    <?php if($row->purchased) continue; else {?>
    <tr <?php if($user->points < $row->points_price)
                echo "class=expensive";?>>

        <td id="first_col"><?=$row->description;?></td>
        <td id= second_col><?=number_format( $row->points_price );?></td>
        <td id="Third_col">
            <?php if($user->points >= $row->points_price): ?>
                <form action="chorez.php?rt=user/rewards" method="post">
                    <input id="bod" type=submit value="<?=$row->ID?>" name="buy_reward">
                </form>
            <?php endif; ?>
        <td>

    </tr>
    <?php } ?>

<?php endforeach;?>
</table>

<div class = "balance">
    <p id="left" class = "moji_bodovi"> Moji bodovi:</p>
    <table> <tr>
        <td><?=number_format( $user->points);?></td>
        <td><img src="./app/image/bod.png"></td>
    </tr> </table>
</div>

<img class = "box" src="./app/image/Mbox.png">