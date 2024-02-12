<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>簡易掲示板</title>
</head>
<body>
    <?php
        // DB接続設定
        $dsn = 'mysql:dbname=【データベース名】;host=【MySQLホスト名】';
        $user = '【ユーザー名】';
        $password = '【パスワード】';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        // テーブル作成
        $sql = "CREATE TABLE IF NOT EXISTS tbtest"
        ." ("
        . "no INT AUTO_INCREMENT PRIMARY KEY,"
        . "name CHAR(32),"
        . "comment TEXT,"
        . "password TEXT,"
        . "date text"
        .");";
        $stmt = $pdo->query($sql);
        
        // テーブル一覧表示
        // $sql ='SHOW TABLES';
        // $result = $pdo -> query($sql);
        // foreach ($result as $row){
        //     echo $row[0];
        //     echo '<br>';
        // }
        // echo "<hr>";
        
        // テーブルの構成詳細確認
        // $sql ='SHOW CREATE TABLE tbtest';
        // $result = $pdo -> query($sql);
        // foreach ($result as $row){
        //     echo $row[1];
        // }
        // echo "<hr>";
    ?>
    <!--いずれかの送信ボタンを押した際の処理-->
    <?php
        $post_no = "";
        $post_name = "";
        $post_comment = "";
        $post_date = "";
        $post_password = "";
        
        $message = "　";
        
        // 新規投稿フォームの内容を送信
        if(!empty($_POST["post_name"]) && !empty($_POST["post_comment"])){
            $post_name = $_POST["post_name"];
            $post_comment = $_POST["post_comment"];
            $post_date = date("Y年m月d日 H:i:s");
            $post_password = $_POST["post_password"];

            // 新規
            if($_POST["post_no"] == ""){
                $sql = "INSERT INTO tbtest (name, comment, date, password) VALUES (:name, :comment, :date, :password)";
                $stmt = $pdo->prepare($sql);
                $message = "投稿しました。";
            }
            // 編集
            else{
                $post_no = $_POST["post_no"];

                $sql = 'UPDATE tbtest SET name=:name,comment=:comment,date=:date,password=:password WHERE no=:no';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':no', $post_no, PDO::PARAM_INT);
                $message = "編集しました。";
            }

            $stmt->bindParam(':name', $post_name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $post_comment, PDO::PARAM_STR);
            $stmt->bindParam(':date', $post_date, PDO::PARAM_STR);
            $stmt->bindParam(':password', $post_password, PDO::PARAM_STR);
            $stmt->execute();

            $post_no = "";
            $post_name = "";
            $post_comment = "";
            $post_date = "";
            $post_password = "";
        }
        // 削除フォームの内容を送信
        else if(!empty($_POST["delete_no"])){
            $message = $_POST["delete_no"]." は存在しません。";
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                if($_POST["delete_no"] == $row["no"]){
                    if($row["password"] == ""){
                        $message = "この投稿は削除できません。";
                    }
                    elseif($row["password"] != $_POST["delete_password"]){
                        $message = "パスワードが違います。";
                    }
                    else{
                        $delete_no = $_POST["delete_no"];
                        
                        $sql = 'delete from tbtest where no=:no';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':no', $delete_no, PDO::PARAM_INT);
                        $stmt->execute();
                        $message = "削除しました。";
                    }
                    break;
                }
            }
        }
        // 編集フォームの内容を送信
        else if(!empty($_POST["edit_no"])){
            $message = $_POST["edit_no"]." は存在しません。";
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                if($_POST["edit_no"] == $row["no"]){
                    if($row["password"] == ""){
                        $message = "この投稿は編集できません。";
                    }
                    elseif($row["password"] != $_POST["edit_password"]){
                        echo "パスワードが違います。<hr>";
                        $message = "パスワードが違います。";
                    }
                    else{
                        $post_no = $row["no"];
                        $post_name = $row["name"];
                        $post_comment = $row["comment"];
                        $post_password = $row["password"];
                        $message = $post_no." を編集します。";
                    }
                    break;
                }
            }
        }
        echo $message."<hr>";
    ?>
    <!--新規投稿フォーム-->
    <form action="" method="post">
        <input type="hidden" name="post_no" placeholder="投稿番号" value=<?=$post_no?>>
        <br>
        <input type="text" name="post_name" placeholder="名前" value="<?=$post_name?>">
        <br>
        <input type="text" name="post_comment" placeholder="コメント" value="<?=$post_comment?>">
        <br>
        <input type="password" name="post_password" placeholder="パスワード" value=<?=$post_password?>>
        <input type="submit" name="submit">
    </form>
    <br>
    <!--削除フォーム-->
    <form action="" method="post">
        <input type="number" name="delete_no" placeholder="削除対象番号" value="">
        <br>
        <input type="password" name="delete_password" placeholder="パスワード" value="">
        <input type="submit" name="delete">
    </form>
    <br>
    <!--編集フォーム-->
    <form action="" method="post">
        <input type="number" name="edit_no" placeholder="編集対象番号" value="">
        <br>
        <input type="password" name="edit_password" placeholder="パスワード" value="">
        <input type="submit" name="edit">
    </form>
    <!--データの表示-->
    <?php
        echo "<hr>";
        $sql = 'SELECT * FROM tbtest';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row["no"]." ";
            echo $row["name"]." ";
            echo $row["comment"]." ";
            echo $row["date"].'<br>';
            // echo $row['password'].'<br>';
        }
    ?>
    <?php
        // テーブル削除
        // $sql = 'DROP TABLE tbtest';
        // $stmt = $pdo->query($sql);
    ?>
</body>
</html>