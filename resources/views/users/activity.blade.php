@extends("layout")

@section("content")
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Моя активность</div>
                    @if(!$likes)
                        <p>У вас пока нет активности.</p>
                    @else
                        <ul class="list-unstyled mt-2 ms-1">
                            <div class="ms-3">{{ $likes->links("vendor.pagination.views.bootstrap-5") }}</div>
                            @foreach($likes as $like)

                                <li class="d-flex align-items-center mb-3">
                                    <a href="{{ route("profile", $like->user->name) }}" class="ms-1">
                                        <img src="{{ asset('storage/images/'.$like->user->image) }}" alt="Аватар"
                                             style="width: 100px; height: 100px; border-radius: 50%;">
                                    </a>
                                    <div class="ms-3">
                                        <a href="{{ route("profile", $like->user->name) }}"
                                           class="text-decoration-none">
                                            <h6 class="fs-5">{{ $like->user->name }}</h6>
                                        </a>
                                        <p class="text-muted mb-0">
                                        <p>
                                            Оценил(а) ваш пост от {{ $like->post->created_at->format('d.m.Y') }}:
                                            <a href="{{ route('post.comments', $like->post->id) }}"
                                               class="text-decoration-none">
                                                {{ Str::limit(strip_tags($like->post->content), 30) }}
                                            </a>
                                        </p>

                                        </p>
                                    </div>
                                </li>

                            @endforeach
                            <div class="ms-3">{{ $likes->links("vendor.pagination.views.bootstrap-5") }}</div>
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
