<?php global $controller, $action, $help;
$cs = new ChorezService();
$user_menu = $cs->getUserByID($_SESSION['user']);?>

<div class = "menu" id = "noselect">
        <?php if($controller!='user' || $action!='index')
        echo "<a id=user href='chorez.php?rt=user' " .
                "style = background-image:url('./app/image/user" . $user_menu->image . ".png')> </a>";
        else echo "<span id=user class=not_selected " .
                "style = background-image:url('./app/image/user" . $user_menu->image . ".png')> </span>";?>
        
        <?php if($controller!='chore' && $controller!='account')
        echo "<a id=chores href='chorez.php?rt=chore'> </a>";
        else echo "<span id=chores class=not_selected> </span>";?>
        
        <?php if($controller!='user' || $action!='household')
        echo "<a id=household href='chorez.php?rt=user/household'> </a>";
        else echo "<span id=household class=not_selected> </span>";?>

        <?php if($controller!='user' || $action!='rewards')
         echo "<a id=rewards href='chorez.php?rt=user/rewards'> </a>";
         else echo "<span id=rewards class=not_selected> </span>";?>
        
        <a href='chorez.php?rt=account&logout=y' id="logout"></a>
</div>

<br>

<div class = "title">
        <?php echo $title; ?>
        <div class = "help">
                <img src="./app/image/qMark.png">
                <p> <?=$help;?> </p>
        </div>
</div>
