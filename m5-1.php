<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission_5-1</title>
    </head>
    <body>
        <?php
        //接続
        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        //編集選択
        if(!empty($_POST['editnum'])&&($_POST['editpass'])){
            $editnum=$_POST["editnum"];
            $editpass = $_POST['editpass'];
            $sql = 'SELECT * FROM mission5';
            $results = $pdo->query($sql);
            foreach($results as $row){
                if($row['id'] == $editnum && $row['pass'] == $editpass){
                    $num_e = $row['id'];
                    $name_e = $row['name'];
                    $comment_e = $row['comment'];
                    $pass_e = $row['pass'];
                }
                elseif($row['id'] == $editnum && $row['pass'] !== $editpass){
                    $num_e = "";
                    $name_e = "";
                    $comment_e = "";
                    $pass_e = "";
                }
            }
        }
        
        if(!empty($_POST['name'])&&($_POST['comment'])&&($_POST['pass'])){
            $name = $_POST['name'];
            $comment = $_POST['comment'];
            $pass = $_POST['pass'];
            $date = date("Y/m/d H:i:s");
           
           //新規投稿
           if(empty($_POST['editNo'])){
               $sql = $pdo -> prepare("INSERT INTO mission5 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
               $sql -> bindParam(':name', $name, PDO::PARAM_STR);
               $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
               $sql -> bindParam(':date', $date, PDO::PARAM_STR);
               $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
               $sql -> execute();
           }
           //編集実行
           else{
               $id = $_POST['editNo'];
               $name = $_POST['name'];
               $comment = $_POST['comment'];
               $date = date("Y/m/d H:i:s");
               $pass = $_POST['pass'];
               $sql = 'UPDATE mission5 SET name=:name,comment=:comment,date=:date,pass=:pass WHERE id=:id';
               $stmt = $pdo->prepare($sql);
               $stmt->bindParam(':name', $name, PDO::PARAM_STR);
               $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
               $stmt->bindParam(':date', $date, PDO::PARAM_STR);
               $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
               $stmt->bindParam(':id', $id, PDO::PARAM_INT);
               $stmt->execute();
        
           }
        }
        
        //削除実行
        if(!empty($_POST['delnum'])&&($_POST['delpass'])){
            $delnum = $_POST['delnum'];
            $delpass = $_POST['delpass'];
            $sql = 'SELECT * FROM mission5';
            $results = $pdo->query($sql);
            foreach($results as $row){
                $id = $delnum;
                if($delpass==$row['pass']){
                    $sql = 'delete from mission5 where id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
        }
        ?>

        <form action=""method="post">
            <input type='text' name='name' placeholder='名前' value='<?php if(!empty($editnum)&&($editpass)){echo $name_e;} ?>'><br>
            <input type='text' name='comment' placeholder='コメント' value='<?php if(!empty($editnum)&&($editpass)){echo $comment_e;} ?>'><br>
            <input type='text' name='pass' placeholder='パスワード' value='<?php if(!empty($editnum)&&($editpass)){echo $pass_e;} ?>'>
            <input type='hidden' name='editNo' value='<?php if(!empty($editnum)&&($editpass)){echo $num_e;} ?>'>
            <input type='submit' name='submit'><br>
        <br>
            <input type='text' name='delnum' placeholder='削除対象番号'><br>
            <input type='text' name='delpass' placeholder='パスワード'>
            <input type='submit' value='削除'><br>
        <br>
            <input type='text' name='editnum' placeholder='編集対象番号'><br>
            <input type='text' name='editpass' placeholder='パスワード'>
            <input type='submit' value='編集'>
        </form>
        
        <?php
        //表示
        $sql = 'SELECT * FROM mission5';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row['id'].' ';
            echo $row['name'].' ';
            echo $row['comment'].' ';
            echo $row['date'].'<br>';
            echo "<hr>";
        }
        ?>
    </body>
</html>