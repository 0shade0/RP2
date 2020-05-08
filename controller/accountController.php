<?php
require_once platformSlashes($dir . '/model/chorezservice.class.php');

class accountController {
// Provjera korisničkog imena i lozinke za prijavu.
public function checkLogin() {
    if (isset($_POST['member_name']) && isset($_POST['member_password'])) {
        $cs = new ChorezService();
        $success = False;
        // Dohvaćamo hashirani password iz baze.
        $passwordHash = $cs->getUserPasswordByUsername($_POST['member_name']);

        // Provjeravamo odgovaraju li korisničko ime i lozinka.
        // Ako ne postoji korisnik s tim korisničkim imenom,
        // ChorezService::getPasswordByUsername() vraća null.
        if ($passwordHash !== null) {
            if (password_verify($_POST['member_password'], $passwordHash)) {
                if (session_status() !== PHP_SESSION_ACTIVE)
                    session_start();

                $_SESSION['user'] =
                    $cs->getUserByUsername($_POST['member_name']);

                $success = True;
            }
        }
    }

    // Neuspješan login.
    if (!$success) {
        global $title, $dir;
        $title = 'User page';
        $message = 'Krivo korisničko ime ili lozinka.';

        require_once platformSlashes($dir . '/view/_header.php');
        require_once platformSlashes($dir . '/view/main_menu.php');
        require_once platformSlashes($dir . '/view/login.php');
        require_once platformSlashes($dir . '/view/site_description.php');
        require_once platformSlashes($dir . '/view/_footer.php');
    }
    // Uspješan login.
    else {
        // TODO: Preusmjeri korisnika na neku stranicu --- vjerojatno
        // popis zadataka.
        global $title, $dir;
        $title = 'User page';

        require_once platformSlashes($dir . '/view/_header.php');
        require_once platformSlashes($dir . '/view/main_menu.php');
        require_once platformSlashes($dir . '/view/site_description.php');
        require_once platformSlashes($dir . '/view/_footer.php');
    }
}

// Obrada korisnikovog zahtjeva za registracijom.
public function register() {
    /* Ovdje za sad uopće neću provjeravati u kojem je obliku korisnik
    napisao email, username, lozinku i podudaraju li se lozinka i
    ponovljena lozinka. Najbolje da to napravimo pomoću Javascripta.
    */
    $cs = new ChorezService();

    global $title, $dir;
    $title = 'User page';

    // Provjeri postoji jesu li varijable postavljene, i postoje li već
    // u bazi korisnici s istim korisničkim imenom ili e-mail adresom.
    if (!isset($_POST['member_name']))
        $message_name = 'Morate upisati korisničko ime.';

    else if (!isset($_POST['member_email']))
        $message_email = 'Morate upisati e-mail adresu.';

    else if (!isset($_POST['member_password'])
        || !isset($_POST['repeat_password']))
        $message_password = 'Morate upisati lozinku.';

    else if ($cs->getUserByUsername($_POST['member_name']) !== null)
        $message_name = 'Korisnik s tim korisničkim imenom već postoji.';

    else if ($cs->getUserByEmail($_POST['member_email']) !== null)
        $message_email = 'Korisnik s tom e-mail adresom već postoji.';

    // Registracija.
    else {
        // Generiraj registracijski niz = niz znakova duljine 20.
        $sequence = '';
        for ($i = 0; $i < 20; ++$i)
            $sequence .= chr(rand(ord("a"), ord("z")));

        // Hashiraj password.
        $passwordHash =
            password_hash($_POST['member_password'], PASSWORD_DEFAULT);

        /* Spremi korisnika u bazu s vrijednostima:
            ID = 0 (MySQL će dodijeliti jedinstveni ID)
            ID_kucanstvo = 0 (dodijelit ćemo mu kućanstvo kad potvrdi e-mail)
            username = $_POST['member_name']
            password_hash = $passwordHash,
            email = $_POST['member_email']
            bodovi = 0
            admin = 1 (svi novoregistrirani će automatski biti administratori)
            registracijski_niz = $sequence
            registriran = 0.
        */
        $user = New User(0, 0, $_POST['member_name'], $passwordHash,
                $_POST['member_email'], 0, 0, $sequence, 0);

        $cs->addNewUser($user);

        // Kada je korisnik ubačen u bazu, nađi ID koji mu je dodijeljen.
        $user = $cs->getUserByUsername($_POST['member_name']);

        /* Link za registraciju šaljemo na mail, a on je oblika
            main.php?rt=users/activate&
                sequence=<registracijski niz>&
                userID=<korisnikov ID>

        NOTE: Slanje maila neće raditi s lokalnog servera, a i link
        koji se šalje unutar bodyja poruke je potrebno promijeniti ovisno o
        tome na kojem se serveru skripta trenutno nalazi.
        */

        $email = $_POST['member_email'];
        $subject = 'Dobrodošli na Chorez!';

        $body = 'Kako biste uspješno dovršili registraciju na Chorezo' .
            'pritisnite ili prekopirajte link' .
            'https://rp2.studenti.math.hr/~korisnicko_ime/projekt/main.php?' .
            'rt=users/activate&sequence=' . $sequence . '&userID=' . $user->ID;

        $bodyWrapped = wordwrap($body, 70, "<br>\n");

        // TODO: Testirati na serveru RP2, dodati automatsko ime servera
        // i još malo popraviti poruku.
        mail($email, $subject, $bodyWrapped);

        // TODO: Dodati još neko mjesto za message ili stranicugdje mogu
        // napisati da je registracija uspješna i da treba potvrditi e-mail.
        $message_name = 'Uspješna registracija! Da biste dovršili ' .
            'registraciju, trebate potvrditi svoju e-mail adresu.';
    }

    require_once platformSlashes($dir . '/view/_header.php');
    require_once platformSlashes($dir . '/view/main_menu.php');
    require_once platformSlashes($dir . '/view/login.php');
    require_once platformSlashes($dir . '/view/site_description.php');
    require_once platformSlashes($dir . '/view/_footer.php');
}

// Aktivacija e-mail adrese.
public function activate() {
    if (isset($_GET['sequence']) && isset($_GET['userID'])) {
        $cs = new ChorezService();

        $user = $cs->getUserByID($_GET['userID']);

        if ($user !== null && $user->registracijski_niz === $_GET['sequence']) {
            // Postavi registriran na 1.
            $cs->set_registriran($_GET['userID'], 1);

            // TODO: Napravi i dodijeli korisniku novo kućanstvo.
        }
    }
}
}
 ?>
