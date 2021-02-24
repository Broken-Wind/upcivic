<div class="modal fade" id="email-participants-modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModal">Email Participants</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ tenant()->route('tenant:admin.programs.roster.email_participants', [$program]) }}" method="POST" id="email_participants">
                    @csrf
                    <div class="form-group">
                      <label for="subject">Subject Line</label>
                      <input type="text" class="form-control" name="subject" id="subject" aria-describedby="helpId" placeholder="Email Subject">
                    </div>
                    <div class="form-group">
                        <label for="message">Message Contents</label>
                        <textarea class="form-control" name="message" id="message" rows="3"></textarea>
                        <small>Message will be sent to primary contacts of each participant.</small>
                    </div>
                    <hr>
                    @foreach($program->contributors as $contributor)
                        <div class="mt-3">
                            CC {{ $contributor->name }} Contacts
                            @forelse($contributor->organization->emailableContacts() as $contact)
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="contact_ids[]" value="{{ $contact->id }}" {{ $contact->hasEmail() ? 'checked' : 'disabled' }}>
                                        {{ $contact->name }} - {{ $contact->email }}
                                    </label>
                                </div>
                            @empty
                                No emailable contacts found.
                            @endforelse
                        </div>
                    @endforeach
                    <div class="form-row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="cc_address_1">Additional CC Address</label>
                            <input type="email"
                                class="form-control" name="cc_address_1" id="cc_address_1" aria-describedby="helpId" placeholder="john@example.com">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="cc_address_2">Additional CC Address</label>
                            <input type="email"
                                class="form-control" name="cc_address_2" id="cc_address_2" aria-describedby="helpId" placeholder="john@example.com">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" form="email_participants">Send Message</button>
            </div>
        </div>
    </div>
</div>
