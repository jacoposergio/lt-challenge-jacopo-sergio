<?php

class Flight{
    public $code_departure;
    public $code_arrival;
    public $price;

    public function __construct($_code_departure, $_code_arrival, $_price) {
        $this->code_departure = $_code_departure;
        $this->code_arrival = $_code_arrival;
        $this->price = $_price;

      }
}



?>