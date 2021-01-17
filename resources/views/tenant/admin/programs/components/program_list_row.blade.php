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
                        {{ $program['id'] }} - {{ $program['internal_name'] }}
                    </a>
                    @endif
                </div>
                <div class="col-12">
                    Min/Max Enrollments: {{ $program['min_enrollments'] ?? 0 }}/{{ $program['max_enrollments'] ?? 0 }}
                </div>
                <div class="col-12">
                    <small>
                        {{ $program['description_of_age_range'] }}
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                <div class="col-12">
                    {{--
                    <div class="col-md-4">
                        <div class="form-group input-group mb-0 pb-0">
                            <input type="number" class="form-control form-control-sm" name="enrollments" value="{{ $program['min_enrollments'] ?? 0 }}">
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
                    --}}
                </div>
                <div class="col-12">
                        @if($program->hasUnstaffedMeetings())
                            <span class="bg-warning" title="This program has one or more unstaffed meetings!"><i class="fas fa-fw fa-exclamation-triangle"></i></span>
                        @endif
                        {{ $program->instructors->implode(', ') }}
                </div>
                @if($instructors->isNotEmpty())
                    <form action="{{ tenant()->route('tenant:admin.programs.instructors.update', ['program' => $program->id]) }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="col">
                                <select class="form-control form-control-sm" style="min-width:100px;" name="instructor_id">
                                    @foreach ( $instructors as $instructor )
                                        <option value="{{ $instructor['id'] }}">{{ $instructor['first_name'] . " " . $instructor['last_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <div class="btn-group">
                                    <button type="submit" class="btn btn-light btn-sm" name="action" value="remove_instructor"><i class="fas fa-fw fa-minus"></i></button>
                                    <button type="submit" class="btn btn-light btn-sm" name="action" value="add_instructor"><i class="fas fa-fw fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
        <div class="col-md-2 text-center">
            <div class="row">
                <div class="col-12">
                    <div class="{{ $program->class_string }} font-weight-bold">{{ $program->status_string }}</div>
                </div>
                <div class="col-12 text-left">
                    @if($program['proposed_at'] == null)
                    <form action="{{ tenant()->route('tenant:admin.programs.destroy', ['program' => $program->id]) }}" method="POST" id="delete_program_{{ $program->id }}">
                        @method('delete')
                        @csrf
                            <a href="{{ tenant()->route('tenant:admin.programs.edit', ['program' => $program->id]) }}">
                                <i class="far fa-edit"></i>
                            </a>
                            <button type="submit" class="btn btn-sm btn-link text-secondary" form="delete_program_{{ $program->id }}" onClick="return confirm('Are you sure you want to delete {{ $program["id"] }} - {{ $program["name"] }}?')">
                                <i class="far fa-trash-alt"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
