@extends('layouts.main')

@section('content')
    @if ($validationErrors)
        <div class="alert alert-danger">
            <ul>
                @foreach ($validationErrors as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div><br />

        <a href="{{\App\Utils\Helpers\ShopDomainHelper::getShopDomain()}}">Return to shop</a>
    @endif
@endsection
