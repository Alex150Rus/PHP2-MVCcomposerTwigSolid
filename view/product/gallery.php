<?php

$str=null;

foreach ($product as $key => $value) {
$str.= "<h3>{$product[$key]->name}</h3><br><p>{$product[$key]->description}</p><br>
<p>{$product[$key]->price}$</p><br><hr>";
}

echo $str;
