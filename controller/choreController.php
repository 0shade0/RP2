<?php
require_once platformSlashes($dir . '/model/chorezservice.class.php');

class choreController {
// Popis mojih zadataka
public function index() {
    global $title, $db, $dir;

    $title = 'Popis zadataka';
    $cs = new ChorezService();
    // Provjeravamo je li neki od zadataka označen kao riješen
    if(isset($_POST['chore_submit'])) {
        foreach($_POST['chore'] as $selected) {
            $chore = $cs->getChoreByID($selected);
            $cs->setCompleted($chore);
        }
    }

    $user = $cs->getUserByID($_SESSION['user']);
    $chores = $cs->getChoresByUser($_SESSION['user']);

    if($chores === array()) $message_info = "Nema zadataka";

    require_once platformSlashes($dir . '/view/_header.php');
    require_once platformSlashes($dir . '/view/main_menu.php');
    require_once platformSlashes($dir . '/view/chore_list.php');
    require_once platformSlashes($dir . '/view/_footer.php');
}

// Detalj nekog zadatka
public function show() {
}

// Stvori novi zadatak
public function create() {
    global $title, $db, $dir;

    $title = 'Novi zadatak';

    $cs = new ChorezService();

    // Varijable koje su za /view/chore_create.php .
    $categories = $cs->getAllCategories();

    require_once platformSlashes($dir . '/view/_header.php');
    require_once platformSlashes($dir . '/view/main_menu.php');
    require_once platformSlashes($dir . '/view/chore_create.php');
    require_once platformSlashes($dir . '/view/_footer.php');
}
}

function DateDiff($date) {
    $now = new DateTime();
    $date = new DateTime($date);

    $interval = $now->diff($date);

    if($interval->invert !== 1) return 0;
    elseif ($interval->format('%a') === '0') {
        if ($interval->format('%h') === '0')
            return $interval->format("%imin");
        else return $interval->format("%hh");
    }
    else return $interval->format("%ad");
}
?>
