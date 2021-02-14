<div class="card">
    <div class="card-header">Getting Enrolled</div>
    <div class="card-body">
        @if(!$program->hasEnrollmentInstructions() && !$program->hasEnrollmentUrl() && !$program->allowsRegistration())
            We couldn't find registration information for this program. Please contact the organizer.
        @endif
        @if($program->hasEnrollmentInstructions())
            {{ $program->enrollment_instructions }}
            @if($program->allowsRegistration() || $program->hasEnrollmentUrl() )
                <hr />
            @endif
        @endif
        @if($program->allowsRegistration())
            <form id="payment-form" action="{{tenant()->route('tenant:programs.orders.create', [$program])}}">
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
        @else
            @if($program->hasEnrollmentUrl())
                <form action="{{ $program->enrollment_url }}" method="GET" target="_blank">
                    <button type="submit" class="btn btn-primary btn-block">Register <i class="fas fa-fw fa-external-link-alt ml-2"></i></button>
                    <small class="form-text text-muted text-center">You will be redirected to the registration website.</small>
                </form>
            @endif
        @endif
    </div>
</div>


