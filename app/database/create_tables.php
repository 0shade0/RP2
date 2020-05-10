<?php
require_once __DIR__ . '/db.class.php';

create_table_users();
create_table_households();
create_table_chores();
create_table_categories();
create_table_rewards();

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
function create_table_users()
{
	$db = DB::getConnection();

	if (has_table('pr_users'))
		exit('Tablica pr_users već postoji.<br>');

	try {
		$st = $db->prepare(
			'CREATE TABLE IF NOT EXISTS pr_users (' .
			'ID int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
			'ID_household int NOT NULL,' .
			'username varchar(50) NOT NULL,' .
			'password varchar(255) NOT NULL,'.
			'email varchar(50) NOT NULL,' .
			'points int NOT NULL,' .
			'admin int NOT NULL,' .
			'registration_sequence varchar(20) NOT NULL,' .
			'registered int NOT NULL)'
		);

		$st->execute();
	}
	catch(PDOException $e) {
		exit('PDO error [create pr_users]: ' . $e->getMessage());
	}

	echo 'Napravio tablicu pr_users.<br>';
}

function create_table_households()
{
	$db = DB::getConnection();

	if (has_table('pr_households'))
		exit('Tablica pr_households već postoji.<br>');

	try {
		$st = $db->prepare(
			'CREATE TABLE IF NOT EXISTS pr_households (' .
			'ID int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
			'name varchar(100) NOT NULL)'
		);

		$st->execute();
	}
	catch(PDOException $e) {
		exit('PDO error [create pr_households]: ' . $e->getMessage());
	}

	echo 'Napravio tablicu pr_households.<br>';
}


function create_table_chores()
{
	$db = DB::getConnection();

	if (has_table('pr_chores'))
		exit('Tablica pr_chores već postoji.<br>');

	try {
		$st = $db->prepare(
			'CREATE TABLE IF NOT EXISTS pr_chores (' .
			'ID int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
			'ID_user int NOT NULL,' .
			'ID_category int NOT NULL,' .
			'description varchar(1000) NOT NULL,' .
			'time_next datetime NOT NULL,' .
			'mandatory int NOT NULL,' .
			'type int NOT NULL,' .
			'points int NOT NULL,' .
			'done int NOT NULL)'
		);

		$st->execute();
	}
	catch(PDOException $e) {
		exit('PDO error [create pr_chores]: ' . $e->getMessage());
	}

	echo 'Napravio tablicu pr_chores.<br>';
}

function create_table_categories()
{
	$db = DB::getConnection();

	if (has_table('pr_categories'))
		exit('Tablica pr_categories već postoji.<br>');

	try {
		$st = $db->prepare(
			'CREATE TABLE IF NOT EXISTS pr_categories (' .
			'ID int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
			'ID_household int NOT NULL,' .
			'name varchar(100) NOT NULL)'
		);

		$st->execute();
	}
	catch(PDOException $e) {
		exit('PDO error [create pr_categories]: ' . $e->getMessage());
	}

	echo 'Napravio tablicu pr_categories.<br>';
}

function create_table_rewards()
{
	$db = DB::getConnection();

	if (has_table('pr_rewards'))
		exit('Tablica pr_rewards već postoji.<br>');

	try {
		$st = $db->prepare(
			'CREATE TABLE IF NOT EXISTS pr_rewards (' .
			'ID int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
			'ID_user int NOT NULL,' .
			'description varchar(300) NOT NULL,' .
			'points_price int NOT NULL, ' .
			'purchased int NOT NULL)'
		);

		$st->execute();
	}
	catch(PDOException $e) {
		exit('PDO error [create pr_rewards]: ' . $e->getMessage());
	}

	echo 'Napravio tablicu pr_rewards.<br>';
}

?>
