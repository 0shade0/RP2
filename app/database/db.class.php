<?php

$db_base = 'mysql:host=localhost:3306;dbname=RP2;charset=utf8';
$db_user = 'root';
$db_pass = '';

class DB {
    // Interna statička varijabla koja čuva konekciju na bazu
    private static $db = null;

    // Zabranimo new DB() i kloniranje;
    final private function __construct() { }
    final private function __clone() { }

    // Statička funkcija za pristup bazi.
    public static function getConnection() {
        // Spoji se samo ako već nisi nekad ranije.
        if( DB::$db === null ) {
            // U glob. varijablama su parametri za spajanje
            global $db_base, $db_user, $db_pass;
            DB::$db = new PDO($db_base, $db_user, $db_pass);
        }

        return DB::$db;
    }
} 
?>