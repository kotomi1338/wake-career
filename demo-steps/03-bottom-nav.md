# STEP 3：BottomNav で完了タスク画面を追加

NativePHP の EDGE コンポーネントを使います。
HTML ではなく、iOS / Android がネイティブで持っている UI パーツです。

---

## 3-1. ルートを追加する

`routes/web.php` を開き、`/completed` のルートを1行追加します：

```php
Route::get('/completed', [TaskController::class, 'completed']);
```

---

## 3-2. コントローラにメソッドを追加する

`app/Http/Controllers/TaskController.php` を開き、`destroy` メソッドの後ろに追加します：

```php
public function completed()
{
    $tasks = Task::where('completed', true)->orderBy('updated_at', 'desc')->get();
    $count = $tasks->count();
    return view('tasks.completed', compact('tasks', 'count'));
}
```

---

## 3-3. 完了画面の Blade を作成する

```bash
touch resources/views/tasks/completed.blade.php
```

`resources/views/tasks/completed.blade.php` を開き、以下を貼り付けます：

```html
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
```

---

## 3-4. タスク一覧画面にも BottomNav を追加する

`resources/views/tasks/index.blade.php` を開き、`</html>` の後ろに以下を追記します：

```html
<native:bottom-nav>
    <native:bottom-nav-item id="tasks" icon="list" label="タスク" url="/" :active="true" />
    <native:bottom-nav-item id="completed" icon="check" label="完了" url="/completed" />
</native:bottom-nav>
```

※ 今回は両方のファイルに記載していますが、実際のアプリでは共通レイアウトにまとめると良いでしょう。

---

## 3-5. 動作確認

Simulator で以下を確認します：

1. 画面下部にネイティブのタブバーが表示される
2. 「完了」タブを押す → 完了件数が大きな緑の数字で表示され、完了タスクの一覧が並ぶ
3. タスク画面でチェックを入れて「完了」タブに戻る → 件数が増える

> 💡 **ポイント：** `<native:bottom-nav>` は HTML タグではありません。
> CSS も JavaScript も不要で、iOS / Android のネイティブタブバーが1タグで出ます。
> `icon="list"` は iOS では SF Symbols、Android では Material Icons に自動変換されます。
