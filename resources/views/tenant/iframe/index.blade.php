@extends('layouts.iframe')
@section('title')
    Sessions
@endsection
@section('content')
    <div class="table-responsive">
        <table class="table bg-white">
            <tbody>
                @forelse ($programs as $program)
                    <tr>
                        <td>
                            <div class="container text-center">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <strong>{{ $program->start_date }}-{{ $program->end_date }}</strong> {{ $program->start_time }}-{{ $program->end_time }}
                                    </div>
                                    <div class="col-sm-4">
                                        {{ $program['name'] }}
                                    </div>
                                    <div class="col-sm-4">
                                        {{ $program->site['name'] }}<br />
                                        <small>{{ $program->otherContributors()->pluck('name')->implode(', ') }}</small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                            <a href="{{ tenant()->route('tenant:iframe.show', ['program' => $program->id]) }}">View Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </tr>
                @empty
                    <tr>
                        <td>No programs added yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
