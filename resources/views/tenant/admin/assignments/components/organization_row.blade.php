<tr>

    <td>{{ $organization->name }}</td>

    <td class="my-1 py-1">
        @forelse($assignments as $assignment)
        <a href="{{ tenant()->route('tenant:admin.assignments.edit', [$assignment])}}">
            <span class="organization-rectangle alert py-1 my-1 {{ $assignment->class_string }} organization-status" title="{{ $assignment->name }}">
                {{ $assignment->name }} <i class="fas fa-fw {{ $assignment->status_icon_string }}"></i>
            </span>
        </a>
        @empty
            <div class="alert alert-info py-1 my-1">No outstanding organization assignments.</div>
        @endforelse
    </td>

    <td class="{{ $instructors->isEmpty() ? 'my-1 py-1' : 'my-2 py-2' }}">
        @forelse($instructors as $instructor)
            @if ($isOutgoingFromTenant)
                <a href="{{ tenant()->route('tenant:admin.assignments.to.organizations.index', [$organization->id]) }}">
                    <span class="instructor-bubble {{ $instructor->getSelfClassStringFor($assignerOrganization)}}" title="{{ $instructor->name }}">{{ $instructor->initials }}</span>
                </a>
            @else
                <a href="{{ tenant()->route('tenant:admin.assignments.from.organizations.index', [$organization->id]) }}">
                    <span class="instructor-bubble {{ $instructor->getSelfClassStringFor($assignerOrganization)}}" title="{{ $instructor->name }}">{{ $instructor->initials }}</span>
                </a>
            @endif
        @empty
            @if($isOutgoingFromTenant)
                @if($organization->hasIncomingAssignmentsForInstructors())
                    <div class="alert py-1 my-1 alert-danger">{{ $organization->name }} has not assigned any instructors yet.</div>
                @else
                    <div class="alert py-1 my-1 alert-info">No outstanding instructor assignments.</div>
                @endif
            @else
                @if($organization->hasOutgoingAssignmentsForInstructors())
                    <div class="alert py-1 my-1 alert-danger">Please assign one or more instructors.</div>
                @else
                    <div class="alert py-1 my-1 alert-info">No outstanding instructor assignments from {{ $organization->name }}.</div>
                @endif
            @endif
        @endforelse
    </td>

    <td class="text-right">
        @if ($isOutgoingFromTenant)
            <a href="{{ tenant()->route('tenant:admin.assignments.to.organizations.index', [$organization->id]) }}">
                <i class="far fa-edit mr-2"></i>
            </a>
        @else
            <a href="{{ tenant()->route('tenant:admin.assignments.from.organizations.index', [$organization->id]) }}">
                <i class="far fa-edit mr-2"></i>
            </a>
        @endif
    </td>

</tr>
