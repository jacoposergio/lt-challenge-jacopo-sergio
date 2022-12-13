<?php

require_once __DIR__ . '/Airport.php';
require_once __DIR__ . '/Flight.php';
require_once __DIR__ . '/Data.php';
require_once __DIR__ . '/FirstStopover.php';
require_once __DIR__ . '/SecondStopover.php';
require_once __DIR__ . '/Distance.php';

// registro i dati inviati dall'utente
if (isset($_POST['submit'])) { 
    $user_departure = $_POST['departure'];
    $user_arrival = $_POST['arrival'];
 }

// ricavo latitudine e longitudine degli aereoporti
$departure_lng = 0;
$departure_lat = 0;
$arrival_lng = 0;
$arrival_lat = 0;
   
foreach($airportsArray as $airport) {
    if($user_departure === $airport->code){
        $departure_lng = $airport->lng;
        $departure_lat = $airport->lat;
    } 
    if($user_arrival === $airport->code){
        $arrival_lng = $airport->lng;
        $arrival_lat = $airport->lat;
    } 
}

// in base alla distanza tra gli aereoporti calcolo il prezzo dei voli diretti

$user_price = 0;
$user_distance = getDistanceBetweenPointsNew($arrival_lng,$arrival_lat,$departure_lng,$departure_lat);
if( $user_distance < 10000){
$user_price = round($user_distance / 3) ;
}if( $user_distance < 2500){
$user_price =  round($user_distance / 2);
}if( $user_distance < 1500){
$user_price =  round($user_distance / 3) ;
}if( $user_distance < 700){
$user_price =  round($user_distance / 4);
}
//istanzio l'oggetto $userFlight che sarà quello del volo diretto
$userFlight = new Flight ( $user_departure, $user_arrival, $user_price);

//creo un array che conterrà le prime tratte del volo
$firstRouteArray = [];
//creo un array che conterrà i codici dei voli del primo scalo
$firstFlightCodeArray = [];
foreach($airportsArray as $airport) {
    //se l'aereoporto dello scalo è uguale a quello di partenza 
    // o se l'aereoporto dello scalo è uguale a quello di arrivo finale
    // non li pusho nell'array
    if($user_departure !==  $airport->code && $airport->code !==  $user_arrival){
        $userFirstStopover = new Flight ( $user_departure, $airport->code, rand(100, 500));
        array_push($firstRouteArray, $userFirstStopover);
    }
   //creo un array che conterrà i codici dei voli del primo scalo
    $flightCode = $airport->code;
    array_push($firstFlightCodeArray, $flightCode);
}

//creo un array che conterrà tutte le seconde tratte,
// sia quelle che arrivano a destinazione, sia quelle che arrivano a un altro scalo
$secondRouteArray = [];
foreach($firstRouteArray as $flight) {
    foreach($firstFlightCodeArray as $code){
        //il prezzo sarà quello della prima tratta ma maggiorato
        $newPrice = $flight->price + rand(50, 150);
         //se l'aereoporto del secondo scalo è uguale a quello di partenza 
         // o se l'aereoporto del secondo scalo è uguale a quello di arrivo del primo scalo
         // non li pusho nell'array
        if($code !==  $user_departure && $flight->code_arrival !== $code){
            $userSecondStopover = new Flight ( $flight->code_arrival, $code , $newPrice);
            array_push($secondRouteArray, $userSecondStopover);         
        }
    } 
}

//creo un array che conterrà le tratte finali del secondo scalo
$thirdRouteArray = [];
foreach($airportsArray as $airport) {
    //se l'aereoporto di partenza della tratta finale è uguale a quello iniziale di partenza 
    // o l'aereoporto di arrivo della tratta finale è uguale a quello iniziale di arrivo 
    // non li pusho nell'array
    if($user_departure !==  $airport->code && $airport->code !==  $user_arrival){
        $userThirdStopover = new Flight ( $airport->code, $user_arrival,  $newPrice );
        array_push($thirdRouteArray, $userThirdStopover);
    }
}

//creo un array che conterrà tutte le tratte con un solo scalo
$oneStopoverArray = [];
$halfTwoStopoverArray = [];
$twoStopoverArray = [];

