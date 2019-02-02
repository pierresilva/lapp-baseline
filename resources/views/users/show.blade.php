@extends('layouts.generator')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($users->name) ? $users->name : 'Users' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('users.users.destroy', $users->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('users.users.index') }}" class="btn btn-primary" title="Show All Users">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('users.users.create') }}" class="btn btn-success" title="Create New Users">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('users.users.edit', $users->id ) }}" class="btn btn-primary" title="Edit Users">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Users" onclick="return confirm(&quot;Delete Users??&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>id</dt>
            <dd>{{ $users->id }}</dd>
            <dt>name</dt>
            <dd>{{ $users->name }}</dd>
            <dt>email</dt>
            <dd>{{ $users->email }}</dd>
            <dt>active</dt>
            <dd>{{ ($users->active) ? 'Yes' : 'No' }}</dd>
            <dt>avatar</dt>
            <dd>{{ $users->avatar }}</dd>
            <dt>permission_user</dt>
            <dd>{{ $users->permission_user }}</dd>
            <dt>role_user</dt>
            <dd>{{ $users->role_user }}</dd>

        </dl>

    </div>
</div>

@endsection