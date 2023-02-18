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

                {{-- Table --}}
                <div class="card-body">
                    <table class="table">
                        <thead class="table table-dark table-hover">
                            <tr>
                                <th>No</th>
                                <th>image</th>
                                <th>title</th>
                                <th>Content</th>
                                <th>Created By</th>
                                <th width="10%" class="text-center">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>


        </div>
    </div>
</div>

@include('includes.modal-delete')

@endsection



@push('scripts')
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        let userDatatable;

        $(document).ready(function () {
            userDatatable = $('table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('post.list') }}",
                order: [],
                columns: [
                    { data: 'DT_RowIndex', sortable: false, searchable: false },
                    { data: 'images' },
                    { data: 'title' },
                    { data: 'content' },
                    { data: 'created_by' },
                    { data: 'action', sortable: false },
                ],
            });
        });
        
        // function destroy(event) {
        //     event.preventDefault();

        //     $.ajax({
        //         url: event.target.action,
        //         type: event.target.method,
        //         data: {
        //             "_method": "DELETE",
        //             "_token": $('meta[name="csrf-token"]').attr('content'),
        //         },
        //         success: function(dataTable) {
        //             dataTable.ajax.reload();
        //         }
        //     });
        // };
        
        // function destroy(route) 
        // {
        //     $.ajax({
        //         url: route,
        //         type: 'POST',
        //         data: {
        //             "_METHOD": "DELETE",
        //             "_token": $('meta[name="csrf-token"]').attr('content'),
        //         },
        //         success: function(dataTable) {
        //             dataTable.ajax.reload();
        //         }
        //     });
        // };

    </script>
    <script src="{{ asset('js/user/delete.js') }}"></script>
@endpush