<div class="container-fluid mb-2 pb-0 alert bg-white">
    <div class="row">
        @if(tenant()->isSubscribed())
            <div class="col-md-1 align-self-center text-center">
                <input form="generate_loa" type="checkbox" name="program_ids[]" value="{{ $program['id'] }}">
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
                    &nbsp;
                </div>
                <div class="col-12">
                    {{ $program['description_of_age_range'] }}
                </div>
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
