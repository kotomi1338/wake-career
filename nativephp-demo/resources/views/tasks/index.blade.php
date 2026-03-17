<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>タスクメモ</title>
    <style>
        body { font-family: sans-serif; padding: 20px; max-width: 400px; margin: 0 auto; }
        input[type="text"] { width: 100%; padding: 10px; font-size: 16px; box-sizing: border-box; }
        button { padding: 10px 20px; font-size: 16px; margin-top: 8px; width: 100%; }
        ul { list-style: none; padding: 0; }
        li { padding: 12px; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 10px; }
        li.completed span { text-decoration: line-through; color: #aaa; }
        .delete-btn { margin-top: 0; width: auto; padding: 6px 12px; font-size: 14px; color: #fff; background: #e53e3e; border: none; border-radius: 4px; cursor: pointer; margin-left: auto; }
    </style>
</head>
<body>
    <h1>タスクメモ</h1>

    <form action="/tasks" method="POST">
        @csrf
        <input type="text" name="title" placeholder="タスクを入力..." required>
        <button type="submit">追加</button>
    </form>

    <ul>
        @foreach ($tasks as $task)
            <li class="{{ $task->completed ? 'completed' : '' }}">
                <form action="/tasks/{{ $task->id }}/toggle" method="POST" style="margin:0">
                    @csrf
                    <input type="checkbox"
                        onchange="this.form.submit()"
                        {{ $task->completed ? 'checked' : '' }}>
                </form>
                <span>{{ $task->title }}</span>
                <form action="/tasks/{{ $task->id }}/destroy" method="POST" style="margin:0">
                    @csrf
                    <button type="submit" class="delete-btn">削除</button>
                </form>
            </li>
        @endforeach
    </ul>
</body>
</html>
