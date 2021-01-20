@extends('layouts.app')

@section('title', 'Areas')

@section('content')
<div class="container">
    @include('shared.form_errors')

    <div class="card mb-4">
        <div class="card-header">Areas</div>

        <div class="card-body">
            <table class="table table-striped">
                @forelse($areas as $area)
                    <tr>
                        <td>
                            {{ $area->name }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td>
                            No areas yet.
                        </td>
                        <td></td>
                    </tr>
                @endforelse
            </table>
            <form action="{{ tenant()->route('tenant:admin.areas.store') }}" method="POST">
                @csrf
                <div class="form-group">
                  <label for="newArea">New Area</label>
                  <input type="text" class="form-control" name="name" id="newArea" aria-describedby="helpId" placeholder="North Winstock">
                </div>
                <button type="submit" class="btn btn-secondary">Add Area</button>
            </form>

        </div>
    </div>
</div>
@endsection
