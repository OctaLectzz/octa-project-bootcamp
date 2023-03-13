@extends('layouts.app')


@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/toastr/toastr.min.css') }}">
@endpush


@section('content')

<div class="container">


    {{-- Post Show --}}
    <div class="row justify-content-center mb-5">
        <div class="col-md-8">

            {{-- Title --}}
            <h1 class="mb-3">{{ $post->title }}</h1>

            {{-- View --}}
            <small class="text-muted fs-5 float-end m-3">
                <i class="fa fa-eye"></i> 
                @if ($post->views >= 1000000)
                  {{ number_format($post->views / 1000000, 2) . 'm' }}
                @elseif ($post->views >= 1000)
                  {{ number_format($post->views / 1000, 2) . 'k' }}
                @else
                  {{ number_format($post->views) }}
                @endif  
            </small>
              
            {{-- Created By --}}
            <p>By. <a href="/posts?user={{ $post->created_by }}" class="text-decoration-none">{{ $post->created_by }}</a></p>

            {{-- Like --}}
            <div class="d-flex align-items-center">
                @if (auth()->check() && $post->likes->where('user_id', auth()->id())->count() > 0)
                    <form action="{{ route('posts.unlike', $post->id) }}" method="post" id="unlike-form">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-lg fa fa-heart text-danger" id="like-button"></button>
                    </form>
                @else
                    <form action="{{ route('posts.like', $post->id) }}" method="post" id="like-form">
                        @csrf
                        <button type="submit" class="btn btn-lg fa fa-heart" id="like-button"></button>
                    </form>
                    @endif
                <p id="like-count" class="mb-0">{{ $post->likes->count() }}</p>
            </div>
            
            {{-- Image --}}
            @if ($post->postImages)
                <img src="{{ asset('storage/postImages/' . $post->postImages) }}" class="card-img-top w-100 mb-3 img-fluid" alt="{{ $post->postImages }}">
            @else
                <img src="https://source.unsplash.com/1120x500" class="card-img-top w-100 mb-3 img-fluid" alt="Unsplash">
            @endif

            {{-- Category --}}
            <div class="d-flex justify-content-center">
                @foreach ($post->category as $category)
                <a href="{{ route('welcome', ['category' => $category->name]) }}" class="text-decoration-none mx-1">
                    <p class="d-inline-block px-2 text-info" style="border: 1px solid; border-radius: 20%;">{{ $category->name }}</p>
                </a>
                @endforeach
            </div>

            {{-- Content --}}
            <article class="my-3 fs-5">
                {!!  $post->content  !!}
            </article>

            {{-- Back to Posts --}}
            <a href="{{ route('welcome') }}" class="btn btn-dark d-inline-block mt-3 mb-5"><i class="bi bi-arrow-bar-left"></i> Back to Posts</a><br>

        </div>
    </div>
    {{-- Post Show --}}


    {{-- Comment --}}
    <div class="row justify-content-center mb-3">
        <div class="col-md-8">
            <h1>Comments</h1><hr>

            {{-- Form Comment --}}
            @if (auth()->check())
                <div class="card my-3 mb-5">
                    <div class="card-body">
                        <form id="add-comment" action="{{ route('comments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            <div class="mb-3">
                                <label for="content" class="form-label">Add comment</label>
                                <textarea name="content" id="content" class="form-control" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-dark" id="comment-button">Submit</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="alert alert-danger mb-5">
                    Anda harus <a href="{{ route('login') }}" class="text-decoration-none">Login</a> terlebih dahulu untuk dapat mengomentari.
                </div>
            @endif

            {{-- All Comments --}}
            <div class="row mb-5">
                <div class="col-md-12">
                    @forelse($post->comments as $comment)
                        <div class="card mb-3">
                            <div class="card-body">
                                {{-- Dropdown --}}
                                <div class="dropdown float-end">
                                    @if(auth()->check() && auth()->user()->id == $comment->user_id)
                                        <button class="btn btn-default dropdown-toggle-no-arrow" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li>
                                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editCommentModal{{ $comment['id'] }}"><i class="bi bi-pencil me-1"></i> Edit Comment</button>
                                            </li>
                                            <hr class="dropdown-divider">
                                            <li>
                                                <form onsubmit="destroy(event)" action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item"><i class="bi bi-trash me-1"></i> Delete Comment</button>
                                                </form>
                                            </li>
                                        </ul>
                                    @endif
                                </div>
                                <div class="row">
                                    {{-- Profile Photo --}}
                                    <div class="col-md-2">
                                        <img src="{{ $comment->images }}" alt="User Avatar" class="rounded rounded-circle p-1 mb-2" width="70" height="70" style="border: 1px rgb(155, 155, 155) solid">
                                    </div>
                                    <div class="col-md-10 mt-2">
                                        {{-- Name --}}
                                        <h5 class="card-title fw-bold">{{ $comment->user->name }}</h5>
                                        {{-- Content --}}
                                        <p class="card-text">{{ $comment->content }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                {{-- Created At --}}
                                <div class="fw-bold float-end text-muted">{{ $comment->created_at->diffForHumans() }}</div>

                                <button type="button" class="btn btn-sm btn-dark btn-sm rounded-5 mx-3" data-bs-toggle="modal" data-bs-target="#replyModal{{ $comment->id }}">
                                    Reply
                                </button>
                                @if($comment->replies->count())
                                    @include('includes.reply-comment')
                                    <hr class="ms-4">
                                @endif
                            </div>
                        </div>
                        @include('includes.modal-delete')
                        @include('includes.modal-editcomment')
                        @include('includes.modal-replycomment')
                    @empty
                        <div class="alert alert-secondary">
                            No Comments yet.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
    {{-- Comment --}}


    {{-- Tag --}}
    <div class="row justify-content-center mb-5">
        <div class="col-md-8">
            @foreach ($post->tag as $tag)
                <a href="{{ route('welcome', ['tag' => $tag->name]) }}" class="text-decoration-none">
                <p class="d-inline me-1">#{{ $tag->name }}</p>
                </a>
            @endforeach
        </div>
    </div>
    {{-- Tag --}}
        
    
    {{-- More Posts --}}
    <div class="row mt-6">
        <h1 class="fw-bold">More Posts</h1><hr>
    </div>
    <div class="row justify-content-center">

        @foreach ($posts as $post)
            <div class="col-md-5 m-auto">
                <div class="row">
                    <div class="col-md-4">
                        <a href="{{ route('post.show', $post->slug) }}" class="text-decoration-none text-dark">
                            @if ($post->postImages)
                                <img src="{{ asset('storage/postImages/' . $post->postImages) }}" class="w-100 mt-3 mb-2 img-fluid card-img-top" alt="...">
                            @else
                                <img src="https://source.unsplash.com/400x300" class="w-100 mt-3 mb-2 img-fluid card-img-top" alt="...">
                            @endif
                        </a>

                        <small class="text-muted">
                            <i class="fa fa-eye"></i> 
                            @if ($post->views >= 1000000)
                                {{ number_format($post->views / 1000000, 1) . 'm' }}
                            @elseif ($post->views >= 1000)
                                {{ number_format($post->views / 1000, 1) . 'k' }}
                            @else
                                {{ $post->views }}
                            @endif  
                        </small>
                    </div>

                    <div class="col-md-8">
                        <a href="{{ route('post.show', $post->slug) }}" class="text-decoration-none text-dark">
                            <h3 class="fw-bold card-title">{{ Str::limit($post->title, 20, '...') }}</h3>
                        </a>

                        <p>
                            <small class="text-muted">By. <a href="" class="text-decoration-none me-2">{{ $post->created_by }}</a> ◉ {{ $post->created_at->diffForHumans() }}</small>
                        </p>

                        <a href="{{ route('post.show', $post->slug) }}" class="text-decoration-none text-dark">
                            <p class="card-title">{{ Str::limit(strip_tags($post->content), 80, '...') }}</p>
                        </a>
                    </div>
                    <hr>
                </div>
            </div>
        @endforeach

    </div>
    {{-- More Posts --}}


</div>



@push('scripts')
    <script>
        const successMessage = "{{ session()->get('success') }}";
            if (successMessage) {
                toastr.success(successMessage)
            }
    </script>

    <script src="{{ asset('vendor/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('js/comment.js') }}"></script>
@endpush



@endsection