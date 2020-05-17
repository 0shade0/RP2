<?php
class Household {
    protected $ID;
    protected $name;
    protected $password;

    // Konstruktor.
    public function __construct ($ID, $name, $password) {
        $this->ID = $ID;
        $this->name = $name;
        $this->password = $password;
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
