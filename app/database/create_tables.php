<?php
require_once __DIR__ . '/db.class.php';

create_table_korisnici();
create_table_kucanstva();
create_table_zadaci();
create_table_kategorije();
create_table_nagrade();

exit(0);

// Provjeri postoji li već tablica u bazi.
function has_table($tableName)
{
	$db = DB::getConnection();

	try {
		$st = $db->prepare('SHOW TABLES LIKE :tableName');
		$st->execute(array('tableName' => $tableName));

		if($st->rowCount() > 0)
			return true;
	}
	catch(PDOException $e) {
		exit('PDO error [show tables]: ' . $e->getMessage());
	}

	return false;
}

//------------------------------------------------------------------------------
// Stvaranje tablica.
function create_table_korisnici()
{
	$db = DB::getConnection();

	if (has_table('pr_korisnici'))
		exit('Tablica pr_korisnici već postoji.<br>');

	try {
		$st = $db->prepare(
			'CREATE TABLE IF NOT EXISTS pr_korisnici (' .
			'ID int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
			'ID_kucanstvo int NOT NULL,' .
			'username varchar(50) NOT NULL,' .
			'password_hash varchar(255) NOT NULL,'.
			'email varchar(50) NOT NULL,' .
			'bodovi int NOT NULL,' .
			'admin int NOT NULL,' .
			'registracijski_niz varchar(20) NOT NULL,' .
			'registriran int NOT NULL)'
		);

		$st->execute();
	}
	catch(PDOException $e) {
		exit('PDO error [create pr_korisnici]: ' . $e->getMessage());
	}

	echo 'Napravio tablicu pr_korisnici.<br>';
}

function create_table_kucanstva()
{
	$db = DB::getConnection();

	if (has_table('pr_kucanstva'))
		exit('Tablica pr_kucanstva već postoji.<br>');

	try {
		$st = $db->prepare(
			'CREATE TABLE IF NOT EXISTS pr_kucanstva (' .
			'ID int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
			'ime varchar(100) NOT NULL)'
		);

		$st->execute();
	}
	catch(PDOException $e) {
		exit('PDO error [create pr_kucanstva]: ' . $e->getMessage());
	}

	echo 'Napravio tablicu pr_kucanstva.<br>';
}


function create_table_zadaci()
{
	$db = DB::getConnection();

	if (has_table('pr_zadaci'))
		exit('Tablica pr_zadaci već postoji.<br>');

	try {
		$st = $db->prepare(
			'CREATE TABLE IF NOT EXISTS pr_zadaci (' .
			'ID int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
			'ID_korisnik int NOT NULL,' .
			'ID_kategorija int NOT NULL,' .
			'opis varchar(1000) NOT NULL,' .
			'vrijeme datetime NOT NULL,' .
			'obavezno int NOT NULL,' .
			'vrsta int NOT NULL,' .
			'vrijednost int NOT NULL)'
		);

		$st->execute();
	}
	catch(PDOException $e) {
		exit('PDO error [create pr_zadaci]: ' . $e->getMessage());
	}

	echo 'Napravio tablicu pr_zadaci.<br>';
}

function create_table_kategorije()
{
	$db = DB::getConnection();

	if (has_table('pr_kategorije'))
		exit('Tablica pr_kategorije već postoji.<br>');

	try {
		$st = $db->prepare(
			'CREATE TABLE IF NOT EXISTS pr_kategorije (' .
			'ID int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
			'ID_kucanstvo int NOT NULL,' .
			'ime varchar(100) NOT NULL)'
		);

		$st->execute();
	}
	catch(PDOException $e) {
		exit('PDO error [create pr_kategorije]: ' . $e->getMessage());
	}

	echo 'Napravio tablicu pr_kategorije.<br>';
}

function create_table_nagrade()
{
	$db = DB::getConnection();

	if (has_table('pr_nagrade'))
		exit('Tablica pr_nagrade već postoji.<br>');

	try {
		$st = $db->prepare(
			'CREATE TABLE IF NOT EXISTS pr_nagrade (' .
			'ID int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
			'ID_korisnik int NOT NULL,' .
			'opis varchar(300) NOT NULL,' .
			'cijena int NOT NULL)'
		);

		$st->execute();
	}
	catch(PDOException $e) {
		exit('PDO error [create pr_nagrade]: ' . $e->getMessage());
	}

	echo 'Napravio tablicu pr_nagrade.<br>';
}

?>
