@extends('layouts.app')
@section('title', 'Program List')
@section('content')

@push('scripts')
<script type="application/javascript">
    var getProgramUrl = "{{ tenant()->route('tenant:api.programs.get_json') }}";
    var updateProgramInstructorsUrl = "{{ tenant()->route('tenant:admin.programs.index') }}";
    function toggle(source) {
        checkboxes = document.querySelectorAll('.bulk-action-checkbox');
        for(var i=0, n=checkboxes.length;i<n;i++) {
            checkboxes[i].checked = source.checked;
        }
    }
</script>
<script src="{{ asset('js/views/programs/index.js')}}"></script>
@endpush
<div class="container">
    @include('shared.form_errors')
    <form id="filters" action="{{ URL::current() }}" method="GET">
        @include('tenant.admin.programs.components.filters_modal')
    </form>
    @include('tenant.admin.programs.components.manage_instructors_modal')

    @if($programsExist)
        @if(tenant()->isSubscribed())
            <form id="bulk_action" name="bulk_action" action="{{ tenant()->route('tenant:admin.programs.bulkAction') }}" method="POST">
                @csrf
            </form>
        @endif
        <div class="form-row mb-4">
            <div class="col-auto">

            @if($templateCount > 0)
                <a class="btn btn-primary" href="{{ tenant()->route('tenant:admin.programs.create') }}">Add/Propose Program</a>
            @endif
            @if(tenant()->isSubscribed())
                {{-- <button type="submit" class="btn btn-secondary" form="bulk_action" name="action" value="generate_loa">Generate LOAs</button> --}}
                <button type="submit" class="btn btn-secondary" form="bulk_action" name="action" value="export">Export to Excel</button>
                <div class="form-check form-check-inline mb-3 ml-3">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" onClick="toggle(this)" />
                        Select All Programs
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

    @if($groupsIncludeArea)
        @include('tenant.admin.programs.components.program_list_with_areas')
    @else
        @include('tenant.admin.programs.components.program_list')
    @endif
</div>
@endsection
