

<!DOCTYPE html>
<html lang="ja">

<head>
<?php include_once('../views/common/head.php'); ?>

    <title>プロフィール画面 / Twitterクローン</title>
    <meta name="description" content="プロフィール画面です">
</head>

<body class="home profile text-center">
    <div class="container">
    <?php include_once('../views/common/side.php'); ?>

        <div class="main">
            <div class="main-header">
                <h1><?php echo $view_requested_user['nickname']; ?></h1>
            </div>
            <div class="profile-area">
                <div class="top">
                    <div class="user"><img src="<?php echo buildImagePath($view_requested_user['image_name'], 'user'); ?>" alt=""></div>

                    <?php if ($view_user['id'] !== $view_requested_user['id']) : ?>
                        <!-- 他人のプロフィール -->
                        <?php if(isset($view_requested_user['follow_id'])) : ?>
                        <button class="btn btn-sm js-follow" data-followed-user-id="<?php echo $view_requested_user['id']; ?>" data-follow-id="<?php echo $view_requested_user['follow_id']; ?>">フォローを外す</button>
                        <?php else :?>
                        <button class="btn btn-sm btn-reverse js-follow" data-followed-user-id="<?php echo $view_requested_user['id']; ?>">フォローする</button>
                        <?php endif; ?>
                        <?php else :?>
                             <!-- 自分のプロフィール -->
                    <button class="btn btn-reverse btn-sm js-modal-button" type="submit" data-bs-toggle="modal" data-bs-target="#js-modal">プロフィール編集</button>

                    <div class="modal fade" id="js-modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="profile.php" method="post" enctype="multipart/form-data">
                                    <div class="modal-header">
                                        <h5 class="modal-title">プロフィールを編集</h5>
                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="user">
                                            <img src="<?php echo buildImagePath($view_user['image_name'], 'user'); ?>" alt="">
                                        </div>
                                        <div class="mb-3">
                                            <label class="mb-1">プロフィール写真</label>
                                            <input type="file" class="form-control form-control-sm" name="image">
                                        </div>

                                        <input type="text" class="mb-4 form-control" name="nickname" maxlength="50" value="<?php echo htmlspecialchars($view_user['nickname']); ?>" placeholder="ニックネーム" required>
                                        <input type="text" class="mb-4 form-control" name="name" maxlength="50" value="<?php echo htmlspecialchars($view_user['name']); ?>" placeholder="ユーザー名" required>
                                        <input type="email" class="mb-4 form-control" name="email" maxlength="254" value="<?php echo htmlspecialchars($view_user['email']); ?>" placeholder="メールアドレス" required>
                                        <input type="password" class="mb-4 form-control" name="password" minlength="4" maxlength="128" value="" placeholder="パスワード変更する場合ご入力ください">
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-reverse" data-bs-dismiss="modal">キャンセル</button>
                                        <button class="btn"  type="submit">保存する</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="name"><?php echo htmlspecialchars($view_requested_user['nickname']); ?></div>
                <div class="text-muted">@<?php echo htmlspecialchars($view_requested_user['name']); ?></div>

                <div class="follow-follower">
                    <div class="follow-count"><?php echo htmlspecialchars($view_requested_user['follow_user_count']); ?></div>
                    <div class="follow-text">フォロー中</div>
                    <div class="follow-count"><?php echo htmlspecialchars($view_requested_user['followed_user_count']); ?></div>
                    <div class="follow-text">フォロワー</div>
                </div>
            </div>
            <div class="ditch"></div>

            <?php if (empty($view_tweets)) : ?>
        <p class="p-3">ツイートがまだありません</p>
      <?php else : ?>

        <div class="tweet-list">

          <?php foreach ($view_tweets as $view_tweet) : ?>
            <?php include('../views/common/tweet.php'); ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>


        </div>
    </div>
    </div>
  <?php include_once('../views/common/foot.php'); ?>
</body>

</html>