<div class="card mb-4">
    <div class="card-header">Meetings <span class="text-muted ml-1">({{ $program->meetings->count() }} total)</span> </div>
    <div class="card-body">
        <form method="POST" action="{{ tenant()->route('tenant:admin.programs.meetings.update', [$program]) }}">
            @csrf
            @if(!$program->isEditable())
                <fieldset disabled="disabled"/>
            @endif
            <table class="table table-striped">
                @forelse($program->meetings->sortBy('start_datetime') as $meeting)
                    <tr>
                        <td><input type="checkbox" name="meeting_ids[]" value="{{ $meeting['id'] }}">
                        </td>
                        <td>{{ $meeting['start_date'] }}{{ $meeting['start_date'] != $meeting['end_date'] ? '-' . $meeting['end_date'] : '' }}</td>
                        <td>{{ $meeting['start_time'] . "-" . $meeting['end_time'] }}</td>
                        <td>{{ $meeting->site['name'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td>Error! Please contact support.</td>
                    </tr>
                @endforelse
            </table>
            <hr/>
            <div class="form-row">
                <div class="form-group">
                    <label for=meeting_start_time"">Start Time</label>
                    @if(!$program->isEditable())
                        <fieldset disabled="disabled"/>
                    @endif
                    <input type="time" class="form-control" name="start_time" id="" placeholder=""
                            value="">
                </div>

                <div class="form-group mx-1">
                    @if(!$program->isEditable())
                        <fieldset disabled="disabled"/>
                    @endif
                    <label for="meeting_end_time">End Time</label>
                    <input type="time" class="form-control" name="end_time" id="" placeholder=""
                            value="">
                </div>
                <div class="form-group mx-1">
                    @if(!$program->isEditable())
                        <fieldset disabled="disabled"/>
                    @endif
                    <label for="site_id">Site</label>
                    <select class="form-control" name="site_id">
                        <option value="">-----</option>
                        @foreach ( $sites as $site )
                            <option
                                value="{{ $site['id'] }}" {{ $program->site['id'] == $site['id'] ? 'selected' : '' }}>{{ $site['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    @if(!$program->isEditable())
                        <fieldset disabled="disabled"/>
                    @endif
                    <label for="shift_meetings">Shift Meeting Dates</label>
                    <div class="input-group">
                        <input type="number" class="form-control" name="shift_meetings" value="0">
                        <div class="input-group-append"><span class="input-group-text">days</span></div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                @if(!$program->isEditable())
                    <fieldset disabled="disabled"/>
                @endif
                <button type="submit" class="btn btn-secondary mr-1" name="update_all" value="update_all">
                    Update All
                </button>
                <button type="submit" class="btn btn-secondary" name="delete_meetings"
                        value=="delete_meetings">Delete Selected
                </button>
            </div>
        </form>

        {{--
        <hr/>
        <form method="POST"
                action="{{ tenant()->route('tenant:admin.programs.meetings.store', [$program]) }}">
            @csrf
            @if(!$program->isEditable())
                <fieldset disabled="disabled"/>
            @endif
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="new_meeting_start_datetime">Start Date & Time</label>
                    <input type="datetime-local" class="form-control" name="start_datetime"
                            value="{{ $program['next_meeting_start_datetime'] }}">
                </div>

                <div class="form-group col-md-3">
                    <label for="new_meeting_end_datetime">End Date & Time</label>
                    <input type="datetime-local" class="form-control" name="end_datetime"
                            value="{{ $program['next_meeting_end_datetime'] }}">
                </div>
                <div class="form-group col-md-3">
                    <label for="new_meeting_site_id">Site</label>
                    <select class="form-control" name="site_id">
                        <option value="">Site TBD</option>
                        @foreach ( $sites as $site )
                            <option value="{{ $site['id'] }}"
                            @if ($site['id'] == $program->site['id'])
                                {{ 'selected '}}
                                @endif
                            >{{ $site['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-secondary">Add</button>
        </form>
        --}}
    </div>
</div>
