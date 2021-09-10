<?php
//////////////////////////
//いいね！データを処理
///////////////////////



/**
 * 
 * @param array $data
 * @return int|false
 */
function createLike(array $data)
{
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    //接続チェック
    if ($mysqli->connect_errno) {
        echo 'MySQLの接続に失敗しました。:' . $mysqli->connect_error . "\n";
        exit;
    }

    //新規登録のSQLを作成
    $query = 'INSERT INTO likes (user_id, tweet_id) VALUES (?, ?)';
    $statement = $mysqli->prepare($query);

    //プレースホルダーにセット
    $statement->bind_param('ii', $data['user_id'], $data['tweet_id']);

    //SQL実行
    if ($statement->execute()) {
        //結果をIDで返却
        $response = $mysqli->insert_id;
    } else {
        //結果を失敗で返却
        $response = false;
        echo 'エラーメッセージ:' . $mysqli->error . "\n";
    }

    // 接続を閉じる
    $statement->close();
    $mysqli->close();

    return $response;
}


/**
 * いいね！を取り消し
 * 
 * @param array $data
 * @return bbool
 */
function deleteLike(array $data)
{
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    //接続チェック
    if ($mysqli->connect_errno) {
        echo 'MySQLの接続に失敗しました。:' . $mysqli->connect_error . "\n";
        exit;
    }

    //更新のSQLを作成
    $query = 'UPDATE likes SET status ="deleted" WHERE id = ? AND user_id = ?';
    $statement = $mysqli->prepare($query);

    //プレースホルダーにセット
    $statement->bind_param('ii', $data['like_id'], $data['user_id']);

    //SQL実行
    $response = $statement->execute();

    if ($response === false) {
        echo 'エラーメッセージ:' . $mysqli->error . "\n";
    }

    //接続を閉じる
    $statement->close();
    $mysqli->close();

    return $response;
}
