<?php
    class Category {
        protected $ID;
        protected $ID_household;
        protected $name;
    
        // Konstruktor.
        public function __construct ($ID, $ID_household, $name) {

            $this->ID = intval($ID);
            $this->ID_household = intval($ID_household);
            $this->name = $name;
        }

        // Stvara novi objekt iz retka iz tablice pr_categories.
        // Koristi se kao:
        //      $category = Category::fromRow($row);
        public static function fromRow(array $row) {
            $instance = new self($row["ID"], $row["ID_household"], $row["name"]);

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

        public function getDefaultCategories() {
            return array("Čišćenje", "Vrt", "Higijena", "Zdravlje", "Kućni ljubimci", "Održavanje", "Škola");
        }
    }
?>