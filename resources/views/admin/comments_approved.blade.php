@extends('layouts.app')

@section('content')
    <div class="container-lg table-responsive">
        <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="th-sm">
                        Name
                    </th>
                    <th class="th-sm">
                        Email
                    </th>
                    <th class="th-sm">
                        Content
                    </th>
                    <th class="th-sm">
                        Comment Date
                    </th>
                    <th class="th-sm">
                        Is Approved
                    </th>
                    <th class="th-sm">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($comments as $comment)
                    <tr id="comment_delete_{{ $comment->id }}">
                        <td>{{ $comment->user->name }}</td>
                        <td>{{ $comment->user->email }}</td>
                        <td>{{ $comment->content }}</td>
                        <td>{{ $comment->created_at->diffForHumans() }}</td>
                        <td><span class="badge badge-pill badge-info"
                                id="unapproved_text_{{ $comment->id }}">UnApproved</span> </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-success comment_approved"
                                    data-id="{{ $comment->id }}">Approved</button>
                                <button type="button" class="btn btn-danger delete_comment"
                                    data-id="{{ $comment->id }}">Delete</button>
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
            $(".delete_comment").on('click', function() {
                const comment_id = $(this).attr('data-id');
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
                            url: '/comments/' + comment_id,
                            type: 'delete',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                $('#comment_delete_' + comment_id).addClass('d-none');
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
                                    text: "Unable to delete the user.",
                                    icon: "error"
                                });
                            }
                        });
                    }
                });
            })

            $('.comment_approved').on('click', function() {
                const comment_id = $(this).attr('data-id');
                $.ajax({
                    url: '/comments/' + comment_id,
                    type: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        _method: 'PUT', // Add this line to override the method to PUT
                        tab: "approved"
                    },
                    success: function(response) {
                        $('#unapproved_text_' + comment_id).text('Approved').removeClass(
                            'badge-info').addClass('badge-success');
                        Swal.fire({
                            title: "Update",
                            text: "Comment Approved",
                            icon: "success"
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            title: "Error!",
                            text: "Unable to delete the user.",
                            icon: "error"
                        });
                    }
                });
            })
        });
    </script>
@endpush
