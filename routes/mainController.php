<?php
//ifで$_SERVER['REQUEST_METHOD']=GET,POSTで場合わけ？
if($_SERVER['REQUEST_METHOD']=='GET'){
  echo getMethod();
}else if($_SERVER['REQUEST_METHOD']=='POST'){
  echo postMethod()."desu";
}else{
  echo "error";
}

  function getMethod(){
    // $result = $_GET['prefecture'];
    //ここに細かい処理を書く
    //データベース接続
    $dsn      = 'mysql:host=localhost;dbname=otenkiApp;charset=utf8';
    $user     = 'root';
    $password = 'root';
    $results = array();
    try{
      $dbh = new PDO($dsn,$user,$password);
      // print('接続に成功しました。');
      // クエリの実行
      
      foreach($dbh->query('SELECT * from weatherReport where prefecture="東京都"') as $row) {
        $results[]= [
          'date'=>$row['date'],
          'weather'=>$row['weather'],
          'prefecture'=>$row['prefecture'],
          'memo'=>$row['memo']
        ];
       
    }
    $dbh = null;
    
    }catch(PDOException $e){
      print("データベースの接続に失敗しました。".$e->getMessage());
      die();
    }
    
    return json_encode($results);//json_encode()してなかったら文字列（Array）が返ってたのに、これで配列が返るようになった。おそらくブラウザが勝手にjson形式を配列に変換してくれてる？

  }
  function postMethod(){
    $result = $_POST['memo'];
    //ここに細かい処理を書く
    return $result;
  }




?>