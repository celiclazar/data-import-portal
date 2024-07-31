@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $importedFile->file_name }}</h1>

        <div class="form-group">
            <input type="text" id="search" class="form-control" placeholder="Search...">
        </div>

        <table class="table table-bordered">
            <thead>
            <tr>
                @foreach($config['files'][array_key_first($config['files'])]['headers_to_db'] as $header => $dbField)
                    <th>{{ $header }}</th>
                @endforeach
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $row)
                <tr>
                    @foreach($config['files'][array_key_first($config['files'])]['headers_to_db'] as $header => $dbField)
                        <td>{{ $row->$dbField }}</td>
                    @endforeach
                    <td>
                        @can($config['permission_required'])
                            <form action="{{ route('data.delete', ['id' => $row->id, 'type' => $importedFile->import_type]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>


        {{ $data->links() }}

        <button id="export" class="btn btn-success">Export</button>
    </div>

    <script>
        document.getElementById('search').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });

        document.getElementById('export').addEventListener('click', function() {
            let filter = document.getElementById('search').value;
            window.location.href = "{{ route('data.export', ['id' => $importedFile->id]) }}?filter=" + encodeURIComponent(filter);
        });
    </script>
@endsection
