<tr>

    <td>{{ $organization->name }}</td>

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
        @forelse($organization->assignedInstructors as $instructor)
            <span class="instructor-bubble alert-danger" title="{{ $instructor->name }}">{{ $instructor->initials }}</span>
        @empty
            <div class="alert-warning text-center organization-status">
                No instructors assigned.
            </div>
        @endforelse
    </td>

    <td class="text-right">
        @if ($isOutgoingFromTenant)
            <a href="{{ tenant()->route('tenant:admin.assignments.outgoing.organizations.index', [$organization->id]) }}">
                <i class="far fa-edit mr-2"></i>
            </a>
        @else
            <a href="{{ tenant()->route('tenant:admin.assignments.incoming.organizations.index', [$organization->id]) }}">
                <i class="far fa-edit mr-2"></i>
            </a>
        @endif
    </td>

</tr>
