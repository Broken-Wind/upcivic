
@forelse($assignments as $assignment)
    <div class="container border-bottom mb-2">
        <div class="row">
            <div class="col-2">
                <strong>{{ $assignment->name }}</strong>
            </div>
            <div class="col-6">{{ $assignment->description }}</div>
            <div class="col-2 text-center">
                <div class="{{ $assignment->class_string }} font-weight-bold">{{ $assignment->status_string }}</div>
            </div>
            <div class="col-2 text-right">
                <input class="mr-2" type="checkbox" name="markAsDone" onClick="return confirm('Mark as Done?')"/>
                <i class="far fa-bell mr-2"></i>
                <i class="far fa-trash-alt"></i>
            </div>
        </div>

        <div class="row my-1">
            <div class="col-2">
            </div>
            <div class="col-6 text-secondary">
                Attachments:
                <a href="/images/myw3schoolsimage.jpg" download="originalContract">
                    <i class="fas fa-download fa-xs"></i> originalContractFile.pdf
                </a>
                <strong>|</strong>
                <i class="far fa-trash-alt fa-xs text-danger"></i>
                <a href="/images/myw3schoolsimage.jpg" download="originalContract">
                    uploadedFile1.pdf
                </a>
                ,
                <i class="far fa-trash-alt fa-xs text-danger"></i>
                <a href="/images/myw3schoolsimage.jpg" download="originalContract">
                    uploadedFile2.pdf
                </a>
                ,
                <i class="fas fa-plus-circle fa-sm" data-toggle="tooltip" title="Upload required document"></i>
            </div>
        </div>

    </div>
@empty
    No tasks assigned yet.
@endforelse

