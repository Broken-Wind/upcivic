@extends('layouts.app')
@section('title', 'Sign Up')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            A fresh verification link has been sent to your email address.
                        </div>
                    @endif

                    Before proceeding, please check your email for a verification link.

                    <form method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        If you did not receive the email,
                        <button type="submit" class="btn btn-link">click here to request another</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
