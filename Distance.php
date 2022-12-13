
<?php
// funzione per calcolare la distanza tra 2 punti del pianeta conoscendo le latiudini e le longitudini
function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2) {
    $theta = $longitude1 - $longitude2; 
    $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta))); 
    $distance = acos($distance); 
    $distance = rad2deg($distance); 
    $distance = $distance * 60 * 1.1515; 
    $distance = (round($distance * 1.609344, 1));
    return $distance; 
    }     
    

?>