@extends('layouts.app')
@section('title', 'Proposals')
@section('content')

@push('scripts')
<script type="application/javascript">
    function toggle(source) {
        checkboxes = document.querySelectorAll('.bulk-action-checkbox');
        for(var i=0, n=checkboxes.length;i<n;i++) {
            checkboxes[i].checked = source.checked;
        }
    }
</script>

@endpush
<div class="container">
    @include('shared.form_errors')
    <form id="filters" action="{{ URL::current() }}" method="GET">
        @include('tenant.admin.programs.components.filters_modal')
    </form>

    @if($programsExist)
        @if(tenant()->isSubscribed())
            <form id="bulk_action" name="bulk_action" action="{{ tenant()->route('tenant:admin.programs.bulkAction') }}" method="POST">
                @csrf
            </form>
        @endif
        <div class="form-row mb-4">
            <div class="col-auto">

            @if($templateCount > 0)
                <a class="btn btn-primary" href="{{ tenant()->route('tenant:admin.programs.create') }}">Add Proposal</a>
                <a class="btn btn-secondary" href="{{ tenant()->route('tenant:admin.templates.index') }}">Program Templates</a>
            @endif
            @if(tenant()->isSubscribed())
                {{-- <button type="submit" class="btn btn-secondary" form="bulk_action" name="action" value="generate_loa">Generate LOAs</button> --}}
                <button type="submit" class="btn btn-secondary" form="bulk_action" name="action" value="export">Export to Excel</button>
                <div class="form-check form-check-inline mb-3 ml-3">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" onClick="toggle(this)" />
                        Select All Proposals
                    </label>
                </div>
                <br>
            @endif
            @if($templateCount == 0)
                Want to propose your own program? <a href="{{ tenant()->route('tenant:admin.templates.create') }}">Add a program</a>
            @endif
            </div>
            <div class="col text-right">
                <button type="button" class="btn btn-light" data-toggle="modal" data-target="#filterModal" form="filters">
                    Set Filters
                </button>
                <a href="{{ url()->current() }}" class="btn btn-light">
                    Reset Filters
                </a>

            </div>
        </div>
    @endif

    @forelse($programGroups as $startDate => $programs)
        <div class="card bg-light mb-3">
            <div class="card-header">
                <strong>Starting {{ $startDate }}</strong>
            </div>
            <div class="card-body pl-0 pr-0">
                @foreach($programs as $program)
                    @include('tenant.admin.programs.components.program_list_row', ['program' => $program])
                @endforeach
            </div>
        </div>
    @empty
        <p>Are you an activity provider?</p>
        <ul>
            <li><a href="{{ tenant()->route('tenant:admin.templates.create') }}">Add program</a>, then use it to submit a proposal</li>
            @if(tenant()->organization->templates->count() > 0)
                <li><a href="{{ tenant()->route('tenant:admin.programs.create') }}">Add proposal</a></li>
            @endif
        </ul>
        <p>Are you a host?</p>
        <ul>
            <li>If you host programs, ask your partners to propose programs to you via {{ config('app.name') }} using this link <a href="{{URL::to('/')}}">{{URL::to('/')}}</a></li>
        </ul>
    @endforelse
</div>
@endsection
