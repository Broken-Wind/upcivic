<tr>

    <td>{{ $organization->name }}</td>

    <td>
        @forelse($assignments as $assignment)
            <span class="organization-rectangle {{ $assignment->class_string }} organization-status">{{ $assignment->acronyms }}</span>
        @empty
        @endforelse
    </td>

    <td class="">
        @forelse($instructors as $instructor)
            <span class="instructor-bubble {{ $instructor->getSelfClassStringFor($assignerOrganization)}}" title="{{ $instructor->name }}">{{ $instructor->initials }}</span>
        @empty
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
