<div class="card-footer">
    <div class="row mb-1">
        <div class="col text-center">
            <strong class="text-uppercase">Organizers</strong>
        </div>
    </div>
    <div class="row">
        @forelse($program->contributors()->publiclyContactable()->get() as $contributor)
            <div class="col-lg-6">
                <div class="card mb-2">
                    <div class="card-body">
                        <strong>{{ $contributor->name }}</strong>
                        <hr>
                        @if(!empty($contributor->phone))
                            <i class="fas fa-fw fa-phone"></i> {{ $contributor->phone }}<br />
                        @endif
                        @if(!empty($contributor->email))
                            <i class="fas fa-fw fa-envelope"></i> {{ $contributor->email }}<br />
                        @endif
                    </div>
                </div>
            </div>
        @empty
        <div class="col">
            We couldn't find contact information for this program.
        </div>
        @endforelse
    </div>
</div>
