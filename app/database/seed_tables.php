<?php
// Ovdje su ID-jevi korisnika ručno postavljeni pa bi trebalo pokretati
// samo ako su sve tablice prazne.
require_once __DIR__ . '/db.class.php';

seed_table_korisnici();
seed_table_kucanstva();
seed_table_zadaci();
seed_table_kategorije();
seed_table_nagrade();

exit(0);

function seed_table_korisnici()
{
	$db = DB::getConnection();

	try {
		$st = $db->prepare('INSERT INTO pr_korisnici' .
			'(ID_kucanstvo, username, password_hash, email, bodovi, ' .
			'admin, registracijski_niz, registriran) VALUES ' .
			'(:ID_kucanstvo, :username, :password_hash, :email, :bodovi, ' .
			':admin, :registracijski_niz, :registriran)');

		// Prvo kućanstvo.
		$st->execute(array(
			'ID_kucanstvo' => 1,
			'username' => 'Mirjana',
			'password_hash' => password_hash('mirjaninalozinka', PASSWORD_DEFAULT),
			'email' => 'mirjana@mail.com',
		 	'bodovi' => 20,
			'admin' => 1,
			'registracijski_niz' => 'abc',
			'registriran' => 1));

		$st->execute(array(
			'ID_kucanstvo' => 1,
			'username' => 'Goran',
			'password_hash' => password_hash('goranovalozinka', PASSWORD_DEFAULT),
			'email' => 'goran@mail.com',
		 	'bodovi' => 5,
			'admin' => 1,
			'registracijski_niz' => 'def',
			'registriran' => 1));

		$st->execute(array(
			'ID_kucanstvo' => 1,
			'username' => 'Josip',
			'password_hash' => password_hash('josipovalozinka', PASSWORD_DEFAULT),
			'email' => 'josip@mail.com',
		 	'bodovi' => 1000,
			'admin' => 0,
			'registracijski_niz' => 'ghi',
			'registriran' => 1));

		// Drugo kućanstvo.
		$st->execute(array(
			'ID_kucanstvo' => 2,
			'username' => 'Filipa',
			'password_hash' => password_hash('filipinalozinka', PASSWORD_DEFAULT),
			'email' => 'filipa@mail.com',
		 	'bodovi' => 250,
			'admin' => 1,
			'registracijski_niz' => 'jkl',
			'registriran' => 1));

		$st->execute(array(
			'ID_kucanstvo' => 2,
			'username' => 'Ana',
			'password_hash' => password_hash('aninalozinka', PASSWORD_DEFAULT),
			'email' => 'ana@mail.com',
		 	'bodovi' => 315,
			'admin' => 1,
			'registracijski_niz' => 'ghi',
			'registriran' => 1));
	}
	catch(PDOException $e) {
		exit("PDO error [insert pr_korisnici]: " . $e->getMessage());
	}

	echo 'Ubacio u tablicu pr_korisnici.<br>';
}

function seed_table_kucanstva()
{
	$db = DB::getConnection();

	try {
		$st = $db->prepare('INSERT INTO pr_kucanstva (ime) VALUES (:ime)');

		// Prvo kućanstvo.
		$st->execute(array('ime' => 'Mirjanina obitelj'));

		// Drugo kućanstvo.
		$st->execute(array('ime' => 'Best friends'));
	}
	catch(PDOException $e) {
		exit("PDO error [insert pr_kucanstva]: " . $e->getMessage());
	}

	echo 'Ubacio u tablicu pr_kucanstva.<br>';
}

