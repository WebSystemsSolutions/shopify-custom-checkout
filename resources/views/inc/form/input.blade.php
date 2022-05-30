<?php
if (!isset($type)) {
    $type = "text";
}
if (!isset($label)) {
    $label = 'label';
}
if (!isset($name)) {
    $name = 'name';
}
if (!isset($class)) {
    $class = '';
}
if (!isset($value)) {
    $value = '';
}
if (!isset($placeholder)) {
    $placeholder = '';
}
?>

<div class="form-group custom-input">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <input placeholder="{{ $placeholder }}" type="{{$type}}" name="{{$name}}" class="form-control {{ $class ?? ''}}" value="{{ $value ?? ''}}">
</div>
