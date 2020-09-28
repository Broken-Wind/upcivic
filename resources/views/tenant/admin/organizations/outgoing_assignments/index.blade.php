@extends('layouts.app')
@section('title', ' Assignments')
@section('content')
<div class="container">
    @include('shared.form_errors')
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="#">Tasks for Techsplosion</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Tasks for Redwood City </a>
        </li>
    </ul>
    <div class="card mb-3">
        <div class="card-header"><strong>General</strong></div>
        <div class="card-body">

            <div class="container">

                <div class="row">
                    <div class="col-2">
                        Sign Contract
                    </div>
                    <div class="col-6"> Bla provide everything that is needed </div>
                    <div class="col-2 text-center">
                        <div class="alert-success font-weight-bold">Done</div>
                    </div>
                    <div class="col-2 text-center">
                        <input type="checkbox" class="form-controll" id="exampleCheck1" onClick="return confirm('Mark as Done?')"/>
                        <i class="far fa-bell mx-3"></i>
                        <i class="far fa-trash-alt"></i>
                    </div>
                </div>

                <div class="row my-4">
                    <div class="col-3 border-right">
                        <a href="/images/myw3schoolsimage.jpg" download="originalContract">
                            originalContractFile.pdf <i class="fas fa-file-download fa-lg mx-2"></i> 
                        </a>
                    </div>
                    <div class="col-3">
                        Missing File <i class="fas fa-file-upload fa-lg mx-2"> </i>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header"><strong>Annette Meyer</strong></div>
        <div class="card-body"></div>
    </div>
</div>
@endsection