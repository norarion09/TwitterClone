<?php
//////////////////
//サインアップコントローラー
//////

//設定を読み込み
include_once '../config.php';
//ユーザーデータ操作モデルを読み込み
include_once '../Models/users.php';

//ユーザー作成
//- $_POST を使用していますが、　filter_input()という便利な関数がある
if (isset($_POST['nickname']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
    $data = [
        'nickname' => $_POST['nickname'],
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'password' => $_POST['password'],
    ];
    if (createUser($data)){
        //ログイン画面に遷移
        header('Location: ' . HOME_URL .'Controllers/sign-in.php');
        exit;
    }
}

//画面表示
include_once '../views/sign-up.php';