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

    $st = $db->prepare('SELECT * FROM pr_korisnici  WHERE ID=:userID');
    $st->execute(array('userID' => userID));

    if ($r = $st->fetch()) {
        $user = new User (
            $r['ID'], $r['ID_kucanstvo'], $r['username'],
            $r['password_hash'], $r['email'], $r['bodovi'], $r['admin'],
            $r['registracijski_niz'], $r['registriran']);

        return $user;
    }
}

// Dohvaćanje korisnika preko korisničkog imena.
public static function getUserByUsername($username) {
    $db = DB::getConnection();

    $st = $db->prepare('SELECT * FROM pr_korisnici  WHERE username=:username');
    $st->execute(array('username' => $username));

    if ($r = $st->fetch()) {
        $user = new User (
            $r['ID'], $r['ID_kucanstvo'], $r['username'],
            $r['password_hash'], $r['email'], $r['bodovi'], $r['admin'],
            $r['registracijski_niz'], $r['registriran']);

        return $user;
    }
}

// Dohvaćanje korisnika preko e-mail adrese.
public static function getUserByEmail($email) {
    $db = DB::getConnection();

    try {
    $st = $db->prepare('SELECT * FROM pr_korisnici WHERE email=:email');
    $st->execute(array('email' => $email));

    if ($r = $st->fetch()) {
        $user = new User (
            $r['ID'], $r['ID_kucanstvo'], $r['username'],
            $r['password_hash'], $r['email'], $r['bodovi'], $r['admin'],
            $r['registracijski_niz'], $r['registriran']);

        return $user;
    }
    }
    catch(PDOException $e) {
        exit('PDO error [select pr_korisnici]: ' . $e->getMessage());
    }
}

// Dohvaćanje lozinke (hashirane) korisnika preko korisničkog imena.
public static function getUserPasswordByUsername($username) {
    $db = DB::getConnection();

    try {
    $st = $db->prepare('SELECT password_hash FROM pr_korisnici ' .
        'WHERE username=:username');
    $st->execute(array('username' => $username));

    if ($r = $st->fetch())
        return $r['password_hash'];
    }
    catch(PDOException $e) {
        exit('PDO error [select pr_korisnici]: ' . $e->getMessage());
    }

}

// Dodavanje novog korisnika u bazu podataka.
public static function addNewUser($user) {
    $db = DB::getConnection();

    try {
    $st = $db->prepare(
        'INSERT INTO pr_korisnici(ID_kucanstvo, username, password_hash, ' .
        'email, bodovi, admin, registracijski_niz, registriran) VALUES ' .
        '(:ID_kucanstvo, :username, :password_hash, :email, :bodovi, ' .
        ':admin, :registracijski_niz, :registriran)');

    $st->execute(array(
        'ID_kucanstvo' => $user->ID_kucanstvo,
        'username' => $user->username,
        'password_hash' => $user->password_hash,
        'email' => $user->email,
        'bodovi' => $user->bodovi,
        'admin' => $user->admin,
        'registracijski_niz' => $user->registracijski_niz,
        'registriran' => $user->registriran));
    }
    catch(PDOException $e) {
        exit('PDO error [insert pr_korisnici]: ' . $e->getMessage());
    }
}

// Postavljanje vrijednosti u stupac registriran korisniku sa zadanim ID-jem.
public static function set_registriran ($userID, $value) {
    $db = DB::getConnection();

    $st = $db->prepare('UPDATE pr_korisnici SET registriran = :value ' .
        'WHERE id=:userID');
    $st->execute(array('value' => $value, 'userID' => $userID));
}
}
?>
