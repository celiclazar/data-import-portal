@extends('layouts.app')

@section('content')
    <div class="container">
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form id="importForm" action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="importType">Select Import Type:</label>
                <select id="importType" name="importType" class="form-control">
                    <option value="">-- Select Import Type --</option>
                    @foreach($config as $type => $settings)
                        @can($settings['permission_required'])
                            <option value="{{ $type }}">{{ $settings['label'] }}</option>
                        @endcan
                    @endforeach
                </select>
            </div>

            <div id="fileInputs">
                @foreach($config as $type => $settings)
                    @foreach($settings['files'] as $fileKey => $fileConfig)
                        @can($settings['permission_required'])
                            <div class="form-group" id="{{ $type }}_{{ $fileKey }}" style="display: none;">
                                <label for="{{ $fileKey }}">{{ $fileConfig['label'] }}</label>
                                <input type="file" name="{{ $fileKey }}" class="form-control">
                                <p>Required Headers: {{ implode(', ', array_keys($fileConfig['headers_to_db'])) }}</p>
                            </div>
                        @endcan
                    @endforeach
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary">Import</button>
        </form>
    </div>

    <script>
        document.getElementById('importType').addEventListener('change', function() {
            const fileInputs = document.getElementById('fileInputs').children;
            for (let i = 0; i < fileInputs.length; i++) {
                fileInputs[i].style.display = 'none';
            }
            const selectedType = this.value;
            if (selectedType) {
                const selectedInputs = document.querySelectorAll(`[id^="${selectedType}"]`);
                selectedInputs.forEach(input => {
                    input.style.display = 'block';
                });
                document.getElementById('importForm').action = "{{ url('data-import') }}/" + selectedType;
            } else {
                document.getElementById('importForm').action = "";
            }
        });
    </script>
@endsection
