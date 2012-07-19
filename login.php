<?php
/*-------------------------------------------------------+
| mSchool V2
| Copyright (C) 2011 - 2012 Христо Димитров & Ясен Георгиев
| http://www.mschool.shareit.bg/
+--------------------------------------------------------+
| Име на файла: index.php
+--------------------------------------------------------+
| Тази програма се реализира като свободен софтуер
| под лиценза Affero GPL. Вие можете да разпространявате
| и/или да модифицирате mSchool под правилата на GNU/GPL.
| Лиценза можете да го прочете във файла license.txt.
| Ако искате да премахнете текста кой е създал системата,
| трява да се свържeте със създателите на mSchool.
+--------------------------------------------------------*/

// Вземаме съдържанието на include/siteview.php
include 'includes/siteview.php';
$SiteView=new SiteView();
// Казваме да искара HTML-а преди съдържанието
$SiteView->top('Вход');
// Ако има стартирана сесия
if ($_SESSION['il']) {
    $SiteView->General->redirect($SiteView->settings['url']);
}
//Ако има заявка през POST
if (isset($_POST['act']) == 1) {
    // Променливи
    $username = $SiteView->General->escape(trim($_POST['user'])); // Вземаме стойноста на полето user
    $pass = trim($_POST['password']); // Вземаме стойноста на полето password
    $cpass = $SiteView->General->crypt_pass($pass); // Криптираме паролата
    // Ако дължината на $username е по-малка от 4 символа
    if (strlen($username) < 4) {
        $error['login'] = $SiteView->General->note('Въвели сте твърде кратко потребителско име!');
    }
    // Ако дължината на $pass е по-малка от 6 символа
    if (strlen($pass) < 6) {
        $error['pass'] = $SiteView->General->note('Въвели сте твърде кратка парола!');
    }
    // Ако няма грешки
    if (!$error) {
        // Променливи
        $rs = $SiteView->General->bg_q('SELECT * FROM users WHERE username="' . $username . '" AND password="' . $cpass . '"'); //Взимаме всички резултати от таблицата users където username=$username и password=$cpass
        $ui = mysql_fetch_assoc($rs); // Казваме, че в променливата $ui се съдържат всички данни на потребителя, които отгаварят на горното MySQL запитване
        // Ако броя на резултатите е един
        if (mysql_num_rows($rs) == 1) {
            // Обновяаме IP-то на потребителя
            $SiteView->General->bg_q('UPDATE users SET ip="' . $_SERVER['REMOTE_ADDR'] . '" WHERE username="' . $username . '"');
            //Стартиаме сесията il
            $_SESSION['il'] = TRUE;
            // Казваме на сесията ui, да съдържа записите на $ui
            $_SESSION['ui'] = $ui;
            // Препращаме към admin/
            $SiteView->General->redirect('admin/');
        } else { // Ако няма резултати
            $SiteView->General->note('Грешни данни!');
        }
    }
}
// Формата
echo '<div align="center"><table><form method="POST">
<tr><td>Потребителско име: </td><td><input type="text" name="user"></td></tr>
<tr><td>Парола: </td><td><input type="password" name="password"></td></tr>
<tr><td><span class="art-button-wrapper"><span class="art-button-l"></span><span class="art-button-r"></span><input type="submit" name="act" value="Вход" class="art-button"></span></td><td></td></tr>
<input type="hidden" name="act" value="1"></table></div>';
//Показваме футъра
$SiteView->footer();
?>