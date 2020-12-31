<tr>
    <td>
        <input type="checkbox" name="organization_program_ids[{{ $organization->id }}][]" value="{{ $program['id'] }}" checked>
    </td>
    <td>
        <div class="row">
            <div class="col-auto">
                {{ $program['short_start_date'] . " - " . $program['short_end_date'] }}
            </div>
            <div class="col-auto">
                {{ $program['start_time'] . " - " . $program['end_time'] }}
            </div>
        </div>
    </td>
    <td>
        <a href="{{ tenant()->route('tenant:admin.programs.show', ['program' => $program->id]) }}">
            #{{ $program['id'] }} - {{ $program['internal_name'] }}
        </a>
    </td>
    <td>
        {{ $program['description_of_age_range'] }}
    </td>
    <td>
        {{ $program->site['name'] }}
    </td>
</tr>
