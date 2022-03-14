<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/css/home.css')}}">
    <script src="{{asset('/js/home.js')}}"></script>
    <title>ToDo</title>
</head>
<body>


    <!--add_todo_section -->
    <section class="add_todo_section">

        <div><span>+</span>ToDo</div>

        <p>
            <form method="post" action="">
            @csrf
                <p><input type="text" id="todo_content" name="todo_content"></p>
                <input type="datetime-local" id="deadline" name="deadline">
                <button type="submit"id="submit" name="add" value="+">+</button>
            </form>
        </p>

    </section>



    <section>

        @foreach ($items as $item)
            <p>
                <form method="post"><!--一括削除-->
                @csrf

                @if(property_exists($item,'mode'))
                    <input type="text" id="todo_content" name="todo_content" value="{{$item->todo_content}}"><!--未完-->
                    <input type="datetime-local" id="deadline" name="deadline" value="{{$item->deadline}}"><!--未完-->
                    <button type="submit"id="submit" name="update" value="{{ $item->id }}">完了</button><!--未完-->
                    <!--やめるボタン-->
                @else
                    <span>{{$item->todo_content}}</span>
                    <span>{{$item->deadline}}</span>
                    <button type="submit" formaction="" name="edit" value="{{$item->id}}">編集</button>
                    <button type="submit" formaction="" name="delete" value="{{$item->id}}">×</button><!--未完-->
                @endif
                </form>
            </p>
        @endforeach
    
    </section>


</body>
</html>