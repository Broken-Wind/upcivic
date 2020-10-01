<tr>

    <td>{{ $organization->name }}</td>

    <td>
        <div class="{{ $organization->getSelfStatusFor(tenant()->organization) }} text-center organization-status">
            {{ $assignments->whereNotNull('approved_at')->count() }} of {{ $assignments->count() }}
        </div>
    </td>

    <td class="">
        @forelse($instructors as $instructor)
            <span class="instructor-bubble {{ $instructor->getSelfClassStringFor(tenant()->organization)}}" title="{{ $instructor->name }}">{{ $instructor->initials }}</span>
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
