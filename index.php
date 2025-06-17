<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>kiwi-sisters-login</title>
    <link rel="stylesheet" href="css/sign.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
</head>

<body>
    <div class="water-place">
        <img src="./img/water.png" class="water" />
    </div>
    <div class="login-box-place">
        <div class="login-box">
            <form method="POST" action="./controller/StartController.php" class="form-container">
                <h1 class="sign-in">Sign In</h1>
                <div class="user">
                    <label for="username" class="label">ID</label>
                    <input type="text" id="username" name="username" required class="form-input" />
                </div>
                <div class="user">
                    <label for="password" class="label">パスワード</label>
                    <input type="password" id="password" name="password" required class="form-input" />
                </div>
                <button type="submit" class="button-login">
                    ログイン
                </button>
                <button type="submit" class="button-signup" onclick="location.href='./controller/SignUpController.php'">
                    新規登録
                </button>
            </form>
        </div>
        <div class="flower-place">
            <img src="./img/flower.png" class="flower" />
        </div>
    </div>

</body>

</html>