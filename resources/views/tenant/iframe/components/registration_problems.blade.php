@if(!empty($program->getRegistrationContacts()))
    <h5>Problems enrolling? Contact:</h5>
    <ul>
        @foreach ($program->getRegistrationContacts() as $registrationContact)
            <li>{{ $registrationContact['name'] }}</li>
            <ul>
                <li>Email: {{ $registrationContact['email'] }}</li>
            </ul>
        @endforeach
    </ul>
@endif
