<div class=event_wrapper>

    <button class="my_button">Moji događaji</button>
    <button class="household_button">Događaji u kućanstvu</button>

    <div class = "events_my">
        <div class = "info" style="color:var(--orange);"><?php if(isset($message_info_my)) 
            echo $message_info_my;?></div>

        <table class = "events_table">
        <?php foreach($events_user as $row): ?>
            <tr <?php if($user->event >= $row->ID) echo 'class=seen';?> >
                <td id="firstcol"><span class="orangedot"></span><?=$row->description?></td>
                <td id="secondcol"><?=DateDiff($row->time_set)?></td>
            </tr>
        <?php endforeach; ?>
        </table>
    </div>

    <div class = "events_household">
        <div class = "info"><?php if(isset($message_info_household)) 
            echo "<br>" . $message_info_household;?></div>

        <table class = "events_table">
        <?php foreach($events_household as $row): ?>
            <tr <?php if($user->event >= $row->ID) echo 'class=seen';?>>
                <td id="firstcol"><span class="bluedot"></span><?=$row->description?></td>
                <td id="secondcol"><?=DateDiff($row->time_set)?></td>
            </tr>
        <?php endforeach; ?>
        </table>
    </div>

</div>

<img class = "events" src="./app/image/Mevents.png">