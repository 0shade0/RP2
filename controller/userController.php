<?php
require_once platformSlashes($dir . '/model/chorezservice.class.php');

class userController {
// Pregled mojih podataka
public function index() {
    global $title, $help, $db, $dir;

    $cs = new ChorezService();
    $user = $cs->getUserByID($_SESSION['user']);
    $title = $user->username;

    if(isset($_POST['user'])) {
        if($_POST['user'] != $user->image) {
            $cs->setUserImage($user->ID, $_POST['user']);
        }
    }

    $chores = $cs->getChoresByUser($user->ID);
    $chorecount = sizeof($chores);

    $chores = $cs->getChoresByHousehold($user->ID_household);
    $householdchorecount = sizeof($chores);

    $help = 'Ovo je Vaša profilna stranica. <br><br>
    Ako želite promijeniti svoju profilnu sliku, kliknite na gumb pored Vaše slike. <br>(nije moguće na mobilnoj verziji) <br><br>
    Ako želite obrisati svoj račun, kliknite na križić u donjem desnom kutu.';

    require_once platformSlashes($dir . '/view/_header.php');
    require_once platformSlashes($dir . '/view/main_menu.php');
    require_once platformSlashes($dir . '/view/user.php');
    require_once platformSlashes($dir . '/view/_footer.php');
}

// Pregled kućanstva
public function household() {
    global $title, $help, $db, $dir;

    $cs = new ChorezService();
    $user = $cs->getUserByID($_SESSION['user']);

    // Provjeri je li promijenjen status admina nekom useru te imam li te privilegije
    if($_SESSION['boss'] && isset($_POST['admin'])) {
        $user_changed = $cs->getUserByID($_POST['admin']);
        // Promjeni samo ako nisam ja i ako je osoba u mom kućanstvu
        if($user_changed->ID !== $user->ID && $user_changed->ID_household === $user->ID_household) {
            $admin_status = $cs->changeUserAdmin($_POST['admin']);

            if($admin_status)
                $event_text = "<strong>(".$user_changed->username.")</strong>"." Korisnik je postao admin";
            else
                $event_text = "<strong>(".$user_changed->username.")</strong>"." Korisnik više nije admin";

            $event = New Event (
                0,
                0,
                $user->ID_household,
                $event_text,
                ""
            );

            $cs->createEvent($event);
            $cs->setHouseholdUnseen($user->ID_household);
            $cs->setEventsSeen($_SESSION['user']);
        }
    }

    
    $household = $cs->getHouseholdByID($user->ID_household);
    $users = $cs->getUsersByHousehold($user->ID_household);
    $title = $household->name . " #" . $household->ID;

    if(!$users) $message_info = "Nema nagrada";

    $help = 'Ovdje se nalazi popis Vaših ukućana. <br><br>
    Vi ste označeni plavom bojom, a ukućani sa zvjezdicom pored svog imena su administratori.
    Oni su zaduženi za zadavanje zadataka, nagrada, i ostalog.';
    if($_SESSION['boss']) $help.= '<br><br> Vi također nosite titulu šefa, tako da možete promijeniti
    status administratora bilo kojeg ukućana klikom na zvijezdicu pored pripadnog imena.';

    require_once platformSlashes($dir . '/view/_header.php');
    require_once platformSlashes($dir . '/view/main_menu.php');
    require_once platformSlashes($dir . '/view/household.php');
    require_once platformSlashes($dir . '/view/_footer.php');
}

// Popis nagrada
public function rewards() {
    global $title, $help, $db, $dir;

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

        $event_text = "<strong>(".$user->username.")</strong>"." Kupljena je nagrada - ".$reward->description;

            $event = New Event (
                0,
                0,
                $user->ID_household,
                $event_text,
                ""
            );

            $cs->createEvent($event);
            $cs->setHouseholdUnseen($user->ID_household);
            $cs->setEventsSeen($_SESSION['user']);
        
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

            $event_text = "<strong>(".$_SESSION['name'].")</strong>"." Nova nagrada - ".$_POST['reward_name'];

            $event = New Event (
                0,
                $user->ID,
                $user->ID_household,
                $event_text,
                ""
            );

            $cs->createEvent($event);
            if($_SESSION['user'] !== $ID) $cs->setEventsUnseen($ID);
        }
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

    if($enter) $help =
    ' Ovdje se nalaze nagrade Vašeg ukućana. <br><br>
    Moguće je dodati novi zadatak popunjavanjem početnog obrasca. <br><br>
    Ako je nagrada obojana zelenom bojom, to znači da je ukućan kupio tu nagradu. Vi kao administrator
    možete zatim kliknuti na kvačicu u znak da ste primili tu informaciju. <br><br>
    Klikom na križić uz nagradu možete ju obrisati. <br><br>
    Na dnu stranice nalaze se bodovi posjećenog ukućana. ';

    else {
        $help =
        ' Ovdje se nalaze Vaše nagrade. <br><br>';
        if($user->admin) $help.='Moguće je dodati novi zadatak popunjavanjem početnog obrasca. <br><br>
        Ako je nagrada obojana zelenom bojom, to znači da ste kupili tu nagradu. Vi kao administrator
        možete zatim kliknuti na kvačicu u znak da ste primili tu informaciju. <br><br>
        Moguće je dodati novi zadatak popunjavanjem početnog obrasca. <br><br>
        Klikom na križić uz nagradu možete ju obrisati. <br><br>';
        $help .= '
        Ukoliko imate dovoljno bodova, uz nagradu će se pojaviti poseban znak. Klikom na njega možete
        kupiti nagradu. <br><br>
        Na dnu stranice nalaze se Vaši bodovi. ';
    }

    require_once platformSlashes($dir . '/view/_header.php');
    require_once platformSlashes($dir . '/view/main_menu.php');
    if($user->admin || $enter)
        require_once platformSlashes($dir . '/view/rewards_admin.php');
    else
        require_once platformSlashes($dir . '/view/rewards.php');
    require_once platformSlashes($dir . '/view/_footer.php');
}

// Popis događaja
public function events() {
    global $title, $help, $db, $dir;

    $title = 'Događaji';
    $cs = new ChorezService();

    $cs->cleanEvents();

    $user = $cs->getUserByID($_SESSION['user']);
    $household = $cs->getHouseholdByID($user->ID_household);

    $events_user = $cs->getEventsbyUser($user);
    $events_household = $cs->getEventsByHousehold($household);

    if(!$events_user) $message_info_my = "Nema događaja";
    if(!$events_household) $message_info_household = "Nema događaja";

    $help = 'Ovdje se nalaze Vaše obavijest. <br><br>
    Desna kartica sadrži obavijesti kućanstva poput grupnih zadataka ili pridošlica kućanstva. <br><br>
    Lijeva kartica sadrži osobne obavijesti poput novih osobnih zadataka ili nagrada.';

    require_once platformSlashes($dir . '/view/_header.php');
    require_once platformSlashes($dir . '/view/main_menu.php');
    require_once platformSlashes($dir . '/view/event_list.php');
    require_once platformSlashes($dir . '/view/_footer.php');

    $cs->setEventsSeen($user->ID);
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