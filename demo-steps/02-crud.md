# STEP 2：デモアプリ実装〜動作確認

---

## 2-1. Task モデルとマイグレーションを作成する

```bash
php artisan make:model Task -m
```

---

## 2-2. マイグレーションファイルを編集する

`database/migrations/xxxx_create_tasks_table.php` を開き、`up()` メソッドを以下に書き換えます：

```php
public function up(): void
{
    Schema::create('tasks', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->boolean('completed')->default(false);
        $table->timestamps();
    });
}
```

---

## 2-3. マイグレーションを実行する

```bash
php artisan migrate
```

---

## 2-4. Task モデルに fillable を追加する

`app/Models/Task.php` を開き、クラスに以下を追加します：

```php
protected $fillable = ['title', 'completed'];
```

**`$fillable` とは？**

`Task::create(['title' => $request->title])` のように、外部から受け取ったデータを一括でDBに保存するとき、Laravel は「どのカラムへの書き込みを許可するか」を事前に明示することを求めます。これを **Mass Assignment（一括代入）保護** といいます。

`$fillable` に書いたカラム名だけが `create()` や `fill()` で書き込み可能になります。書いていないカラムは無視されるため、意図しないカラムを外部から上書きされる攻撃（例：`is_admin=1` を混ぜ込むなど）を防げます。

```php
// 例
// $fillable に 'title' が含まれているので保存される
Task::create(['title' => 'タスク名']);

// $fillable に書いていないカラムは無視される（エラーにはならない）
Task::create(['title' => 'タスク名', 'is_admin' => true]);
```

---

## 2-5. Blade ビューを作成する

```bash
mkdir -p resources/views/tasks
```

```bash
touch resources/views/tasks/index.blade.php
```

`resources/views/tasks/index.blade.php` を開き、以下を貼り付けます：

```html
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
```

---

## 2-6. ルーティングを追加する

`routes/web.php` を開き、全体を以下に書き換えます：

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', [TaskController::class, 'index']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::post('/tasks/{task}/toggle', [TaskController::class, 'toggle']);
Route::post('/tasks/{task}/destroy', [TaskController::class, 'destroy']);
```

---

## 2-7. コントローラを作成する

```bash
php artisan make:controller TaskController
```

`app/Http/Controllers/TaskController.php` を開き、全体を以下に書き換えます：

```php
<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::orderBy('created_at', 'desc')->get();
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required|max:255']);
        Task::create(['title' => $request->title]);
        return redirect('/');
    }

    public function toggle(Task $task)
    {
        $task->update(['completed' => !$task->completed]);
        return redirect('/');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect('/');
    }
}
```

---

## 2-8. 動作確認

Simulator で以下を確認します：

1. タスクを入力して「追加」ボタンを押す → 一覧に表示される
2. チェックボックスを押す → テキストに打ち消し線がつく
3. 「削除」ボタンを押す → 一覧から消える

> 💡 **ポイント：** `Route`、`Eloquent`、`Blade` のコードは全て普通の Laravel と全く同じです。NativePHP 独自の記述はゼロです。
