<?php

/**
 * L'objectif de votre programme est de faire atterrir, sans crash, la capsule "Mars Lander"
 * qui contient le rover Opportunity. La capsule “Mars Lander” permettant de débarquer le rover est pilotée par
 * un programme qui échoue trop souvent dans le simulateur de la NASA.
 * 
 * Sous forme de jeu, le simulateur place Mars Lander dans une zone du ciel de Mars.
 * La zone fait 7000m de large et 3000m de haut.
 * Il existe une unique zone d'atterrissage plane sur la surface de Mars et elle mesure au moins 1000 mètres de large.
 * 
 * Toutes les secondes, en fonction des paramètres d’entrée (position, vitesse, fuel, etc.), le programme doit fournir
 * le nouvel angle de rotation souhaité ainsi que la nouvelle puissance des fusées de Mars Lander: 
 * Angle de -90° à 90°.
 * Puissance des fusées de 0 à 4.
 * 
 * Le jeu modélise une chute libre sans atmosphère. La gravité sur Mars est de 3,711 m/s².
 * Pour une puissance des fusées de X, on génère une poussée équivalente à X m/s² et on consomme X litres de fuel.
 * Il faut donc une poussée de 4 quasi verticale pour compenser la gravité de Mars.
 * Pour qu’un atterrissage soit réussi, la capsule doit :
 *  - atterrir sur un sol plat
 *  - atterrir dans une position verticale (angle = 0°)
 *  - la vitesse verticale doit être limitée ( ≤ 40 m/s en valeur absolue)
 *  - la vitesse horizontale doit être limitée ( ≤ 20 m/s en valeur absolue)
 * 
 */

const G = 3.711;
const MAX_VS = 40;
const DEF_ANGLE = 30;

/**
 * Determines whether your speed (both horizontal and vertical) is ok for landing.
 * It calculates the coordinates of your landing point assuming you start reducing your speed now,
 * and returns whether the horizontal point is farther than the target and the vertical point is above the target.
 * @param x : horizontal position
 * @param x_center : horizontal position of the target (in the center of the landing area)
 * @param h_speed : horizontal speed
 * @param y : vertical position
 * @param height_plateau : height of the landing area
 * @param v_speed : vertical speed
 * @return : an array of two booleans : if you stop now, will your x be < x_target and will your y be > y_target
 */
function can_stop_on_time($x, $x_center, $h_speed, $y, $height_plateau, $v_speed){ 

  $res = array("horizontal_distance" => false, "altitude" => false);

  if($y < $height_plateau) return $res;

  // Horizontal speed
  $h_distance = abs($x - $x_center);
  $h_distance_before_stop = 0;
  $current_h_speed = abs($h_speed);
  $diminution_h_max = DEF_ANGLE / 90 * 4;

  // Vertical altitude
  $altitude = $y - $height_plateau;
  $current_v_speed = $v_speed;
  $diminution_altitude = -(4 - G) * ((90 - DEF_ANGLE) / 90);
  
  while(($current_h_speed > 0) && ($altitude > 0)){
    $h_distance_before_stop += $current_h_speed;
    $current_h_speed -= $diminution_h_max;
    $altitude += $current_v_speed; // speed < 0 means you're falling
    $current_v_speed += $diminution_altitude;
  }
  
  $res["horizontal_distance"] = $h_distance_before_stop < $h_distance;
  $res["altitude"] = $altitude > 100;

  return $res;
}

/**
 * Provides your desired angle according to your position and what phase you are currently in.
 * @param X : horizontal position
 * @param x_center : horizontal position of the target (in the center of the landing area)
 * @param phase : ["acceleration" | "stopping" | "went_too_far", "inverse_stopping"]; basically, should you be facing the target or not
 * @param situation_initiale : ["left"|"right"]; meaning, if you started "left" of your target, you want to go to the right, and vice-versa.
 */
function get_max_angle($X, $x_center, $phase = "acceleration", $initial_situation = "left"){  
  if($phase == "acceleration"){
    if($X < $x_center) return -DEF_ANGLE;
    else return DEF_ANGLE;
  }elseif($phase == "stopping"){
    if($initial_situation == "left") return DEF_ANGLE;    
    else return -DEF_ANGLE;
  }elseif($phase == "went_too_far"){
    if($initial_situation == "left") return DEF_ANGLE;
    else return -DEF_ANGLE;
  }elseif($phase == "inverse_stopping"){
    if($initial_situation == "left") return -DEF_ANGLE;    
    else return DEF_ANGLE;
  }
  else{
    return 0;
  }
}

