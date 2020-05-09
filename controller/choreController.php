<?php
require_once platformSlashes($dir . '/model/chorezservice.class.php');

class choreController {
// Popis mojih zadataka
public function index() {
    global $title, $db, $dir;

    $title = 'Popis zadataka';

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
}

}
