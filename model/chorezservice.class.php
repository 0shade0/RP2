<?php
require_once platformSlashes($dir . '/app/database/db.class.php');
require_once platformSlashes($dir . '/model/user.class.php');
// TODO: Dodati ostale modele.

class ChorezService {
    //--------------------------------------------------------------------------
    //  Funkcije za dohvaćanje iz tablice korisnika
    //--------------------------------------------------------------------------

    // Dohvaćanje korisnika s određenim korisničkim imenom.
    public static function getUserByUsername($username) {
        $db = DB::getConnection();

        $st = $db->prepare('SELECT * FROM pr_korisnici WHERE username=:username');
        $st->execute(array('username' => $username));

        if ($r = $st->fetch()) {
            $user = new User (
                $r['ID'], $r['ID_kucanstvo'], $r['username'],
                $r['password_hash'], $r['email'], $r['bodovi'], $r['admin'],
                $r['registracijski_niz'], $r['registriran']);

            return $user;
        }
    }

    public static function getUserByEmail($email) {
        $db = DB::getConnection();

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

    // Dohvaćanje lozinke (hashirane) korisnika s određenim korisničkim imenom.
    // Ukoliko takav korisnik ne postoji, ne vraća ništa.
    public static function getUserPasswordByUsername($username) {
        $db = DB::getConnection();

        $st = $db->prepare('SELECT password_hash FROM pr_korisnici ' .
            'WHERE username=:username');
        $st->execute(array('username' => $username));

        if ($r = $st->fetch())
            return $r['password_hash'];
    }

    // Dodavanje novog korisnika u bazu podataka.
    public static function addNewUser($user) {
        $db = DB::getConnection();

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
}
?>
