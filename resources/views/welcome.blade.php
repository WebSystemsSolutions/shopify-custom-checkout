@extends('shopify-app::layouts.default')

@section('content')
    <!-- You are: (shop domain name) -->
    <button type="button" class="btn btn-primary">
        <a href="{{ shopify_route('checkout') }}">click</a>
    </button>
    {{--import is running--}}
@endsection

@section('scripts')
    @parent

    <script>
        actions.TitleBar.create(app, { title: 'Welcome' });
    </script>
@endsection
