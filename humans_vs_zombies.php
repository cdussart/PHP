<?php

/**
 * Returns the distance between two points, being given the (x,y) coordinates of these two points.
 * @param x1 : x of the first point
 * @param y1 : y of the first point
 * @param x2 : x of the second point
 * @param y2 : y of the second point
 */
function get_distance($x1, $y1, $x2, $y2){
  return sqrt(($x2-$x1)*($x2-$x1) + ($y2-$y1)*($y2-$y1));
}

while (TRUE)
{
  fscanf(STDIN, "%d %d", $x, $y); // my coordinates
  fscanf(STDIN, "%d", $humanCount); // number of remaining humans

  // Locate all humans
  for ($i = 0; $i < $humanCount; $i++) 
  {
    fscanf(STDIN, "%d %d %d", $humanId, $humanX, $humanY);
  }

  // Locate all zombies
  fscanf(STDIN, "%d", $zombieCount);
  $nearest_coordinates = array();
  $nearest_distance = 30000;
  for ($i = 0; $i < $zombieCount; $i++)
  {
    fscanf(STDIN, "%d %d %d %d %d", $zombieId, $zombieX, $zombieY, $zombieXNext, $zombieYNext);
    $distanceI = get_distance($zombieXNext, $zombieYNext, $x, $x);
    if( $distanceI < $nearest_distance){
      $nearest_distance = $distanceI;
      $nearest_coordinates = [$zombieXNext, $zombieYNext];
    }
  }

  // Get nearest zombie
  echo($nearest_coordinates[0]." ".$nearest_coordinates[1]."\n"); // Go to nearest zombie
}
?>