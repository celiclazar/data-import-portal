@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Assign Permission: {{ $permission->name }}</h1>
        <form action="{{ route('permissions.assignStore', $permission) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="users">Select Users</label>
                <select name="users[]" class="form-control" multiple>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Assign Permission</button>
        </form>
    </div>
@endsection
