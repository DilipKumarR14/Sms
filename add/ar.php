<?php
$user = array(0);
$db = array(1);
$j = count($db);
$can = false;
$r = array_diff($user,$db);
print_r($r);
if(empty($r)){
    echo "can send sms \n";
}else{
    echo "sorry\n";
}
?>