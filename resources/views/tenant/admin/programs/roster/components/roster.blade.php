@include('tenant.admin.programs.roster.components.email_participants_modal')
<div class="card mb-4">
    <div class="card-header">Roster</div>
    <div class="card-body">
        <button class="btn btn-secondary" data-toggle="modal" data-target="#email-participants-modal">
            Email Participants
        </button>
        <table class="table table-striped">
            @foreach($program->tickets()->unavailable()->get()->sortBy('participant.last_name') as $ticket)
                <tr>
                    <td>
                        @if(isset($ticket->participant))
                            {{ $ticket->participant->name }}
                            @if(!empty($ticket->participant->needs))
                                - {{ $ticket->participant->needs }}
                            @endif
                            @if(!empty($ticket->participant->birthday))
                                <span class="text-muted">- {{ $ticket->participant->formatted_birthday }}</span>
                            @endif
                            @if(!empty($ticket->order))
                                <small class="text-muted">- {{ $ticket->code }}</small>
                            @endif
                        @else
                            Unknown Participant
                        @endif
                        @if(isset($ticket->participant))
                            <br>
                            @forelse($ticket->participant->contacts as $contact)
                                <small>{{ $contact->name }} - {{ $contact->phone }} - {{ $contact->email ?? 'No known email address.' }}</small>
                                @if(!$loop->last)
                                    <br>
                                @endif
                            @empty
                                <small>No contacts.</small>
                            @endforelse
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
