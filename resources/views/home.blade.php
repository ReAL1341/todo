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
    <h1>{{$current_channel}}</h1>


    <!--add_todo_section -->
    <section class="add_todo_section">
        <div><span>+</span>ToDo</div>

        <p>
            <form method="post" action="">
            @csrf
                <p><input type="text" id="todo_content" name="todo_content"></p>
                <input type="datetime-local" id="deadline" name="deadline">
                <input type="hidden" name="channel" value="{{$current_channel}}">
                <button type="submit" name="add" value="+">+</button>
            </form>
        </p>
    </section>


    <!-- channel_list_section -->   <!-- チャンネル移動、削除していいですか -->
    <section class="channel_list_section">
        <form method="post" action="">
        @csrf
            <input type="text" name="name">
            <button type="submit" name="add_channel" value="add_channel">+</button>
        </form>

        @foreach($channels as $channel)
            <form method="post" action="">
            @csrf
            @if($channel->name != 'やることリスト')
                <p>
                    <button type="submit" name="change_channel" value="{{$channel->name}}">{{$channel->name}}</button>
                    <button type="submit" name="channel_delete" value="{{$channel->name}}">×</button>
                </p>
            @else
                    <button type="submit" name="change_channel" value="{{$channel->name}}">{{$channel->name}}</button>
            @endif
            </form>
        @endforeach
    </section>


    <!-- todo_list_section -->
    <section class="todo_list_section">
        @foreach ($items as $item)
            <p>
                <form method="post"><!--一括削除-->
                @csrf

                @if(property_exists($item,'mode'))
                    <input type="text" id="todo_content" name="todo_content" value="{{$item->todo_content}}">
                    <input type="datetime-local" id="deadline" name="deadline" value="{{$item->deadline}}">
                    <input type="hidden" name="channel" value="{{$current_channel}}">
                    <button type="submit"id="submit" name="update" value="{{ $item->id }}">完了</button>
                    <!--やめるボタン-->
                @else
                    <span>{{$item->todo_content}}</span>
                    <span>{{$item->deadline}}</span>
                    <input type="hidden" name="channel" value="{{$current_channel}}">
                    <button type="submit" formaction="" name="edit" value="{{$item->id}}">編集</button>
                    <button type="submit" formaction="" name="delete" value="{{$item->id}}">×</button>
                @endif
                </form>
            </p>
        @endforeach
    </section>


</body>
</html>