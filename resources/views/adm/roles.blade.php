@extends('layouts.adm')

@section('title','Roles page')

@section('content')
    <h1>{{ __('messages.roles_page') }}</h1>
    <form action="{{route('adm.roles.search')}}" method="GET">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">@</span>
            </div>
            <input type="text" name="search" class="form-control" placeholder="{{ __('messages.search') }}" aria-label="rolename" aria-describedby="basic-addon1">
            <button class="btn btn-success" type="submit">{{ __('messages.search') }}</button>
        </div>
    </form>
    <a class="btn btn-primary mb-2" href="{{route('adm.roles.create')}}">{{ __('messages.create_role') }}</a>
    <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">{{ __('messages.name') }}</th>
            <th>{{ __('messages.edit') }}</th>
          </tr>
        </thead>
        <tbody>
            @for($i=0;$i<count($roles); $i++)
                <tr>
                    <th scope="row">{{$i+1}}</th>
                    <td>{{$roles[$i]->name}}</td>
                    <td>
                        <a class="btn btn-primary" href="{{route('adm.roles.edit', $roles[$i]->id)}}">{{ __('messages.edit') }}</a>
                    </td>
                    <td>
                        <form method="post" action="{{route('adm.roles.destroy', $roles[$i]->id)}}">
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
