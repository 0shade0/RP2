<?php
require_once platformSlashes($dir . '/model/chorezservice.class.php');

class choreController {
// Popis mojih zadataka
public function index() {
    global $title, $db, $dir;

    $title = 'Popis zadataka';
    $cs = new ChorezService();
    // Provjeravamo je li neki od zadataka označen kao riješen ili obrisan
    if(isset($_POST['chore_submit'])) {
        if(isset($_POST['chore']))
        foreach($_POST['chore'] as $selected) {
            $chore = $cs->getChoreByID($selected);
            $cs->setCompleted($chore);
        }
    }
    else if(isset($_POST['chore_delete'])) {
        if(isset($_POST['chore']))
        foreach($_POST['chore'] as $selected) {
            $chore = $cs->getChoreByID($selected);
            $cs->deleteChore($chore);
        }
    }

    $user = $cs->getUserByID($_SESSION['user']);
    $chores = $cs->getChoresByUser($_SESSION['user']);
    $chores_group = $cs->getChoresByHousehold($user->ID_household);

    if(!$chores) $message_info_my = "Nema zadataka";
    if(!$chores_group) $message_info_group = "Nema zadataka";
    

    require_once platformSlashes($dir . '/view/_header.php');
    require_once platformSlashes($dir . '/view/main_menu.php');
    // Ako sam admin
    if($user->admin) {
        $my_page = True;
        if(isset($_GET['id'])) $id = $_GET['id'];
        else $id = $_SESSION['user'];
        $chores_next = $cs->getFutureChoresByUser($id, $user->ID_household);
        if(!$chores_next) $message_info_next = "Nema zadataka";
        require_once platformSlashes($dir . '/view/chore_list_admin.php');
    }
    // Ako nisam admin
    else
        require_once platformSlashes($dir . '/view/chore_list.php');
    require_once platformSlashes($dir . '/view/_footer.php');
}

// Pogledaj nečije zadatke (samo admin)
public function show() {
    global $title, $db, $dir;

    $title = 'Popis zadataka';
    $cs = new ChorezService();
    $user = $cs->getUserByID($_SESSION['user']);
    $id=$user->ID;

    // Ako ID nije postavljen ili ako je ID moj ID ili ako nisam admin idi na moje zadatke
    if(!isset($_GET['id']) || $_GET['id'] == $_SESSION['user'] || !$user->admin) {
        $this->index();
        return;
    }
    // Inače pogledaj je li korisnik iz mog kućanstva
    else {
        $user_show = $cs->getUserByID($_GET['id']);
        // Ako nema usera sa tim IDom ili taj user nije u mom kućanstvu, idi na moje zadatke
        if(!$user_show || $user_show->ID_household !== $user->ID_household) {
            $this->index();
            return; 
        }
        else $id=$user_show->ID;
    }

    // Sada smo uspješno ušli u nečije zadatke

    $title .= " - " . $user_show->username;

    // Provjeravamo je li neki od zadataka označen kao riješen ili obrisan
    if(isset($_POST['chore_submit'])) {
        if(isset($_POST['chore']))
        foreach($_POST['chore'] as $selected) {
            $chore = $cs->getChoreByID($selected);
            $cs->setCompleted($chore);
        }
    }
    else if(isset($_POST['chore_delete'])) {
        if(isset($_POST['chore']))
        foreach($_POST['chore'] as $selected) {
            $chore = $cs->getChoreByID($selected);
            $cs->deleteChore($chore);
        }
    }

    $chores = $cs->getChoresByUser($user_show->ID);
    $chores_group = $cs->getChoresByHousehold($user_show->ID_household);
    $chores_next = $cs->getFutureChoresByUser($user_show->ID, $user_show->ID_household);

    if(!$chores) $message_info_my = "Nema zadataka";
    if(!$chores_group) $message_info_group = "Nema zadataka";
    if(!$chores_next) $message_info_next = "Nema zadataka";

    require_once platformSlashes($dir . '/view/_header.php');
    require_once platformSlashes($dir . '/view/main_menu.php');
    require_once platformSlashes($dir . '/view/chore_list_admin.php');
    require_once platformSlashes($dir . '/view/_footer.php');

}

// Stvori novi zadatak
public function create() {
    global $title, $db, $dir;

    $cs = new ChorezService();

    $title = 'Novi zadatak za mene';
    $user = $cs->getUserByID($_SESSION['user']);

    if(isset($_GET['id'])) {
        $user = $cs->getUserByID($_GET['id']);
        if($user && $user->ID != $_SESSION['user'])
            $title = 'Novi zadatak za '.$user->username;
    }  
    

    // Varijable koje su za /view/chore_create.php .
    $categories = $cs->getAllCategories($user->ID_household);

    require_once platformSlashes($dir . '/view/_header.php');
    require_once platformSlashes($dir . '/view/main_menu.php');
    require_once platformSlashes($dir . '/view/chore_create.php');
    require_once platformSlashes($dir . '/view/_footer.php');
}
}

function DateDiff($date) {
    $now = new DateTime();
    $date = new DateTime($date);

    $interval = $date->diff($now);

    if ($interval->format('%a') === '0') {
        if ($interval->format('%h') === '0')
            return $interval->format("%imin");
        else return $interval->format("%hh");
    }
    else return $interval->format("%ad");
}

?>
