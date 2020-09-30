<tr>

    <td>{{ $organization }}</td>

    <td>
        @if($assignments->count() == 0)
            <div class="alert-warning text-center organization-status">
                No tasks assigned.
            </div>
        @else
            <div class="alert-danger text-center organization-status">
                {{ $assignments->whereNotNull('approved_at')->count() }} of {{ $assignments->count() }}
            </div>
        @endif
    </td>

    <td class="">
        <span class="instructor-bubble alert-danger" title="Calin Furau">CF</span>
        <span class="instructor-bubble alert-warning" title="Greg Intermaggio">GI</span>
        <span class="instructor-bubble alert-success" title="Netta Ravid">NR</span>
    </td>

    <td class="text-right">
        @if ($isOutgoingAssignments)
            <a href="{{ tenant()->route('tenant:admin.assignments.outgoing.organizations.index', [$assignments->first()->assigned_to_organization_id]) }}">
                <i class="far fa-edit mr-2"></i>
            </a>
        @else
            <a href="{{ tenant()->route('tenant:admin.assignments.incoming.organizations.index', [$assignments->first()->assigned_by_organization_id]) }}">
                <i class="far fa-edit mr-2"></i>
            </a>
        @endif
    </td>

</tr>
