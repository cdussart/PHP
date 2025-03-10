<?php

// Care only about killing zombies. Don't care about protecting humans.

// game loop
while (TRUE)
{
  $next_coordinates = array();
  fscanf(STDIN, "%d %d", $x, $y); // my coordinates
  fscanf(STDIN, "%d", $humanCount); // number of remaining humans

  // Locate all humans
  for ($i = 0; $i < $humanCount; $i++) 
  {
    fscanf(STDIN, "%d %d %d", $humanId, $humanX, $humanY);
  }

  // Locate all zombies
  fscanf(STDIN, "%d", $zombieCount); 
  for ($i = 0; $i < $zombieCount; $i++)
  {
    fscanf(STDIN, "%d %d %d %d %d", $zombieId, $zombieX, $zombieY, $zombieXNext, $zombieYNext);
    if(!in_array($zombieXNext, $next_coordinates))
    {
      $next_coordinates[$zombieXNext] = [];
    }
    if(!in_array($zombieYNext, $next_coordinates[$zombieXNext]))
    {
      $next_coordinates[$zombieXNext][$zombieYNext] = 1;
    }else{
      $next_coordinates[$zombieXNext][$zombieYNext]++;
    }
  }

  $best_x = -1;
  $best_y = -1;
  $nb_targets = 0;
  foreach($next_coordinates as $x => $arr){
    foreach($arr as $y => $nb_zombies){
        if($nb_zombies > $nb_targets)
        {
          $nb_targets = $nb_zombies;
          $best_x = $x;
          $best_y = $y;
        }
    }
  }

  echo($best_x." ".$best_y."\n"); // Destination coordinates
}
?>