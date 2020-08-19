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
            {{ $program['id'] }} - {{ $program['internal_name'] }}
        </div>
        <div class="col-md-3">
            <!-- Current instructors here -->
        </div>
        <div class="col-md-2">
            @if(tenant()->isPublic())
                @if($program->willPublish())
                    <i class="fas fa-fw fa-clock" title="Publishing on {{ $program->getContributorFromTenant()['published_at']->format('F d, Y') }}"></i>
                @elseif($program->isPublished())
                    <i class="fas fa-fw fa-globe" title="This program is published!"></i>
                @else
                    <i class="fas fa-fw fa-times" title="This program is not yet scheduled to publish."></i>
                @endif
            @endif
        </div>
    </div>

    <form action="{{ tenant()->route('tenant:admin.programs.enrollments.update', [$program])}}" method="POST">
        @method('PUT')
        @csrf
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
                        <!-- Enrollment check buttons
                            @if(!empty($program['enrollments_via']))
                                <a class="btn btn-light btn-sm" target="_blank" href="{{ $program['enrollments_via'] }}">
                                    <i class="fas fa-fw fa-external-link-alt"></i>
                                </a>
                            @else
                                <button onClick="return confirm('Do you really want to send an enrollment check email?');" type="submit" class="btn btn-light btn-sm" name="check_enrollments" value="{{ $program['id'] }}">
                                    <i class="fas fa-fw fa-envelope"></i>
                                </button>
                            @endif
                        -->
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
                <!-- action buttons here -->

                <a href="{{ tenant()->route('tenant:admin.programs.edit', ['program' => $program->id]) }}">
                    <svg width="1.2em" height="1.2em" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                    </svg>
                </a>

                <!-- Todo: Use a delete route to delete program if not proposed. -->
                <a href="{{ tenant()->route('tenant:admin.programs.edit', ['program' => $program->id]) }}" style="margin-left: 10px; color:red">
                    <svg width="1.2em" height="1.2em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                    </svg>
                </a>
                <!-- Bulk action checkbox:
                    <input form="bulk_action" type="checkbox" name="program_session_ids[]" value="{{ $program['id'] }}">
                -->
            </div>
        </div>
    </form>
</div>
