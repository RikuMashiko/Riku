<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>missin5-1</title>
    </head>
<body>
    <?php
    echo "好きなテレビ番組は何ですか？教えてください！"."<br>";
    ?>
    <form action=""method="post">
       <input type="text" name="name"
        placeholder="名前"
        value=<?php echo $editname ?>><br>
        <input type="text" name="comment"
       placeholder="コメント"
        value=<?php echo $editcomment ?>><br>
        <input type="password" name="pass1"
        placeholder="パスワード">
        <input type="hidden" name="hiddenname"
        value=<?php echo $editnum?>>
        <input type="submit" value="送信">
        <br>
        <br>
        <input type="number" name="delete" placeholder="削除対象番号"><br>
        <input type="password" name="pass2"
        placeholder="パスワード">
        <input type="submit" value="削除">
        <br>
        <br>
        <input type="number" name="ednum"
        placeholder="編集対象番号"><br>
        <input type="password" name="pass3"
        placeholder="パスワード">
        <input type="submit" value="編集">
    </form>
    <?php
    echo"『投稿一覧』"."<br>";
    
    //DB接続設定
$dsn='mysql:dbname=DBNAME;host=localhost';
$user='USERNAME';
$password='PASSWORD';
$pdo=new PDO($dsn, $user, $password,
array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//テーブルの作成
$sql = "CREATE TABLE IF NOT EXISTS tbtest"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "pass1 char(32),"
	. "time char(32)"
	.");";
	$stmt = $pdo->query($sql);
	
	//定義つけ
	
	$name=$_POST["name"];
    $comment=$_POST["comment"];
    $time=date("Y年m月d日 H:i:s");
    
    $delete=$_POST["delete"];
    $ednum=$_POST["ednum"];
    $hiddenname=$_POST["hiddenname"];
    //$editcomment=$_POST["editcomment"];
    $pass1=$_POST["pass1"];
    $pass2=$_POST["pass2"];
    $pass3=$_POST["pass3"];
	//データの挿入
	if(!empty($name && $comment)&&($pass1))
	{
	    if(empty($hiddenname)){
	        $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, pass1, time) 
	        VALUES (:name, :comment, :pass1, :time)");
	        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        	$sql -> bindParam(':pass1', $pass1, PDO::PARAM_STR);
	        $sql -> bindParam(':time', $time, PDO::PARAM_STR);
	        $sql -> execute();
	    }
	    else{
	        $id=$hiddenname;
	        $sql = 'UPDATE tbtest SET name=:name,
	        comment=:comment,pass1=:pass1,time=:time WHERE id=:id';
	        $stmt = $pdo->prepare($sql);
	        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	        $stmt->bindParam(':pass1', $pass1, PDO::PARAM_STR);
	        $stmt->bindParam(':time', $time, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
	    }
	}
	     //削除
    if(!empty($delete)&&($pass2)){
        $id=$delete;
        $sql = 'SELECT * FROM tbtest WHERE id=:id ';
        $stmt = $pdo->prepare($sql);                 
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
        $stmt->execute();  
        $results = $stmt->fetchAll(); 
        
	    foreach ($results as $row){
	        $selectpass2=$row['pass1'];
	    }
	    if(($pass2)==($selectpass2)){
	        $sql = 'delete from tbtest where id=:id';
	        $stmt = $pdo->prepare($sql);
	        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	        $stmt->execute();
	    }
    }
    //内容の編集
   if(!empty($ednum)&&($pass3)){
       $id=$ednum;
       $sql = 'SELECT * FROM tbtest WHERE id=:id ';
       $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
       $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
       $stmt->execute();                             // ←SQLを実行する。
       $results = $stmt->fetchAll(); 
       
       foreach ($results as $row){
           $selectpass3=$row['pass1'];
           $selectname=$row['name'];
           $selectcomment=$row['comment'];
       }
       if(($pass3)==($selectpass3)){
           $editnum=$ednum;
           $editname=$selectname;
           $editcomment=$selectcomment;
	       /*$sql = 'UPDATE tbtest SET name=:name,comment=:comment,
	       pass1=:pass1,time=:time WHERE id=:id';
	       $stmt = $pdo->prepare($sql);
	       $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	       $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	       $stmt->bindParam(':pass1', $pass1, PDO::PARAM_STR);
	       $stmt->bindParam(':time', $time, PDO::PARAM_STR);
           $stmt->bindParam(':id', $id, PDO::PARAM_INT);
           $stmt->execute();
           */
       }
    }

//データの表示
$sql = 'SELECT * FROM tbtest';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['time'].'<br>';
	echo "<hr>";
	}
   
    ?>
     
</body>
</html>