
<div class="chore_wrapper">
    <p class="chore_header"> Osobni zadaci </p>
    <div class = "info"><?php if(isset($message_info_my)) 
        echo $message_info_my . "<br><br>";?></div>

    <?php if($chores): ?>

    <form action="" method="post">
    <table class=chores>
    <?php foreach($chores as $row): ?>
        <tr>
            <td class="chore_category"> <?=$cs->getCategoryByID($row->ID_category)->name?> </td>
            <td class="chore_description"> <?=$row->description?> <?php if($row->mandatory) echo '*';?></td>
            <td class="chore_time"> Prije <?=DateDiff($row->time_next)?> </td>
            <td class="chore_points"> <?=$row->points.'<img src="./app/image/bod.png">'?> </td>
            <td class="chore_confirm"> <?='<input type="checkbox" name="chore[]" value="'.$row->ID.'">'?> </td>
        </tr>

    <?php endforeach; ?>
    </table>
    <input class=chore_delete type=submit value="" name="chore_delete">
    <?php if(isset($my_page) && $my_page) echo
    '<input class=chore_submit type=submit value="" name="chore_submit">'; ?>
    </form>

    <?php endif; ?>
</div>


<div class="chore_wrapper">
    <p class="chore_header"> Zadaci kućanstva </p>
    <div class = "info"><?php if(isset($message_info_group)) 
        echo $message_info_group . "<br><br>";?></div>

    <?php if($chores_group): ?>
    <form action="" method="post">
    <table class=chores>
    <?php foreach($chores_group as $row): ?>
        <tr>
            <td class="chore_category"> <?=$cs->getCategoryByID($row->ID_category)->name?> </td>
            <td class="chore_description"> <?=$row->description?> </td>
            <td class="chore_time"> Prije <?=DateDiff($row->time_next)?> </td>
            <td class="chore_points"> <?=$row->points.'<img src="./app/image/bod.png">'?> </td>
            <td class="chore_confirm"> <?='<input type="checkbox" name="chore[]" value="'.$row->ID.'">'?> </td>
        </tr>

    <?php endforeach; ?>


    </table>
    <input class=chore_delete type=submit value="" name="chore_delete">
    <?php if(isset($my_page) && $my_page) echo
    '<input class=chore_submit type=submit value="" name="chore_submit">'; ?>
    </form>

    <?php endif; ?>
</div>


<div class="chore_wrapper">
    <p class="chore_header"> Nadolazeći zadaci </p>
    <div class = "info"><?php if(isset($message_info_next)) 
        echo $message_info_next . "<br><br>";?></div>

    <?php if($chores_next): ?>

    <form action="" method="post">
    <table class=chores>
    <?php foreach($chores_next as $row): ?>
        <tr class="future_chore">
            <td class="chore_category"> <?=$cs->getCategoryByID($row->ID_category)->name?> </td>
            <td class="chore_description"> <?=$row->description?> <?php if($row->mandatory) echo '*';?> </td>
            <td class="chore_time"> Za <?=DateDiff($row->time_next)?> </td>
            <td class="chore_points"> <?=$row->points.'<img src="./app/image/bod.png">'?> </td>
            <td class="chore_confirm"> <?='<input type="checkbox" name="chore[]" value="'.$row->ID.'">'?> </td>
        </tr>

    <?php endforeach; ?>


    </table>
    <input class=chore_delete type=submit value="" name="chore_delete">
    </form>

    <?php endif; ?>

    <a href="chorez.php?rt=chore/create&id=<?=$id?>"> 
        <input class=chore_create type=submit value="" name="chore_create">
    </a>
</div>



<?php if(isset($my_page) && $my_page): ?>

<div class = "balance">
    <p id="left" class = "moji_bodovi"> Moji bodovi:</p>
    <table> <tr>
        <td><?=number_format( $user->points);?></td>
        <td><img src="./app/image/bod.png"></td>
    </tr> </table>
</div>

<?php endif; ?>


<img class = "book" src="./app/image/Mchores.png">