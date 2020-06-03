<?php
// Ovdje su ID-jevi korisnika ručno postavljeni pa bi trebalo pokretati
// samo ako su sve tablice prazne.
require_once __DIR__ . '/db.class.php';

seed_table_users();
seed_table_households();
seed_table_chores();
seed_table_categories();
seed_table_rewards();
seed_table_events();

exit(0);

function seed_table_users()
{
	$db = DB::getConnection();

	try {
		$st = $db->prepare('INSERT INTO pr_users' .
			'(ID_household, username, password, email, points, image, event, ' .
			'admin, registration_sequence, registered) VALUES ' .
			'(:ID_household, :username, :password, :email, :points, :image, :event, ' .
			':admin, :registration_sequence, :registered)');

		// Prvo kućanstvo.
		$st->execute(array(
			'ID_household' => 1,
			'username' => 'Mirjana',
			'password' => password_hash('mirjaninalozinka', PASSWORD_DEFAULT),
			'email' => 'mirjana@mail.com',
			'points' => 20,
			'image' => 13,
			'event' => 0,
			'admin' => 1,
			'registration_sequence' => 'abc',
			'registered' => 1));

		$st->execute(array(
			'ID_household' => 1,
			'username' => 'Goran',
			'password' => password_hash('goranovalozinka', PASSWORD_DEFAULT),
			'email' => 'goran@mail.com',
			'points' => 5,
			'image' => 11,
			'event' => 0,
			'admin' => 1,
			'registration_sequence' => 'def',
			'registered' => 1));

		$st->execute(array(
			'ID_household' => 1,
			'username' => 'Josip',
			'password' => password_hash('josipovalozinka', PASSWORD_DEFAULT),
			'email' => 'josip@mail.com',
			'points' => 1000,
			'image' => 9,
			'event' => 0,
			'admin' => 0,
			'registration_sequence' => 'ghi',
			'registered' => 1));

		// Drugo kućanstvo.
		$st->execute(array(
			'ID_household' => 2,
			'username' => 'Filipa',
			'password' => password_hash('filipinalozinka', PASSWORD_DEFAULT),
			'email' => 'filipa@mail.com',
		 	'points' => 250,
			'image' => 2,
			'event' => 0,
			'admin' => 1,
			'registration_sequence' => 'jkl',
			'registered' => 1));

		$st->execute(array(
			'ID_household' => 2,
			'username' => 'Ana',
			'password' => password_hash('aninalozinka', PASSWORD_DEFAULT),
			'email' => 'ana@mail.com',
		 	'points' => 315,
			'image' => 4,
			'event' => 0,
			'admin' => 1,
			'registration_sequence' => 'ghi',
			'registered' => 1));
	}
	catch(PDOException $e) {
		exit("PDO error [insert pr_users]: " . $e->getMessage());
	}

	echo 'Ubacio u tablicu pr_users.<br>';
}

function seed_table_households()
{
	$db = DB::getConnection();

	try {
		$st = $db->prepare('INSERT INTO pr_households (name, password) VALUES (:name, :password)');

		// Prvo kućanstvo.
		$st->execute(array(
			'name' => 'Mirjanina obitelj',
			'password' => password_hash('mirjaninaobitelj', PASSWORD_DEFAULT)));

		// Drugo kućanstvo.
		$st->execute(array(
			'name' => 'Best friends',
			'password' => password_hash('bestfriends', PASSWORD_DEFAULT)));
	}
	catch(PDOException $e) {
		exit("PDO error [insert pr_households]: " . $e->getMessage());
	}

	echo 'Ubacio u tablicu pr_households.<br>';
}

