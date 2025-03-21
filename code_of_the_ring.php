<?php

/**
 * Le but du jeu est d'aider Blub à s'échapper de la forêt en lui envoyant une série d'instructions
 * qui lui permettront d'épeler un phrase à l'aide de pierres magiques.
 * 
 * Pour ce puzzle, il vous est demandé de générer une réponse d'une seule ligne.
 * Votre rang prendra en compte votre pourcentage de réussite aux tests mais également la longueur
 * de la ligne de réponse produite. Plus courte sera la réponse, meilleur sera votre rang.
 * 
 * Règles
 * 
 * Votre programme reçoit en entrée une chaîne de caractères (la phrase magique que Blub doit épeler)
 * et doit fournir en sortie une série d'instructions à destination de Blub.
 * 
 * Ces instructions provoquent le déplacement de Blub et le font intéragir avec les différentes pierres.
 * 
 * La forêt dans laquelle se trouve Blub obéit aux règles suivantes :
 * 
 *     - La forêt est constituée de 30 zones distinctes.
 *     - Blub peut se déplacer vers la gauche ou vers la droite et passer d'une zone à l'autre
 *     quand il reçoit un signe inférieur < ou supérieur >. Toutes les zones sont alignées et contiguës.
 *     - La dernière zone est reliée à la première zone, les zones formant une boucle.
 *     - Chaque zone contient une pierre magique sur laquelle est gravée une rune.
 *       Blub peut modifier la rune inscrite sur la pierre
 * 
 * Les runes fonctionnent de la manière suivante :
 * 
 *    - Chaque rune est représentée par une lettre de l'alphabet (A-Z) ou par un espace.
 *    - Toutes les pierres sont initialement des runes espaces.
 *    - Blub change la lettre d'une rune en augmentant ou diminuant la lettre quand il reçoit un signe plus +
 *      ou un signe moins -.
 *    - La lettre après le Z est l'espace. La lettre après l'espace est le A.
 *      Les autres lettres suivent l'ordre alphabétique.
 *    - Blub peut activer la rune se trouvant sur la pierre de la zone où il se trouve.
 *      L'activation d'une rune ajoute la lettre correspondante à la phrase qu'il épèle.
 *      Il effectue cette action quand il reçoit l'instruction point ..
 *    - Une rune peut être activée plusieurs fois.
 * 
 * Vous perdez si :
 * 
 *    - À la fin des mouvements de Blub, la phrase écrite n'est pas la bonne.
 *    - Blub fait plus de 4000 actions dans la forêt.
 *    - Les instructions fournies à Blub sont incorrectes.
 *    - Votre programme ne fournit pas d'instructions à Blub dans le temps imparti.
 */

const NB_RUNES = 5;
const ALPHABET_SIZE = 27;
const HALF_ALPHABET_SIZE = 13 ;

// Dictionaries : char->positon and position->char
$position_char = array(' '=>'0', 'A'=>'1', 'B'=>'2', 'C'=>'3', 'D'=>'4', 'E'=>'5', 'F'=>'6', 'G'=>'7', 'H'=>'8', 'I'=>'9', 'J'=>'10', 'K'=>'11', 'L'=>'12', 'M'=>'13', 'N'=>'14', 'O'=>'15', 'P'=>'16', 'Q'=>'17', 'R'=>'18', 'S'=>'19', 'T'=>'20', 'U'=>'21', 'V'=>'22', 'W'=>'23', 'X'=>'24', 'Y'=>'25', 'Z'=>'26');
$char_position = array('0'=>' ', '1'=>'A', '2'=>'B', '3'=>'C', '4'=>'D', '5'=>'E', '6'=>'F', '7'=>'G', '8'=>'H', '9'=>'I', '10'=>'J', '11'=>'K', '12'=>'L', '13'=>'M', '14'=>'N', '15'=>'O', '16'=>'P', '17'=>'Q', '18'=>'R', '19'=>'S', '20'=>'T', '21'=>'U', '22'=>'V', '23'=>'W', '24'=>'X', '25'=>'Y', '26'=>'Z');

// Create an array of 5 stones to modify (5 seemed like the best compromise between walking all the stones and modifying always the same one)
$areas = array();
for($i=0;$i<NB_RUNES;$i++){
  $areas[$i] = array('rune' => ' ');
}
$current_area = 2;

// Get program input
$magic_phrase = stream_get_line(STDIN, 500 + 1, "\n");
$phrase_length = strlen($magic_phrase);
$res="";

// Process input
for($i=0;$i<$phrase_length;$i++){

  // Get current character and its position in alphabet
  $char = $magic_phrase[$i];
  $position_alphabet_char = $position_char[$char];
  
  // Loop vars init
  $distance_to_rune = NB_RUNES;
  $distance_inside_rune = 27;
  $total_distance = $distance_to_rune + $distance_inside_rune;
  $chosen_area_id = -1;

  // Choose the area that allows for the smallest number of instructions
  foreach($areas as $area_id => $area_values){

    // Calculate the number of instructions needed to go to the stone and modify its character
    $rune = $area_values['rune'];
    $position_alphabet_rune = $position_char[$rune];
    $distance_inter_runes = $area_id - $current_area;
    $distance_intra_rune = $position_alphabet_char - $position_alphabet_rune;
    if($distance_intra_rune > HALF_ALPHABET_SIZE) $distance_intra_rune -= 27; // = we'd better go the other way round
    $total_rune_distance = abs($distance_inter_runes) + abs($distance_intra_rune);
    
    // If the number of instructions is smaller than the current one, choose this stone for modification
    if($total_rune_distance < $total_distance){
      $distance_to_rune = $distance_inter_runes;
      $distance_inside_rune = $distance_intra_rune;
      $total_distance = $total_rune_distance;
      $chosen_area_id = $area_id;
    } 
  }

  // Go to chosen area
  if($distance_to_rune < 0){
    for($j=-1;$j>=$distance_to_rune;$j--) $res .= "<";
    $current_area = $chosen_area_id;
  } 
  elseif($distance_to_rune > 0){
    for($j=1;$j<=$distance_to_rune;$j++) $res .= ">";
    $current_area = $chosen_area_id;
  } 
  
  // Change area rune
  if($distance_inside_rune != 0){

    if($distance_inside_rune < 0) for($j=-1;$j>=$distance_inside_rune;$j--) $res .= "-";
    elseif($distance_inside_rune > 0) for($j=1;$j<=$distance_inside_rune;$j++) $res .= "+";

    $new_char_position = $position_char[$areas[$current_area]['rune']] + $distance_inside_rune;
    if($new_char_position < 0) $new_char_position += ALPHABET_SIZE;
    $areas[$current_area]['rune'] = $char_position[$new_char_position];
    
  }

  // Validate
  $res .= ".";

}

// Send message
$res .= "\n";
echo($res);

?>