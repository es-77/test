@extends('layouts.app')

@section('content')
    <div class="row d-flex justify-content-center container-contact100">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-0 border" style="background-color: #f0f2f5;">
                <div class="card-body p-4">
                    @foreach ($feedbacks as $feedback)
                        <div class="card mb-4 ">
                            <div class="card-body">
                                <p><b>Title </b> : {{ $feedback->title }}</p>
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex flex-row align-items-center">
                                        <img src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/img%20(4).webp" alt="avatar"
                                            width="25" height="25" />
                                        <p class="small mb-0 ms-2"> <b>Category</b> : {{ $feedback->category }}</p>
                                    </div>
                                    <div class="d-flex flex-row align-items-center">
                                        <p class="small text-muted mb-0">
                                        <form action="{{ route('feedback.vote', $feedback->id) }}" method="post">
                                            @csrf
                                            <button class="btn btn-primary text-black mr-6" type="submit"
                                                data-toggle="tooltip" data-placement="top"
                                                title="Add Your Vote On Click This Button">Vote count:
                                                {{ $feedback->votes_count }}</button>
                                        </form>
                                        </p>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex flex-row align-items-center">
                                        <p>
                                            <button class="btn btn-primary text-black" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseExample_{{ $feedback->id }}" aria-expanded="false"
                                                aria-controls="collapseExample_{{ $feedback->id }}" data-toggle="tooltip"
                                                data-placement="top" title="Add Your Comment On Click This Button">
                                                Comments : {{ count($feedback->comments) }}
                                            </button>
                                        </p>
                                    </div>
                                    <div class="d-flex flex-row align-items-center">
                                        @if (auth()->user()->type == 'admin')
                                            <a class="btn text-black  text-black mr-6"
                                                href="{{ route('feedback.show', $feedback->id) }}" data-toggle="tooltip"
                                                data-placement="top" title="Feedback Details Click On Button">View
                                                Details</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="collapse" id="collapseExample_{{ $feedback->id }}">
                                    <div class="row">
                                        <div class="card shadow-0 border" style="background-color: #f0f2f5;">
                                            <div class="card-body p-4">
                                                <div class="form-outline">
                                                    <form method="post"
                                                        action="{{ route('comment.store', ['feedbackId' => $feedback->id]) }}">
                                                        @csrf
                                                        <textarea name="content" id="myeditorinstance">Write your comment here</textarea>
                                                        <button class="btn btn-primary text-black mr-6" type="submit"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="Send comment">Send</button>
                                                    </form>
                                                </div>
                                                @forelse ($feedback->comments as $comment)
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between">
                                                                <div class="d-flex flex-row align-items-center">
                                                                    <img src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/img%20(4).webp"
                                                                        alt="avatar" width="25" height="25" />
                                                                    <!-- ... Display user avatar, name, etc. ... -->
                                                                    <p class="small mb-0 ms-2"> Comment By :
                                                                        {{ $comment->user->name }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex flex-row align-items-center">
                                                                <p class="small text-muted mb-0">Comment <br>
                                                                    {!! $comment->content !!}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <p>No Comments For This Feedback</p>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $feedbacks->links() }}
            </div>
        </div>
    </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/animate/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/css-hamburgers/hamburgers.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/animsition/css/animsition.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/main.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
@endpush
@push('script')
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdn.rawgit.com/kevinchappell/tinymce-mention/master/dist/tinymce.mention.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-Gn5384xq1aoWXA+058RXP/63C+iiOWLC0aFxhrGsO4M=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-pzjw8zmz9ATKxIep9tiCxS/Z9fNfEX/hoj+2GGcL5D9gP1L2rCB6sFlOTWEI8A2u" crossorigin="anonymous">
    </script>

    <script>
        tinymce.init({
            selector: 'textarea#myeditorinstance',
            plugins: 'mention powerpaste advcode table lists checklist',
            toolbar: 'undo redo | blocks | bold italic | bullist numlist checklist | code | table',
            mentions: {
                source: function(query, success) {
                    // Your mention source logic here
                    var users = [{
                            username: 'user1',
                            display: 'User One'
                        },
                        {
                            username: 'user2',
                            display: 'User Two'
                        },
                        // Add more users as needed
                    ];

                    success(users);
                },
                itemRenderer: function(item, escape) {
                    return '<li>' +
                        '<span>' + escape(item.display) + '</span>' +
                        '</li>';
                }
            }
        });
    </script>
@endpush
