@extends('layouts.iframe')
@section('title')
    Sessions
@endsection
@section('content')
    <div class="table-responsive">
        <table class="table bg-white">
            <tbody>
                @foreach ($programs as $program)
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
                                        {{ $program->site['name'] }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <a href="/programs/iframe/{{ $program['id'] }}" style="text-decoration:none;">View Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
