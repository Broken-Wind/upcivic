
@forelse($instructors as $instructor)
    <a href="{{ tenant()->route('tenant:admin.instructors.show', [$instructor]) }}">{{ $instructor->first_name }}</a>
    @if(!$loop->last)
        ,&nbsp;
    @endif
@empty
@endforelse
