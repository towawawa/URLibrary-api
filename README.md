# URLibrary-api

## ローカル環境構築手順
1.  `.env.example`をコピーして、`.env`を作成
    必要に応じて、中身を書き換えてください

2.  ビルド、コンテナを起動する
```
$ docker compose up -d
```

4.  コンテナへ入る
```
$ docker exec -it urlibrary-api bash
```

5.  パッケージのインストール
```
composer install
```

6.  ブラウザで`http://localhost:8080/`へアクセスし、ページが表示されれば完了
