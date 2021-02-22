@include('tenant.admin.programs.components.preview_program_modal')
@include('tenant.admin.programs.components.cancel_program_modal')

<form method="POST" id="publish_program" action="{{ tenant()->route('tenant:admin.programs.published.update', [$program]) }}">
    @method('put')
    @csrf
</form>
<form method="POST" id="approve_program" action="{{tenant()->route('tenant:admin.programs.approve')}}">
    @csrf
    <input type="hidden" name="approve_program_id" value="{{ $program->id }}">
    <input type="hidden" name="contributor_id" value="approve_all">
</form>
<form method="POST" id="mark_sent" action="{{tenant()->route('tenant:admin.programs.mark_sent', [$program])}}">
    @csrf
</form>

<div class="row">
    <div class="col-12">
        <div class="alert {{ $program->class_string }}">
            {!! $program->status_description !!}
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col">
        @if($program->canBePublished())
            {{--
            <div class="form-group">
                <label for="published_at">Publish On:</label>
                <input type="date" class="form-control" name="published_at" id="published_at"
                    value="{{ !empty($program->getContributorFor(tenant())['published_at']) ? $program->getContributorFor(tenant())['published_at']->format('Y-m-d') : '' }}"
                    aria-describedby="published_at_help">
                <small id="published_at_help" class="form-text text-muted">The date this program should be published</small>
            </div>

            <button type="submit" id="update_publish_date" name="update_publish_date" class="btn btn-secondary mx-1">Update </button>
            --}}
            @if($program->isPublished())
                <button type="submit" id="unpublish_now" name="unpublish_now" form="publish_program" value="1"
                        class="btn btn-secondary">Unpublish
                </button>
            @else
                <button type="submit" id="publish_now" name="publish_now" form="publish_program" value="1"
                        class="btn btn-primary">Publish
                </button>
            @endif
        @endif
        @if($program->canBeApproved())
                <button type="submit" class="btn btn-secondary ml-1" form="approve_program" onClick="return confirm('Are you sure you want to mark this program as fully approved?');">Mark Fully Approved</button>
        @endif
        @if(!$program->isProposalSent() && $program->hasOtherContributors())
            <a class="btn btn-primary" href="" data-toggle="modal" data-target="#preview-program-modal">Preview & Send</a>
            <button type="submit" class="btn btn-secondary ml-1" form="mark_sent" onClick="return confirm('You cannot send a proposal email via {{ config('app.name') }} after marking as sent. Are you sure?');">Mark as Sent</button>
        @endif
        <button class="btn btn-danger" data-toggle="modal" data-target="#cancel-program-modal">Cancel Program</button>
    </div>
</div>
