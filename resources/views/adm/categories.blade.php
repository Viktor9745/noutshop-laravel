@extends('layouts.adm')

@section('title','categories page')

@section('content')
    <h1>{{ __('messages.cat_page') }}</h1>
    <form action="{{route('adm.categories.search')}}" method="GET">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">@</span>
            </div>
            <input type="text" name="search" class="form-control" placeholder="{{ __('messages.search') }}" aria-label="categoriename" aria-describedby="basic-addon1">
            <button class="btn btn-success" type="submit">{{ __('messages.search') }}</button>
        </div>
    </form>
    <a class="btn btn-primary mb-2" href="{{route('adm.categories.create')}}">{{ __('messages.create_cat') }}</a>
    <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">{{ __('messages.name') }}</th>
            <th scope="col">{{ __('messages.code') }}</th>
            <th>{{ __('messages.edit') }}</th>
          </tr>
        </thead>
        <tbody>
            @for($i=0;$i<count($categories); $i++)
                <tr>
                    <th scope="row">{{$i+1}}</th>
                    <td>{{$categories[$i]->name}}</td>
                    <td>{{$categories[$i]->code}}</td>
                    <td>
                        <a class="btn btn-primary" href="{{route('adm.categories.edit', $categories[$i]->id)}}">{{ __('messages.edit') }}</a>
                    </td>
                    <td>
                        <form method="post" action="{{route('adm.categories.destroy', $categories[$i]->id)}}">
                            @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit">{{ __('messages.delete') }}</button>
                        </form>
                    </td>
                </tr>
            @endfor
        </tbody>
      </table>
@endsection
