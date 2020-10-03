<?php
//ifで$_SERVER['REQUEST_METHOD']=GET,POSTで場合わけ？
if($_SERVER['REQUEST_METHOD']=='GET'){
  echo getMethod();//json文字列を出力してjsで受け取り
}else if($_SERVER['REQUEST_METHOD']=='POST'){
  echo postMethod();
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
    $prefecture = htmlspecialchars($_GET["prefecture"]);//インジェクション対策
    try{
      $dbh = new PDO($dsn,$user,$password);
      // print('接続に成功しました。'.$prefecture);
      //preparedステートメントを使う
      $prepare = $dbh->prepare('SELECT prefecture,date,weather,memo from weatherReport where prefecture= ?');
      $prepare->bindValue(1,$prefecture,PDO::PARAM_STR);
      $prepare->execute();
      // fetchで取ってきた配列は””がついておらずjsonエンコードできないので整形
      foreach($prepare->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $results[]= [
          'date'=>$row['date'],
          'weather'=>$row['weather'],
          'prefecture'=>$row['prefecture'],
          'memo'=>$row['memo']
        ];
      }
      return json_encode($results);
      $dbh = null;
    
    }catch(PDOException $e){
      print("データベースの接続に失敗しました。".$e->getMessage());
      die();
    }

  }
  function postMethod(){
    $result = $_POST['memo'];
    //ここに細かい処理を書く
    return $result;
  }




?>