function seed_table_chores()
{
	$db = DB::getConnection();

	try {
		$st = $db->prepare('INSERT INTO pr_chores' .
			'(ID_user, ID_category, description, time_next, mandatory, ' .
			'type, points, done) VALUES ' .
			'(:ID_user, :ID_category, :description, :time_next, :mandatory, ' .
			':type, :points, :done)');

		// Zadaci za korisnike u prvom kućanstvu.
		$st->execute(array(
			'ID_user' => 1,
			'ID_category' => 7,
			'description' => 'Oprati suđe',
			'time_next' => '2020-06-01 20:20:00',
			'mandatory' => 0,
			'type' => 1,
			'points' => 20,
			'done' => 0));

		$st->execute(array(
			'ID_user' => 1,
			'ID_category' => 8,
			'description' => 'Oprati prozore',
			'time_next' => '2020-06-01 15:30:00',
			'mandatory' => 1,
			'type' => 3,
			'points' => 50,
			'done' => 0));

		$st->execute(array(
			'ID_user' => 2,
			'ID_category' => 7,
			'description' => 'Oprati veš',
			'time_next' => '2020-05-31 20:20:00',
			'mandatory' => 1,
			'type' => 1,
			'points' => 20,
			'done' => 0));

		$st->execute(array(
			'ID_user' => 3,
			'ID_category' => 8,
			'description' => 'Oprati prozore',
			'time_next' => '2020-05-27 15:30:00',
			'mandatory' => 1,
			'type' => 3,
			'points' => 50,
			'done' => 0));

		$st->execute(array(
			'ID_user' => 1,
			'ID_category' => 2,
			'description' => 'Okopaj krumpir',
			'time_next' => '2020-06-01 20:20:00',
			'mandatory' => 1,
			'type' => 0,
			'points' => 150,
			'done' => 0));

		$st->execute(array(
			'ID_user' => 1,
			'ID_category' => 5,
			'description' => 'Usisati kuću',
			'time_next' => '2020-05-30 15:30:00',
			'mandatory' => 0,
			'type' => 2,
			'points' => 50,
			'done' => 0));

		$st->execute(array(
			'ID_user' => 2,
			'ID_category' => 7,
			'description' => 'Skuhati ručak',
			'time_next' => '2020-06-01 20:20:00',
			'mandatory' => 1,
			'type' => 1,
			'points' => 200,
			'done' => 0));

		$st->execute(array(
			'ID_user' => 3,
			'ID_category' => 6,
			'description' => 'Riješiti zadaću',
			'time_next' => '2020-05-27 15:30:00',
			'mandatory' => 1,
			'type' => 1,
			'points' => 100,
			'done' => 0));

		$st->execute(array(
			'ID_user' => 1,
			'ID_category' => 5,
			'description' => 'Obrisati prašinu',
			'time_next' => '2020-05-30 20:20:00',
			'mandatory' => 0,
			'type' => 2,
			'points' => 40,
			'done' => 0));

		$st->execute(array(
			'ID_user' => 1,
			'ID_category' => 2,
			'description' => 'Zaliti cvijeće',
			'time_next' => '2020-06-01 11:30:00',
			'mandatory' => 0,
			'type' => 3,
			'points' => 100,
			'done' => 0));

		$st->execute(array(
			'ID_user' => 2,
			'ID_category' => 3,
			'description' => 'Obrisati kupaonicu',
			'time_next' => '2020-06-01 11:20:00',
			'mandatory' => 0,
			'type' => 2,
			'points' => 50,
			'done' => 0));

		$st->execute(array(
			'ID_user' => 3,
			'ID_category' => 6,
			'description' => 'Pročitati lektiru',
			'time_next' => '2020-05-27 15:30:00',
			'mandatory' => 1,
			'type' => 3,
			'points' => 300,
			'done' => 0));

			

		// Zadaci za korisnike u drugom kućanstvu.
		$st->execute(array(
			'ID_user' => 4,
			'ID_category' => 9,
			'description' => 'Izvesti Flokija',
			'time_next' => '2020-06-01 17:15:00',
			'mandatory' => 1,
			'type' => 1,
			'points' => 15,
			'done' => 0));

		$st->execute(array(
			'ID_user' => 4,
			'ID_category' => 10,
			'description' => 'Iznijeti smeće',
			'time_next' => '2020-06-01 16:00:00',
			'mandatory' => 0,
			'type' => 2,
			'points' => 10,
			'done' => 0));

	}
	catch(PDOException $e) {
		exit("PDO error [insert pr_chores]: " . $e->getMessage());
	}

	echo 'Ubacio u tablicu pr_chores.<br>';
}

