<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>完了済みタスク</title>
    <style>
        body { font-family: sans-serif; padding: 20px; max-width: 400px; margin: 0 auto; }
        .count-section { text-align: center; padding: 40px 0 32px; }
        .count-number { font-size: 80px; font-weight: bold; color: #38a169; line-height: 1; }
        .count-label { font-size: 16px; color: #888; margin-top: 8px; }
        ul { list-style: none; padding: 0; }
        li { padding: 12px; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 10px; color: #aaa; }
        li span { text-decoration: line-through; }
    </style>
</head>
<body>
    <div class="count-section">
        <div class="count-number">{{ $count }}</div>
        <div class="count-label">件のタスクを完了しました</div>
    </div>

    <ul>
        @foreach ($tasks as $task)
            <li>
                <span>✓</span>
                <span>{{ $task->title }}</span>
            </li>
        @endforeach
    </ul>
</body>
</html>

<native:bottom-nav>
    <native:bottom-nav-item id="tasks" icon="list" label="タスク" url="/" />
    <native:bottom-nav-item id="completed" icon="check" label="完了" url="/completed" :active="true" />
</native:bottom-nav>
