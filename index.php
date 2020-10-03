<?php
if(empty($_SERVER['PATH_INFO'])){
  //紹介ページを表示
  include('./views/index.html');   
  exit;
}

//スラッシュで区切られたurlを取得
$analysis = explode('/', $_SERVER['PATH_INFO']);
$call;

foreach ($analysis as $value) {

    if ($value !== "") {
        $call = $value;
        break;
    }
}

//GET,POSTはcontrollerの中？
if (file_exists('./routes'.$call.'.php')) {
  include('./routes/'.$call.'.php');
} else {
  include('./routes/error.php');
}

if (file_exists('./models/'.$call.'.php')) {

  include('./models/'.$call.'.php');
  //$call名のクラスをインスタンス化します
  $class = new $call();
  //modelのindexメソッドを呼ぶ仕様です
  $ret = $class->index($analysis);
  //配列キーが設定されている配列なら展開します
  if (!is_null($ret)) {
      if(is_array($ret)){
         extract($ret);
      }
  }
}

// if (file_exists('./views/'.$call.'.php')) {
//   // echo $_SERVER['PATH_INFO'];
//   include('./views/'.$call.'.php');
// } else {
//   include('./views/error.php');
// }


