<?php

class userController
{
    public function index()
    {
        if(session_id() == '') session_start();
        global $title, $dir;
        $title = 'User page';

        require_once platformSlashes($dir . '/view/_header.php');
        require_once platformSlashes($dir . '/view/main_menu.php');
        require_once platformSlashes($dir . '/view/_footer.php');
    }
}

?>