function seed_table_categories()
{
	$db = DB::getConnection();

	try {
		$st = $db->prepare('INSERT INTO pr_categories' .
			'(ID_household, name) VALUES (:ID_household, :name)');

		// Default kategorije
		$st->execute(array(
			'ID_household' => 0,
			'name' => 'Čišćenje'));

		$st->execute(array(
			'ID_household' => 0,
			'name' => 'Vrt'));	

		$st->execute(array(
			'ID_household' => 0,
			'name' => 'Higijena'));	

		$st->execute(array(
			'ID_household' => 0,
			'name' => 'Zdravlje'));	

		$st->execute(array(
			'ID_household' => 0,
			'name' => 'Održavanje'));	

		$st->execute(array(
			'ID_household' => 0,
			'name' => 'Škola'));	

		// Kategorije prvog kućanstva.
		$st->execute(array(
			'ID_household' => 1,
			'name' => 'Kuhinja'));

		$st->execute(array(
			'ID_household' => 1,
			'name' => 'Čišćenje'));

		// Kategorije prvog kućanstva.
		$st->execute(array(
			'ID_household' => 2,
			'name' => 'Kućni ljubimci'));

		$st->execute(array(
			'ID_household' => 2,
			'name' => 'Čišćenje'));

	}
	catch(PDOException $e) {
		exit("PDO error [insert pr_categories]: " . $e->getMessage());
	}

	echo 'Ubacio u tablicu pr_categories.<br>';
}

function seed_table_rewards()
{
	$db = DB::getConnection();

	try {
		$st = $db->prepare('INSERT INTO pr_rewards' .
			'(ID_user, description, points_price, purchased) VALUES ' .
			'(:ID_user, :description, :points_price, :purchased)');

		$st->execute(array(
			'ID_user' => 3,
			'description' => 'Sladoled',
			'points_price' => '50',
			'purchased' => 0));

		$st->execute(array(
			'ID_user' => 1,
			'description' => 'Odlazak na odmor',
			'points_price' => '10000',
			'purchased' => 0));

		$st->execute(array(
			'ID_user' => 2,
			'description' => 'Odlazak na odmor',
			'points_price' => '10000',
			'purchased' => 0));

		$st->execute(array(
			'ID_user' => 3,
			'description' => 'Odlazak na odmor',
			'points_price' => '10000',
			'purchased' => 0));
	}
	catch(PDOException $e) {
		exit("PDO error [insert pr_rewards]: " . $e->getMessage());
	}

	echo 'Ubacio u tablicu pr_rewards.<br>';
}

function seed_table_events()
{
	$db = DB::getConnection();

	try {
		$st = $db->prepare('INSERT INTO pr_events' .
			'(ID_user, ID_household, description, time_set) VALUES ' .
			'(:ID_user, :ID_household, :description, :time_set)');

		$st->execute(array(
			'ID_user' => 3,
			'ID_household' => 1,
			'description' => 'Kupljena nagrada - Sladoled',
			'time_set' => '2020-06-01 16:00:00'));

		$st->execute(array(
			'ID_user' => 3,
			'ID_household' => 1,
			'description' => 'Novi zadatak - Riješi zadaću (Mirjana)',
			'time_set' => '2020-06-09 8:00:00'));

		$st->execute(array(
			'ID_user' => 0,
			'ID_household' => 1,
			'description' => 'Novi zadatak - Iznijeti smeće (Mirjana)',
			'time_set' => '2020-06-01 12:00:00'));
	}
	catch(PDOException $e) {
		exit("PDO error [insert pr_events]: " . $e->getMessage());
	}

	echo 'Ubacio u tablicu pr_rewards.<br>';
}


?>
