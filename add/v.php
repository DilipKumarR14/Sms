<?php
include "inte.json";
$str = file_get_contents('inte.json');
$res = json_decode($str,true);
$R = $res['CC'];
echo $R;
?>