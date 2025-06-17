<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <title>kiwi-sisters-start</title>
    <link rel="stylesheet" href="../css/start.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
</head>

<body>
    <?php session_start(); ?>
    <?php
    unset($_SESSION['login']);
    $pdo=new PDO('mysql:host=localhost;dbname=kiwi_datas;charset=utf8', 
        'staff', 'password');
    $sql=$pdo->prepare('select * from login where name=? and pass=?');
    $sql->execute([$_REQUEST['username'], $_REQUEST['password']]);
    foreach ($sql as $row) {
        $_SESSION['login']=[
            'name'=>$row['name'], 
            'pass'=>$row['pass']];
    }
    if (isset($_SESSION['login'])) {
        ?>
        <div class="choice">
            <div class="title-place">
                <h1 class="title">タイトル</h1>
            </div>
            <div class="menu" id="menu">
                <div class="menu-item active"><span class="indicator">▶</span>
                    <button type="button" class="button" onclick="location.href='StorySelectController.php'">話を選ぶ</button>
                </div>
                <div class="menu-item"><span class="indicator">▶</span>
                    <button type="button" class="button" onclick="location.href='DataLoadController.php'">続きから</button>
                </div>
                <div class="menu-item"><span class="indicator">▶</span>
                    <button type="button" class="button" onclick="location.href='Setting.php'">オプション</button>
                </div>
            </div>
        </div>

        <script>
            const items = document.querySelectorAll('.menu-item');
            let index = 0;

            const updateActive = () => {
                items.forEach((item, i) => {
                    item.classList.toggle('active', i === index);
                });
            };

            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowDown') {
                    index = (index + 1) % items.length;
                    updateActive();
                } else if (e.key === 'ArrowUp') {
                    index = (index - 1 + items.length) % items.length;
                    updateActive();
                } else if (e.key === 'Enter') {
                    const activeItem = items[index];
                    const button = activeItem.querySelector('button');
                    if (button) button.click();
                }

            });
        </script>

        <div class="illust">
            <h1 class="h1">イラストが乗る予定</h1>
        </div>
    <?php
    } else {
        echo 'ログイン名またはパスワードが違います。';
        // フロントおねしゃす
    }
    ?>
    
</body>

</html>