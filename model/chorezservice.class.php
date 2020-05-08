<?php
require_once platformSlashes($dir . '/app/database/db.class.php');
require_once platformSlashes($dir . '/model/user.class.php');
// TODO: Napraviti i dodati ostale modele.

class ChorezService {
//--------------------------------------------------------------------------
//  Funkcije za dohvaćanje iz tablice korisnika
//--------------------------------------------------------------------------

// Dohvaćanje korisnika preko ID-ja.
public static function getUserByID($userID) {
    $db = DB::getConnection();

    $st = $db->prepare('SELECT * FROM pr_users  WHERE ID=:userID');
    $st->execute(array('userID' => userID));

    if ($r = $st->fetch()) {
        $user = new User (
            $r['ID'], $r['ID_household'], $r['username'],
            $r['password'], $r['email'], $r['points'], $r['admin'],
            $r['registration_sequence'], $r['registered']);

        return $user;
    }
}

// Dohvaćanje korisnika preko korisničkog imena.
public static function getUserByUsername($username) {
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
public static function getUserByEmail($email) {
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

// Dohvaćanje lozinke (hashirane) korisnika preko korisničkog imena.
public static function getUserPasswordByUsername($username) {
    $db = DB::getConnection();

    try {
    $st = $db->prepare('SELECT password FROM pr_users ' .
        'WHERE username=:username');
    $st->execute(array('username' => $username));

    if ($r = $st->fetch())
        return $r['password'];
    }
    catch(PDOException $e) {
        exit('PDO error [select pr_users]: ' . $e->getMessage());
    }

}

// Dodavanje novog korisnika u bazu podataka.
public static function addNewUser($user) {
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
    }
    catch(PDOException $e) {
        exit('PDO error [insert pr_users]: ' . $e->getMessage());
    }
}

// Postavljanje vrijednosti u stupac registered korisniku sa zadanim ID-jem.
public static function set_registered ($userID, $value) {
    $db = DB::getConnection();

    $st = $db->prepare('UPDATE pr_users SET registered = :value ' .
        'WHERE id=:userID');
    $st->execute(array('value' => $value, 'userID' => $userID));
}
}
?>
