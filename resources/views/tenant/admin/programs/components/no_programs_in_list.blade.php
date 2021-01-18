
<p>Are you an activity provider?</p>
<ul>
    <li><a href="{{ tenant()->route('tenant:admin.templates.create') }}">Add program</a>, then use it to submit a proposal</li>
    @if(tenant()->organization->templates->count() > 0)
        <li><a href="{{ tenant()->route('tenant:admin.programs.create') }}">Add proposal</a></li>
    @endif
</ul>
<p>Are you a host?</p>
<ul>
    <li>If you host programs, ask your partners to propose programs to you via {{ config('app.name') }} using this link <a href="{{URL::to('/')}}">{{URL::to('/')}}</a></li>
</ul>
