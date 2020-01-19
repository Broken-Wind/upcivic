<div class="container-fluid mb-2 pb-0 alert bg-white">
    <div class="row mb-2">
        <div class="col-md-3">
            <div class="row">
                <div class="col-auto">
                    <strong>
                        {{ $program['short_start_date'] . " - " . $program['short_end_date'] }}
                    </strong>
                </div>
                <div class="col-auto">
                    {{ $program['start_time'] . " - " . $program['end_time'] }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
                {{ $program['internal_name'] }}
        </div>
        <div class="col-md-3">
            <!-- Current instructors here -->
        </div>
        <div class="col-md-2">
            #{{ $program['id'] }}
        </div>
    </div>

    <form action="" method="POST">
       @method('PATCH')
        @csrf
        <input type="hidden" name="program_session_id" value="{{ $program['id'] }}">
        <div class="row">
            <div class="col-md-3">
                {{ $program->site['name'] }}<br />
                <small class="text-muted mt-0 pt-0">
                    @forelse($program->otherContributors() as $contributor)
                        {{ $contributor->organization['name'] }},
                    @empty
                    @endforelse
                    {{ $program['description_of_age_range'] }}
                </small>
            </div>
            <div class="col-md-4">
                <div class="form-group input-group mb-0 pb-0">
                    <input type="number" class="form-control form-control-sm" name="enrollments" value="{{ $program['enrollments'] ?? 0 }}">
                    &nbsp;
                    <span style="margin-top:2px">/</span>
                    &nbsp;
                    <input type="number" class="form-control form-control-sm" name="max_enrollments" value="{{ $program['max_enrollments'] ?? 0 }}">
                    &nbsp;
                    <div class="btn-group">
                        @if(!empty($program['enrollments_via']))
                            <a class="btn btn-light btn-sm" target="_blank" href="{{ $program['enrollments_via'] }}">
                                <i class="fas fa-fw fa-external-link-alt"></i>
                            </a>
                        @else
                            <button onClick="return confirm('Do you really want to send an enrollment check email?');" type="submit" class="btn btn-light btn-sm" name="check_enrollments" value="{{ $program['id'] }}">
                                <i class="fas fa-fw fa-envelope"></i>
                            </button>
                        @endif
                    <button type="submit" class="btn btn-light btn-sm" name="update_enrollments[{{ $program['id'] }}]" value="update_enrollments"><i class="fas fa-fw fa-save"></i></button>
                    </div>
                </div>
                <small class="text-muted mt-0 pt-0"><!-- Enrollments last updated here --></small>
            </div>
            <div class="col-md-3">
                <div class="form-row">
                    <!-- Add instructors here -->
                </div>
            </div>
            <div class="col-md-2">
                <!-- Status icon here & action buttons here -->

                <a href="{{ tenant()->route('tenant:admin.programs.edit', ['program' => $program->id]) }}">Edit Program</i></a>

                <!-- Bulk action checkbox:
                    <input form="bulk_action" type="checkbox" name="program_session_ids[]" value="{{ $program['id'] }}">
                -->
            </div>
        </div>
    </form>
</div>
