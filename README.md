簡易掲示板を作成しました。
10 - 13行目
  DB接続設定を行う。【データベース名】【ユーザー名】【パスワード】はそれぞれ書き換える。
16 - 24行目
  データを登録するためのテーブルを作成。
  no: 投稿番号
  name: 名前
  comment: コメント
  password: パスワード
  date: 投稿日時
27 - 33行目
  コメントアウトを外して実行すると、作成したテーブルの一覧が表示される。
36 - 40行目
  コメントアウトを外して実行すると、テーブルの構成詳細を確認できる。
54 - 87行目
  新規投稿フォームの送信ボタンを押した場合の処理。
  61 - 65行目
    新規投稿の処理。
  67 - 74行目
    既存の投稿の編集の処理。
89 - 114行目
  削除フォームの送信ボタンを押した場合の処理。
  削除対象番号$_POST["delete_no"]と、データ内の番号$row["no"]が一致した場合、そのうちパスワードが一致した場合に削除する。
  パスワードを設定していない投稿は削除できない。
116 - 140行目
  編集フォームの送信ボタンを押した場合の処理。
  編集対象番号$_POST["edit_no"]と、データ内の番号$row["no"]が一致した場合、そのうちパスワードが一致した場合に編集する。
  パスワードを設定していない投稿は編集できない。
144 - 153行目
  新規投稿フォーム。
156 - 161行目
  削除フォーム。
164 - 169行目
  編集フォーム。
171 - 184行目
  データの表示。
  182行目
    コメントアウトを外して実行した場合、パスワードが表示される。
187 - 188行目
  コメントアウトを外して実行した場合、テーブルを削除するので注意。