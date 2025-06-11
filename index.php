<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>kiwi-sisters</title>
  <link rel="stylesheet" href="css/hoge.css">
  <link rel="stylesheet" href="public/build.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Kiwi Maru', serif;
    }
  </style>
</head>

<body class="w-full h-screen m-0">

  <div class="login-box-place">
    <div class="login-box">
      <form method="POST" action="controller/login.php" class="form">
        <h1 class="text-white text-90 font-normal">Sign In</h1>

        <label for="username" class="mt-32 text-white text-18">ユーザー名:</label>
        <input type="text" id="username" name="username" required
          class="mt-8 w-full max-w-xs p-16 rounded bg-white text-black" />

        <label for="password" class="mt-24 text-white text-18">パスワード:</label>
        <input type="password" id="password" name="password" required
          class="mt-8 w-full max-w-xs p-16 rounded bg-white text-black" />

        <button type="submit"
          class="mt-40 bg-white text-[#67111b] px-32 py-16 rounded hover:bg-[#ed344a] hover:text-white transition-colors duration-300">
          ログイン
        </button>
      </form>
    </div>
  </div>

</body>

</html>
