<?php
//ifで$_SERVER['REQUEST_METHOD']=GET,POSTで場合わけ
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
    //入力バリデーション処理ここにおく
    $prefectureNameArray =[
      "釧路・根室・十勝地方",
      "宗谷地方"            ,
      "岐阜県"              ,
      "神奈川県"            ,
      "新潟県"              ,
      "和歌山県"            ,
      "宮城県"              ,
      "胆振・日高地方"      ,
      "石狩・空知・後志地方",
      "山形県"              ,
      "渡島・檜山地方"      ,
      "秋田県"              ,
      "上川・留萌地方"      ,
      "青森県"              ,
      "網走・北見・紋別地方",
      "岩手県"              ,
      "鳥取県"              ,
      "香川県"              ,
      "滋賀県"              ,
      "奈良県"              ,
      "島根県"              ,
      "愛媛県"              ,
      "兵庫県"              ,
      "富山県"              ,
      "三重県"              ,
      "京都府"              ,
      "大阪府"              ,
      "静岡県"              ,
      "福井県"              ,
      "広島県"              ,
      "愛知県"              ,
      "岡山県"              ,
      "千葉県"              ,
      "福島県"              ,
      "茨城県"              ,
      "埼玉県"              ,
      "山梨県"              ,
      "長野県"              ,
      "東京都"              ,
      "群馬県"              ,
      "石川県"              ,
      "栃木県"              ,
      "宮崎県"              ,
      "山口県"              ,
      "鹿児島県"            ,
      "大分県"              ,
      "徳島県"              ,
      "福岡県"              ,
      "高知県"              ,
      "佐賀県"              ,
      "熊本県"              ,
      "沖縄本島地方"        ,
      "長崎県"              ,
      "八重山地方"          ,
      "宮古島地方"          ,
      "大東島地方"  ];
     
    //todo printではなくエラーを投げないとダメ
    //エラー処理。レスポンスデータを一から作らないとaxiosのcatchに入らない
    try{
      if(empty($prefecture)){
        throw new Exception("都道府県名または適切な地域名を入力してください。");
      }else if($prefecture==="北海道"){
        throw new Exception("「宗谷地方」、「上川・留萌地方」、「網走・北見・紋別地方」、「釧路・根室・十勝地方」、「胆振・日高地方」、「石狩・空知・後志地方」、「渡島・檜山地方」、からお選びびください。");
      }else if($prefecture==="沖縄県"){
        $prefecture = "沖縄本島地方";
        // print("沖縄本島、大東島地方、宮古島地方、八重山地方からお選びください");
        // die();
      }else if(!in_array($prefecture,$prefectureNameArray)){
        throw new Exception("入力された内容をもう一度ご確認ください。");
      }
    }catch(Exception $e){
      print($e->getMessage());
      http_response_code(400);//レスポンスオブジェクトはどこにあるの？
      die();
    }
    
    
    try{
      //DB接続
      $dbh = new PDO($dsn,$user,$password);
      //preparedステートメントを使う
      $prepare = $dbh->prepare('SELECT prefecture,date,weather,memo from weatherReport where prefecture= ?');
      $prepare->bindValue(1,$prefecture,PDO::PARAM_STR);
      $prepare->execute();
      // fetchで取ってきた配列は””がついておらずjsonエンコードできないので整形→select json_array()で行ける説、下記の方ができるやつっぽいけど
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