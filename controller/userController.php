<?php
require_once platformSlashes($dir . '/model/chorezservice.class.php');

class userController
{
    public function index()
    {
        if(session_id() == '') session_start();
        global $title, $dir;
        $title = 'User page';

        require_once platformSlashes($dir . '/view/_header.php');
        require_once platformSlashes($dir . '/view/main_menu.php');
        require_once platformSlashes($dir . '/view/login.php');
        require_once platformSlashes($dir . '/view/site_description.php');
        require_once platformSlashes($dir . '/view/_footer.php');
    }

    public function test2()
    {
        if(session_id() == '') session_start();
        global $title, $dir;
        $title = 'User page';

        require_once platformSlashes($dir . '/view/_header.php');
        require_once platformSlashes($dir . '/view/main_menu.php');

        require_once platformSlashes($dir . '/view/_footer.php');
    }

    public function test3()
    {
        if(session_id() == '') session_start();
        global $title, $dir;
        $title = 'User page';

        require_once platformSlashes($dir . '/view/_header.php');
        require_once platformSlashes($dir . '/view/main_menu.php');

        require_once platformSlashes($dir . '/view/_footer.php');
    }

    public function test4()
    {
        if(session_id() == '') session_start();
        global $title, $dir;
        $title = 'User page';

        require_once platformSlashes($dir . '/view/_header.php');
        require_once platformSlashes($dir . '/view/main_menu.php');

        require_once platformSlashes($dir . '/view/_footer.php');
    }

    public function test5()
    {
        if(session_id() == '') session_start();
        global $title, $dir;
        $title = 'User page';

        require_once platformSlashes($dir . '/view/_header.php');
        require_once platformSlashes($dir . '/view/main_menu.php');

        require_once platformSlashes($dir . '/view/_footer.php');
    }

    // Provjera korisničkog imena i lozinke za prijavu.
    public function checkLogin() {
        if (isset($_POST['member_name']) && isset($_POST['member_password'])) {
            $cs = new ChorezService();

            // Dohvaćamo hashirani password iz baze.
            $passwordHash =
                $cs->getUserPasswordByUsername($_POST['member_name']);

            // Provjeravamo odgovara li ono što je korisnik napisao
            // hashiranom passwordu u bazi. Ako je null, korisnik
            // s tim korisničkim imenom ne postoji.
            if ($passwordHash !== null) {
                if (password_verify($_POST['member_password'], $passwordHash)) {
                    if (session_status() !== PHP_SESSION_ACTIVE)
                        session_start();

                    // Provjeriti sprema li se u sesiju pod tim imenom.
                    $_SESSION['user'] =
                        $cs->getUserByUsername($_POST['member_name']);
                }
            }
            // TODO: Treba dodati prijavu o grešci.
            // TODO: Treba preusmjeriti korisnika na neku stranicu.
            global $title, $dir;
            $title = 'User page';
            require_once platformSlashes($dir . '/view/_header.php');
            require_once platformSlashes($dir . '/view/main_menu.php');

            require_once platformSlashes($dir . '/view/_footer.php');
        }
    }

