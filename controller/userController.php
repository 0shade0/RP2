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
}

?>
