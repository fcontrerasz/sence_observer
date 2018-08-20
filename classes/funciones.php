<?php
require_once('lib/nusoap.php');

function obj2array($obj) {
    $out = array(); 
    foreach ($obj as $key => $val) {
        switch(true) { 
            case is_object($val): 
                $out[$key] = obj2array($val);
                break;
            case is_array($val):
                $out[$key] = obj2array($val);
                break;
            default:
                $out[$key] = $val;
        }

    }
    return $out;
}

function convert_multi_array($array) {
  $out = implode("&",array_map(function($a) {return implode("~",$a);},$array));
  print_r($out);
}


function implode_recur ($separator, $arrayvar){
$output = " ";
foreach ($arrayvar as $av)
if (is_array ($av)) $out .= implode_r ($separator, $av); // Recursive Use of the Array
else $out .= $separator.$av;

return $output;
}

 ?>
