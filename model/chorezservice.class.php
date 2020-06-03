<?php
require_once platformSlashes($dir . '/app/database/db.class.php');
require_once platformSlashes($dir . '/model/user.class.php');
require_once platformSlashes($dir . '/model/household.class.php');
require_once platformSlashes($dir . '/model/chore.class.php');
require_once platformSlashes($dir . '/model/category.class.php');
require_once platformSlashes($dir . '/model/reward.class.php');
require_once platformSlashes($dir . '/model/event.class.php');

class ChorezService {
//--------------------------------------------------------------------------
//  Funkcije za dohvaćanje iz tablice korisnika
//--------------------------------------------------------------------------

// Dohvaćanje korisnika preko ID-ja.
public function getUserByID($userID) {
    $db = DB::getConnection();

    $st = $db->prepare('SELECT * FROM pr_users  WHERE ID=:userID');
    $st->execute(array('userID' => $userID));

    if ($r = $st->fetch()) {
        $user = new User (
            $r['ID'], $r['ID_household'], $r['username'],
            $r['password'], $r['email'], $r['points'], $r['image'], $r['event'], $r['admin'],
            $r['registration_sequence'], $r['registered']);

        return $user;
    }
}

// Dohvaćanje korisnika preko korisničkog imena.
public function getUserByUsername($username) {
    $db = DB::getConnection();

    $st = $db->prepare('SELECT * FROM pr_users  WHERE username=:username');
    $st->execute(array('username' => $username));

    if ($r = $st->fetch()) {
        $user = new User (
            $r['ID'], $r['ID_household'], $r['username'],
            $r['password'], $r['email'], $r['points'], $r['image'], $r['event'], $r['admin'],
            $r['registration_sequence'], $r['registered']);

        return $user;
    }
}

// Dohvaćanje korisnika preko e-mail adrese.
public function getUserByEmail($email) {
    $db = DB::getConnection();

    try {
    $st = $db->prepare('SELECT * FROM pr_users WHERE email=:email');
    $st->execute(array('email' => $email));

    if ($r = $st->fetch()) {
        $user = new User (
            $r['ID'], $r['ID_household'], $r['username'],
            $r['password'], $r['email'], $r['points'], $r['image'], $r['event'], $r['admin'],
            $r['registration_sequence'], $r['registered']);

        return $user;
    }
    }
    catch(PDOException $e) {
        exit('PDO error [select pr_users]: ' . $e->getMessage());
    }
}

// Dodavanje novog korisnika u bazu podataka.
public function addNewUser($user) {
    $db = DB::getConnection();

    try {
    $st = $db->prepare(
        'INSERT INTO pr_users(ID_household, username, password, ' .
        'email, points, image, event, admin, registration_sequence, registered) VALUES ' .
        '(:ID_household, :username, :password, :email, :points, :image, :event, ' .
        ':admin, :registration_sequence, :registered)');

    $st->execute(array(
        'ID_household' => $user->ID_household,
        'username' => $user->username,
        'password' => $user->password,
        'email' => $user->email,
        'points' => $user->points,
        'image' => $user->image,
        'event' => $user->event,
        'admin' => $user->admin,
        'registration_sequence' => $user->registration_sequence,
        'registered' => $user->registered));

    // Vrati ID dodanog korisnika.
    return $db->lastInsertId();
    }
    catch(PDOException $e) {
        exit('PDO error [insert pr_users]: ' . $e->getMessage());
    }
}

// Postavljanje vrijednosti u stupac registered korisniku sa zadanim ID-jem.
public function set_registered ($userID, $value) {
    $db = DB::getConnection();

    try {
    $st = $db->prepare('UPDATE pr_users SET registered = :value ' .
        'WHERE ID=:userID');

    $st->execute(array('value' => $value, 'userID' => $userID));
    }
    catch(PDOException $e) {
        exit('PDO error [update pr_users]: ' . $e->getMessage());
    }
}

public function setUserAdmin ($userID) {
    $db = DB::getConnection();

    try {
    $st = $db->prepare('UPDATE pr_users SET admin = 1 ' .
        'WHERE ID=:userID');

    $st->execute(array('userID' => $userID));
    }
    catch(PDOException $e) {
        exit('PDO error [update pr_users]: ' . $e->getMessage());
    }
}

public function changeUserAdmin ($userID) {
    $db = DB::getConnection();

    try {
    $st = $db->prepare('UPDATE pr_users SET admin = 1-admin ' .
        'WHERE ID=:userID');

    $st->execute(array('userID' => $userID));

    $user = $this->getUserByID($userID);

    return $user->admin;
    }
    catch(PDOException $e) {
        exit('PDO error [update pr_users]: ' . $e->getMessage());
    }
}

public function addUserToHousehold($user, $household) {
    $db = DB::getConnection();

    try {
    $st = $db->prepare('UPDATE pr_users SET ID_household = :householdID ' .
        'WHERE ID=:userID');

    $st->execute(array(
        'householdID' => $household->ID,
        'userID' => $user->ID));
    }
    catch(PDOException $e) {
        exit('PDO error [update pr_households]: ' . $e->getMessage());
    }
}

public function setUserImage($userID, $value) {
    $db = DB::getConnection();

    try {
        $st = $db->prepare('UPDATE pr_users SET image = :value ' .
            'WHERE ID=:userID');
    
        $st->execute(array('value' => $value, 'userID' => $userID));
        }
        catch(PDOException $e) {
            exit('PDO error [update pr_users]: ' . $e->getMessage());
    }
}

public function getUsersByHousehold($household_ID) {
    $db = DB::getConnection();

    try {
        $st = $db->prepare(
            'SELECT * FROM pr_users  WHERE ID_household=:householdID
            ORDER BY admin DESC');
        $st->execute(array('householdID' => $household_ID));

        // Bit će popunjen sa svim korisnicima iz kućanstva household_ID
        $users = array();

        while ($r = $st->fetch()) {
            array_push($users, new User (
                $r['ID'], $r['ID_household'], $r['username'],
                $r['password'], $r['email'], $r['points'], $r['image'], $r['event'], $r['admin'],
                $r['registration_sequence'], $r['registered']));

        }

        // Vrati array sa svim nagradama korisnika userID.
        // sortiran uzlazno po cijeni u bodovima
        return $users;
    }
    catch(PDOException $e) {
        exit('PDO error [select pr_users]: ' . $e->getMessage());
    }
}

public function getFirstIDInHousehold($ID_household) {
    $db = DB::getConnection();

    try {
    $st = $db->prepare('SELECT ID FROM pr_users WHERE ID_household=:householdID');
    $st->execute(array('householdID' => $ID_household));

    return $st->fetch();

    }
    catch(PDOException $e) {
        exit('PDO error [select pr_users]: ' . $e->getMessage());
    }
}

public function giveUserPoints($ID, $points) {
    $db = DB::getConnection();

    $user = $this->getUserByID($ID);

    try {
        $st = $db->prepare('UPDATE pr_users SET points = :points ' .
            'WHERE ID=:userID');
    
        $st->execute(array('points' => $user->points + $points, 'userID' => $ID));
        }
        catch(PDOException $e) {
            exit('PDO error [update pr_users]: ' . $e->getMessage());
    }
}

public function setEventsSeen($ID) {
    $db = DB::getConnection();

    try {
        $st = $db->prepare('UPDATE pr_users SET event = 0 ' .
            'WHERE ID=:userID');
    
        $st->execute(array('userID' => $ID));
        }
        catch(PDOException $e) {
            exit('PDO error [update pr_users]: ' . $e->getMessage());
    }
}

public function setEventsUnseen($ID) {
    $db = DB::getConnection();

    try {
        $st = $db->prepare('UPDATE pr_users SET event = 1 ' .
            'WHERE ID=:userID');
    
        $st->execute(array('userID' => $ID));
        }
        catch(PDOException $e) {
            exit('PDO error [update pr_users]: ' . $e->getMessage());
    }
}

public function deleteUser($ID) {
    $db = DB::getConnection();
    try {
        $st = $db->prepare(
            'DELETE FROM pr_users WHERE ID=:ID');
        $st->execute(array(
            'ID' => $ID ));

        $st = $db->prepare(
            'DELETE FROM pr_chores WHERE ID_user=:ID');
        $st->execute(array(
            'ID' => $ID ));

        $st = $db->prepare(
            'DELETE FROM pr_events WHERE ID_user=:ID');
        $st->execute(array(
            'ID' => $ID ));

        $st = $db->prepare(
            'DELETE FROM pr_rewards WHERE ID_user=:ID');
        $st->execute(array(
            'ID' => $ID ));
    }
    catch(PDOException $e) {
        exit('PDO error [delete pr_users]: ' . $e->getMessage());
    }
}

//--------------------------------------------------------------------------
//  Funkcije za dohvaćanje iz tablice kućanstava
//--------------------------------------------------------------------------
public function getHouseholdByID($householdID) {
    $db = DB::getConnection();

    $st = $db->prepare('SELECT * FROM pr_households  WHERE ID=:householdID');
    $st->execute(array('householdID' => $householdID));

    if ($r = $st->fetch()) {
        $household = new Household (
            $r['ID'], $r['name'], $r['password']);

        return $household;
    }
}

public function addNewHousehold($name, $password) {
    $db = DB::getConnection();

    try {
        $st = $db->prepare('INSERT INTO pr_households(name, password) VALUES (:name, :password)');

        $st->execute(array('name' => $name, 'password' => $password));

    // Vrati ID dodanog kućanstva.
    return $db->lastInsertId();
    }
    catch(PDOException $e) {
        exit('PDO error [insert pr_households]: ' . $e->getMessage());
    }
}

public function setHouseholdUnseen($ID_household) {
    $db = DB::getConnection();

    try {
        $st = $db->prepare('UPDATE pr_users SET event = 1 ' .
            'WHERE ID_household=:householdID');
    
        $st->execute(array('householdID' => $ID_household));
        }
        catch(PDOException $e) {
            exit('PDO error [update pr_users]: ' . $e->getMessage());
    }
}

// Brisanje kućanstva ako više nema članova
public function deleteHousehold($ID) {
    $db = DB::getConnection();

    try {
        $st = $db->prepare(
            'DELETE FROM pr_households WHERE ID=:ID');
        $st->execute(array(
            'ID' => $ID ));

        $st = $db->prepare(
            'DELETE FROM pr_categories WHERE ID_household=:ID');
        $st->execute(array(
            'ID' => $ID ));

        $st = $db->prepare(
            'DELETE FROM pr_events WHERE ID_household=:ID');
        $st->execute(array(
            'ID' => $ID ));
    }
    catch(PDOException $e) {
        exit('PDO error [delete pr_users]: ' . $e->getMessage());
    }
}

//--------------------------------------------------------------------------
//  Funkcije za dohvaćanje iz tablice zadataka
//--------------------------------------------------------------------------

// Stavlja jednokratni zadatak u stanje "done", a ako se ponavlja stavlja ga na idući period.
public function setCompleted ($chore) {
    $db = DB::getConnection();

    $time_next = $chore->time_next;

    // Ako je zadatak riješen, gotovo
    if($chore->done) return;

    // Provjeri je li zadatak riješiv tj. je li mu vrijeme
    $now = new DateTime();
    $date = new DateTime($time_next);

    $interval = $now->diff($date);

    if($interval->invert !== 1) return;

    // Ako je zadatak ponavljajući, onda će postati aktivan od ponoći.
    $today = date("Y-m-d") . " 00:00:00";

    if ($chore->type == 1) { // Dnevni zadatak.
        // time vraća vrijeme u sekundama, dan ima 24*60*60 sekundi.
        $time_next = date("Y-m-d", time() + 24*60*60) . " 00:00:00"; 
    } else if ($chore->type == 2) {
        $time_next = date("Y-m-d", time() + 7*24*60*60) . " 00:00:00";

    // Mijenjam mjesec u idući mjesec.
    } else if ($chore->type == 3) {
        $time_next = date("Y-m-d", time() + 30*24*60*60) . " 00:00:00";
    
    } else if ($chore->type == 4) {
        $time_next = date("Y-m-d", time() + 365*24*60*60) . " 00:00:00";
    }

    try {
        $st = $db->prepare('UPDATE pr_chores SET time_next = :time_next, done = :done ' .
            'WHERE ID=:choreID');

        // Stupac done će biti "1" za zadatke koji se ne ponavljaju, inače "0".
        $st->execute(array('time_next' => $time_next, 'done' => intval(! $chore->type),
            'choreID' => $chore->ID));

        // Ako dođe do ovdje tablica je uspješno promijenjena.
        $chore->time_next = $time_next;
        $chore->done = intval(! $chore->type);

        $this->giveUserPoints($chore->ID_user, $chore->points);

    }
    catch(PDOException $e) {
        exit('PDO error [update pr_chores]: ' . $e->getMessage());
    }
}

// Stavlja jednokratni zadatak u stanje "done", a ako se ponavlja stavlja ga na idući period.
public function setCompletedAlt ($chore) {
    $db = DB::getConnection();

    $time_next = $chore->time_next;

    // Ako je zadatak ponavljajući, onda će postati aktivan od ponoći.
    $today = date("Y-m-d") . " 00:00:00";

    if ($chore->type == 1) { // Dnevni zadatak.
        // time vraća vrijeme u sekundama, dan ima 24*60*60 sekundi.
        $time_next = date("Y-m-d", time() + 24*60*60) . " 00:00:00"; 
    } else if ($chore->type == 2) {
        $time_next = date("Y-m-d", time() + 7*24*60*60) . " 00:00:00";

    // Mijenjam mjesec u idući mjesec.
    } else if ($chore->type == 3) {
        $month = substr($time_next, 5, 2);
        $year = substr($time_next, 0, 4);

        if ($month === "09") $month = "10";
        else if ($month === "12") {
            $month = "00";
            $year = strval($year + 1);
        } else {
            $month[1] = strval($month[1] + 1);
        }

        $time_next = $year . "-" . $month . "-01 00:00:00";
    
    } else if ($chore->type == 4) {
        $year = substr($time_next, 0, 4);

        $year = strval($year + 1);

        $time_next = $year . substr($time_next, 4);
    }

    try {
    $st = $db->prepare('UPDATE pr_chores SET time_next = :time_next, done = :done' .
        'WHERE ID=:choreID');

    // Stupac done će biti "1" za zadatke koji se ne ponavljaju, inače "0".
    $st->execute(array('time_next' => $time_next, "done" => intval(! $chore->type),
         'choreID' => $chore->ID));

    // Ako dođe do ovdje tablica je uspješno promijenjena.
    $chore->time_next = $time_next;
    $chore->done = intval(! $chore->type);
    
    }
    catch(PDOException $e) {
        exit('PDO error [update pr_chores]: ' . $e->getMessage());
    }
}

public function getChoreByID($choreID) {
    $db = DB::getConnection();

    try {
        $st = $db->prepare(
            'SELECT * FROM pr_chores  WHERE ID=:choreID');
        $st->execute(array('choreID' => $choreID));

        if ($row = $st->fetch()) {
            $chore = Chore::fromRow($row);
    
            return $chore;
        }
        
    }
    catch(PDOException $e) {
        exit('PDO error [select pr_chores]: ' . $e->getMessage());
    }
}

// dohvaća zadatke koji tek trebaju doći /osobne i od kućanstva
public function getFutureChoresByUser($ID_user, $ID_household) {
    $db = DB::getConnection();

    try {
        $chores = array();

        $st = $db->prepare(
            'SELECT * FROM pr_chores ' .
            'WHERE ID_user=:userID AND time_next > CURRENT_TIMESTAMP() ' .
            'AND mandatory=1 ORDER BY time_next ASC');
        $st->execute(array('userID' => $ID_user));

        while ($row = $st->fetch())
            array_push($chores, Chore::fromRow($row));

        $st = $db->prepare(
            'SELECT pr_chores.* FROM pr_users JOIN pr_chores ' .
            'WHERE ID_user = pr_users.ID AND ID_household=:householdID ' .
            'AND time_next > CURRENT_TIMESTAMP() AND mandatory=0 ORDER BY time_next ASC');
        $st->execute(array('householdID' => $ID_household));

        while ($row = $st->fetch())
            array_push($chores, Chore::fromRow($row));

        return $chores;
        
    }
    catch(PDOException $e) {
        exit('PDO error [select pr_chores]: ' . $e->getMessage());
    }
}

// dohvaća zadatke čiji je datum prošao (dakle sada trebaju biti vidljivi)
public function getChoresByUser($ID_user) {
    $db = DB::getConnection();

    try {
        $st = $db->prepare(
            'SELECT * FROM pr_chores ' .
            'WHERE ID_user=:userID AND time_next < CURRENT_TIMESTAMP() ' .
            'AND mandatory=1 ORDER BY time_next DESC');
        $st->execute(array('userID' => $ID_user));

        $chores = array();

        while ($row = $st->fetch())
            array_push($chores, Chore::fromRow($row));

        return $chores;
        
    }
    catch(PDOException $e) {
        exit('PDO error [select pr_chores]: ' . $e->getMessage());
    }
}

// Dohvaća zadatake sa kategorijom ID_category - u svrhu brisanje kategorija
public function getChoresByCategory($category) {
    $db = DB::getConnection();

    try {
        $st = $db->prepare(
            'SELECT * FROM pr_chores ' .
            'WHERE ID_category=:categoryID ');

        $st->execute(array('categoryID' => $category->ID));

        $chores = array();

        while ($row = $st->fetch())
            array_push($chores,  Chore::fromRow($row));

        return $chores;
        
    }
    catch(PDOException $e) {
        exit('PDO error [select pr_chores]: ' . $e->getMessage());
    }
}

// Dohvaća samo mandatory:0 zadatke iz kućanstva
public function getChoresByHousehold($ID_household) {
    $db = DB::getConnection();

    try {
        $st = $db->prepare(
            'SELECT pr_chores.* FROM pr_users JOIN pr_chores ' . 
            'WHERE ID_user = pr_users.ID AND ID_household=:householdID ' .
            'AND time_next < CURRENT_TIMESTAMP() AND mandatory=0 ORDER BY time_next DESC');
        $st->execute(array('householdID' => $ID_household));

        $chores = array();

        while ($row = $st->fetch())
            array_push($chores, Chore::fromRow($row));

        return $chores;
        
    }
    catch(PDOException $e) {
        exit('PDO error [select pr_chores]: ' . $e->getMessage());
    }
}

public function addNewChore($chore) {
    $db = DB::getConnection();

    try {
        $st = $db->prepare("INSERT INTO pr_chores(" .
            "ID_user, ID_category, description, time_next, mandatory, type, points, done)" .
            "VALUES (:ID_user, :ID_category, :description, :time_next, :mandatory, :type," .
            ":points, :done)");

        $st->execute(array('ID_user' => $chore->ID_user, "ID_category" => $chore->ID_category,
            "description" => $chore->description, "time_next" => $chore->time_next,
            "mandatory" => $chore->mandatory, "type" => $chore->type, 
            "points" => $chore->points, "done" => $chore->done));

        // Vrati ID dodanog zadatka.
        return $db->lastInsertId();
    }
    catch(PDOException $e) {
        exit('PDO error [insert pr_chores]: ' . $e->getMessage());
    }
}

public function deleteChore($chore) {
    $db = DB::getConnection();
    try {
        $st = $db->prepare(
            'DELETE FROM pr_chores WHERE ID=:ID');

        $st->execute(array(
            'ID' => $chore->ID ));
    }
    catch(PDOException $e) {
        exit('PDO error [delete pr_chore]: ' . $e->getMessage());
    }
}

//--------------------------------------------------------------------------
//  Funkcije za dohvaćanje iz tablice kategorija
//--------------------------------------------------------------------------

// Vraća array defaultnih kategorija, i kategorija koje koristi neko kućanstvo ako je kućanstvo
// prosljeđeno funkciji.
public function getAllCategories($ID_household = NULL) {
    $db = DB::getConnection();
    
    $ret = array();
    
    if ($ID_household !== NULL) {
        try {
            $st = $db->prepare('SELECT * FROM pr_categories WHERE ID_household=:ID_household OR ID_household="0"');
        
            $st->execute(array("ID_household" => $ID_household));
    
            while ($row = $st->fetch()) {
                array_push($ret, new Category ($row['ID'], $row['ID_household'], $row['name']));
            }
    
        } catch(PDOException $e) {
            exit('PDO error [select pr_categories]: ' . $e->getMessage());
        }
    }

    // Vrati array s imenima kategorija.
    return $ret;
}

public function getCategoryByID($categoryID) {
    $db = DB::getConnection();

    try {
        $st = $db->prepare(
            'SELECT * FROM pr_categories  WHERE ID=:categoryID');
        $st->execute(array('categoryID' => $categoryID));

        if ($row = $st->fetch()) {
            $category = Category::fromRow($row);
    
            return $category;
        }
        
    }
    catch(PDOException $e) {
        exit('PDO error [select pr_categories]: ' . $e->getMessage());
    }
}

public function getCategoryByName($name) {
    $db = DB::getConnection();

    try {
        $st = $db->prepare(
            'SELECT * FROM pr_categories  WHERE name=:name');
        $st->execute(array('name' => $name));

        if ($row = $st->fetch()) {
            $category = Category::fromRow($row);
    
            return $category;
        }
        
    }
    catch(PDOException $e) {
        exit('PDO error [select pr_categories]: ' . $e->getMessage());
    }
}

public function addNewCategory($household_ID, $name) {
    $db = DB::getConnection();

    try {
    $st = $db->prepare('INSERT INTO pr_categories (ID_household, name) 
        VALUES (:ID_household, :categoryname)');

    $st->execute(array("ID_household" => $household_ID, 'categoryname' => $name));

    // Vrati ID dodane kategorije.
    return $db->lastInsertId();
    }
    catch(PDOException $e) {
        exit('PDO error [insert pr_categories]: ' . $e->getMessage());
    }
}

public function deleteCategory($category) {
    $db = DB::getConnection();
    try {
        $st = $db->prepare(
            'DELETE FROM pr_categories WHERE ID=:ID');

        $st->execute(array(
            'ID' => $category->ID ));
    }
    catch(PDOException $e) {
        exit('PDO error [delete pr_categories]: ' . $e->getMessage());
    }
}

//--------------------------------------------------------------------------
//  Funkcije za dohvaćanje iz tablice nagrade
//--------------------------------------------------------------------------
public function getRewardsByID($userID) {
    $db = DB::getConnection();

    try {
        $st = $db->prepare(
            'SELECT * FROM pr_rewards  WHERE ID_user=:userID
            ORDER BY points_price ASC');
        $st->execute(array('userID' => $userID));

        // Bit će popunjen sa svim podacima nagrada korisnika userID
        // koje nisu kupljene.
        $reward = array();

        while ($r = $st->fetch()) {
            array_push($reward, new Reward (
                $r['ID'], $r['ID_user'], $r['description'],
                $r['points_price'], $r['purchased']));

        }

        // Vrati array sa svim nagradama korisnika userID.
        // sortiran uzlazno po cijeni u bodovima
        return $reward;
    }
    catch(PDOException $e) {
        exit('PDO error [select pr_rewards]: ' . $e->getMessage());
    }
}

public function getRewardByID($userID, $rewardID) {
    $db = DB::getConnection();

    try {
        $st = $db->prepare(
            'SELECT * FROM pr_rewards  WHERE ID_user=:userID
            AND purchased=:purchased AND ID=:rewardID');

        $st->execute(array(
            'userID' => $userID, 'rewardID' => $rewardID, 'purchased' => '0'));

        if ($r = $st->fetch()) {
            $reward = new Reward (
                $r['ID'], $r['ID_user'], $r['description'],
                $r['points_price'], $r['purchased']);
                return $reward;
        }
    }
    catch(PDOException $e) {
        exit('PDO error [select pr_rewards]: ' . $e->getMessage());
    }
}

public function addNewReward($reward) {
    $db = DB::getConnection();

    try {
    $st = $db->prepare(
        'INSERT INTO pr_rewards(ID_user, description, points_price, ' .
        'purchased) VALUES (:ID_user, :description, :points_price, ' .
        ':purchased)');

    $st->execute(array(
        'ID_user' => $reward->ID_user,
        'description' => $reward->description,
        'points_price' => $reward->points_price,
        'purchased' => $reward->purchased ));

    // Vrati ID dodane nagrade.
    return $db->lastInsertId();
    }
    catch(PDOException $e) {
        exit('PDO error [insert pr_rewards]: ' . $e->getMessage());
    }
}

public function deleteRewardByID($ID_reward) {
    $db = DB::getConnection();
    try {
        $st = $db->prepare(
            'DELETE FROM pr_rewards WHERE ID=:ID');

        $st->execute(array(
            'ID' => $ID_reward ));
    }
    catch(PDOException $e) {
        exit('PDO error [delete pr_rewards]: ' . $e->getMessage());
    }
}

public function buyReward($ID_user, $ID_reward, $points, $price) {
    $db = DB::getConnection();
    try {
        $st = $db->prepare(
            'UPDATE pr_rewards SET purchased = :purchased ' .
            'WHERE ID=:rewardID');

        $st->execute(array(
            'purchased' => 1,
            'rewardID' => $ID_reward ));

        $st = $db->prepare(
            'UPDATE pr_users SET points = :points ' .
            'WHERE ID=:userID');

        $st->execute(array(
            'points' => $points - $price,
            'userID' => $ID_user ));
    }
    catch(PDOException $e) {
        exit('PDO error [update pr_rewards]: ' . $e->getMessage());
    }
}
//--------------------------------------------------------------------------
//  Funkcije za dohvaćanje iz tablice događaja
//--------------------------------------------------------------------------
public function getEventsByUser($user) {
    $db = DB::getConnection();

    try {
        $st = $db->prepare(
            'SELECT * FROM pr_events  WHERE ID_user=:userID
            ORDER BY time_set DESC');
        $st->execute(array('userID' => $user->ID));

        // Bit će popunjen sa svim podacima eventova korisnika user
        $events = array();

        while ($row = $st->fetch()) {
            array_push($events, Event::fromRow($row));
        }

        // Vrati array sa svim eventovima korisnika user
        return $events;
    }
    catch(PDOException $e) {
        exit('PDO error [select pr_rewards]: ' . $e->getMessage());
    }
}

public function getEventsByHousehold($household) {
    $db = DB::getConnection();

    try {
        $st = $db->prepare(
            'SELECT * FROM pr_events  WHERE ID_household=:householdID AND ID_user=0
            ORDER BY time_set DESC');
        $st->execute(array('householdID' => $household->ID));

        // Bit će popunjen sa svim podacima eventova kućanstva household koji nisu osobni eventovi (id_user = 0)
        $events = array();

        while ($row = $st->fetch()) {
            array_push($events, Event::fromRow($row));
        }

        // Vrati array sa svim eventovima korisnika kućanstva household.
        return $events;
    }
    catch(PDOException $e) {
        exit('PDO error [select pr_rewards]: ' . $e->getMessage());
    }
}


// Brisanje događaja starijih od tjedan dana
public function cleanEvents() {
    $db = DB::getConnection();

    try {
        $st = $db->prepare(
            'DELETE FROM pr_events WHERE datetime < NOW() - INTERVAL 1 WEEK');
        $st->execute();
    }
    catch(PDOException $e) {
        exit('PDO error [select pr_rewards]: ' . $e->getMessage());
    }
}


// Stvara novi event pomoću user podataka i opisa text
public function createEvent($event) {
    $db = DB::getConnection();

    try {
    $st = $db->prepare(
        'INSERT INTO pr_events(ID_user, ID_household, description, time_set) ' .
        'VALUES (:ID_user, :ID_household, :description, NOW())');

    $st->execute(array(
        'ID_user' => $event->ID_user,
        'ID_household' => $event->ID_household,
        'description' => $event->description ));
    }
    catch(PDOException $e) {
        exit('PDO error [insert pr_rewards]: ' . $e->getMessage());
    }
}

}
?>
