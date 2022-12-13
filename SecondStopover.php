<?php

class SecondStopover extends FirstStopover{
    public $code_second_stop_departure;
    public $code_second_stop_arrival;   

    public function __construct($_price, $_code_departure, $_code_arrival, $_code_stop_departure, $_code_stop_arrival, $_code_second_stop_departure, $_code_second_stop_arrival ) {
        $this->price = $_price;
        $this->code_departure = $_code_departure;
        $this->code_arrival = $_code_arrival;
        $this->code_stop_departure = $_code_stop_departure;
        $this->code_stop_arrival = $_code_stop_arrival;
        $this->code_second_stop_departure = $_code_second_stop_departure;
        $this->code_second_stop_arrival = $_code_second_stop_arrival;
     }

}



?>