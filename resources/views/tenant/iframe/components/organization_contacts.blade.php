<h5>{{ $organization['name'] }} Contacts:</h5>
<ul>
    @foreach ($organization->administrators as $contact)
    <li>{{ $contact['name'] }}{{ !empty($contact->administrator['title']) ? ", " . $contact->administrator['title'] : ''}}</li>
        <ul>
            <li>Email: {{ $contact['email'] }}</li>
        </ul>
    @endforeach
</ul>
