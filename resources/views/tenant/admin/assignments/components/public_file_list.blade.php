@if($files->count() > 0)
<div class="row">
    <div class="col">
        <div>From {{ $organizationName }}: </div>
        @forelse($files as $file)
            <a href="{{ URL::signedRoute('tenant:assignments.public.download', [tenant()->slug, $file]) }}" class="ml-1">
                {{ $file->filename }} <i class="fas fa-download mr-1"></i>
            </a>
            <br />
        @empty
        @endforelse
    </div>
</div>
@endif
