<div class="card mb-4">
    <div class="card-header">Enrollment Information</div>
    <div class="card-body">
        <form action="{{ tenant()->route('tenant:admin.programs.roster.update', [$program]) }}" method="POST">
            @csrf
            <div class="form-row">
                <div class="col-sm-4">
                    <div class="form-group">
                    <label for="min_enrollments">Minimum Enrollments</label>
                    <input type="number"
                        class="form-control" name="min_enrollments" id="min_enrollments" placeholder="5" value="{{ $program->min_enrollments }}">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                    <label for="minimim_enrollments">Current Enrollments</label>
                    <input type="number"
                        class="form-control" name="enrollments" id="enrollments" placeholder="10" value="{{ $program->enrollments }}" {{ $program->allowsRegistration() ? 'readonly' : '' }}>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                    <label for="minimim_enrollments">Maximum Enrollments</label>
                    <input type="number"
                        class="form-control" name="max_enrollments" id="max_enrollments" placeholder="12" value="{{ $program->max_enrollments }}">
                    </div>
                </div>
            </div>
            @if($program->allowsRegistration())
                <div class="form-group">
                <label for="price">Price to Register</label>
                <div class="input-group input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                    </div>
                    <input type="text"
                        class="form-control" name="price" id="price" aria-describedby="priceHelp" placeholder="20.00" value="{{ $program->formatted_price }}">
                    </div>
                    <small id="priceHelp" class="form-text text-muted">We'll charge participants this much to enroll.</small>
                </div>
            @else
                <div class="form-group">
                    <label for="enrollment_url">Enrollment URL</label>
                    <div class="input-group">
                        <input type="url" class="form-control" name="enrollment_url" value="{{ $program->enrollment_url }}" id="enrollment_url" aria-describedby="enrollment_url_help" placeholder="http://my-registration-website.org">
                        @if($program->hasEnrollmentUrl())
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <a href="{{ $program->enrollment_url }}" target="_blank">
                                        <i class="fas fa-fw fa-external-link-alt ml-2"></i>
                                    </a>
                                </span>
                            </div>
                        @endif
                    </div>
                    <small id="enrollment_url_help" class="form-text text-muted">We'll direct people to this URL to register.</small>
                </div>
            @endif
            <div class="form-group">
                <label for="enrollmentInstructions">Enrollment Instructions</label>
                <textarea class="form-control" name="enrollment_instructions" id="enrollment_instructions" rows="3">{{ $program->enrollment_instructions }}</textarea>
            </div>
            <button type="submit" class="btn btn-secondary">
                Update Enrollments
            </button>
        </form>
    </div>
</div>