    // Obrada korisnikovog zahtjeva za registracijom.
    public function register() {
        // Ovdje za sad uopće neću provjeravati u kojem je obliku korisnik
        // napisao email, username, lozinku i podudaraju li se lozinka i
        // ponovljena lozinka. Najbolje da to napravimo pomoću Javascripta.
        $cs = new ChorezService();

        global $title, $dir;
        $title = 'User page';

        // Provjeri postoji li već korisnik s istim korisničkim imenom.
        // Pretpostavljam da su ove $message_name/email/password za poruku
        // o grešci.
        if ($cs->getUserByUsername($_POST['member_name']) !== null) {
            $message_name = 'Korisnik s tim korisničkim imenom već postoji.';
            require_once platformSlashes($dir . '/view/_header.php');
            require_once platformSlashes($dir . '/view/main_menu.php');
            require_once platformSlashes($dir . '/view/login.php');
            require_once platformSlashes($dir . '/view/site_description.php');
            require_once platformSlashes($dir . '/view/_footer.php');
        }
        // Provjeri postoji li već korisnik s tim e-mailom.
        else if ($cs->getUserByEmail($_POST['member_email']) !== null) {
            $message_email = 'Korisnik s tim e-mailom već postoji.';
            require_once platformSlashes($dir . '/view/_header.php');
            require_once platformSlashes($dir . '/view/main_menu.php');
            require_once platformSlashes($dir . '/view/login.php');
            require_once platformSlashes($dir . '/view/site_description.php');
            require_once platformSlashes($dir . '/view/_footer.php');
        }
        // Kreće registracija.
        else {
            // Generiraj registracijski niz = niz znakova duljine 20.
            $sequence = '';
            for ($i = 0; $i < 20; ++$i)
                $sequence .= chr(rand(ord("a"), ord("z")));

            // Hashiraj password.
            $passwordHash =
                password_hash($_POST['member_password'], PASSWORD_DEFAULT);

            /* Spremi korisnika u bazu kao za sad neregistriranog.
            ID postavljam na 0 jer će nam MySQL dati ID kad ubacujemo
            u bazu.

            QUESTION: Kada korisnici mogu birati kućanstvo? Možda nakon što
            potvrde e-mail adresu. Onda će trebati dodati i neku formu
            za odabir/stvaranje novog kućanstva.
            Za sada ID kućanstva stavljam na 0.

            Bodovi novostvorenog korisnika su 0.

            QUESTION: Gdje se korisnika može postaviti za administratora?
            Možda bi mogli svi zadano ne biti administratori, a onda se u
            nekim postavkama mogu postaviti za administratora.
            Za sada admin stavljam na 0.

            Korisnik još nije potvrdio e-mail adresu pa $registriran = 0.
            */
            $user = New User(0, 0, $_POST['member_name'], $passwordHash,
                    $_POST['member_email'], 0, 0, $sequence, 0);

            // TODO: Zaštititi ako dođe do neke greške.
            $cs->addNewUser($user);

            // Kada je korisnik ubačen u bazu, nađi ID koji mu je dodijeljen.
            $user = $cs->getUserByUsername($_POST['member_name']);
            echo 'Ubačen u bazu';

            // Registracijski link će biti oblika
            //  users/activate&sequence=<registracijski_niz>&
            //  ID=<korisnikov ID>.
            // Kako bismo mogli provjeriti podudaraju li se korisnik
            // i registracijski niz koji mu je pridijeljen u bazi.

            // NOTE: Slanje maila ne radi na lokalnom serveru pa to treba
            // isprobati na RP2 serveru.
            $email = $_POST['member_email'];
            $subject = 'Dobrodošli na Chorez!';

            // NOTE: Zbog toga ću ovdje ostaviti link koji neće funkcionirati
            // pa ćemo dodati to na kraju kada budemo stavili na RP2.
            // Ako želite testirati, upišite svoje podatke za RP2.
            $body = 'Kako biste uspješno dovršili registraciju na Chorezo' .
                "\n" .
                'pritisnite ili prekopirajte link' . "\n" .
                'https://rp2.studenti.math.hr/~korisnicko_ime/projekt/main.php' .
                "\n" .
                '?rt=users/activate&sequence=' . $sequence .
                '&userId=' . $user->ID;

            /* NOTE: Za testiranje, dodajte
                main.php?rt=users/activate&sequence=<registracijski_niz>&
                ID=<korisnikov ID>

            a registracijski niz i korisnikov ID pronađite u bazi. */
            mail($email, $subject, $body);

            echo 'Uspješna registracija!';
            // TODO: Dodati još neko mjesto za message gdje mogu napisati
            // da je registracija uspješna i da treba potvrditi e-mail.

            require_once platformSlashes($dir . '/view/_header.php');
            require_once platformSlashes($dir . '/view/main_menu.php');

            require_once platformSlashes($dir . '/view/_footer.php');
        }

    }
}

?>
