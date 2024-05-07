@extends("layout")

@section("content")
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    @if($otherUser->name == auth()->user()["name"])
                        <div class="card-header">Избранное</div>
                    @else
                        <div class="card-header">Диалог с {{ $otherUser->name }}</div>
                    @endif
                    <div class="card-body adaptive-dialogue" id="messages-container">
                        <ul class="list-group">
                            @foreach ($messages as $message)
                                <div class="message">
                                    <div class="message-content">
                                        <div class="user-info">
                                            <div>
                                                @if($message->sender->name == Auth::user()["name"])
                                                    <a href="{{route("me")}}" class="text-decoration-none">
                                                        <img src="{{asset("storage/images/".$message->sender->image)}}"
                                                             alt="Avatar"
                                                             class="rounded-circle me-2 cursor-pointer"
                                                             style="width: 50px; height: 50px;">
                                                    </a>
                                                    <a href="{{route("me")}}" class="text-decoration-none">
                                                        {{ $message->sender->name }}
                                                    </a>
                                                @else
                                                    <a href="{{route("profile", $message->sender->name)}}">
                                                        <img src="{{asset("storage/images/".$message->sender->image)}}"
                                                             alt="Avatar"
                                                             class="rounded-circle me-2 cursor-pointer"
                                                             style="width: 50px; height: 50px;">
                                                    </a>
                                                    <a href="{{route("profile", $message->sender->name)}}"
                                                       class="text-decoration-none">
                                                        {{trim($message->sender->name)}}
                                                    </a>
                                                @endif

                                                {{ $message->created_at->format('H:i:s') }}
                                            </div>
                                            <p>{{ $message->content }}</p>

                                        </div>
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                            <div class="mb-5"></div>
                        </ul>
                    </div>

                    <div class="card-footer">
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <div class="d-flex justify-content-center">
                            <form method="POST" action="{{ route('message.send') }}" style="width: 100%;">
                                @csrf
                                <div class="form-group">
                                    <label for="content">Новое сообщение:</label>
                                    <textarea class="form-control" id="content" name="content" rows="3"></textarea>
                                </div>
                                <input type="hidden" name="receiver_id" value="{{ $otherUser->id }}">
                                <button type="submit" class="btn btn-primary">Отправить</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Прокручиваем вниз при загрузке страницы
        window.scrollTo(0, document.body.scrollHeight);
    });
</script>

