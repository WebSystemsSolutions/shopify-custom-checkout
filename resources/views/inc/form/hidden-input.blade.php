<?php
if (!isset($type)) {
    $type = "text";
}
if (!isset($name)) {
    $name = 'name';
}
if (!isset($value)) {
    $value = '';
}
?>

<input type="{{$type}}" name="{{$name}}" value="{{$value}}">