// Find the landing area using the first input lines (provide the coordinates of the points of the whole area)
$x_min = -1;
$x_max = 15000;
$height_plateau = 0;
$prevX = -1;
$prevY = -1;
$coordinates = array();
fscanf(STDIN, "%d", $N);
for ($i = 0; $i < $N; $i++)
{
    fscanf(STDIN, "%d %d", $landX, $landY);
    $coordinates[$landX] = $landY;
    if($landY != $prevY){ $prevX = $landX; $prevY = $landY; }
    else{
      if($landX - $prevX >= 1000){ $x_min = $prevX; $x_max = $landX; $height_plateau = $landY; }
    }
}

// Initialisation of variables
$x_center = ($x_max + $x_min)/2;
$initial_situation = "";
$initial_height = -1;
$acceleration = true;
$slowdown = false;
$comeback = false;
$landing = false;

// game loop
while (TRUE)
{
  // $HS: the horizontal speed (in m/s), can be negative.
  // $VS: the vertical speed (in m/s), can be negative.
  // $F: the quantity of remaining fuel in liters.
  // $R: the rotation angle in degrees (-DEF_ANGLE to DEF_ANGLE).
  // $P: the thrust power (0 to 4).
  fscanf(STDIN, "%d %d %d %d %d %d %d", $X, $Y, $HS, $VS, $F, $R, $P);

  if($initial_situation == "") $initial_situation = $X < $x_min ? "left" : "right";
  if($initial_height == -1) $initial_height = $Y -$height_plateau;

  $angle = 0;
  $thrust = 4;

  // During the acceleration phase :
  if($acceleration){

    $canstop_and_altitudeok = can_stop_on_time($X, $x_center, $HS, $Y, $height_plateau, $VS);
    
    // If altitude is high enough
    if($canstop_and_altitudeok["altitude"]){

      // If the target distance is far enough, you may accelerate
      if($canstop_and_altitudeok["horizontal_distance"]) $angle = get_max_angle($X, $x_center, "acceleration");
        
      // Otherwise, you must start stopping now
      else{
        $angle = get_max_angle($X, $x_center, "stopping", $initial_situation);
        $acceleration = false;
        $slowdown = true;
      }
    }
  }
    
  // During the stopping / slowing down phase :
  elseif($slowdown){
        
    $went_too_far = $initial_situation == "left" && $X > $x_max || $initial_situation == "right" && $X < $x_min; 
    
    // If you're above the landing area :
    if(!$went_too_far){

      // If your horizontal speed is still a bit high, the angle must be used to keep slowing down
      if(abs($HS) > 2){
        $angle = get_max_angle($X, $x_center, "stopping", $initial_situation);
      }
        
      // Otherwise, you go for a 0 angle and land
      else{
        $angle = 0;
        $slowdown = false;
        $landing = true;
      }
    }
        
      // If you're not above the landing area though the acceleration phase is over, it means you've gone too far.
    else{
      $angle = get_max_angle($X, $x_center, "went_too_far", $initial_situation);
      $slowdown = false;
      $comeback = true;
    }
  }
    
  // If you've gone too far and are coming back to the landing area :
  elseif($comeback){
      
    $above = $X > $x_min && $X < $x_max;

    // If you're not above, keep going
    if(!$above){
      $angle = get_max_angle($X, $x_center, "went_too_far", $initial_situation);
    }
        
    // If you're above, you may land
    else{
      $comeback = false;
      $landing = true;
    }    
  }
    
  // Landing phase
  else{

    // If your horizontal speed is still a bit high, the angle must be used to keep slowing down
    if(abs($HS) > 2){
      $angle = get_max_angle($X, $x_center, "inverse_stopping", $initial_situation);
    }
      
    // Otherwise, you go for a 0 angle and land
    else{
      $angle = 0;
      if(abs($VS) < MAX_VS){
        $thrust = 3;
      }
    }
  }

  // Play your chosen angle and thrust
  echo($angle." ".$thrust."\n");
}
?>