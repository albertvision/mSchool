<?php
/*-------------------------------------------------------+
| mSchool V2
| Copyright (C) 2011 - 2012 Христо Димитров & Ясен Георгиев
| http://www.mschool.shareit.bg/
+--------------------------------------------------------+
| Име на файла: contacts.php
+--------------------------------------------------------+
| Тази програма се реализира като свободен софтуер
| под лиценза Affero GPL. Вие можете да разпространявате
| и/или да модифицирате mSchool V2 под правилата на GNU/GPL.
| Лиценза можете да го прочете във файла license.txt.
| Ако искате да премахнете текста кой е създал системата,
| трява да се свържeте със създателите на mSchool V2.
+--------------------------------------------------------*/

// Вземаме съдържанието на include/siteview.php
include 'includes/siteview.php';
$SiteView=new SiteView();
// Казваме да искара HTML-а преди съдържанието
$SiteView->top('Контакти');
//Съдържание
echo '<h2>Контакти</h2>';
// Ако има заявка през POST
if (isset($_POST['send'])) {
    // Променливи
    $name = mysql_real_escape_string(trim($_POST['name'])); // Вземаме стойноста на полето name
    $email = mysql_real_escape_string(trim($_POST['email'])); // Вземаме стойноста на полето email
    $title = mysql_real_escape_string(trim($_POST['subject'])); // Вземаме стойноста на полето title
    $content = mysql_real_escape_string(trim($_POST['content'])); // Вземаме стойноста на полето content
    $captcha = md5($_POST['captcha']); // Вземаме стойноста на полето captcha и я криптираме в MD5
    // Ако дължината на $name е по-малка от 5 символа
    if (strlen($name) < 5) {
        $errors[] = $SiteView->General->note('Твърде кратко име!');
    }
    // Проверяваме дали е вализен имейл адреса
    if (!@eregi("^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$", $email)) {
        $errors[] = $SiteView->General->note('Невалидна е-поща!');
    }
    // Ако дължината на $title е по-малка от 5 символа
    if (strlen($title) < 5) {
        $errors[] = $SiteView->General->note('Твърде кратка тема!');
    }
    // Ако дължината на $content е по-малка от 4 символа
    if (strlen($content) < 4) {
        $errors[] = $SiteView->General->note('Твърде кратко съобщение!');
    }
    // Ако дължината на $name е повече от 255 символа
    if (strlen($content) > 255) {
        $errors[] = $SiteView->General->note('Твърде дълго съобщение!');
    }
    // Ако дължината на $name е повече от 255 символа
    if ($captcha != $_SESSION['captcha']) {
        $errors[] = $SiteView->General->note('Грешен анти-спам код!');
    }
    // Ако няма никакви грешки
    if (!$errors) {
        // Променливи за изпращане на съобщението
        $to = $SiteView->settings['email'] . ', '; // До кого да се изпрати
        $subject = 'Съобщение от "' . $SiteView->settings['title'] . '"'; // Относно
        // Самото съобщение в HTML
        $message = '<html>
        <head>
          <title>Съобщение от "' . $SiteView->settings['title'] . '"</title>
        </head>
        <body>
        <b>Здравей!</b><br>
        ' . $name . ' < ' . $email . ' > ти е изпратил съобщение!
        <table>
        <tr><td>От:</td><td>' . $name . '</td> </tr>
        <tr><td>Е-поща:</td><td>' . $email . '</td> </tr>
        <tr><td>Относно:</td><td>' . $title . '</td> </tr>
        <tr><td>Съобщение:</td><td>' . $content . '</td> </tr>
        </table>
        <hr>
        <i>Това съобщение се изпраща от контактната форма на "' . $SiteView->settings['title'] . '"!</i>
        </body>
        </html>
        ';
        // Хедъри
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf8' . "\r\n";
        $headers .= 'From: ' . $email . '' . "\r\n"; // От кого се изпраща съощението
        // Ако съобщението се изпрати успешно
        if (mail($to, $subject, $message, $headers)) {
            $SiteView->General->note('Изпращането е успешно!');
        } else { // Ако не се е изпратило
            $SiteView->General->note('Съобщението не е изпратено! Моля, опитайте отново по-късно!');
        }
    }
}
echo '<h3>Информация за училището</h3>
    <table>
        <tr><td>Име на училището:</td><td>'.$SiteView->settings['name'].' - '.$SiteView->settings['town'].'</td></tr>
        <tr><td>Адрeс на училището:</td><td>'.$SiteView->settings['address'].'</td></tr>
        <tr><td>Е-поща на училището:</td><td>'.$SiteView->settings['email'].'</td></tr>
    </table>';
// Самата форма
echo '<h3>Изпрати ни съобщение</h3><table><form method="POST">
<tr><td>Вашето име: </td><td><input type="text" name="name" value="' . $name . '"></td></tr>
<tr><td>Ваша е-поща: </td><td><input type="text" name="email" value="' . $email . '"></td></tr>
<tr><td>Тема: </td><td><input type="text" name="subject" value="' . $title . '"></td></tr>
<tr><td>Съобщение: </td><td><textarea cols="30" rows="8" name="content">' . $content . '</textarea></td></tr>
<tr><td>Анти-спам код: </td><td><img src="captcha.php"></td></tr>
<tr><td>Повтори кода: </td><td><input type="text" name="captcha"></td></tr>
<tr><td><span class="art-button-wrapper"><span class="art-button-l"></span><span class="art-button-r"></span><input type="submit" name="send" value="Изпрати" class="art-button"></span></td><td><span class="art-button-wrapper"><span class="art-button-l"></span><span class="art-button-r"></span><input type="reset" value="Изчисти" class="art-button"></span></td></tr>
</form></table>';
//Показваме футъра
$SiteView->footer();
?>