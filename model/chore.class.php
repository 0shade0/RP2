<?php
    class Chore {
        protected $ID;
        protected $ID_user;
        protected $ID_category;
        protected $description;
        protected $time_next;
        protected $mandatory;
        protected $type;
        protected $points;
    
        // Konstruktor.
        public function __construct ($ID, $ID_user, $ID_category, $description,
            $time_next, $mandatory, $type, $points) {

            $this->ID = intval($ID);
            $this->ID_user = intval($ID_user);
            $this->ID_category = intval($ID_category);
            $this->description = $description;
            $this->time_next = $time_next;   // primjer formata: '2020-05-15 20:20:00' 
            $this->mandatory = intval($mandatory);
            $this->type = intval($type);
            $this->points = intval($points);
        }

        // Stvara novi objekt iz retka iz tablice pr_chores.
        // Koristi se kao:
        //      $chore = Chore::fromRow($row);
        public static function fromRow(array $row) {
            $instance = new self($row["ID"], $row["ID_user"], $row["ID_category"], $row["description"],
                $row["time_next"], $row["mandatory"], $row["type"], $row["points"]);

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