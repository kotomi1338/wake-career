# STEP 1：Laravelプロジェクト作成〜初回起動確認

---

## 1-1. Laravel プロジェクトを作成する

```bash
composer create-project laravel/laravel nativephp-demo
```

```bash
cd nativephp-demo
```

---

## 1-2. NativePHP Mobile をインストールする

```bash
composer require nativephp/mobile
```

```bash
php artisan native:install
```

インタラクティブに質問が出ます：
- `Which platforms would you like to support?` → `iOS` 等を選択（スペースで選択、Enterで確定）

---

## 1-3. iOS Simulator を起動してアプリをビルドする

```bash
php artisan native:run --ios
```

ターミナルにビルドログが流れ、Xcode がコンパイルを始めます。
数分後、iOS Simulator にアプリが起動します。

> 💡 **確認ポイント：** Laravel のウェルカム画面が表示されればOK
