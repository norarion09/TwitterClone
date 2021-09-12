<?php
//////////////////////
//ユーザーデータを処理
////////////////////

/**
 * 
 * @param array $data
 * @return bool
 */
function createUser(array $data){
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
//接続チェック
if ($mysqli->connect_errno) {
    echo 'MySQLの接続に失敗しました。:' . $mysqli->connect_error . "\n";
    exit;
}

//新規登録のSQLを作成
$query = 'INSERT INTO users (email, name, nickname, password) VALUES (?, ?, ?, ?)';
$statement =$mysqli->prepare($query);

//パスワードをハッシュ値に変更
$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

//?の部分にセットする内容
//第一引数のsは変数の型を指定
$statement->bind_param('ssss', $data['email'], $data['name'], $data['nickname'], $data['password'],);

//処理を実行
$response = $statement->execute();
if($response === false){
    echo 'エラーメッセージ:' . $mysqli->error . "\n";
}

//接続を開放
$statement->close();
$mysqli->close();

return $response;

}


/**
 * 
 * @param string $email
 * @param string $password
 * @return arrayfalse
 * 
 */
function findUserAndCheckPassword(string $email, string $password){

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    //接続チェック
    if ($mysqli->connect_errno) {
        echo 'MySQLの接続に失敗しました。:' . $mysqli->connect_error . "\n";
        exit;
    }

    //入力値をエスケープ
    $email = $mysqli->real_escape_string($email);

    //クエリを作成
    //ー外部からのリクエストは何が入ってくるかわからないので、必ず、エスケープしたものを区オートで囲む
    $query = 'SELECT * FROM users WHERE email = "' . $email . '"';

    //SQL実行
    $result = $mysqli->query($query);
    if (!$result){
        //MySQL処理中にエラー発生
        echo 'エラーメッセージ:' . $mysqli->error . "\n";
        $mysqli->close();
        return false;
    }

    //ユーザー情報を取得
    $user = $result->fetch_array(MYSQLI_ASSOC);
    if (!$user){
        //ユーザーが存在しない
        $mysqli->close();
        return false;
    }
    
    //パスワードチェック
    if (!password_verify($password, $user['password'])){
        //パスワード不一致
        $mysqli->close();
        return false;
    }

    $mysqli->close();

    return $user;

}

/**
 * ユーザーを一件取得
 * 
 * @param int $user_id
 * @param int $login_user_id
 * @return array|false
 */
function findUser(int $user_id, int $login_user_id = null)
{
    // DB接続
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($mysqli->connect_errno) {
        echo 'MySQLの接続に失敗しました。: ' . $mysqli->connect_error . "\n";
        exit;
    }



 // エスケープ（SQLインジェクション対策）
 $user_id = $mysqli->real_escape_string($user_id);
 $login_user_id = $mysqli->real_escape_string($login_user_id);

 // ------------------------------------
 // SQLクエリを作成(検索)
 // ------------------------------------
 $query = <<<SQL
     SELECT
         U.id,
         U.name,
         U.nickname,
         U.email,
         U.image_name,
         -- フォロー中の数
         (SELECT COUNT(1) FROM follows WHERE status = 'active' AND follow_user_id = U.id) AS follow_user_count,
         -- フォロワーの数
         (SELECT COUNT(1) FROM follows WHERe status = 'active' AND followed_user_id = U.id) AS followed_user_count,
         -- ログインユーザーがフォローしている場合、フォローIDが入る
         F.id AS follow_id
     FROM
         users AS U
         LEFT JOIN
             follows AS F ON F.status = 'active' AND F.followed_user_id = '$user_id' AND F.follow_user_id = '$login_user_id'
     WHERE
         U.status = 'active' AND U.id = '$user_id'
 SQL;

 // ------------------------------------
 // 戻り値を作成
 // ------------------------------------
 // クエリを実行し、SQLエラーでない場合
 if ($result = $mysqli->query($query)) {
     // 戻り値用の変数にセット：ユーザー情報1件
     $response = $result->fetch_array(MYSQLI_ASSOC);
 } else {
     // 戻り値用の変数にセット：失敗
     $response = false;
     echo 'エラーメッセージ：' . $mysqli->error . "\n";
 }

 // ------------------------------------
 // 後処理
 // ------------------------------------
 // DB接続を開放
 $mysqli->close();

 return $response;
}