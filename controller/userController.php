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

    require_once platformSlashes($dir . '/view/_header.php');
    require_once platformSlashes($dir . '/view/main_menu.php');
    require_once platformSlashes($dir . '/view/rewards.php');
    require_once platformSlashes($dir . '/view/_footer.php');
}

}
