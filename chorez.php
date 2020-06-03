<?php

$dir = __DIR__;

function platformSlashes($path){
    return str_replace('/', DIRECTORY_SEPARATOR, $path);
}

require_once platformSlashes($dir . '/app/database/db.class.php');
$db = DB::getConnection();

// Logout opcija i opcija brisanja profila
if(session_id() == '') session_start();
if(isset($_POST['delete_account']) && isset($_SESSION['user'])) {

    require_once platformSlashes($dir . '/model/chorezservice.class.php');
    $cs = new ChorezService();
    $user = $cs->getUserByID($_SESSION['user']);
    if($user) {
        $cs->deleteUser($user->ID);

        $event_text = "Kućanstvo je napustio korisnik - <strong>".$user->username."</strong>";

        $event = New Event(
            0,
            0,
            $user->ID_household,
            $event_text,
            ""
        );

        $cs->createEvent($event);
        $cs->setHouseholdUnseen($user->ID_household);

        $household = $cs->getUsersByHousehold($user->ID_household);
        if(!$household) $cs->deleteHousehold($user->ID_household);
    }
}
if(isset($_GET['logout']) && $_GET['logout']==='y') unset ($_SESSION['user']);

$title = 'Naslov nije zadan';
$help = 'Poruka za pomoć nije zadana.';

if(isset($_POST['register']) || isset($_POST['register_new']))
{
    $controller = 'account';
    $action = 'register';
}
elseif(!isset($_SESSION['user']))
{
    $controller = 'account';
    $action = 'index';
}
elseif(!isset($_GET['rt']))
{
    $controller = 'chore';
    $action = 'index';
}
else
{
    $parts = explode('/', $_GET['rt']);

    if(isset($parts[0]) && preg_match('/^[A-Za-z0-9]+$/', $parts[0]))
        $controller = $parts[0];
    else
        $controller = 'chore';

    if(isset($parts[1]) && preg_match('/^[A-Za-z0-9]+$/', $parts[1]))
        $action = $parts[1];
    else
        $action = 'index';
}

// Ako sam ulogiran, onda preusmjeri account screen na chore screen
if(isset($_SESSION['user']) && $controller === 'account') {
    $controller = 'chore';
    $action = 'index';
}

$controllerName = $controller . 'Controller';

require_once platformSlashes($dir . '/controller/' . $controllerName . '.php');
$con = new $controllerName();
$con->$action();


// Čišćenje formi nakon obavljanja
unset($_POST);

?>
