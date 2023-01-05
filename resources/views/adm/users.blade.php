@extends('layouts.adm')

@section('title','Users page')

@section('content')
    <h1>{{ __('messages.user_page') }}</h1>
    <form action="{{route('adm.users.search')}}" method="GET">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">@</span>
            </div>
            <input type="text" name="search" class="form-control" placeholder="{{ __('messages.search') }}" aria-label="Username" aria-describedby="basic-addon1">
            <button class="btn btn-success" type="submit">{{ __('messages.search') }}</button>
        </div>
    </form>
    <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">{{ __('messages.name') }}</th>
            <th scope="col">{{ __('messages.email') }}</th>
            <th scope="col">{{ __('messages.role') }}</th>
            <th>Ban</th>
            <th>{{ __('messages.edit') }}</th>
            <th>{{ __('messages.delete') }}</th>
          </tr>
        </thead>
        <tbody>
            @for($i=0;$i<count($users); $i++)
                <tr>
                    <th scope="row">{{$i+1}}</th>
                    <td>{{$users[$i]->name}}</td>
                    <td>{{$users[$i]->email}}</td>
                    <td>{{$users[$i]->role->name}}</td>
                    <td>
                      <form action="
                      @if($users[$i]->is_active)
                        {{route('adm.users.ban', $users[$i]->id)}}
                      @else
                        {{route('adm.users.unban', $users[$i]->id)}}
                      @endif
                      " method="post">
                        @csrf
                        @method('PUT')
                        <button class="btn {{$users[$i]->is_active ? 'btn-danger' : 'btn-success'}}" type="submit">
                          @if($users[$i]->is_active)
                            Ban
                          @else
                            Unban
                          @endif
                        </button>
                      </form>
                    </td>
                    <td>
                        <a class="btn btn-primary" href="{{route('adm.users.edit', $users[$i]->id)}}">{{ __('messages.edit') }}</a>
                    </td>
                    <td>
                        @if ($users[$i]->name!='admin')
                        <form method="post" action="{{route('adm.users.destroy', $users[$i]->id)}}">
                            @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit">{{ __('messages.delete') }}</button>
                        </form>
                        @endif
                    </td>
                </tr>
            @endfor
        </tbody>
      </table>
@endsection

