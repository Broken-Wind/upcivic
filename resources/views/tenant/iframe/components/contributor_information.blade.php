<div class="card">
    <div class="card-header">
        Contact Information
    </div>
    <div class="card-body">
        @forelse($program->contributors()->publiclyContactable()->get() as $contributor)
            <h3>{{ $contributor->name }}</h3>
            @if(!empty($contributor->phone))
                <i class="fas fa-fw fa-phone"></i> {{ $contributor->phone }}<br />
            @endif
            @if(!empty($contributor->email))
                <i class="fas fa-fw fa-envelope"></i> {{ $contributor->email }}<br />
            @endif
        @empty
            We couldn't find contact information for this program.
        @endforelse
    </div>
</div>
