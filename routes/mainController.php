<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  echo getMethod(); 
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
  echo postMethod();
} else {
  echo "error";
}

function getMethod(){
$prefecture = htmlspecialchars($_GET["prefecture"]);
//都道府県リスト読み込み
require_once($_SERVER['DOCUMENT_ROOT']."/data/prefectures.php");

  //ユーザー入力エラー処理
  try {
    if (empty($prefecture)) {
      throw new Exception("都道府県名または適切な地域名を入力してください。");
    } elseif ($prefecture === "北海道") {
      throw new Exception(
        "「宗谷地方」、「上川・留萌地方」、「網走・北見・紋別地方」、「釧路・根室・十勝地方」、「胆振・日高地方」、「石狩・空知・後志地方」、「渡島・檜山地方」、からお選びびください。"
      );
    } elseif ($prefecture === "沖縄県") {
        $prefecture = "沖縄本島地方";
        // print("沖縄本島、大東島地方、宮古島地方、八重山地方からお選びください");
        // die();
    } elseif (!in_array($prefecture, $prefectureNameArray)) {
        throw new Exception("入力された内容をもう一度ご確認ください。");
    }
  } catch (Exception $e) {
      print($e->getMessage());
      http_response_code(400);
      die();
  }

  try {
    //DB接続
    require_once($_SERVER['DOCUMENT_ROOT']."/data/dbPassword.php");
    $dbh = new PDO($dsn, $user, $password);
    //preparedステートメントを使う
    $sqlStatement =
      'SELECT prefecture,date,weather,memo,id from weatherReport where prefecture= ?';
    $prepare = $dbh->prepare($sqlStatement);
    // $prepare->bindValue(1, $prefecture, PDO::PARAM_STR)でもできる
    $prepare->execute([$prefecture]);
    //vueで自動生成できるように配列を作成
    // $results = $prepare->fetchAll(PDO::FETCH_ASSOC);
    foreach ($prepare->fetchAll(PDO::FETCH_ASSOC) as $row) {
      $results[] = [
        'date' => $row['date'],
        'weather' => $row['weather'],
        'prefecture' => $row['prefecture'],
        'memo' => $row['memo'],
        'id' => $row['id'],
      ];
    }
    $dbh = null;
    return json_encode($results);
    
  } catch (PDOException $e) {
    print "データベースの接続に失敗しました。" . $e->getMessage();
    die();
  }
}

function postMethod()
{
  $memoText = htmlspecialchars($_POST["memoText"]);
  $memoId = htmlspecialchars($_POST["memoId"]);
  $prefecture = htmlspecialchars($_POST["prefecture"]);

  try {
    require_once($_SERVER['DOCUMENT_ROOT']."/data/dbPassword.php");
    $dbh = new PDO($dsn, $user, $password);

    $sqlStatement1 = 'UPDATE weatherReport set memo= ? where id= ?';
    $sqlStatement2 =
      'SELECT prefecture,date,weather,memo,id from weatherReport where prefecture= ?';

    //受けとったメモをPOST
    $prepare1 = $dbh->prepare($sqlStatement1);
    $prepare1->bindValue(1, $memoText, PDO::PARAM_STR);
    $prepare1->bindValue(2, $memoId, PDO::PARAM_INT);
    $prepare1->execute();

    //更新したものを返す
    $prepare2 = $dbh->prepare($sqlStatement2);
    $prepare2->bindValue(1, $prefecture, PDO::PARAM_STR);
    $prepare2->execute();

    foreach ($prepare2->fetchAll(PDO::FETCH_ASSOC) as $row) {
      $results[] = [
        'date' => $row['date'],
        'weather' => $row['weather'],
        'prefecture' => $row['prefecture'],
        'memo' => $row['memo'],
        'id' => $row['id'],
      ];
    }
    $dbh = null;
    return json_encode($results);
  } catch (PDOException $e) {
    print "データベースの接続に失敗しました。" . $e->getMessage();
    die();
  }
}
?>
 