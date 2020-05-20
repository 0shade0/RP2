<div class = "info"><?php if(isset($message_info)) 
    echo $message_info . "<br><br>";?></div>

<?php if($chores !== null): ?>

<form action="chorez.php?rt=chore" method="post">
<table class=chores>
<?php foreach($chores as $row):
    if(DateDiff($row->time_next) !== 0): ?>
    <tr>
        <td class="chore_category"> <?=$cs->getCategoryByID($row->ID_category)->name?> </td>
        <td class="chore_description"> <?=$row->description?> </td>
        <td class="chore_time"> <?=DateDiff($row->time_next)?> </td>
        <td class="chore_points"> <?=$row->points.'<img src="./app/image/bod.png">'?> </td>
        <td class="chore_confirm"> <?='<input type="checkbox" name="chore[]" value="'.$row->ID.'">'?> </td>
    </tr>

<?php endif; endforeach; ?>
</table>

<input class=chore_submit type=submit value="" name="chore_submit">
</form>

<?php endif; ?>

<div class = "balance">
    <p id="left" class = "moji_bodovi"> Moji bodovi:</p>
    <table> <tr>
        <td><?=number_format( $user->points);?></td>
        <td><img src="./app/image/bod.png"></td>
    </tr> </table>
</div>

<img class = "book" src="./app/image/Mchores.png">