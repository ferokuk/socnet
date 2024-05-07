@extends("layout")

@section("content")
    <div class="container">
        @if ($friends->count() > 0)
            <div class="ms-3">{{ $friends->links("vendor.pagination.views.bootstrap-5") }}</div>
            <ul class="list-group">
                @if(session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif
                <ul class="list-group">
                    @foreach ($friends as $friend)
                        <li class="list-group-item">
                            <form action="{{ $type=="FOLL"?route('friend.delete', $friend->id):route('friend.delete_sub', $friend->id) }}" method="POST" class="float-right">
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('profile', $friend->name) }}">
                                        <img src="{{asset('storage/images/'.$friend->image)}}"
                                             class="rounded-circle mr-2" width="100" height="100" alt="Avatar">
                                    </a>
                                    <span>
                                        <a href="{{ route('profile', $friend->name) }}" class="btn fs-4">
                                            {{ $friend->name }}
                                        </a>
                                    </span>
                                    @csrf
                                    @method('DELETE')
                                    @php
                                        $is_subscribed_back = DB::table("friendships")
                                        ->where("user_id", $friend->id)
                                        ->where("friend_id", auth()->id())
                                        ->exists();
                                        $is_subscribed = DB::table("friendships")
                                        ->where("user_id", auth()->id())
                                        ->where("friend_id", $friend->id)
                                        ->exists();
                                    @endphp
                                    @if($is_subscribed_back and $is_subscribed)
                                        <a href="{{route("dialogue", $friend->name)}}" class="btn btn-primary btn-sm">Сообщение</a>
                                    @endif
                                    <button type="submit"
                                            class="btn btn-danger ms-2 btn-sm">{{$type=="FOLL"?"Отписаться":"Удалить"}}</button>
                                </div>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </ul>
        @else
            @if($login == auth()->user()["name"])
                <p>У вас ещё нет пользователей, на которых вы подписаны</p>
            @else
                <p>У этого пользователя ещё нет пользователей, на которых он подписан</p>
            @endif
        @endif
    </div>
@endsection
