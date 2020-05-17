<main>
    <h1>Labwork</h1>

<?php

$to = isset($_POST['to']) ? $_POST['to'] : null;
$text = isset($_POST['text']) ? $_POST['text'] : null;
$captchaInput = isset($_POST['captcha']) ? $_POST['captcha'] : null;
$captchaPostedKey = isset($_POST['captcha-key']) ? $_POST['captcha-key'] : null;

function mailUser($to, $text): bool
{
    $subject = "Mail subject";
    return mail($to, $subject, $text);
}

$isCaptchaCorrect = false;
if ($to && $text && $captchaInput && $captchaPostedKey) {
    if ($captchaPostedKey == hash("md5", $captchaInput)) {
        $isCaptchaCorrect = true;
        if (mailUser("test@example.com", "hello there")) {
            echo "Email has been sent.";
        } else {
            echo "Error while sending message.";
        }
    }

    unset($_POST['to']);
    unset($_POST['text']);
    unset($_POST['captcha']);
    unset($_POST['captcha-key']);
}

function generateCaptcha(): int
{
    $captchaMin = 100000;
    $captchaMax = 999999;
    $captchaKey = random_int($captchaMin, $captchaMax);

    $captchaWidth = 64;
    $captchaHeight = 28;
    $captchaPath = "images/captcha.png";
    $captcha = imagecreatetruecolor($captchaWidth, $captchaHeight);

    $text = strval($captchaKey);
    $white = imagecolorallocate($captcha, 255, 255, 255);
    $grey = imagecolorallocate($captcha, 128, 128, 128);
    $black = imagecolorallocate($captcha, 0, 0, 0);
    $fontSize = 10;
    $coord = 6;
    imagefilledrectangle($captcha, 0, 0, $captchaWidth, $captchaHeight, $grey);
    imagestring($captcha, $fontSize, $coord, $coord, $text, $white);
    
    for ($i = 0; $i < 7; $i++) {
        $x1 = random_int(0, $captchaWidth);
        $x2 = random_int(0, $captchaWidth);
        $y1 = 0;
        $y2 = $captchaHeight;
        imageline($captcha, $x1, $y1, $x2, $y2, $black);
    }
    
    imagepng($captcha, $captchaPath);
    
    return $captchaKey;
}

$captchaKey = generateCaptcha();
    
?>
<form action="labwork" method="post">
	<label for="to">To:</label>
	<input name="to" id="to" type="email" required><br>

	<label for="text">Text:</label>
	<textarea name="text" id="text" cols="30" rows="10" required></textarea><br>

    <label for="captcha">Captcha:</label>
    <img src="images/captcha.png" alt="img-captcha">
    <input name="captcha" id="captcha" type="text" required><br>
    <span class="captcha-status"><?= $isCaptchaCorrect ? "" : "Captcha was entered incorrectly"; ?></span>

    <input type="hidden" name="captcha-key" value="<?= hash("md5", $captchaKey); ?>">
    
    <button>Submit</button>
</form>
</main>
