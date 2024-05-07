<!DOCTYPE html>
<html lang="en">

@include("layout")

<body>
    <main class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Редактор постов</div>
                        <div class="card-body">
                            @if(isset($post))
                            <h1>Редактирование поста</h1>
                            <form method="post" action="{{ route('post.update', $post->id) }}">
                                @csrf
                                @method('PUT')
                                <textarea name="post_content" id="editor">{{ $post->content }}</textarea>
                                <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                            </form>
                            @else
                            <h1>Создание нового поста</h1>
                            <form method="post" action="{{ route('post.store') }}">
                                @csrf
                                <textarea name="post_content" id="editor"></textarea>
                                <button type="submit" class="btn btn-primary">Создать пост</button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>
</body>

</html>