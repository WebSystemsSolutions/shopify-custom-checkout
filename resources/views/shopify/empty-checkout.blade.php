@extends('layouts.main')

@section('content')
    <p> Your checkout is empty </p>
    <a href="{{\App\Utils\Helpers\ShopDomainHelper::getShopDomain()}}">Return to shop</a>
@endsection
