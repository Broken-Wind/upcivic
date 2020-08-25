 @if (isset($meetings) && count($meetings) > 0)
    <div class="table-responsive">
        <table class="table table-striped table-sm table-bordered text-center">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Site</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($meetings as $meeting)
                    <tr>
                        <td>{{ $meeting['start_date'] }}{{ $meeting['start_date'] != $meeting['end_date'] ? '-' . $meeting['end_date'] : '' }}</td>
                        <td>{{ $meeting['start_time'] . "-" . $meeting['end_time'] }}</td>
                        <td>{{ $meeting->site['name'] }}{!! $meeting->getLinkedPinHtml() !!}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
@else
    No meetings
@endif
