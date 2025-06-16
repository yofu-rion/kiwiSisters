<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <title>kiwi-sisters-signup</title>
    <link rel="stylesheet" href="../css/start.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
</head>

<body>
    <div class="choice">
        <div class="title-place">
            <h1 class="title">タイトル</h1>
        </div>
        <div class="menu" id="menu">
            <div class="menu-item active"><span class="indicator">▶</span> 話を選ぶ</div>
            <div class="menu-item"><span class="indicator">▶</span> つづきから</div>
            <div class="menu-item"><span class="indicator">▶</span> オプション</div>
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
                alert(`「${items[index].innerText.replace("▶", "").trim()}」を選択しました`);
            }
        });
    </script>

    <div class="illust">
        <h1 class="h1">イラストが乗る予定</h1>
    </div>
</body>

</html>