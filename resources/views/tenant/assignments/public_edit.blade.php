@extends('layouts.app')
@section('title', $assignment->name . ' - Assigned to ' . $assignment->assignee->name)
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

@section('content')
<div class="container">
    @include('shared.form_errors')

    @if($assignment->isSignableDocument())
        @include('tenant.assignments.signable_documents.components.document')
    @else
        @include('tenant.assignments.public_assignment')
    @endif
</div>
@endsection
