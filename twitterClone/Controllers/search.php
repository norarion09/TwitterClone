<?php 
///////////////////////
//サーチコントローラー
///////////

//設定を読み込み
include_once '../config.php';
//便利な関数を読み込み
include_once '../util.php';

//ツイートデータ操作もdるを読み込む
include_once '../Models/tweets.php';


//ログインしているか
$user = getUserSession();
if (!$user) {
    //ログインしていない
    header('Location:' . HOME_URL . 'Controllers/sign-in.php');
    exit;
}

//検索キーワードを取得
$keyword = null;
if(isset($_GET['keyword'])){
    $keyword = $_GET['keyword'];
}

//画面表示
$view_user = $user;
$view_keyword = $keyword;

//ツイート一覧

$view_tweets = findTweets($user, $keyword);
include_once '../views/search.php';