foreach($secondRouteArray as $secondRoute){
    foreach($firstRouteArray as $firstRoute){
        //se la prima rotta arriva alla seconda e se la seconda termina a destinazione, 
        // il viaggio è completato in uno scalo e pusho nell'array 
        if( $firstRoute->code_arrival === $secondRoute->code_departure && $secondRoute->code_arrival === $user_arrival){
           $oneStopoverPrice =  $firstRoute->price + $secondRoute->price;
           //creo gli oggetti con 1 scalo e li pusho nell'array
           $firstStopoverElement = new FirstStopover ( $oneStopoverPrice, $firstRoute->code_departure, $firstRoute->code_arrival, $secondRoute->code_departure, $secondRoute->code_arrival);
           array_push($oneStopoverArray, $firstStopoverElement); 
           asort($oneStopoverArray);
    
      //se la destinazione della prima rotta è uguale alla partenza della seconda 
      // e la seconda rotta non giunge direttamente a destinazione
      //  servirà un secondo scalo   
        }if($firstRoute->code_arrival === $secondRoute->code_departure && $secondRoute->code_arrival !== $user_arrival){
            $oneStopoverPrice =  $firstRoute->price + $secondRoute->price;
           $firstStopoverElement = new FirstStopover ( $oneStopoverPrice, $firstRoute->code_departure, $firstRoute->code_arrival, $secondRoute->code_departure, $secondRoute->code_arrival);
           // pusho nell'array che contiene la prima rotta del secondo scalo
           array_push($halfTwoStopoverArray, $firstStopoverElement); 
           asort($halfTwoStopoverArray);
           foreach($thirdRouteArray as $thirdRoute){
                // se l'arrivo della rotta intermedia è uguale alla partenza di quella finale
                // e l'arrivo della finale è uguale all'arrivo definitivo
                //creo gli oggetti con 2 scali e li pusho nell'array
                if($secondRoute->code_arrival === $thirdRoute->code_departure && $thirdRoute->code_arrival === $user_arrival){
                    $secondStopoverElement = new SecondStopover ( $oneStopoverPrice, $firstRoute->code_departure, $firstRoute->code_arrival, $secondRoute->code_departure, $secondRoute->code_arrival,$thirdRoute->code_departure, $thirdRoute->code_arrival);  
                    array_push($twoStopoverArray, $secondStopoverElement); 
                    asort($twoStopoverArray); 
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" 
    <title></title>
</head>
<body>
  <div class="container mt-5">

<form action="index.php" method="post">
  
   <div>Scegli un aereoporto di partenza</div>
    <select name="departure">
    <?php
    foreach($airportsArray as $airport) {
    ?>
	    <option value="<?php echo $airport->code ?>"><?php echo $airport->name ?></option>
    <?php
     }
     ?>
	  </select>

      <div>Scegli un aereoporto di arrivo</div>
    <select name="arrival">
    <?php
    foreach($airportsArray as $airport) {
    ?>
	    <option value="<?php echo $airport->code ?>"><?php echo $airport->name ?></option>
    <?php
     }
     ?>
	  </select>

      <p>La distanza tra i due aereoporti è di <?php echo getDistanceBetweenPointsNew($arrival_lng,$arrival_lat,$departure_lng,$departure_lat). ' km'; ?></p>

      <input type="submit" name="submit" value="Invia"/>

      <table id="mytable" class="table mt-5" style="visibility:visible;">
  <thead>
    <tr>
      <th scope="col">Partenza</th>
      <th scope="col">Scalo 1</th>
      <th scope="col">Scalo 2</th>
      <th scope="col">Arrivo</th>
      <th scope="col">Prezzo</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><?php echo $user_departure ?></td>
      <td></td>
      <td></td>
      <td><?php echo $user_arrival ?></td>
      <td><?php echo $user_price ?> &euro; </td>
    </tr>
    <tr>
      <?php
        foreach($oneStopoverArray as $singleFlight) {
        ?>
            <td> <?php echo $singleFlight->code_departure ?></th>
            <td><?php echo $singleFlight->code_arrival ?></td>
            <td></td>
            <td><?php echo $singleFlight->code_stop_arrival ?></td>
            <td><?php echo $singleFlight->price ?> &euro;</td>
        </tr>
        <?php
        }
        ?>
    </tr>
    <tr>
      <?php
        foreach($twoStopoverArray as $singleFlight) {
        ?>
            <td> <?php echo $singleFlight->code_departure ?></th>
            <td><?php echo $singleFlight->code_arrival ?></td>
            <td><?php echo $singleFlight->code_stop_arrival ?></td>
            <td><?php echo $singleFlight->code_second_stop_arrival; ?></td>
            <td><?php echo $singleFlight->price ?> &euro;</td>
        </tr>
        <?php
        }
        ?>
    </tr>
  </tbody>
</table>
  </div>
      
</body>
</html>