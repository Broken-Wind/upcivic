@extends('layouts.app')

@push('css')
    <style>
        @font-face {
            font-family: Otto;
            src: url({{ asset('fonts/Otto.ttf') }}) format("truetype");
            font-weight: 400; // use the matching font-weight here ( 100, 200, 300, 400, etc).
            font-style: normal; // use the matching font-style here
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
        }
        .signature {
            font-family: Otto, Times, serif;
            font-size: 36px;
        }
        .page-break {
            page-break-after: always;
        }
        table {
            width: 100%;
        }
        th {
            text-align: left;
            padding: 5px;
        }
        td {
            padding: 5px;
        }
    </style>
@endpush
@section('title')
    Letter of Agreement
@endsection
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Document from {{ $assignment->assignedByOrganization->name }}</div>
        <div class="card-body">
            <h3>{{ $assignment->metadata['document_title'] }}</h3>
            {!! $assignment->metadata['document_text'] !!}
            <hr>
            <h3>Programs ({{ $programs->count() }} total)</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name & Location</th>
                    <th>Date & Time</th>
                    <th>Base Fee</th>
                </tr>
                @forelse($programs as $program)
                    <tr>
                        <td>{{ $program->id }}</td>
                        <td>
                            {{ $program->name }}<br />
                            {{ $program->site->name }} - {{ $program->location->name }}</td>
                        <td>
                            {{ $program['start_date'] . " - " . $program['end_date'] }}
                            <br />
                            {{ $program['start_time'] }}-{{ $program['end_time'] }}
                        </td>
                        <td>${{ $program->formatted_base_fee }} {{ $program->shared_invoice_type }}</td>
                    </tr>
                @empty
                @endforelse
            </table>
            <hr>
            <h3>Signatures</h3>

            <div class="signature-container alert alert-secondary mb-4">
                <h4>
                    {{ $assignment->assignedToOrganization->name }} Representative
                </h4>
                @if(!$assignment->isSignedByOrganization($assignment->assignedToOrganization))
                    <form method="POST" action="{{ tenant()->route('tenant:assignments.signatures.store', [$assignment]) }}">
                        @csrf
                        @include('shared.form_errors')
                        <input type="hidden" name="organization_id" value="{{ $assignment->assignedToOrganization->id }}">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Your signature" name="signature">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit" onClick="return confirm('Are you sure you want to sign this document?')">Apply Signature</button>
                            </div>
                        </div>
                        <small class="form-text text-muted">Type your name</small>
                    </form>
                @else
                    @if(isset($assignment->metadata['assigned_to_organization_signature']))
                        <span class="signature">
                            {{ $assignment->metadata['assigned_to_organization_signature']['signature'] ?? '' }}
                        </span>
                        <br>
                        <small class="text-muted">
                            {{ $assignment->metadata['assigned_to_organization_signature']['timestamp'] ?? '' }}
                            {{ $assignment->metadata['assigned_to_organization_signature']['ip'] ?? '' }}
                        </small>
                    @else
                        {{ $assignment->assignedByOrganization->name }} hasn't signed this document yet.
                    @endif
                @endif
            </div>
            <div class="signature-container alert alert-secondary">
                <h4>
                    {{ $assignment->assignedByOrganization->name }} Representative
                </h4>
                @if(isset($assignment->metadata['assigner_signature']))
                    <span class="signature">
                        {{ $assignment->metadata['assigner_signature']['name'] ?? '' }}
                    </span>
                    {{ $assignment->metadata['assigner_signature']['timestamp'] ?? '' }}
                    {{ $assignment->metadata['assigner_signature']['ip'] ?? '' }}
                @else
                    {{ $assignment->assignedByOrganization->name }} hasn't signed this document yet.
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
