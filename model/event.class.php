<?php
    class Event {
        protected $ID;
        protected $ID_user;
        protected $ID_household;
        protected $description;
        protected $time_set;

        // Konstruktor.
        public function __construct ($ID, $ID_user, $ID_household, $description, $time_set) {

            $this->ID = intval($ID);
            $this->ID_user = intval($ID_user);
            $this->ID_household = intval($ID_household);
            $this->description = $description;
            $this->time_set = $time_set;
        }

        // Stvara novi objekt iz retka iz tablice pr_events.
        // Koristi se kao:
        //      $event = Event::fromRow($row);
        public static function fromRow(array $row) {
            $instance = new self($row["ID"], $row["ID_user"], $row["ID_household"], $row["description"], $row["time_set"]);

            return $instance;
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
