@if($program->shouldDisplayMap())
    <div class="card mt-3">
        <div class="card-img-top text-center">
            <a href="https://www.google.com/maps/search/?api=1&query={{ $site['address'] }}" target="_blank">
                <img style="height: auto; width: 100%; display: block;" src="https://maps.googleapis.com/maps/api/staticmap?zoom=13&size=550x200&maptype=roadmap&markers=color:red%7C{{ $site['address'] }}&key=AIzaSyDwiEaE-xMvLPqkn2DMBOZzoEiGc3AraTA" alt="">
            </a>
        </div>
        <div class="card-body">
            <strong>{{ $program->site->name }}</strong><br />
            {{ $program->site->address }}<br />
            @if(!empty($program->site->phone))
                <i class="fas fa-fw fa-phone"></i> {{ $program->site->phone }}
            @endif
        </div>
    </div>
@endif
