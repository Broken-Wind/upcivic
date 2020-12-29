@if($files->count() > 0)
    <div class="row my-1">
        <div class="mr-1">From {{ $organizationName }}: </div>
        @forelse($files as $file)
            <a href="{{ $file->download_link }}" class="ml-1">
                {{ $file->filename }} <i class="fas fa-download mr-1"></i>
            </a>
            @if(Auth::check() && $file->canDelete(\Auth::user()))
                <form method="POST" action="{{ tenant()->route('tenant:admin.files.destroy', [$file]) }}" enctype="multipart/form-data" class="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-link text-danger py-0 my-0 pl-1" onClick="return confirm('Are you sure?')">
                        <i class="far fa-trash-alt"></i>
                    </button>
                </form>
            @endif
            @if(!$loop->last) | @endif
        @empty
        @endforelse
    </div>
@endif