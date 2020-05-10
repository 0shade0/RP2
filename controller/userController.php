<?php
require_once platformSlashes($dir . '/model/chorezservice.class.php');

class userController {
// Pregled mojih podataka
public function index() {
    global $title, $db, $dir;

    $cs = new ChorezService();
    $user = $cs->getUserByID($_SESSION['user']);
    $title = $user->username;

    require_once platformSlashes($dir . '/view/_header.php');
    require_once platformSlashes($dir . '/view/main_menu.php');
    require_once platformSlashes($dir . '/view/user.php');
    require_once platformSlashes($dir . '/view/_footer.php');
}

// Pregled podataka pojedinog ukućana
public function show() {
}

// Pregled kućanstva
public function household() {
    global $title, $db, $dir;

    $cs = new ChorezService();
    $user = $cs->getUserByID($_SESSION['user']);
    $household = $cs->getHouseholdByID($user->ID_household);
    $title = $household->name;

    require_once platformSlashes($dir . '/view/_header.php');
    require_once platformSlashes($dir . '/view/main_menu.php');
    require_once platformSlashes($dir . '/view/household.php');
    require_once platformSlashes($dir . '/view/_footer.php');
}

// Popis nagrada
public function rewards() {
    global $title, $db, $dir;

    $title = 'Nagrade';
    $cs = new ChorezService();

// Dodavanje nagrade
    if(isset($_POST['add_reward'])) {
        if(!preg_match('/^[1-9][0-9]*$/' , $_POST['reward_price']))
            $message = "Cijena mora biti broj (min = 1).";
        elseif(strlen($_POST['reward_name']>300))
            $message = "Opis je predugačak.";
        else {
            $reward_new = new Reward(
                '0',
                $_SESSION['user'],
                $_POST['reward_name'],
                $_POST['reward_price'],
                '0');
            $cs->addNewReward($reward_new);
        }

        $message_info = "Nagrada je uspješno dodana.";
    }

// Uklanjanje nagrade
    if(isset($_POST['remove_reward'])) {
        $cs->deleteRewardByID($_POST['remove_reward']);
        
        $message_info = "Nagrada je uspješno uklonjena.";
    }

    $rewards = $cs->getRewardsByID($_SESSION['user']);
    $user = $cs->getUserByID($_SESSION['user']);

    if(!$rewards) $message_info = "Nema nagrada";

    require_once platformSlashes($dir . '/view/_header.php');
    require_once platformSlashes($dir . '/view/main_menu.php');
    if($user->admin)
        require_once platformSlashes($dir . '/view/rewards_admin.php');
    else
        require_once platformSlashes($dir . '/view/rewards.php');
    require_once platformSlashes($dir . '/view/_footer.php');
}

}
