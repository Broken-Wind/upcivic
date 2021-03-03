<div class="container-fluid mb-2 pb-0 alert bg-white">
    <div class="row">
        @if(tenant()->isSubscribed())
            <div class="col-md-1 align-self-center text-center ">
                <input form="bulk_action" type="checkbox" name="program_ids[]" value="{{ $program['id'] }}" class="bulk-action-checkbox">
            </div>
        @endif
        <div class="col-md-3">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-auto">
                            {{ $program['short_start_date'] . " - " . $program['short_end_date'] }}
                        </div>
                        <div class="col-auto">
                            {{ $program['start_time'] . " - " . $program['end_time'] }}
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    {{ $program->site['name'] }}<br />
                    <small class="text-muted mt-0 pt-0">
                        @forelse($program->otherContributors() as $contributor)
                            {{ $contributor->organization['name'] }}
                        @empty
                        @endforelse
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                <div class="col-12">
                    @if ($program->isProposed())
                    <a href="{{ tenant()->route('tenant:admin.programs.show', ['program' => $program->id]) }}">
                        #{{ $program['id'] }} - {{ $program['internal_name'] }}
                    </a>
                    @else
                    <a href="{{ tenant()->route('tenant:admin.programs.edit', ['program' => $program->id]) }}">
                        #{{ $program['id'] }} - {{ $program['internal_name'] }}
                    </a>
                    @endif
                </div>
            </div>
            <div class="form-row align-items-center">
                <div class="col-10">
                    @include('tenant.admin.programs.components.enrollment_progress_bar')
                </div>
                <div class="col-2">
                    <a href="{{ tenant()->route('tenant:admin.programs.roster.edit', [$program]) }}" class="btn btn-sm btn-light">
                        <i class="fas fa-fw fa-id-card"></i>
                    </a>
                </div>
                    {{-- <div class="form-group input-group mb-0 pb-0">
                        <input type="number" class="form-control form-control-sm" name="enrollments" value="{{ $program->enrollments }}">
                        &nbsp;
                        <span style="margin-top:2px">/</span>
                        &nbsp;
                        <input type="number" class="form-control form-control-sm" name="max_enrollments" value="{{ $program->max_enrollments }}">
                        &nbsp;
                        <div class="btn-group">
                            <button onClick="return confirm('Do you really want to send an enrollment check email?');" type="submit" class="btn btn-light btn-sm" name="check_enrollments" value="{{ $program->id }}">
                                <i class="fas fa-fw fa-envelope"></i>
                            </button>
                            <button type="submit" class="btn btn-light btn-sm" name="update_enrollments[{{ $program->id }}]" value="update_enrollments"><i class="fas fa-fw fa-save"></i></button>
                        </div>
                    </div>
                    <small class="text-muted mt-0 pt-0">
                        {{ $program->enrollments_last_updated }}
                    </small> --}}
            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                <div class="col-12">
                    @if($program->hasUnstaffedMeetings())
                        <span class="bg-warning" title="This program has one or more unstaffed meetings!"><i class="fas fa-fw fa-exclamation-triangle"></i></span>
                    @endif
                    @include('tenant.admin.programs.components.instructor_linked_list', ['instructors' => $program->instructors])
                </div>
                <div class="col-12">
                    @if($instructors->isNotEmpty())
                        <button type="button" class="btn btn-light btn-sm manageInstructorsButton" data-program-id="{{ $program->id }}" data-toggle="modal" data-target="#manage-instructors-modal" form="filters">
                            Manage Instructors
                        </button>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-2 text-center">
            <div class="row">
                <div class="col-12">
                    <div class="{{ $program->class_string }} font-weight-bold">{{ $program->status_string }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
