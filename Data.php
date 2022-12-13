
<?php

// istanzio 5 aereoporti
$charlesDeGaulle = new Airport ( '1','Charles de Gaulle Airport, Parigi, Francia','CDG','49.0097','2.5477' );
$leonardoDaVinci = new Airport ( '2','Leonardo Da Vinci, Fiumicino, Italia','FCO','41.7735','12.2397' );
$amsterdamSchiphol = new Airport ( '3','Amsterdam Schiphol, Amsterdam, Olanda','AMS','52.3086','4.7641' );
$osloAirport = new Airport ( '4','Oslo Airport Gardermoen, Oslo, Norvegia','OSL','60.1976','11.1004' );
$mohammedV = new Airport ( '5','OMohammed V International Airport, Casablanca, Marocco','CMN','33.3700','7.5845' );

$airportsArray = [];
array_push($airportsArray, $charlesDeGaulle, $leonardoDaVinci, $amsterdamSchiphol, $osloAirport, $mohammedV);

?>