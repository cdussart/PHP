<?php

/**
 * Function that returns the reverse of a given state
 * @param state : state to be reversed
 */
function reverse_state(string $state){
    return $state == "*" ? "~" : "*";
}
  
/**
 * Updates the grid according to the number of the button that was pressed
 * @param button_nr : number of the button that was pressed. It's a string since the inputs are string and no cast is performed.
 */
function update_states(string $button_nr){
  
  global $states;
  
  $to_be_updated = [];
  
  if($button_nr == 1) $to_be_updated = ["1","2","4","5"];
  elseif( $button_nr == 2) $to_be_updated = ["1","2","3"];
  elseif( $button_nr == 3) $to_be_updated = ["2","3","5","6"];
  elseif( $button_nr == 4) $to_be_updated = ["1","4","7"];
  elseif( $button_nr == 5) $to_be_updated = ["2","4","5","6","8"];
  elseif( $button_nr == 6) $to_be_updated = ["3","6","9"];
  elseif( $button_nr == 7) $to_be_updated = ["4","5","7","8"];
  elseif( $button_nr == 8) $to_be_updated = ["7","8","9"];
  elseif( $button_nr == 9) $to_be_updated = ["5","6","8","9"];

  foreach($to_be_updated as $e){
    $states[$e] = reverse_state($states[$e]);
  }
  
}
  
# Retrieve user inputs
$row1 = stream_get_line(STDIN, 5 + 1, "\n");
$row2 = stream_get_line(STDIN, 5 + 1, "\n");
$row3 = stream_get_line(STDIN, 5 + 1, "\n");
$allButtonsPressed = stream_get_line(STDIN, 100 + 1, "\n");

# Store the inputs in a grid
$states = array("1" => $row1[0], "2" => $row1[2], "3" => $row1[4], "4" => $row2[0], "5" => $row2[2], "6" => $row2[4], "7" => $row3[0], "8" => $row3[2], "9" => $row3[4]);

# Process the buttons pressed by the user
$nb_buttons_pressed = strlen($allButtonsPressed);
for ($i = 0; $i < $nb_buttons_pressed; $i++){
  $button_nr = $allButtonsPressed[$i];
  update_states($button_nr);
}
    
# Find the number that needs to be pressed to win
$answer = -1;
if( $states[1] == "~" and $states[2] == "~" and $states[4] == "~" and $states[5] == "*"){
    $answer = 1;
}elseif( $states[1] == "~" and $states[2] == "~" and $states[3] == "~" ){
    $answer = 2;
}elseif( $states[2] == "~" and $states[3] == "~" and $states[5] == "*" and $states[6] == "~"){
    $answer = 3;
}elseif( $states[1] == "~" and $states[4] == "~" and $states[7] == "~" ){
    $answer = 4;
}elseif( $states[2] == "~" and $states[5] == "*" and $states[8] == "~" and $states[4] == "~" and $states[6] == "~"){
    $answer = 5;
}elseif( $states[3] == "~" and $states[6] == "~" and $states[9] == "~" ){
    $answer = 6;
}elseif( $states[4] == "~" and $states[5] == "*" and $states[7] == "~" and $states[8] == "~"){
    $answer = 7;
}elseif( $states[7] == "~" and $states[8] == "~" and $states[9] == "~" ){
    $answer = 8;
}elseif( $states[5] == "*" and $states[6] == "~" and $states[8] == "~" and $states[9] == "~"){
    $answer = 9;
}

echo($answer);

?>
