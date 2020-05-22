
<div class="chore_create_form">
    <form action="chorez.php?rt=chore/show&id=<?=$user->ID?>" method="post" id="chore_create_form">
        <p> Opis zadatka </p>
        <input class="chore_description" type="text" name="chore_description" required> <br>

        <p> Kategorija zadatka </p>
        <div class="create_category">
            <?php foreach ($categories as $row): ?>
                <div> <text><?=$row->name?></text>
                    <?php if($row->ID_household !== 0): ?>
                        <a href="chorez.php?rt=chore/create&id=<?=$user->ID?>&rmv=<?=$row->ID?>">
                            <img src="./app/image/x.png">
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <p> Učestalost zadatka </p>
        <div class="create_frequency" >
            <div> <p>0</p> Jednokratan </div>
            <div> <p>1</p> Dnevni </div>
            <div> <p>2</p> Tjedni </div>
            <div> <p>3</p> Mjesečni </div>
            <div> <p>4</p> Godišnji </div>
        </div>

        <input class="category_input" type=text placeholder=Kategorija name="chore_category" required>

        <div class="create_time">
            <input class="time_input" type=text placeholder=# value=0 name="time_input" required>
            <span> Jednokratan </span>
        </div>

        <div class="create_time">
            <input type=checkbox class="create_mandatory" name="create_mandatory">
            <label name="create_mandatory"> Za cijelo kućanstvo </label>
        </div>

        <div class="create_points">
            <input type="text" name="chore_points" placeholder=Bodovi required>
            <img src="./app/image/bod.png">
        </div>
        
        <input class="create_submit" type="submit" name="chore_create_submit" value="Stvori novi zadatak!">
    </form>
</div>
