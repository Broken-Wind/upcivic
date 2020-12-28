@if($files->count() > 0)
    <div class="row my-1">
        <div class="mr-1">From {{ $organizationName }}: </div>
        @forelse($files as $file)
            <a href="{{ tenant()->route('tenant:assignments.public.download', [$file]) }}" class="ml-1">
                {{ $file->filename }} <i class="fas fa-download mr-1"></i>
            </a>
            @if(!$loop->last) | @endif
        @empty
        @endforelse
    </div>
@endif