<div class="card mb-4">
    <div class="card-header">Registration Options</div>
    <div class="card-body">
        <form method="POST"
              action="{{ tenant()->route('tenant:admin.programs.update_registration_options', [$program]) }}">
            @method('put')
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="min_enrollments">Minimum Enrollments</label>
                    <input type="number" class="form-control" name="min_enrollments" placeholder="5"
                           value="{{ old('min_enrollments') ?: $program['min_enrollments'] }}">
                </div>
                <div class="form-group col-md-6">
                    <label for="max_enrollments">Maximum Enrollments</label>
                    <input type="number" class="form-control" name="max_enrollments" placeholder="12"
                           value="{{ old('max_enrollments') ?: $program['max_enrollments'] }}">
                </div>
            </div>
            <div class="form-check mb-3">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" name="internal_registration" id="internal_registration" value="1" {{ $program->internal_registration ? ' checked' : '' }}>
                    Register via {{ config('app.name') }}
                </label>
            </div>
            <div {{ !$program->internal_registration ? 'style=display:none;' : '' }} class="alert alert-info" id="internal_registration_details">
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
            </div>
            <div id="external_registration_details" {{ $program->internal_registration ? 'style=display:none;' : '' }}>
                <div class="form-group">
                <label for="price">Enrollment Website</label>
                <input type="text"
                    class="form-control" name="enrollment_url" id="enrollment_url" aria-describedby="enrollmentUrlHelp" value="{{ $program->suggested_enrollment_url }}" placeholder="https://my-registration-website.com">
                <small id="enrollmentUrlHelp" class="form-text text-muted">We'll direct participants to this link to register.</small>
                </div>
            </div>
            <div class="form-group">
                <label for="enrollmentInstructions">Enrollment Instructions</label>
                <textarea class="form-control" name="enrollment_instructions" id="enrollment_instructions" rows="3">{{ $program->enrollment_instructions }}</textarea>
            </div>
            <button type="submit" class="btn btn-secondary">Update</button>
        </form>
    </div>
</div>

<script type="application/javascript">
    let internalCheckbox = document.getElementById('internal_registration');
    internalCheckbox.addEventListener('click', function (evt) {
        if (evt.target.checked) {
            document.getElementById('internal_registration_details').style.display = 'block';
            document.getElementById('external_registration_details').style.display = 'none';
        } else  {
            document.getElementById('internal_registration_details').style.display = 'none';
            document.getElementById('external_registration_details').style.display = 'block';
        }
    });
</script>
