@extends('layouts.app')
@section('title', $tenant->name . ' Settings')
@section('content')
    <div class="container">
        @include('shared.form_errors')
        <div class="card mb-4">
            <div class="card-header">Info</div>
            <div class="card-body">
                <form method="POST" action="{{ tenant()->route('tenant:admin.update') }}">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label for="createOrganization">Organization Name</label>
                        <input type="text" class="form-control" name="name" value="{{ $tenant['name'] }}"
                               placeholder="Acme Rec">
                    </div>
                    <div class="form-group">
                        <label for="createOrganization">Public Phone Number</label>
                        <input type="tel" class="form-control" name="phone" value="{{ $tenant['phone'] }}"
                               placeholder="555-444-5555">
                    </div>
                    <div class="form-group">
                        <label for="createOrganization">Public Email Address</label>
                        <input type="email" class="form-control" name="email" value="{{ $tenant['email'] }}"
                               placeholder="john@example.com">
                    </div>
                    <div class="form-group">
                      <label for="proposal_next_steps">Next Steps for Approved Proposals</label>
                      <textarea class="form-control" name="proposal_next_steps" id="proposal_next_steps" rows="3">{{ $tenant['proposal_next_steps'] }}</textarea>
                      <small class="text-muted">This will be included in an email to your partners any time you approve a program. You can leave this blank, or type something like "Don't forget to return your background check form!"</small>
                    </div>

                    <div class="form-group mb-3">
                        <label for="slug">Vanity URL</label>
                        <input type="text" class="form-control" value="{{ config('app.url') . "/" . $tenant['slug'] }}"
                               id="slug" aria-describedby="slugHelp" placeholder="acmerec" disabled>
                        <small class="text-muted" aria-label="slugHelp">Please contact support to change your vanity
                            URL.</small>
                    </div>
                    <button type="submit" class="btn btn-secondary">Update</button>
                </form>
            </div>
        </div>
        <div class="card mb-4" id="administrators">
            <div class="card-header">Administrators</div>
            <div class="card-body">
                <table class="table table-striped">
                    @forelse($tenant['aggregated_administrators'] as $administrator)
                        @if($loop->first)
                            <tr>
                                <td>Name</td>
                                <td>Email</td>
                            </tr>
                        @endif
                        <tr>
                            <td>{{ $administrator['name'] }}</td>
                            <td>{{ $administrator['email'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td>No administrators found.</td>
                        </tr>
                    @endforelse
                </table>
                <hr/>
                <strong><p>Add users to {{tenant()->name}}</p></strong>
                <form method="POST" action="{{ tenant()->route('tenant:admin.users.invites.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email">User Email</label>
                        <input type="text" class="form-control" name="email" id="email" aria-describedby="userEmail" placeholder="" value="{{ $email }}">
                    </div>
                    <button type="submit" id="submit" class="btn btn-secondary">Grant Access</button>
                </form>
            </div>
        </div>
        <div class="card mb-4" id="requesting-proposals">
            <div class="card-header">Requesting Proposals from Partners</div>
            <div class="card-body">
                To request proposals from your partners, send them the following instructions:
                <hr />
                <div id="proposal-instructions">
                    <strong>How to send proposals to {{ tenant()->name }} using {{ config('app.name') }}:</strong><br />
                    1. Sign up for your free account at {{ route('register') }}<br />
                    2. Add a program to use for sending proposals<br />
                    3. Once sent, {{ tenant()->name }} staff will review each program you propose, and get back to you!
                </div>
                <hr />
                <button type="button" class="btn btn-secondary" onClick="toClipboard('proposal-instructions')">Copy to Clipboard</button>
            </div>
        </div>
        <div class="card mb-4" id="publishing">
            <div class="card-header">Publishing</div>
            <div class="card-body">
                Embed this code snippet to your website to inform your users in <b>real-time</b> about upcoming programs schedules.
                <div class="bg-light my-2">
                    <code id="iframe-code">&lt;iframe src="{{ tenant()->route('tenant:iframe.index') }}" title="Scheduled programs"
                        style="width: 100%; min-height: 600px; height: 100%"&gt;&lt;/iframe&gt;</code>
                </div>
                <p>
                    <small class="text-muted">WordPress and other content management systems may require additional setup.
                        If you need assistance, email support@upcivic.com</small>
                </p>
                <button type="button" class="btn btn-secondary" onClick="toClipboard('iframe-code')">Copy to Clipboard</button>
                <hr/>
                <p> Preview </p>
                <iframe style="width: 100%; min-height: 600px; height: 100%"
                        src="{{ tenant()->route('tenant:iframe.index') }}" title="Scheduled programs"></iframe>
            </div>
        </div>
    </div>
    <script type="application/javascript">
        function toClipboard(elementId) {
            var range = document.createRange();
            range.selectNode(document.getElementById(elementId));
            window.getSelection().removeAllRanges(); // clear current selection
            window.getSelection().addRange(range); // to select text
            document.execCommand("copy");
            window.getSelection().removeAllRanges();// to deselect
            alert('Copied to clipboard.')
        }
    </script>
@endsection
