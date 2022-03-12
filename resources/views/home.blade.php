<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/css/home.css')}}">
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
                <input type="submit" id="submit" value="+">
            </form>
        </p>

    </section>



    <section>
        @foreach ($items as $item)
           <p><span>{{ $item->todo_content }}</span> <span>{{ $item->deadline }}</span></p>
        @endforeach
    </section>


</body>
</html>