function seed_table_zadaci()
{
	$db = DB::getConnection();

	try {
		$st = $db->prepare('INSERT INTO pr_zadaci' .
			'(ID_korisnik, ID_kategorija, opis, vrijeme, obavezno, ' .
			'vrsta, vrijednost) VALUES ' .
			'(:ID_korisnik, :ID_kategorija, :opis, :vrijeme, :obavezno, ' .
			':vrsta, :vrijednost)');

		// Zadaci za korisnike u prvom kućanstvu.
		$st->execute(array(
			'ID_korisnik' => 1,
			'ID_kategorija' => 1,
			'opis' => 'Oprati suđe',
			'vrijeme' => '2020-05-07 20:20:00',
			'obavezno' => 0,
			'vrsta' => 1,
			'vrijednost' => 20));

		$st->execute(array(
			'ID_korisnik' => 2,
			'ID_kategorija' => 1,
			'opis' => 'Oprati suđe',
			'vrijeme' => '2020-05-07 20:20:00',
			'obavezno' => 0,
			'vrsta' => 1,
			'vrijednost' => 20));

		$st->execute(array(
			'ID_korisnik' => 3,
			'ID_kategorija' => 2,
			'opis' => 'Oprati prozore',
			'vrijeme' => '2020-04-07 15:30:00',
			'obavezno' => 1,
			'vrsta' => 3,
			'vrijednost' => 50));

		// Zadaci za korisnike u drugom kućanstvu.
		$st->execute(array(
			'ID_korisnik' => 4,
			'ID_kategorija' => 1,
			'opis' => 'Izvesti Flokija',
			'vrijeme' => '2020-05-02 17:15:00',
			'obavezno' => 1,
			'vrsta' => 1,
			'vrijednost' => 15));

		$st->execute(array(
			'ID_korisnik' => 4,
			'ID_kategorija' => 2,
			'opis' => 'Iznijeti smeće',
			'vrijeme' => '2020-05-06 16:00:00',
			'obavezno' => 0,
			'vrsta' => 2,
			'vrijednost' => 10));

		$st->execute(array(
			'ID_korisnik' => 5,
			'ID_kategorija' => 2,
			'opis' => 'Iznijeti smeće',
			'vrijeme' => '2020-05-06 16:00:00',
			'obavezno' => 0,
			'vrsta' => 2,
			'vrijednost' => 10));

	}
	catch(PDOException $e) {
		exit("PDO error [insert pr_zadaci]: " . $e->getMessage());
	}

	echo 'Ubacio u tablicu pr_zadaci.<br>';
}

function seed_table_kategorije()
{
	$db = DB::getConnection();

	try {
		$st = $db->prepare('INSERT INTO pr_kategorije' .
			'(ID_kucanstvo, ime) VALUES (:ID_kucanstvo, :ime)');

		// Kategorije prvog kućanstva.
		$st->execute(array(
			'ID_kucanstvo' => 1,
			'ime' => 'Kuhinja'));

		$st->execute(array(
			'ID_kucanstvo' => 1,
			'ime' => 'Čišćenje'));

		// Kategorije prvog kućanstva.
		$st->execute(array(
			'ID_kucanstvo' => 2,
			'ime' => 'Kućni ljubimci'));

		$st->execute(array(
			'ID_kucanstvo' => 2,
			'ime' => 'Čišćenje'));

	}
	catch(PDOException $e) {
		exit("PDO error [insert pr_kategorije]: " . $e->getMessage());
	}

	echo 'Ubacio u tablicu pr_kategorije.<br>';
}

function seed_table_nagrade()
{
	$db = DB::getConnection();

	try {
		$st = $db->prepare('INSERT INTO pr_nagrade' .
			'(ID_korisnik, opis, cijena) VALUES (:ID_korisnik, :opis, :cijena)');

		$st->execute(array(
			'ID_korisnik' => 3,
			'opis' => 'Sladoled',
			'cijena' => '50'));

		$st->execute(array(
			'ID_korisnik' => 1,
			'opis' => 'Odlazak na odmor',
			'cijena' => '10000'));

		$st->execute(array(
			'ID_korisnik' => 2,
			'opis' => 'Odlazak na odmor',
			'cijena' => '10000'));

		$st->execute(array(
			'ID_korisnik' => 3,
			'opis' => 'Odlazak na odmor',
			'cijena' => '10000'));
	}
	catch(PDOException $e) {
		exit("PDO error [insert pr_nagrade]: " . $e->getMessage());
	}

	echo 'Ubacio u tablicu pr_nagrade.<br>';
}

?>
