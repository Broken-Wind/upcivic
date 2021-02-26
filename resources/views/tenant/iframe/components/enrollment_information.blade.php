<div class="card">
    <div class="card-header">Getting Enrolled</div>
    <div class="card-body">
        @if(!$contributor->hasEnrollmentInstructions() && !$contributor->hasEnrollmentUrl() && !$contributor->acceptsRegistrations())
            We couldn't find registration information for this program. Please contact the organizer.
        @endif
        @if($contributor->hasEnrollmentInstructions())
            {{ $contributor->enrollment_instructions }}
            @if($contributor->acceptsRegistrations() || $contributor->hasEnrollmentUrl() )
                <hr />
            @endif
        @endif
        @if($contributor->acceptsRegistrations())
            @if($program->isFull())
                <div class="alert alert-warning">
                    We're sorry, this program is full. Please contact the organizers for more information.
                </div>
            @else
                <form id="payment-form" action="{{tenant()->route('tenant:programs.orders.create', [$program])}}" target="_blank">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="inputGroupSelect01">${{ $program->formatted_price }} x</label>
                        </div>
                        <select class="form-control" name="numberOfSpots">
                            @for($i = 1; $i <= $program->maxTicketOrder(); $i++)
                                <option value="{{ $i }}"{{ $i == 1 ? ' selected' : '' }}>{{ $i }} Participant{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Enroll Now <i class="fas fa-fw fa-external-link-alt ml-2"></i></button>
                    <small class="form-text text-muted text-center">You will be redirected to the registration website.</small>
                </form>
            @endif
        @else
            @if($contributor->hasEnrollmentUrl())
                <form action="{{ $contributor->enrollment_url }}" method="GET" target="_blank">
                    <button type="submit" class="btn btn-primary btn-block">Register <i class="fas fa-fw fa-external-link-alt ml-2"></i></button>
                    <small class="form-text text-muted text-center">You will be redirected to the registration website.</small>
                </form>
            @endif
        @endif
    </div>
</div>


