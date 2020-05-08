<?php
class User {
    protected $ID;
    protected $ID_kucanstvo;
    protected $username;
    protected $password_hash;
    protected $email;
    protected $bodovi;
    protected $admin;
    protected $registracijski_niz;
    protected $registriran;

    // Konstruktor.
    public function __construct ($ID, $ID_kucanstvo, $username, $password_hash,
        $email, $bodovi, $admin, $registracijski_niz, $registriran) {
        $this->ID = $ID;
        $this->ID_kucanstvo = $ID_kucanstvo;
        $this->username = $username;
        $this->password_hash = $password_hash;
        $this->email = $email;
        $this->bodovi = $bodovi;
        $this->admin = $admin;
        $this->registracijski_niz = $registracijski_niz;
        $this->registriran = $registriran;
    }

    // Getteri i setteri.
    public function __get($property) {
        if (property_exists($this, $property))
            return $this->$property;
    }

    public function __set($property, $value) {
        if (property_exists($this, $property))
            $this->$property = $value;

        // Za omogućavanje ulančavanja.
        return $this;
    }
}
?>
