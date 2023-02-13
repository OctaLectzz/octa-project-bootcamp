@extends('layouts.app')



@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush



@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">

                {{-- Alert --}}
                @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button  type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="card-body">
                    <table class="table" onsubmit="return confirm('Are You Sure?')">
                        <thead class="table table-dark table-hover">
                            <tr>
                                <th>No</th>
                                <th>Foto Profil</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th class="text-center">Status</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



@push('scripts')
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script>

        $(document).ready(function () {
            let dataTable = $('table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('user.list') }}",
                order: [],
                columns: [
                    { data: 'DT_RowIndex', sortable: false, searchable: false },
                    { data: 'images', sortable: false, searchable: false },
                    { data: 'name' },
                    { data: 'email' },
                    { data: 'status' },
                    { data: 'action', sortable: false },
                ],
            });
        });

        // function destroy(route) 
        // {
        //     $.ajax({
        //         url: route,
        //         type: 'POST',
        //         data: {
        //             "_METHOD": "DELETE",
        //             "_token": $('meta[name="csrf-token"].attr('content')'),
        //         },
        //         success: function(dataTable) {
        //             dataTable.ajax.reload();
        //         }
        //     });
        // };

    </script>
@endpush