<?php
require_once platformSlashes($dir . '/app/database/db.class.php');
require_once platformSlashes($dir . '/model/user.class.php');
require_once platformSlashes($dir . '/model/household.class.php');
// TODO: Napraviti i dodati ostale modele.

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

}
?>
