<?php

/**
 * Vous devez aider les clones à atteindre la sortie pour s'échapper de la zone du générateur.
 * L'objectif est d'obtenir 100% avec le code le plus court possible.
 * 
 * Règles
 * 
 * La zone est rectangulaire et de taille variable. Elle est composée de plusieurs étages (0 = étage inférieur)
 * et chaque étage comporte plusieurs positions possible pour les clones
 * (0 = position la plus à gauche, width - 1 = position la plus à droite).
 * 
 * L'objectif est de sauver au moins un clone en un nombre limité de tours.
 * 
 * En détail :
 * 
 * Les clones sortent d'un unique générateur à intervalles réguliers, tous les 3 tours.
 * Le générateur est placé à l'étage 0. Les clones sortent en se dirigeant vers la droite. * 
 * Les clones avancent d'une position par tour en ligne droite, dans leur direction actuelle. * 
 * Un clone est détruit par un laser s'il dépasse la position 0 ou la position width - 1.
 * La zone dispose d'ascenseurs pour monter d'un étage à l'autre. Quand un clone arrive sur une position
 * où se trouve un ascenseur, il monte d'un étage. Monter d'un étage prend un tour de jeu.
 * Au tour suivant le clone continue sa progression dans la direction qu'il avait avant de monter.
 * À chaque tour vous pouvez soit ne rien faire, soit bloquer le clone de tête
 * (c-à-d celui qui est sorti le plus tôt).
 * Une fois qu'un clone est bloqué, vous ne pouvez plus agir dessus. Le clone suivant prend le rôle
 * de clone de tête et peut être bloqué à son tour. * 
 * Quand un clone avance ou se trouve sur une position sur laquelle se situe un clone bloqué,
 * il change de direction. * 
 * Si un clone bloque au pied d'un ascenseur, l'ascenseur ne peut plus être utilisé.
 * Quand un clone atteint l'étage et la position de l'aspirateur, il est sauvé et disparait de la zone.
 * Note : Pour ce puzzle il n'y a au maximum qu'un ascenseur par étage
 */

 /**
  * Get general input data.
  *
  * $nbFloors: number of floors
  * $width: width of the area
  * $nbRounds: maximum number of rounds
  * $exitFloor: floor on which the exit is found
  * $exitPos: position of the exit on its floor
  * $nbTotalClones: number of generated clones
  * $nbAdditionalElevators: ignore (always zero)
  * $nbElevators: number of elevators
*/
fscanf(STDIN, "%d %d %d %d %d %d %d %d", $nbFloors, $width, $nbRounds, $exitFloor, $exitPos, $nbTotalClones, $nbAdditionalElevators, $nbElevators);

/**
 * Get elevators data
 * 
 * elevatorFloor: floor on which this elevator is found
 * $elevatorPos: position of the elevator on its floor
 */
$elevators = array();
for ($i = 0; $i < $nbElevators; $i++)
{
  fscanf(STDIN, "%d %d", $elevatorFloor, $elevatorPos);
  $elevators[$elevatorFloor] = $elevatorPos;
}

// Game loop
while (TRUE)
{
  /**
   * Get leading clone data
   * 
   * $cloneFloor: floor of the leading clone
   * $clonePos: position of the leading clone on its floor
   * $direction: direction of the leading clone: LEFT or RIGHT
  */
  fscanf(STDIN, "%d %d %s", $cloneFloor, $clonePos, $cloneDirection);

  # Find the exit position
  $exit_position = $cloneFloor == $exitFloor ? $exitPos : $elevators[$cloneFloor];

  # If the clone is at the exit position (elevator), wait for it to go inside
  if($clonePos == $exit_position) echo("WAIT\n");

  # Else, if the clone is not oriented correctly block it, otherwise let it go forward
  else{
    $right_direction = $clonePos < $exit_position ? "RIGHT" : "LEFT";
    echo($cloneDirection != $right_direction ? "BLOCK\n" : "WAIT\n");
  }
}

?>