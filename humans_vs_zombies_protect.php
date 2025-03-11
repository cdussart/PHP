<?php

// Grab the nearest human and stay there

function get_distance($x1, $y1, $x2, $y2){
  return sqrt(($x2-$x1)*($x2-$x1) + ($y2-$y1)*($y2-$y1));
}

function get_nearest_human($human_coordinates, $ash_x, $ash_y){
  $nearest_human = array("x" => 0, "y" => 0);
  $distance = 20000;
  foreach($human_coordinates as $x => $arr){
    foreach($arr as $y => $nb_humans){
      $d = get_distance($x, $y, $ash_x, $ash_y);
      if($d < $distance){
        $distance = $d;
        $nearest_human["x"] = $x;
        $nearest_human["y"] = $y;
      }
    }
  }
  return array("x" => $nearest_human["x"], "y" => $nearest_human["y"]);
}

// game loop
while (TRUE)
{

  $human_coordinates = array();
  $next_zombie_coordinates = array();

  fscanf(STDIN, "%d %d", $x, $y); // my coordinates

  // Locate all humans
  fscanf(STDIN, "%d", $humanCount); 
  for ($i = 0; $i < $humanCount; $i++) 
  {
    fscanf(STDIN, "%d %d %d", $humanId, $humanX, $humanY);
    if(!in_array($humanX, $human_coordinates))
    {
      $human_coordinates[$humanX] = [];
    }
    if(!in_array($humanY, $human_coordinates[$humanX]))
    {
      $human_coordinates[$humanX][$humanY] = 1;
    }else{
      $human_coordinates[$humanX][$humanY]++;
    }
  }

  // Locate all zombies
  fscanf(STDIN, "%d", $zombieCount); 
  for ($i = 0; $i < $zombieCount; $i++)
  {
    fscanf(STDIN, "%d %d %d %d %d", $zombieId, $zombieX, $zombieY, $zombieXNext, $zombieYNext);
    if(!in_array($zombieXNext, $next_zombie_coordinates))
    {
      $next_zombie_coordinates[$zombieXNext] = [];
    }
    if(!in_array($zombieYNext, $next_zombie_coordinates[$zombieXNext]))
    {
      $next_zombie_coordinates[$zombieXNext][$zombieYNext] = 1;
    }else{
      $next_zombie_coordinates[$zombieXNext][$zombieYNext]++;
    }
  }

  $nearest_human_coordinates = get_nearest_human($human_coordinates, $ash_x, $ash_y);

  echo($nearest_human_coordinates["x"]." ".$nearest_human_coordinates["y"]."\n"); // Destination coordinates
}
?>