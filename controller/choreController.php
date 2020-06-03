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
            $title_message = "Uspješno riješeni zadaci.";
        }
    }
    else if(isset($_POST['chore_delete'])) {
        if(isset($_POST['chore']))
        foreach($_POST['chore'] as $selected) {
            $chore = $cs->getChoreByID($selected);
            $cs->deleteChore($chore);
            $title_message = "Uspješno obrisani zadaci.";
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
    if(!isset($_GET['id']) || !$user->admin) {
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
    if($user_show->admin) $title .= '*';

    if($_GET['id'] === $_SESSION['user']) {
        $title = "Popis zadataka";
        $my_page = True;
    }

    // Provjeravamo je li stvoren novi zadatak
    if(isset($_POST['chore_create_submit']) &&
        isset($_POST['chore_description']) &&
        isset($_POST['chore_category']) &&
        isset($_POST['time_input']) &&
        isset($_POST['chore_points'])) {

            // Ako je označeno za sve ukućane, onda je mandatory = 0;
            if(isset($_POST['create_mandatory']))
                $mandatory = 0;
            else $mandatory = 1;

            //Ako kategorija ne postoji, dodaj novu kategoriju
            $category = $cs->getCategoryByName($_POST['chore_category']);
            if(!$category)
                $category_id = $cs->addNewCategory($user_show->ID_household, $_POST['chore_category']);
            else
                $category_id = $category->ID;

            // Novi zadatak sa podacima iz form-a
            $chore = New Chore(
                0,
                $user_show->ID,
                $category_id,
                $_POST["chore_description"],
                date("Y-m-d H:i:s", time()),
                $mandatory,
                $_POST['time_input'],
                $_POST['chore_points'],
                0);

            $cs->addNewChore($chore);
            $title_message = "Uspješno dodan zadatak " . $_POST['chore_description'] . ".";

            if($mandatory) {
                $event_user = $user_show->ID;
                if($_SESSION['user'] !== $user_show->ID) $cs->setEventsUnseen($user->ID);
            }
            else {
                $event_user = 0;
                $cs->setHouseholdUnseen($user_show->ID_household);
                $cs->setEventsSeen($_SESSION['user']);
            }

            switch($_POST['time_input']) {
                case 1:
                    $event_type = "dnevni ";
                break;
                case 2:
                    $event_type = "tjedni ";
                break;
                case 3:
                    $event_type = "mjesečni ";
                break;
                case 4:
                    $event_type = "godišnji ";
                break;
                default: $event_type = "";
            }

            $event = New Event(
                0,
                $event_user,
                $user_show->ID_household,
                "<strong>(".$_SESSION['name'].")</strong>"." Novi ".$event_type."zadatak - ".$_POST['chore_description'],
                ""
            );
            $cs->createEvent($event);
        }

    // Provjeravamo je li neki od zadataka označen kao riješen ili obrisan
    if(isset($_POST['chore_submit'])) {
        if(isset($_POST['chore']))
        foreach($_POST['chore'] as $selected) {
            $chore = $cs->getChoreByID($selected);
            $cs->setCompleted($chore);
            $title_message = "Uspješno riješeni zadaci.";
        }
    }
    else if(isset($_POST['chore_delete'])) {
        if(isset($_POST['chore']))
        foreach($_POST['chore'] as $selected) {
            $chore = $cs->getChoreByID($selected);
            $cs->deleteChore($chore);
            $title_message = "Uspješno obrisani zadaci.";
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

    if(!$user->admin) {
        $this->index();
        return;
    }

    if(isset($_GET['id'])) {
        $user = $cs->getUserByID($_GET['id']);
        if($user && $user->ID != $_SESSION['user']) {
            $title = 'Novi zadatak za '.$user->username;
            if($user->admin) $title .= '*';
        }
    }

    // Ako je neka od kategorija označena za brisanje
    if(isset($_GET['rmv'])) {
        $category = $cs->getCategoryByID($_GET['rmv']);
        if($category) {
            $chores = $cs->getChoresByCategory($category->ID);
            foreach($chores as $row)
                $cs->deleteChore($row);
            $cs->deleteCategory($category);

            $title_message = "Uspješno obrisana kategorija " . $category->name . ".";
        }
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
