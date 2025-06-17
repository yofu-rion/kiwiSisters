<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <title>kiwi-sisters-signup-done</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
</head>

<body>
    <?php
    $pdo=new PDO('mysql:host=localhost;dbname=kiwi_datas;charset=utf8', 
        'staff', 'password');

    $sql=$pdo->prepare('select * from login where name=?');
    $sql->execute([$_REQUEST['username']]);

    if (empty($sql->fetchAll())) {

        if ($_REQUEST['password'] == $_REQUEST['password_check']) {
            $sql=$pdo->prepare('insert into login values(?,?)');
            $sql->execute([$_REQUEST['username'], $_REQUEST['password']]);
            echo '登録しました。';
            // フロントおねしゃす
        }else{
            echo 'パスワードが一致しません。';
            // フロントおねしゃす
        }
        
    } else {
        echo 'IDがすでに使用されていますので、変更してください。';
        // フロントおねしゃす
    }
    ?>
</body>
