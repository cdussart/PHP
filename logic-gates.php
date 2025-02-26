<?php

/**
 * A logic gate is an electronic device implementing a boolean function, performing a logical operation on one or more binary inputs and producing a single binary output.
 * Given n input signal names and their respective data, and m output signal names with their respective type of gate and two input signal names, provide m output signal names and their respective data, in the same order as provided in input description.
 * All type of gates will always have two inputs and one output.
 * All input signal data always have the same length.
 * The type of gates are :
 * AND : performs a logical AND operation.
 * OR : performs a logical OR operation.
 * XOR : performs a logical exclusive OR operation.
 * NAND : performs a logical inverted AND operation.
 * NOR : performs a logical inverted OR operation.
 * NXOR : performs a logical inverted exclusive OR operation.
 * Signals are represented with underscore and minus characters, an undescore matching a low level (0, or false) and a minus matching a high level (1, or true).
 */

// Define the results for each gate type
const TYPES = array(
    "AND" => array("_" => array("_" => "_", "-" => "_"),
                   "-" => array("_" => "_", "-" => "-")
    ),
    "OR" => array("_" => array("_" => "_", "-" => "-"),
                  "-" => array("_" => "-", "-" => "-")
    ),
    "XOR" => array("_" => array("_" => "_", "-" => "-"),
                   "-" => array("_" => "-", "-" => "_")
    ),
    "NAND" => array("_" => array("_" => "-", "-" => "-"),
                    "-" => array("_" => "-", "-" => "_")
    ),
    "NOR" => array("_" => array("_" => "-", "-" => "_"),
                   "-" => array("_" => "_", "-" => "_")
    ),
    "NXOR" => array("_" => array("_" => "-", "-" => "_"),
                    "-" => array("_" => "_", "-" => "-")
    )
);

// Initialization of variables
$inputs = [];
$outputs = [];

// Get the number of inputs and number of outputs
fscanf(STDIN, "%d", $n);
fscanf(STDIN, "%d", $m);

/**
 * Get the inputs from the user and store them.
 * Example of input : "A __---_-__--_-"
 * Store the inputs in an associative array, like $inputs[0] = array("A" => "__---_-__--_-")
 */
for ($i = 0; $i < $n; $i++)
{
    fscanf(STDIN, "%s %s", $inputName, $inputSignal);
    if(!in_array($inputName, $inputs)){
        $inputs[] = array($inputName => []);
    }
    $inputs[$inputName][] = $inputSignal;
}

/**
 * Get the outputs from the user and store them.
 * Example of output : "C AND A B", so : output name, logic gate, and the two inputs we want to combine.
 * Store the outputs in an associative array, like $outputs["C"] = ["AND", "A", "B"]
 */
for ($i = 0; $i < $m; $i++)
{
    fscanf(STDIN, "%s %s %s %s", $outputName, $type, $inputName1, $inputName2);
    $outputs[] = array($outputName,$type, $inputName1, $inputName2);
}

// Echo the results
$lastTrailingN = $m-1;
for ($i = 0; $i < $m; $i++)
{

    // Gather the data
    $output = $outputs[$i];
    $outputName = $output[0];
    $signalType = $output[1];
    $inputName1 = $output[2];
    $inputName2 = $output[3];
    $inputSignal1 = $inputs[$inputName1][0];
    $inputSignal2 = $inputs[$inputName2][0];

    // Hopefully, the signals will have the same length, but if not, we will stop outputting when the shorter input stops.
    $outputLength = 0;
    $length1 = strlen($inputSignal1);
    $length2 = strlen($inputSignal2);
    if($length1 == $length2){
        $outputLength = $length1;
    }else{
        if($length1 < $length2){
            $outputLength = $length1; 
        }else{
            $outputLength = $length2;
        }
    }

    // Echo the outputs
    echo("$outputName ");
    for($iSignal = 0; $iSignal < $outputLength; $iSignal++){
        $signal1 = $inputSignal1[$iSignal];
        $signal2 = $inputSignal2[$iSignal];
        echo constant('TYPES')[$signalType][$signal1][$signal2];
    }
    if($i != $lastTrailingN) echo("\n");
 
}
?>
