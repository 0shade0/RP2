<?php
require_once platformSlashes($dir . '/model/chorezservice.class.php');

class userController {
// Pregled mojih podataka
public function index() {
    global $title, $db, $dir;

    $cs = new ChorezService();
    $user = $cs->getUserByID($_SESSION['user']);
    $title = $user->username;

    if(isset($_POST['user'])) {
        if($_POST['user'] != $user->image) {
            $cs->setUserImage($user->ID, $_POST['user']);
        }
    }

    require_once platformSlashes($dir . '/view/_header.php');
    require_once platformSlashes($dir . '/view/main_menu.php');
    require_once platformSlashes($dir . '/view/user.php');
    require_once platformSlashes($dir . '/view/_footer.php');
}

// Pregled kućanstva
public function household() {
    global $title, $db, $dir;

    $cs = new ChorezService();
    $user = $cs->getUserByID($_SESSION['user']);
    $household = $cs->getHouseholdByID($user->ID_household);
    $users = $cs->getUsersByHousehold($user->ID_household);
    $title = $household->name;

    if(!$users) $message_info = "Nema nagrada";

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
    $user = $cs->getUserByID($_SESSION['user']);

    $enter = False;

// Provjerava ima li pristup korisniku
    if(isset($_GET['id'])) {
        $enter = True;

        if(!$user->admin) $enter = False;

        $user_id = $cs->getUserByID($_GET['id']);
        if($user_id->ID_household != $user->ID_household)
            $enter = False;

        if($user_id->ID === $_SESSION['user'])
            $enter = False;
    }

    if($enter)
    {
        $ID =  $_GET['id'];
        $user = $user_id;
        $title = "Nagrade - " . $user->username;
        if($user_id->admin) $title.= "*";
    }
    else
    {
        $ID = $_SESSION['user'];
        $user = $cs->getUserByID($ID);
    }

// Kupovanje nagrade
    if(isset($_POST['buy_reward'])) {
        $reward = $cs->getRewardByID($ID, $_POST['buy_reward']);

        if($reward) {
            $cs->buyReward($ID, $_POST['buy_reward'], $user->points, $reward->points_price);
            $message_info = "Nagrada je uspješno kupljena.";
        }
        unset($_POST['buy_reward']);
    }

// Dodavanje nagrade
    if(isset($_POST['add_reward'])) {
        if(!preg_match('/^[1-9][0-9]*$/' , $_POST['reward_price']))
            $message = "Cijena mora biti broj (min = 1).";
        elseif(strlen($_POST['reward_name']>300))
            $message = "Opis je predugačak.";
        else {
            $reward_new = new Reward(
                '0',
                $ID,
                $_POST['reward_name'],
                $_POST['reward_price'],
                '0');
            $cs->addNewReward($reward_new);
            $message_info = "Nagrada je uspješno dodana.";
        }

        unset($_POST['add_reward']);
    }

// Uklanjanje nagrade
    if(isset($_POST['remove_reward'])) {
        $cs->deleteRewardByID($_POST['remove_reward']);

        $message_info = "Nagrada je uspješno uklonjena.";
        unset($_POST['remove_reward']);
    }

// Dohvat varijabli NAKON promjena
    $rewards = $cs->getRewardsByID($ID);
    $user = $cs->getUserByID($ID);

    if(!$rewards) $message_info = "Nema nagrada";

    require_once platformSlashes($dir . '/view/_header.php');
    require_once platformSlashes($dir . '/view/main_menu.php');
    if($user->admin || $enter)
        require_once platformSlashes($dir . '/view/rewards_admin.php');
    else
        require_once platformSlashes($dir . '/view/rewards.php');
    require_once platformSlashes($dir . '/view/_footer.php');
}

}
