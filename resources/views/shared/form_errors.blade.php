@if (count($errors))

    <div class="form-group">

        <div class="alert alert-danger">

            @foreach ($errors->all() as $error)
                @if($loop->last)
                    {{ $error }}
                @else
                    <p>{{ $error }}</p>
                @endif
            @endforeach

        </div>

    </div>

@endif

@if(session('success'))

    <div class="alert alert-success">{{ session('success') }}</div>

@endif
