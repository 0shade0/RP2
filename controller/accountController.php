<?php
require_once platformSlashes($dir . '/model/chorezservice.class.php');

class accountController {
// Provjera korisničkog imena i lozinke za prijavu.
public function index() {
    $success = False;

    if (isset($_POST['log_name']) && isset($_POST['log_password'])) {
        $cs = new ChorezService();
        // Dohvaćamo hashirani password iz baze.
        $user = $cs->getUserByUsername($_POST['log_name']);

        // Provjeravamo odgovaraju li korisničko ime i lozinka.
        // Ako ne postoji korisnik s tim korisničkim imenom,
        // ChorezService::getPasswordByUsername() vraća null.
        if ($user !== null) {
            $passwordHash = $user->password;

            if (password_verify($_POST['log_password'], $passwordHash)) {
                if (session_status() !== PHP_SESSION_ACTIVE)
                    session_start();

                $_SESSION['user'] = $user->ID;
                $_SESSION['name'] = $user->username;

                if($cs->getFirstIDInHousehold($user->ID_household)['ID'] === $_SESSION['user']) {
                    $_SESSION['boss'] = 1;
                    $cs->setUserAdmin($_SESSION['user']);
                }
                else
                    $_SESSION['boss'] = 0;

                if(isset($_POST['remember']))
                    setcookie("member_login", $user->username, time() + (86400 * 30), "/");
                else setcookie('member_login', null, -1, '/'); 

                $success = True;
            }
            else $message = 'Neispravna lozinka.';
        }
        else $message = 'Korisnik "' . $_POST['log_name'] . '" ne postoji.';
    }

    // Neuspješan login.
    if (!$success) {
        global $title, $dir;
        $title = 'Dobrodošli u CHOREZ';

        require_once platformSlashes($dir . '/view/_header.php');
        require_once platformSlashes($dir . '/view/login.php');
        require_once platformSlashes($dir . '/view/site_description.php');
        require_once platformSlashes($dir . '/view/_footer.php');
    }
    // Uspješan login.
    else {
        global $dir;
        require_once platformSlashes($dir . '/controller/choreController.php');
        $forward = New choreController();
        $forward->index();
    }
}

// Obrada korisnikovog zahtjeva za registracijom.
public function register() {

    $cs = new ChorezService();

    // Provjeri postoji jesu li varijable postavljene, i postoje li već
    // u bazi korisnici s istim korisničkim imenom ili e-mail adresom.

    if(isset($_POST['register'])) $novo = False;
    else $novo = True;

    // korisničko ime
    if (!isset($_POST['reg_name']))
        $message_name = 'Morate upisati korisničko ime.';

    else if ($cs->getUserByUsername($_POST['reg_name']) !== null)
        $message_name = 'Korisnik s tim korisničkim imenom već postoji.'; 


    // e-mail adresa
    if (!isset($_POST['reg_email']))
        $message_email = 'Morate upisati e-mail adresu.';

    else {
        $email = $_POST['reg_email'];
        $email = filter_var( $email, FILTER_SANITIZE_EMAIL );

        if(filter_var( $email, FILTER_VALIDATE_EMAIL )===False)
        {
            $content_valid = False;
            $message_email = "E-mail adresa nije u ispravnom formatu.";
        }

        else if ($cs->getUserByEmail($_POST['reg_email']) !== null)
            $message_email = 'Korisnik s tom e-mail adresom već postoji.';
    }
    

    // lozinka
    if (!isset($_POST['reg_password'])
        || !isset($_POST['reg_repeat']))
        $message_password = 'Morate upisati lozinku.';

    else if ($_POST['reg_password'] != $_POST['reg_repeat'])
        $message_password = 'Lozinke nisu jednake.';



    // ime i lozinka novog kućanstva
    if(isset($novo) && $novo) {
        if (!isset($_POST['house_name']) || $_POST['house_name'] === "")
            $message_hname = 'Morate upisati ime kućanstva.';
        

        if(!isset($_POST['house_newpassword']) || $_POST['house_newpassword'] === "") 
            $message_hpassword_new = 'Morate upisati lozinku.';

    }


    // ID i lozinka postojećeg kućanstva
    if (isset($novo) && !$novo) {
        if (!isset($_POST['house_id']) || $_POST['house_id'] === "")
            $message_hid = 'Morate upisati ime kućanstva.';

        else if (!preg_match("/^[1-9][0-9]*$/", $_POST['house_id']))
            $message_hid = '#ID kućanstva mora biti broj (različit od 0).';

        else if ($cs->getHouseholdByID($_POST['house_id']) === null)
            $message_hid = 'Kućanstvo s tim #ID ne postoji.';
        
        
            
        
        if(!isset($_POST['house_password']) || $_POST['house_password'] === "") 
            $message_hpassword = 'Morate upisati lozinku.';

        else if (!isset($message_hid)) {
            $household =  $cs->getHouseholdByID($_POST['house_id']);

            if(password_verify($_POST['house_password'], $household->password)) {
                $passwordHash =
                    password_hash($_POST['house_password'], PASSWORD_DEFAULT);
                $ID_household = $household->ID;
            }
                
            else $message_hpassword = 'Neispravna lozinka.';
        }
    }


    // Registracija.
    if(
        !isset($message_name) && !isset($message_email) && !isset($message_password) &&
        !isset($message_hname) && !isset($message_hpassword_new) && !isset($message_hid) && !isset($message_hpassword)) {
        // Generiraj registracijski niz = niz znakova duljine 20.
        $sequence = '';
        for ($i = 0; $i < 20; ++$i)
            $sequence .= chr(rand(ord("a"), ord("z")));

        // Hashiraj password.
        $passwordHash =
            password_hash($_POST['reg_password'], PASSWORD_DEFAULT);


        /* Spremi korisnika u bazu s vrijednostima:
            ID = 0 (MySQL će dodijeliti jedinstveni ID)
            ID_household = broj novog odnosno postojećeg kućanstva ovisno o vrsti registracije
            username = $_POST['reg_name']
            password = $passwordHash,
            email = $_POST['reg_email']
            points = 0
            image = 1
            admin = 0 ako ulazi u neko kućanstvo, 1 ako stvara novo kućanstvo
            registration_sequence = $sequence
            registered = 0.
        */
        $admin = 0;

        // Stvaramo novo kućanstvo ako je izabrana ta opcija
        if($novo) {
            $password_household = password_hash($_POST['house_newpassword'], PASSWORD_DEFAULT);
            $ID_household = $cs->addNewHousehold($_POST['house_name'], $password_household);
            $admin = 1;

            $event_text = "Stvoreno je kućanstvo - <strong>".$_POST['house_name']."</strong>";

            $event = New Event(
                0,
                0,
                $ID_household,
                $event_text,
                ""
            );
    
            $cs->createEvent($event);
        }

        $user = New User(0, $ID_household, $_POST['reg_name'], $passwordHash,
                $_POST['reg_email'], 0, 1, 1, $admin, $sequence, 0);

        $userID = $cs->addNewUser($user);

        /* Link za registraciju šaljemo na mail, a on je oblika
            main.php?rt=account/activate&
                sequence=<registracijski niz>&
                userID=<korisnikov ID>

        NOTE: Slanje maila neće raditi s lokalnog servera, a i link
        koji se šalje unutar bodyja poruke je potrebno promijeniti ovisno o
        tome na kojem se serveru skripta trenutno nalazi.
        */

        $email = $_POST['reg_email'];
        $subject = 'Dobrodošli na Chorez!';
        $headers = "From: no-reply" . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        $body = '
                <html>
                    <body>
                        <img src="https://rp2.studenti.math.hr/~damcupi/RP2/app/image/chorez.png" width="400" style="display:block; margin-left:20px; margin-bottom: 30px;">
                        <div>
                            <div>
                            <p style=font-size:20pt;><strong>Bok '.$_POST['reg_name'].'!</strong></p>
                            <p>Hvala Vam što ste se prijavili na Chorez. </p>
                            <p>Preostaje Vam još samo jedan korak.
                            Kako biste uspješno dovršili registraciju, <strong>pritisnite</strong> ili <strong>prekopirajte</strong> link:
                            <a href="https://rp2.studenti.math.hr/~damcupi/RP2/chorez.php?rt=account/activate&sequence='.$sequence.'&userID='.$userID.'">
                                https://rp2.studenti.math.hr/~damcupi/RP2/chorez.php?rt=account/activate&sequence='.$sequence.'&userID='.$userID.' </a> </p>
                            </div>
                            <div>
                            <p>Ovo su Vaši podaci:</p>
                            <p style="margin-left: 20px;">korisničko ime: <strong>'.$_POST['reg_name'].'</strong></p>
                            <p style="margin-left: 20px;">lozinka: <strong>'.$_POST['reg_password'].'</strong></p>
                            </div>';
        if($novo) $body.='
                            <div>
                            <p>Ako želite pozvati još članova u svoje kućanstvo, proslijedite im ove podatke za registraciju:</p>
                            <p style="margin-left: 20px;">#ID: <strong>'.$ID_household.'</strong></p>
                            <p style="margin-left: 20px;">lozinka: <strong>'.$_POST['house_newpassword'].'</strong></p>
                            </div>';
        $body.='
                            <div style="text-align: center; font-size: 16pt;">
                                Vaš Chorez tim ❤
                            </div>
                        </div>
                    </body>
                </html>
                ';

        mail($email, $subject, $body, $headers);

        $message_info = 'Uspješna registracija! Da biste dovršili ' .
        'registraciju, trebate potvrditi svoju e-mail adresu. ' .
        'Link za potvrdu poslan je na Vaš e-mail. <br>' . 
        '(napomena: provjerite neželjenu poštu)';

        $event_text = "Kućanstvu se pridružio korisnik - <strong>".$_POST['reg_name']."</strong>";

        $event = New Event(
            0,
            0,
            $ID_household,
            $event_text,
            ""
        );

        $cs->createEvent($event);
        $cs->setHouseholdUnseen($ID_household);
    }

    global $title, $dir;
    $title = 'Dobrodošli u CHOREZ';

    // Preusmjeri korisnika na početnu stranicu.
    require_once platformSlashes($dir . '/view/_header.php');
    require_once platformSlashes($dir . '/view/login.php');
    require_once platformSlashes($dir . '/view/site_description.php');
    require_once platformSlashes($dir . '/view/_footer.php');
}

// Aktivacija e-mail adrese.
public function activate() {
    if (isset($_GET['sequence']) && isset($_GET['userID'])) {
        $cs = new ChorezService();

        $user = $cs->getUserByID($_GET['userID']);
        if ($user !== null && $user->registration_sequence === $_GET['sequence']) {

            // Postavi registriran na 1.
            $cs->set_registered($_GET['userID'], 1);
        }
    }

    global $title, $dir;
    $title = 'Dobrodošli u CHOREZ';

    // Preusmjeri korisnika na početnu stranicu i ispiši potvrdu o
    // uspješnoj aktivaciji računa.
    $message_info = 'Uspješno ste aktivirali svoj račun! Prijavite se.';

    require_once platformSlashes($dir . '/view/_header.php');
    require_once platformSlashes($dir . '/view/login.php');
    require_once platformSlashes($dir . '/view/site_description.php');
    require_once platformSlashes($dir . '/view/_footer.php');
}
}
 ?>
