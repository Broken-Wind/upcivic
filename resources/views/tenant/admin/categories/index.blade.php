@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<div class="container">
    @include('shared.form_errors')

    <div class="card mb-4">
        <div class="card-header">Categories</div>

        <div class="card-body">
            <table class="table table-striped">
                @forelse($categories as $category)
                    <tr>
                        <td>
                            {{ $category->name }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td>
                            No categories yet.
                        </td>
                        <td></td>
                    </tr>
                @endforelse
            </table>
            <form action="{{ tenant()->route('tenant:admin.categories.store') }}" method="POST">
                @csrf
                <div class="form-group">
                  <label for="newCategory">New Category</label>
                  <input type="text" class="form-control" name="name" id="newCategory" aria-describedby="helpId" placeholder="STEM">
                </div>
                <button type="submit" class="btn btn-secondary">Add Category</button>
            </form>

        </div>
    </div>
</div>
@endsection
