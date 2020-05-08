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
}
?>
