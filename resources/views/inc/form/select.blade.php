<?php
if (!isset($label)) {
    $label = 'label';
}
if (!isset($name)) {
    $name = 'name';
}
if (!isset($disabled)) {
    $disabled = false;
}
if (!isset($class)) {
    $class = '';
}
if (!isset($url)) {
    $url = '';
}
if (!isset($oldName)) {
    $oldName = '';
}
if (!isset($dataAttr)) {
    $dataAttr = '';
}
if (!isset($id)) {
    $id = '';
}
?>

<div class="form-group custom-input">
    <label for="{{ $name }}">{{ $label }}</label>
    <select name="{{ $name }}" class="form-control {{ $class ?? ''}}" {{ $disabled ? "disabled": "" }} data-src="{{ $url }}" {{ $dataAttr }} id="{{ $id }}">
        @if(!empty($values))
            <option value=""></option>
            @foreach($values as $value)
                <option value="{{ $value }}" @if($value == old($oldName)) selected @endif>{{ $value }}</option>
            @endforeach
        @endif
    </select>
</div>
