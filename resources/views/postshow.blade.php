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
            <small class="text-muted fs-5 float-end mx-3">
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

            {{-- Category --}}
            <div class="d-flex justify-content-center">
                @foreach ($post->category as $category)
                <a href="{{ route('welcome', ['category' => $category->name]) }}" class="text-decoration-none mx-1">
                    <p class="d-inline-block px-2 text-info" style="border: 1px solid; border-radius: 20%;">{{ $category->name }}</p>
                </a>
                @endforeach
            </div>
            
            {{-- Image --}}
            @if ($post->postImages)
                <img src="{{ asset('storage/postImages/' . $post->postImages) }}" class="card-img-top w-100 mb-3 img-fluid" alt="{{ $post->postImages }}">
            @else
                <img src="https://source.unsplash.com/1120x500" class="card-img-top w-100 mb-3 img-fluid" alt="Unsplash">
            @endif

            {{-- Share --}}
            <div class="mb-2 ms-2 float-end">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}" target="_blank" class="btn btn-info" id="share-button"><i class="fa fa-share-square"></i>
                </a>
            </div>
            
            {{-- Save --}}
            <div class="mb-2 float-end">
                @if (Auth::check())
                    @if ($post->savedByUser(Auth::user()))
                        <form action="{{ route('posts.unsave', $post->id) }}" method="post" class="add-comment">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-warning" id="save-button">
                                <i class="fa fa-bookmark"></i> Unsave
                                {{-- <span id="like-count" class="text-dark fw-bold">{{ $post->saves->count() }}</span> --}}
                            </button>
                        </form>
                    @else
                        <form action="{{ route('posts.save', $post->id) }}" method="post" class="add-comment">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning" id="save-button">
                                <i class="fa fa-bookmark"></i> <span class="text-dark">Save</span>
                                {{-- <span id="like-count" class="text-dark fw-bold">{{ $post->saves->count() }}</span> --}}
                            </button>
                        </form>
                    @endif
                @endif
            </div>

            {{-- Like --}}
            <div class="mb-2">
                @if (auth()->check() && $post->likes->where('user_id', auth()->id())->count() > 0)
                    <form action="{{ route('posts.unlike', $post->id) }}" method="post" id="unlike-form" class="add-comment">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn text-danger" id="like-button">
                            <i class="fa fa-heart fs-4"></i>
                            <span id="like-count" class="text-dark fs-4">{{ $post->likes->count() }}</span>
                        </button>
                    </form>
                @else
                    <form action="{{ route('posts.like', $post->id) }}" method="post" id="like-form" class="add-comment">
                        @csrf
                        <button type="submit" class="btn" id="like-button">
                            <i class="fa fa-heart fs-4"></i>
                            <span id="like-count" class="fs-4">{{ $post->likes->count() }}</span>
                        </button>
                    </form>
                @endif
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
                        <form class="add-comment" action="{{ route('comments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            <div class="mb-3">
                                <label for="content" class="form-label">Add comment</label>
                                <textarea name="content" id="content" class="content form-control" maxlength="255" onkeyup="countCharacters()" required></textarea>
                                <small class="character-count text-muted fst-italic">255</small> <small class="text-muted fst-italic">Character Left</small>
                            </div>
                            <button type="submit" class="add-comment-button btn btn-dark">Submit</button>
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
                    @forelse($post->comments()->orderByDesc('created_at')->get() as $comment)
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

                                {{-- Reply Button --}}
                                @if (auth()->check())
                                    <div class="comment">
                                        <button class="btn-reply btn btn-sm btn-dark rounded-3" data-toggle="modal" data-target="#replyModal{{ $comment->id }}" data-bs-toggle="modal" data-bs-target="#replyModal{{ $comment->id }}">Reply</button>
                                    </div>
                                @endif

                                {{-- All Replies Comments --}}
                                @if(count($comment->replies))
                                    <hr>
                                    @include('includes.reply-comment')
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

@endsection


@push('scripts')
    <script>
        const successMessage = "{{ session()->get('success') }}";
            if (successMessage) {
                toastr.success(successMessage)
            }
    </script>

    <script>
        function countCharacters() {
            var maxLength = 255;
            var currentLength = document.querySelector(".content").value.length;
            var remainingLength = maxLength - currentLength;
            document.querySelector(".character-count").innerHTML = remainingLength;
        }
    </script>

    <script src="{{ asset('vendor/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('js/post.js') }}"></script>
@endpush