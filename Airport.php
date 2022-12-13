<?php

class Airport{
    public int $id;
    public $name;
    public $code;
    public $lat;
    public $lng;

    public function __construct($_id, $_name, $_code, $_lat, $_lng) {
        $this->id = $_id;
        $this->name = $_name;
        $this->code = $_code;
        $this->lat = $_lat;
        $this->lng = $_lng;
      }

}



?>