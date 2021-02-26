@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">@yield('code')</div>
            <div class="card-body">
                <h1>@yield('name')</h1>
                <hr/>
                <h3>@yield('description')</h3>
                <br/>
                <p> You can <a href="/">return to our front page</a>, or <a href="mailto:support@upcivic.com">drop us a line</a> if you can't find what you're looking for.</p>
            </div>
        </div>
    </div>
@endsection