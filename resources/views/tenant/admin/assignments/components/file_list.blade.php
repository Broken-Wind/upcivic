@if($files->count() > 0)
    <div class="mr-1">From {{ $organizationName }}:</div>
    @forelse($files as $file)
        @if(Auth::check() && $file->canDelete(\Auth::user()))
            <form method="POST" action="{{ tenant()->route('tenant:admin.files.destroy', [$file]) }}" enctype="multipart/form-data" id="delete-file">
                @csrf
                @method('DELETE')
            </form>
        @endif
        <a href="{{ $file->download_link }}" class="ml-1">
            {{ $file->filename }} <i class="fas fa-fw fa-download mr-1"></i>
        </a>
        @if(Auth::check() && $file->canDelete(\Auth::user()))
            <button type="submit" class="btn btn-link text-danger py-0 my-0 pl-1" onClick="return confirm('Are you sure?')" form='delete-file'>
                <i class="fas fa-fw fa-trash-alt"></i>
            </button>
        @endif
        <br />
    @empty
    @endforelse
@endif
