<?php
    class Reward {
        protected $ID;
        protected $ID_user;
        protected $description;
        protected $points_price;
        protected $purchased;

        // Konstruktor.
        public function __construct ($ID, $ID_user, $description, $points_price) {

            $this->ID = intval($ID);
            $this->ID_user = intval($ID_user);
            $this->description = $description;
            $this->points_price = intval($points_price);
        }

        // Stvara novi objekt iz retka iz tablice pr_rewards.
        // Koristi se kao:
        //      $reward = Reward::fromRow($row);
        public static function fromRow(array $row) {
            $instance = new self($row["ID"], $row["ID_user"], $row["description"], $row["points_price"]);

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
