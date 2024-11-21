<?php
session_start();
if (!isset($_GET['action']) || $_GET['action'] !== 'generate_captcha') {
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Captcha</title>
</head>
<body>
    <h1>Регистрация</h1>
    <img src="<?= $_SERVER['PHP_SELF'] ?>?action=generate_captcha" alt="Капча" srcset="">
    <form action="" method="post">
        Введите капчу: <input type="text" name="captchaEntered" value=""> <br>
        <input type="submit" value="OK"> <br>
    </form>
</body>
</html>

<?php
} elseif ($_GET['action'] === 'generate_captcha') {
    $img = imagecreatefromjpeg('noise.jpg');
    $black = imagecolorallocate($img, 0, 0, 0);
    $symbolsArr = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',0,1,2,3,4,5,6,7,8,9];
    $captchaSymbNum = rand(6, 8);
    $captchaText = "";
    for($i = 0; $i < $captchaSymbNum; $i++) {
        $randomSymbNum = rand(0, count($symbolsArr) - 1);
        $captchaText .= (string)$symbolsArr[$randomSymbNum];
    };
    $_SESSION['captchaText'] = $captchaText;
    $x = 10;
    foreach (str_split($captchaText) as $char) {
        $randDegree = rand(-15, 15);
        imagettftext($img, 17, $randDegree, $x, 30, $black, 'brlnsdb.ttf', $char);
        $x += 20; // Расстояние между символами
    }
    imagejpeg($img);
    imagedestroy($img);
    exit(); 
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $enteredText = trim(strip_tags($_POST['captchaEntered']));
    if ($enteredText === $_SESSION['captchaText']) {
        echo 'Правильно';
        unset($_SESSION['captchaText']);
    } else {
        echo 'Неправильно';
    }
}
?>