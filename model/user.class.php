<?php
class User {
    protected $ID;
    protected $ID_household;
    protected $username;
    protected $password;
    protected $email;
    protected $points;
    protected $image;
    protected $admin;
    protected $registration_sequence;
    protected $registered;

    // Konstruktor.
    public function __construct ($ID, $ID_household, $username, $password,
        $email, $points, $image, $admin, $registration_sequence, $registered) {
        $this->ID = $ID;
        $this->ID_household = $ID_household;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->points = $points;
        $this->image = $image;
        $this->admin = $admin;
        $this->registration_sequence = $registration_sequence;
        $this->registered = $registered;
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
