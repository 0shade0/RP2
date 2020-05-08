<?php
require_once platformSlashes($dir . '/model/chorezservice.class.php');

class testController
{
    public function index()
    {
        if(session_id() == '') session_start();
        global $title, $dir;
        $title = 'Uvodna stranica';

    require_once platformSlashes($dir . '/view/_header.php');
    require_once platformSlashes($dir . '/view/main_menu.php');
    // Ako je korisnik već ulogiran, ne prikazuj formu za login.
    if (!isset($_SESSION['user']))
        require_once platformSlashes($dir . '/view/login.php');
    require_once platformSlashes($dir . '/view/site_description.php');
    require_once platformSlashes($dir . '/view/_footer.php');
    }

    public function test2()
    {
        if(session_id() == '') session_start();
        global $title, $dir;
        $title = 'Nagrade';

        require_once platformSlashes($dir . '/view/_header.php');
        require_once platformSlashes($dir . '/view/main_menu.php');
        require_once platformSlashes($dir . '/view/rewards.php');
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

}
