@include('tenant.admin.programs.components.preview_program_modal')

<div class="col-6">
    <div class="row">
        <div class="alert {{ $program->class_string }}">
            {!! $program->status_description !!}
        </div>  
    </div>
    <div class="row mb-4">
        @if($program->canBePublished())
            <form method="POST" id="publish_program" action="{{ tenant()->route('tenant:admin.programs.published.update', [$program]) }}">
                @method('put')
                @csrf
                {{--
                <div class="form-group">
                    <label for="published_at">Publish On:</label>
                    <input type="date" class="form-control" name="published_at" id="published_at"
                        value="{{ !empty($program->getContributorFromTenant()['published_at']) ? $program->getContributorFromTenant()['published_at']->format('Y-m-d') : '' }}"
                        aria-describedby="published_at_help">
                    <small id="published_at_help" class="form-text text-muted">The date this program should be published</small>
                </div>

                <button type="submit" id="update_publish_date" name="update_publish_date" class="btn btn-secondary mx-1">Update </button>
                --}}
                @if($program->isPublished())
                    <button type="submit" id="unpublish_now" name="unpublish_now" value="1"
                            class="btn btn-secondary">Unpublish
                    </button>
                @else
                    <button type="submit" id="publish_now" name="publish_now" value="1"
                            class="btn btn-primary">Publish
                    </button>
                @endif
            </form>
        @endif

        @if(!$program->isProposalSent())
            <a class="btn btn-primary" href="" data-toggle="modal" data-target="#preview-program-modal">Preview & Send</a>
        @endif

        <form method="POST" action="{{tenant()->route('tenant:admin.programs.destroy', [$program])}}" id="delete-program">
            @csrf
            @method('DELETE')
                <button type="submit" form="delete-program" class="btn btn-danger ml-1" onClick="return confirm('Are you sure you want to cancel this proposal? This cannot be undone.')">Cancel</button>
        </form>

    </div>
</div>
