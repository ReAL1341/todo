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
            @error('todo_content')
                <p>{{$message}}</p>
            @enderror
            @error('deadline')
                <p>{{$message}}</p>
            @enderror

            <form method="post" action="">
            @csrf
                <p><input type="text" id="todo_content" name="todo_content" value="{{old('todo_content')}}"></p>
                <input type="datetime-local" id="deadline" name="deadline" value="{{old('deadline')}}">
                <input type="hidden" name="channel" value="{{$current_channel}}">
                <button type="submit" name="add" value="+">+</button>
            </form>
        </p>
    </section>


    <!-- channel_list_section -->   <!-- チャンネル移動、削除していいですか -->
    <section class="channel_list_section">
        @error('name')
            <p>{{$message}}</p>
        @enderror
        
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
        @if(property_exists($items,'mode'))
            <form method="post">
            @csrf
                @foreach($items as $item)
                    <p>
                        <input type="checkbox" id="{{$item->id}}" name="delete_items[]" value="{{$item->id}}">
                        <label for="{{$item->id}}">
                            <span>{{$item->todo_content}}</span>
                            <span>{{$item->deadline}}</span>
                        </label>
                    </p>
                @endforeach
                <p><button type="submit" name="delete_multi" value="{{$current_channel}}">削除</button></p>
            </form>
        @else
            <form method="post">
            @csrf
                    <button type="submit" name="delete_multi_request" value="{{ $current_channel }}">まとめて削除</button>
            </form>
            @foreach ($items as $item)
                <p>
                    <form method="post">
                    @csrf

                    @if(property_exists($item,'mode'))
                        <input type="text" id="todo_content" name="todo_content" value="{{$item->todo_content}}">
                        <input type="datetime-local" id="deadline" name="deadline" value="{{$item->deadline}}">
                        <input type="hidden" name="channel" value="{{$current_channel}}">
                        <button type="submit"id="submit" name="update" value="{{ $item->id }}">完了</button>
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
        @endif
    </section>


</body>
</html>