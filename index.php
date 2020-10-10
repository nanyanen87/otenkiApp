<?php
if (empty($_SERVER['PATH_INFO'])) {
  include __DIR__."/views/index.html";
  exit();
}


//nginxのlocation設定が必要
//動的なwebページつくるにはlocation ~ \.php${}を設定しないとダメ

//スラッシュで区切られたurlを取得
//url直接入力でもページ見れちゃう
//todo controller名/method名で値を渡せるようにする
// $analysis = explode('/', $_SERVER['PATH_INFO']);
// $call;
// foreach ($analysis as $value) {
//     if ($value !== "") {
//         $call = $value;

//         break;
//     }
// }

// if (file_exists('./routes' . $call . '.php')) {
//     include './routes/' . $call . '.php';
// } else {
//     //用意してない
//     include './routes/error.php';
// }


//sample
// if (file_exists('./models/'.$call.'.php')) {

//   include('./models/'.$call.'.php');
//   //$call名のクラスをインスタンス化します
//   $class = new $call();
//   //modelのindexメソッドを呼ぶ仕様です
//   $ret = $class->index($analysis);
//   //配列キーが設定されている配列なら展開します
//   if (!is_null($ret)) {
//       if(is_array($ret)){
//          extract($ret);
//       }
//   }
// }

//sample
// if (file_exists('./views/'.$call.'.php')) {
//   // echo $_SERVER['PATH_INFO'];
//   include('./views/'.$call.'.php');
// } else {
//   include('./views/error.php');
// }
