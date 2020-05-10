<?php
require_once platformSlashes($dir . '/app/database/db.class.php');
require_once platformSlashes($dir . '/model/user.class.php');
require_once platformSlashes($dir . '/model/household.class.php');
require_once platformSlashes($dir . '/model/chore.class.php');
require_once platformSlashes($dir . '/model/category.class.php');
require_once platformSlashes($dir . '/model/reward.class.php');

class ChorezService {
//--------------------------------------------------------------------------
//  Funkcije za dohvaćanje iz tablice korisnika
//--------------------------------------------------------------------------

// Dohvaćanje korisnika preko ID-ja.
public function getUserByID($userID) {
    $db = DB::getConnection();

    $st = $db->prepare('SELECT * FROM pr_users  WHERE ID=:userID');
    $st->execute(array('userID' => $userID));

    if ($r = $st->fetch()) {
        $user = new User (
            $r['ID'], $r['ID_household'], $r['username'],
            $r['password'], $r['email'], $r['points'], $r['admin'],
            $r['registration_sequence'], $r['registered']);

        return $user;
    }
}

// Dohvaćanje korisnika preko korisničkog imena.
public function getUserByUsername($username) {
    $db = DB::getConnection();

    $st = $db->prepare('SELECT * FROM pr_users  WHERE username=:username');
    $st->execute(array('username' => $username));

    if ($r = $st->fetch()) {
        $user = new User (
            $r['ID'], $r['ID_household'], $r['username'],
            $r['password'], $r['email'], $r['points'], $r['admin'],
            $r['registration_sequence'], $r['registered']);

        return $user;
    }
}

// Dohvaćanje korisnika preko e-mail adrese.
public function getUserByEmail($email) {
    $db = DB::getConnection();

    try {
    $st = $db->prepare('SELECT * FROM pr_users WHERE email=:email');
    $st->execute(array('email' => $email));

    if ($r = $st->fetch()) {
        $user = new User (
            $r['ID'], $r['ID_household'], $r['username'],
            $r['password'], $r['email'], $r['points'], $r['admin'],
            $r['registration_sequence'], $r['registered']);

        return $user;
    }
    }
    catch(PDOException $e) {
        exit('PDO error [select pr_users]: ' . $e->getMessage());
    }
}

// Dodavanje novog korisnika u bazu podataka.
public function addNewUser($user) {
    $db = DB::getConnection();

    try {
    $st = $db->prepare(
        'INSERT INTO pr_users(ID_household, username, password, ' .
        'email, points, admin, registration_sequence, registered) VALUES ' .
        '(:ID_household, :username, :password, :email, :points, ' .
        ':admin, :registration_sequence, :registered)');

    $st->execute(array(
        'ID_household' => $user->ID_household,
        'username' => $user->username,
        'password' => $user->password,
        'email' => $user->email,
        'points' => $user->points,
        'admin' => $user->admin,
        'registration_sequence' => $user->registration_sequence,
        'registered' => $user->registered));

    // Vrati ID dodanog korisnika.
    return $db->lastInsertId();
    }
    catch(PDOException $e) {
        exit('PDO error [insert pr_users]: ' . $e->getMessage());
    }
}

// Postavljanje vrijednosti u stupac registered korisniku sa zadanim ID-jem.
public function set_registered ($userID, $value) {
    $db = DB::getConnection();

    try {
    $st = $db->prepare('UPDATE pr_users SET registered = :value ' .
        'WHERE ID=:userID');

    $st->execute(array('value' => $value, 'userID' => $userID));
    }
    catch(PDOException $e) {
        exit('PDO error [update pr_users]: ' . $e->getMessage());
    }
}

public function addUserToHousehold($user, $household) {
    $db = DB::getConnection();

    try {
    $st = $db->prepare('UPDATE pr_users SET ID_household = :householdID ' .
        'WHERE ID=:userID');

    $st->execute(array(
        'householdID' => $household->ID,
        'userID' => $user->ID));
    }
    catch(PDOException $e) {
        exit('PDO error [update pr_households]: ' . $e->getMessage());
    }
}

//--------------------------------------------------------------------------
//  Funkcije za dohvaćanje iz tablice kućanstava
//--------------------------------------------------------------------------
public function getHouseholdByID($householdID) {
    $db = DB::getConnection();

    try {
    $st = $db->prepare('SELECT * FROM pr_households  WHERE ID=:householdID');
    $st->execute(array('householdID' => $householdID));

    if ($r = $st->fetch()) {
        $household = new Household ($r['ID'], $r['name']);

        return $household;
    }
    }
    catch(PDOException $e) {
        exit('PDO error [select pr_households]: ' . $e->getMessage());
    }
}

public function addNewHousehold($household) {
    $db = DB::getConnection();

    try {
    $st = $db->prepare('INSERT INTO pr_households(name) VALUES (:name)');

    $st->execute(array('name' => $household->name));

    // Vrati ID dodanog kućanstva.
    return $db->lastInsertId();
    }
    catch(PDOException $e) {
        exit('PDO error [insert pr_households]: ' . $e->getMessage());
    }
}

//--------------------------------------------------------------------------
//  Funkcije za dohvaćanje iz tablice zadataka
//--------------------------------------------------------------------------

// Stavlja jednokratni zadatak u stanje "done", a ako se ponavlja stavlja ga na iduci period.
public function setCompleted ($chore) {
    $db = DB::getConnection();

    $time_next = $chore->time_next;

    // Ako je zadatak ponavljajući, onda će postati aktivan od ponoći.
    $today = date("Y-m-d") . " 00:00:00";

    if ($chore->type == 1) { // Dnevni zadatak.
        // time vraća vrijeme u sekundama, dan ima 24*60*60 sekundi.
        $time_next = date("Y-m-d", time() + 24*60*60) . " 00:00:00"; 
    } else if ($chore->type == 2) {
        $time_next = date("Y-m-d", time() + 7*24*60*60) . " 00:00:00";

    // Mijenjam mjesec u idući mjesec.
    } else if ($chore->type == 3) {
        $month = substr($time_next, 5, 2);
        $year = substr($time_next, 0, 4);

        if ($month === "09") $month = "10";
        else if ($month === "12") {
            $month = "00";
            $year = strval($year + 1);
        } else {
            $month[1] = strval($month[1] + 1);
        }

        $time_next = $year . "-" . $month . "01 00:00:00";
    
    } else if ($chore->type == 4) {
        $year = substr($time_next, 0, 4);

        $year = strval($year + 1);

        $time_next = $year . substr($time_next, 4);
    }

    try {
    $st = $db->prepare('UPDATE pr_chores SET time_next = :time_next, done = :done' .
        'WHERE ID=:choreID');

    // Stupac done će biti "1" za zadatke koji se ne ponavljaju, inače "0".
    $st->execute(array('time_next' => $time_next, "done" => intval(! $chore->type), 'choreID' => $chore->ID));

    // Ako dođe do ovdje tablica je uspješno promijenjena.
    $chore->time_next = $time_next;
    $chore->done = intval(! $chore->type);
    
    }
    catch(PDOException $e) {
        exit('PDO error [update pr_chores]: ' . $e->getMessage());
    }
}

//--------------------------------------------------------------------------
//  Funkcije za dohvaćanje iz tablice kategorija
//--------------------------------------------------------------------------

// Vraća array defaultnih kategorija, i kategorija koje koristi neko kućanstvo ako je kućanstvo prosljeđeno funkciji.
public function getAllCategories($household = NULL) {
    $db = DB::getConnection();
    
    $ret = Category::getDefaultCategories();
    
    if ($household !== NULL) {
        try {
            $st = $db->prepare('SELECT * FROM pr_categories WHERE ID_household=:ID_household');
        
            $st->execute(array("ID_household" => $household->ID_household));
    
            while ($row = $st->fetch()) {
                array_push($ret, $row["name"]);
            }
    
        } catch(PDOException $e) {
            exit('PDO error [select pr_categories]: ' . $e->getMessage());
        }
    }

    // Vrati array s imenima kategorija.
    return $ret;
}

public function addNewCategory($household, $name) {
    $db = DB::getConnection();

    try {
    $st = $db->prepare('INSERT INTO pr_categories (ID_household, name) VALUES (:ID_household, :name)');

    $st->execute(array("ID_household" => $household->ID, 'name' => $name));

    // Vrati ID dodane kategorije.
    return $db->lastInsertId();
    }
    catch(PDOException $e) {
        exit('PDO error [insert pr_categories]: ' . $e->getMessage());
    }
}

//--------------------------------------------------------------------------
//  Funkcije za dohvaćanje iz tablice nagrade
//--------------------------------------------------------------------------
}
?>
