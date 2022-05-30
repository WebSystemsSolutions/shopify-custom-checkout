<?php

$productCounter = 0;

//to delete
if (($key = array_search('Rest of World', $shippingZonesNames)) !== false) {
    unset($shippingZonesNames[$key]);
}
?>

@extends('layouts.main')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div><br />
@endif
<div class="flex-parent-horizontal">
    <div class="flex-child-horizontal">
        <h4 class="checkout-header">Custom checkout</h4>
        <form method="post" action="{{route('confirm')}}">
            @csrf
            @foreach($checkoutItems as $item)
                @include(
                'inc.form.hidden-input',
                [
                    'type' => 'hidden',
                    'name' => 'item[' . $productCounter . '][id]',
                    'value' => $item['id']
                ])
                @include(
               'inc.form.hidden-input',
               [
                   'type' => 'hidden',
                   'name' => 'item[' . $productCounter . '][quantity]',
                   'value' => $item['quantity']
               ])
               <?php $productCounter++; ?>
            @endforeach

            @include(
                'inc.form.input',
                [
                    'type' => 'email',
                    'name' => 'email',
                    'label' => __('email'),
                    'value' => old('email'),
                ]
            )
            @include(
                'inc.form.input',
                [
                    'type' => 'tel',
                    'name' => 'phone',
                    'label' => __('phone'),
                    'value' => old('phone'),
                    'placeholder' => 'Enter phone with area code'
                ]
            )
            @include(
                'inc.form.input',
                [
                    'type' => 'text',
                    'name' => 'firstName',
                    'label' => __('first name'),
                    'value' => old('firstName'),
                ]
            )
            @include(
                'inc.form.input',
                [
                    'type' => 'text',
                    'name' => 'lastName',
                    'label' => __('last name'),
                    'value' => old('lastName'),
                ]
            )
            @include(
                'inc.form.select',
                [
                    'name' => 'country',
                    'class' => 'js-country',
                    'url' => route('shipments'),
                    'label' => __('country'),
                    'values' => $shippingZonesNames ?? [],
                    'oldName' => 'country',
                ]
            )
            @include(
                'inc.form.input',
                [
                    'type' => 'text',
                    'name' => 'city',
                    'label' => __('city'),
                    'value' => old('city'),
                ]
            )
            @include(
                'inc.form.input',
                [
                    'type' => 'text',
                    'name' => 'street',
                    'label' => __('street'),
                    'value' => old('street'),
                ]
            )
            @include(
                'inc.form.input',
                [
                    'type' => 'text',
                    'name' => 'postalCode',
                    'label' => __('postal code'),
                    'value' => old('postalCode'),
                ]
            )
            @include(
                'inc.form.select',
                [
                    'name' => 'shippingMethodId',
                    'class' => 'js-shipment',
                    'id' => 'shipment',
                    'label' => __('shipping method'),
                    'url' => route('addShipments'),
                    'oldName' => 'shippingMethodId',
                    'dataAttr' => sprintf("data-old-method-id=%s", old('shippingMethodId')),
                ]
            )
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <div class="flex-child-horizontal">
        <h4 class="checkout-header">Your order</h4>
        <table class="order-items">
            <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Count</th>
                <th>Price</th>
            </tr>
            </thead>
            <tbody>
            @foreach($checkoutItems as $item)
                <tr>
                    <td>
                        <div class="box">
                            <img src="{{isset($item['image']) ? $item['image'] : asset('image.png')}}" alt="{{$item['title']}}">
                        </div>
                    </td>
                    <td>
                        <p>{{$item['title']}}</p>
                    </td>
                    <td>
                        <p>{{$item['quantity']}}</p>
                    </td>
                    <td>
                        <p>{{$item['price']}}</p>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <hr>
        <div class="price">
            <p class="js-cart-price">Cart items price: {{$cartItemsPrice}}</p>
            <p class="js-shipment-price">Shipment price: 0</p>
            <hr>
            <p class="js-total-price">Total price: {{$cartItemsPrice}}</p>
        </div>
    </div>
</div>

@endsection
