<div class="card">
    <div class="card-header">
        Site Information
    </div>
    <div class="card-img-top text-center">
        <a href="https://www.google.com/maps/search/?api=1&query={{ $site['address'] }}" target="_blank">
            <img style="height: auto; width: 100%; display: block;" src="https://maps.googleapis.com/maps/api/staticmap?zoom=13&size=550x200&maptype=roadmap&markers=color:red%7C{{ $site['address'] }}&key=AIzaSyDwiEaE-xMvLPqkn2DMBOZzoEiGc3AraTA" alt="">
        </a>
    </div>
    <div class="card-body">
        <strong>Name:</strong> {{ $site['name'] }}<br />
        <strong>Address:</strong> {{ $site['address'] }}<br />
        <strong>Phone:</strong> {{ $site['phone'] }}<br />
    </div>

</div>
