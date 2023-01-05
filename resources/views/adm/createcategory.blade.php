@extends('layouts.adm')

@section('title','Create category page')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <form action="{{route('adm.categories.store')}}" method="post">
            @csrf
            <div class="form-group">
                <label for="nameInput">{{ __('messages.name') }}</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="nameInput" name="name" placeholder="Enter name">
                @error('name')
                    <div class="alert alert-danger">{{$message}}</div>
                @enderror
                </div>
                <div class="form-group">
                    <label for="codeInput">{{ __('messages.code') }}</label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="codeInput" name="code" placeholder="Enter code">
                    @error('code')
                        <div class="alert alert-danger">{{$message}}</div>
                    @enderror
                </div>
            <div class="form-group mt-3">
                <button type="submit" style="float:right;" class="btn btn-success">{{ __('messages.create_cat') }}</button>
            </div>
        </form>
@endsection
