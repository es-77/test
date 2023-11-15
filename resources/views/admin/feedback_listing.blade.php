@extends('layouts.app')

@section('content')
    <div class="container-lg mt-4 mb-4 table-responsive">
        <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="th-sm">
                        Title
                    </th>
                    <th class="th-sm">
                        Description
                    </th>
                    <th class="th-sm">
                        Category
                    </th>
                    <th class="th-sm">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($feedBackies as $feedback)
                    <tr id="feedback_delete_{{ $feedback->id }}">
                        <td>{{ $feedback->title }}</td>
                        <td>{{ $feedback->description }}</td>
                        <td>{{ $feedback->category }}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-danger delete_feedback"
                                    data-id="{{ $feedback->id }}">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    no data found
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" id="theme-styles">
    <style>
        .container-lg {
            margin-top: 20px;
            margin-bottom: 20px;
            padding: 20px;
        }
    </style>
@endpush

@push('script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
    <!-- First, import jQuery, Popper.js, and Bootstrap JS -->
    {{-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script> --}}
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <!-- Then, import DataTables -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>



    <script>
        $(document).ready(function() {
            $('#dtBasicExample').DataTable();
            $('.dataTables_length').addClass('bs-select');
            $(".delete_feedback").on('click', function() {
                const feedback_id = $(this).attr('data-id');
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/feedback/' + feedback_id,
                            type: 'delete',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                $('#feedback_delete_' + feedback_id).addClass('d-none');
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Your file has been deleted.",
                                    icon: "success"
                                });
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                                Swal.fire({
                                    title: "Error!",
                                    text: "Unable to delete the feedback.",
                                    icon: "error"
                                });
                            }
                        });
                    }
                });
            })
        });
    </script>
@endpush
