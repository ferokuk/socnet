@extends("layout")
@section("content")
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Список диалогов</div>
                    <div class="card-body">
                        @if ($dialogs->count() > 0)
                            <ul class="list-group">
                                @foreach ($dialogs as $dialog)
                                    <a href="{{ route("dialogue", $dialog["user"]->name) }}"
                                       class="text-decoration-none">
                                        <li class="list-group-item dialogue">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <img src="{{asset('storage/images/'.$dialog["user"]->image)}}"
                                                         class="rounded-circle"
                                                         style="width: 75px; height: 75px;">
                                                </div>
                                                <div>
                                                    @if($dialog['user']->name == auth()->user()["name"])
                                                        <h5>Избранное</h5>
                                                    @else
                                                        <h5>{{ $dialog['user']->name }}</h5>
                                                    @endif
                                                    <p>{{ $dialog['lastMessage']->created_at->format('d.m.Y H:i:s') }}</p>
                                                    <p>
                                                        @if ($dialog['lastMessage']->sender_id == Auth::id())
                                                            <img
                                                                src="{{ asset('storage/images/'.Auth::user()->image) }}"
                                                                alt="Your Avatar" class="rounded-circle"
                                                                style="width: 25px; height: 25px;">
                                                        @endif
                                                        <span>{{ Str::limit($dialog['lastMessage']->content, 60) }}</span>
                                                    </p>

                                                </div>
                                            </div>
                                        </li>
                                    </a>
                                @endforeach
                            </ul>
                        @else
                            <p>У вас нет диалогов.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<style>
    .dialogue:hover {
        background-color: rgba(75, 200, 243, 0.39);
    }
</style>
