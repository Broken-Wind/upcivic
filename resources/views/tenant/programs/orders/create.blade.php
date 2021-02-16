@extends('layouts.app')
@section('title')
    Enter Order Details
@endsection
@section('content')
<div class="container">
    <form method="POST" action="{{tenant()->route('tenant:programs.orders.store', [$program])}}">
        @csrf
        @include('shared.form_errors')
        <input type="hidden" name="ticket_quantity" value="{{ $numberOfSpots }}">
        <div class="card">
            <div class="card-header">
                Participant Information
            </div>
            <div class="card-body">
                @for($i = 1; $i <= max(1, $numberOfSpots); $i++)
                    <h4>Participant #{{ $i }}</h4>
                    <div class="form-group form-row">
                        <div class="col-md-4 form-group">
                            <label>Participant First Name</label>
                            <input name="participants[{{ $i }}][first_name]" type="text" class="form-control" required value="{{ old("participants.{$i}.first_name") }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Participant Last Name</label>
                            <input name="participants[{{ $i }}][last_name]" type="text" class="form-control" required value="{{ old("participants.{$i}.last_name") }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Birthday</label>
                            <input name="participants[{{ $i }}][birthday]" type="date" class="form-control" required value="{{ old("participants.{$i}.birthday") }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Allergies, medical considerations, and special needs</label>
                        <textarea class="form-control" name="participants[{{ $i }}][needs]" rows="3">{{ old("participants.{$i}.needs") }}</textarea>
                    </div>
                    @if($i < $numberOfSpots)
                        <hr />
                    @endif
                @endfor
            </div>
        </div>
        <p />
        <div class="card">
            <div class="card-header">
                Primary Contact Information
            </div>
            <div class="card-body">
                <div class="form-group form-row">
                    <div class="col-md-6 form-group">
                        <label>Primary Contact First Name</label>
                        <input name="primary_contact[first_name]" type="text" class="form-control" required value="{{ old("primary_contact.first_name") }}">
                    </div>
                    <div class="col-md-6 form-group">
                            <label>Primary Contact Last Name</label>
                        <input name="primary_contact[last_name]" type="text" class="form-control" required value="{{ old("primary_contact.last_name") }}">
                    </div>
                </div>
                <div class="form-group form-row">
                    <div class="col-md-6 form-group">
                        <label>Phone Number</label>
                        <input name="primary_contact[phone]" type="text" class="form-control" required value="{{ old("primary_contact.phone") }}">
                    </div>
                </div>
            </div>
        </div>
        <p />
        <div class="card">
            <div class="card-header">
                Additional Emergency Contact Information <text class="text-muted">(Optional)</text>
            </div>
            <div class="card-body">
                <div class="form-group form-row">
                    <div class="col-md-6 form-group">
                        <label>First Name</label>
                        <input name="alternate_contact[first_name]" type="text" class="form-control" value="{{ old("alternate_contact.first_name") }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Last Name</label>
                        <input name="alternate_contact[last_name]" type="text" class="form-control" value="{{ old("alternate_contact.last_name") }}">
                    </div>
                </div>
                <div class="form-group form-row">
                    <div class="col-md-6 form-group">
                        <label>Phone Number</label>
                        <input name="alternate_contact[phone]" type="text" class="form-control" value="{{ old("alternate_contact.phone") }}">
                    </div>
                </div>
            </div>
        </div>
        <br/>
        <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"

            data-key="{{ config('services.stripe.key') }}"

            data-amount="{{ $program->price * $numberOfSpots }}"

            data-name="Default Company"

            data-locale="auto" id="stripe-button">
        </script>
    </form>
</div>
@endsection
