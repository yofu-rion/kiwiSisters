<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>kiwi-sisters-signup</title>
    <link rel="stylesheet" href="../css/sign.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
</head>

<body>
    <div class="water-place">
        <img src="../img/water.png" class="water" />
    </div>
    <div class="login-box-place">
        <div class="login-box">
            <form method="POST" action="SignUp.php" class="form-container">
                <h1 class="sign-up">Sign Up</h1>
                <div class="user-signup">
                    <label for="username" class="label">ID</label>
                    <input type="text" id="username" name="username" required class="form-input" />
                </div>
                <div class="user-signup">
                    <label for="password" class="label">パスワード</label>
                    <input type="password" id="password" name="password" required class="form-input" />
                </div>
                <div class="user-signup">
                    <label for="password" class="label">パスワード(確認)</label>
                    <input type="password" id="password_check" name="password_check" required class="form-input" />
                </div>
                <button type="submit" class="button-login">
                    新規登録
                </button>
                <button type="submit" class="button-signup" onclick="location.href='../index.php'">
                    キャンセル
                </button>
            </form>
        </div>
        <div class="flower-place">
            <img src="../img/flower.png" class="flower" />
        </div>
    </div>

</body>

</html>