@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Manage Permissions for {{ $user->name }}</h1>

        <!-- Display current permissions -->
        <div class="mb-4">
            <h4>Current Permissions:</h4>
            <ul>
                @forelse($userPermissions as $permission)
                    <li>{{ $permission }}</li>
                @empty
                    <li>No permissions assigned</li>
                @endforelse
            </ul>
        </div>

        <!-- Form to update permissions -->
        <form action="{{ route('users.updatePermissions', $user) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="permissions">Select Permissions</label>
                <select name="permissions[]" class="form-control" multiple>
                    @foreach($permissions as $permission)
                        <option value="{{ $permission->name }}" {{ in_array($permission->name, $userPermissions) ? 'selected' : '' }}>
                            {{ $permission->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Permissions</button>
        </form>
    </div>
@endsection
