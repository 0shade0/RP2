<?php
class Household {
    protected $ID;
    protected $name;

    // Konstruktor.
    public function __construct ($ID, $name) {
        $this->ID = $ID;
        $this->name = $name;
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
