<?php

///////////////////
//サインインコントローラー
/////////////

//設定を読み込み
include_once '../config.php';
//便利な関数を読み込み
include_once '../util.php';

//ユーザーデータ操作モデルを読み込み
include_once '../Models/users.php';

//ログインチェック
$try_login_result = null;
if(isset($_POST['email']) && isset($_POST['password'])){
    //ログインチェック実行
    $user = findUserAndCheckPassword($_POST['email'], $_POST['password']);

    if($user){
        //ログイン成功
        //ユーザー情報をセッションに保存
        saveUserSession($user);


        //ホーム画面へ遷移
        header('Location:' .HOME_URL .'Controllers/home.php');
        exit;
    } else{
        //ログイン失敗
        $try_login_result = false;
    }
}


//画面表示
$view_try_login_result = $try_login_result;
include_once '../views/sign-in.php';