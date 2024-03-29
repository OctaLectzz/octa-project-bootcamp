@extends('layouts.app')


@section('content')

<div class="container">

  {{-- Title --}}
  <h1 class="text-center fw-bold">WELCOME TO THE BLOG PAGE</h1>
  <p class="mb-5 text-center fs-4">
    All Posts 
    @if ($categoriesName)
      in Category "{{ $categoriesName }}"
    @elseif ($tagsName)
      in Tag "{{ $tagsName }}"
    @else
      Page
    @endif
  </p>
  {{-- Title --}}

  
  {{-- Search --}}
  {{-- <div class="row justify-content-center mb-3">
    <div class="col-md-6">
        <form action="/">
          @if (request('category'))
          <input type="hidden" name="category" value="{{ request('category') }}">
          @endif
          @if (request('author'))
          <input type="hidden" name="author" value="{{ request('author') }}">
          @endif
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search..." name="search" value="{{ request('search') }}">
            <button class="btn btn-dark" type="submit">Search</button>
          </div>
        </form>
    </div>
  </div> --}}
  {{-- Search --}}


  {{-- Pinned Post --}}
  @if (!($categoriesName) && !($tagsName))
    <div id="carouselExampleCaptions" class="carousel slide mb-4" data-bs-ride="carousel">

      <div class="carousel-indicators">
        @foreach($pinnedPost as $index => $post)
          <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"></button>
        @endforeach
      </div>

      <div class="carousel-inner">
        @foreach ($pinnedPost as $post)
          <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
            
            {{-- View --}}
            <small class="text-light fs-3 m-2 ms-3 position-absolute view">
              <i class="fa fa-eye"></i> 
              @if ($post->views >= 1000000)
                {{ number_format($post->views / 1000000, 2) . 'm' }}
              @elseif ($post->views >= 1000)
                {{ number_format($post->views / 1000, 2) . 'k' }}
              @else
                {{ $post->views }}
              @endif  
            </small>
            
            <a href="{{ route('post.show', $post->slug) }}">
              {{-- Image --}}
              @if ($post->postImages)
                <img src="{{ asset('storage/postImages/' . $post->postImages) }}" class="img-fluid w-100 h-300" alt="{{ $post->postImages }}" style="filter: brightness(60%)">
              @else
                <img src="https://source.unsplash.com/1120x500" class="img-fluid w-100 h-300" alt="Unsplash" style="filter: brightness(60%)">
              @endif
            </a>
            
            <div class="carousel-caption d-none d-md-block">

              {{-- Title --}}
              <h4 class="fw-bold">{{ $post->title }}</h4>

              {{-- Content --}}
              <p>{{ Str::limit(strip_tags($post->content), 70, '...') }}</p>

              {{-- Category --}}
              @foreach($post->category as $category)
                <a href="{{ route('welcome', ['category' => $category->name]) }}" class="text-decoration-none">
                  <p class="d-inline-block mx-1 p-1 px-2 text-light" style="border: 1px solid; border-radius: 30px;">{{ $category->name }}</p>
                </a>
              @endforeach
            </div>
    
          </div>
        @endforeach
      </div>

      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
      
    </div>
  @endif
  {{-- Pinned Post --}}


  {{-- More Posts --}}
  <div class="container">
    <div class="row mb-5">
      <h1 class="mt-3">
        @if ($categoriesName)
          Posts in Category "{{ $categoriesName }}"
        @elseif ($tagsName)
          Posts in Tag "{{ $tagsName }}"
        @else
          All Posts
        @endif
      </h1><hr>

      @foreach ($posts as $post)
        <div class="col-md-4 mb-3">
          <div class="card card-hover">

            {{-- Image --}}
            <a href="{{ route('post.show', $post->slug) }}">
              @if ($post->postImages)
                <img src="{{ asset('storage/postImages/' . $post->postImages) }}" class="card-img-top" alt="{{ $post->postImages }}">
              @else
                <img src="https://source.unsplash.com/500x300" class="card-img-top" alt="Unsplash">
              @endif
            </a>

            <div class="card-body">
              {{-- Category --}}
              @foreach ($post->category->take(3) as $category)
                <a href="{{ route('welcome', ['category' => $category->name]) }}" class="text-decoration-none">
                  <p class="d-inline-block px-2 text-info" style="border: 1px solid; border-radius: 20%;">{{ $category->name }}</p>
                </a>
              @endforeach

              {{-- View --}}
              <small class="text-muted float-end">
                @if ($post->views >= 1000000)
                  {{ number_format($post->views / 1000000, 2) . 'm' }}
                @elseif ($post->views >= 1000)
                  {{ number_format($post->views / 1000, 2) . 'k' }}
                @else
                  {{ $post->views }}
                @endif
                <i class="fa fa-eye"></i>
              </small>

              {{-- Title --}}
              <a href="{{ route('post.show', $post->slug) }}" class="text-decoration-none text-dark">
                <h5 class="card-title">{{ Str::limit($post->title, 35, '..') }}</h5>
              </a>

              {{-- Created By & Created At --}}
              <p>
                <small class="text-muted">
                  By. <span class="text-info me-2">{{ $post->created_by }}</span> ◉ {{ $post->created_at->diffForHumans() }}
                </small>
              </p>

              {{-- Content --}}
              <a href="{{ route('post.show', $post->slug) }}" class="text-decoration-none text-dark">
                <p class="card-text">{{ Str::limit(strip_tags($post->content), 80, '...') }}</p>
              </a>

              {{-- Read More --}}
              <a href="{{ route('post.show', $post->slug) }}" class="btn btn-outline-dark mt-4">Read More</a>

              {{-- Like --}}
              <div class="float-end mt-4">
                @if (auth()->check() && $post->likes->where('user_id', auth()->id())->count() > 0)
                  <form action="{{ route('posts.unlike', $post->id) }}" method="post" class="add-comment">
                    @csrf
                    @method('delete')
                    <button type="submit" class="like-button btn btn-lg fa fa-heart text-danger">
                        <span class="text-dark">{{ $post->likes->count() }}</span>
                    </button>
                  </form>
                @else
                  <form action="{{ route('posts.like', $post->id) }}" method="post" class="add-comment">
                    @csrf
                    <button type="submit" class="like-button btn btn-lg fa fa-heart">
                        <span class="">{{ $post->likes->count() }}</span>
                    </button>
                  </form>
                @endif
              </div>
              
            </div>

          </div>
        </div>
      @endforeach

    </div>
  </div>
  {{-- More Posts --}}


  {{-- Pagination --}}
  @if ($post->is_pinned === 0)
    <nav aria-label="Page navigation example">
      <ul class="pagination pagination-dark justify-content-end">
        {{ $posts->links() }}
      </ul>
    </nav>
  @endif
  {{-- Pagination --}}

</div>


@push('scripts')
    <script src="{{ asset('js/post.js') }}"></script>
@endpush


@endsection