<!-- Ovo je placeholder view. -->

<div class="chore_create">
    <form action="chorez.php?rt=chore" method="post" id="chore_create_form">
        Ime zadatka:
        <input type="text" name="chore_name" required> <br>

        Opis zadatka: <br>
        <textarea name="chore_description" rows="4" cols="50" required>
        </textarea> <br>

        Tip zadataka: 
        <select name="chore_category">
            <?php
                foreach ($categories as $key => $category) {
                	if ($key === 0) $selected = "selected";
                    else $selected = "";
                    echo "<option value='" . $category . "'" . $selected . ">" . $category;
                    
                    echo "</option>";
                }
            ?>
        </select> <br>

        Kada treba obaviti zadatak?
        <input type="text" name="chore_time_next" value= <?php echo "'" . date("Y-m-d H:i:s") . "'"; ?> required> <br>

        Je li zadatak nužan?
       	<input type="checkbox" name="chore_mandatory" value="True"> <br>
        
        Koliko bodova vrijedi zadatak?
        <input type="text" name="chore_points" required> <br>
    </form>
</div>