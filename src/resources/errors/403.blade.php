    @extends('laravelMain::' . $layout_file)
    @section('title', 'Create Menu')
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"> -->
    @endpush
    @section('content')
    <div class="container">

        <div class="row">
            <p class="text-danger text-center mt-4 ">You have no rights to perform this action</p>
        </div>

    </div>
    @endsection

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @endpush
