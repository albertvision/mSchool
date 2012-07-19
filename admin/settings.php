<?php
/*-------------------------------------------------------+
| mSchool V2
| Copyright (C) 2011 - 2012 Христо Димитров & Ясен Георгиев
| http://www.mschool.shareit.bg/
+--------------------------------------------------------+
| Име на файла: admin/settings.php
+--------------------------------------------------------+
| Тази програма се реализира като свободен софтуер
| под лиценза Affero GPL. Вие можете да разпространявате
| и/или да модифицирате mSchool под правилата на GNU/GPL.
| Лиценза можете да го прочете във файла license.txt.
| Ако искате да премахнете текста кой е създал системата,
| трява да се свържeте със създателите на mSchool.
+--------------------------------------------------------*/

// Вземаме съдържанието на ../include/adminview.php
include '../includes/adminview.php';
$AdminView=new AdminView();
// Казваме да искара HTML-а преди съдържанието
$AdminView->top('Настройки');
// Ако има заявка през POST['update']
if (isset($_POST['update'])) {
    // Променливи
    $name = $AdminView->General->escape($_POST['name']); // Взима името на училището
    $town = $AdminView->General->escape($_POST['town']); // Взима селището на училището
    $email = $AdminView->General->escape($_POST['email']); // Взима главния имейл на сайта
    $address = $AdminView->General->escape($_POST['address']); // Взима адреса на сайта
    $description = $AdminView->General->escape($_POST['description']); // Взима описанието на сайта
    $keywords = $AdminView->General->escape($_POST['keywords']); // Взима ключовите думи на сайта
    $date = $AdminView->General->escape($_POST['date']); // Взима датата
    $time = $AdminView->General->escape($_POST['time']); // Взима часът
	$logo = $_FILES['logo']; // Взима герба
    // Ако дължината на $name е по-малка от 4 символа
    if (strlen($name) < 4) {
        $error[] = $AdminView->General->note('Твърде кратко заглавие!');
    }
    // Ако дължината на $name е повече от 1500 символа
    if (strlen($name) > 100) {
        $error[] = $AdminView->General->note('Твърде дълго заглавие!');
    }
    // Ако дължината на $town е по-малка от 4 символа
    if (strlen($town) < 3) {
        $error[] = $AdminView->General->note('Твърде кратко селище!');
    }
    // Ако дължината на $town е повече от 1500 символа
    if (strlen($town) > 200) {
        $error[] = $AdminView->General->note('Твърде дълго селище!');
    }
    // Ако главният имейл е невалиден
    if (!@eregi("^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$", $email)) {
        $error[] = $AdminView->General->note('Невалидна е-поща!');
    }
    // Ако дължината на $address е по-малка от 2 символа
    if (strlen($address) < 3) {
        $error[] = $AdminView->General->note('Твърде кратък адрес!');
    }
    // Ако дължината на $address е повече от 1500 символа
    if (strlen($address) > 200) {
        $error[] = $AdminView->General->note('Твърде дълъг адрес!');
    }
    // Ако дължината на $description е по-малка от 5 символа
    if (strlen($description) < 5) {
        $error['3'] = $AdminView->General->note('Твърде кратко описание!');
    }
    // Ако дължината на $keywords е по-малка от 3 символа
    if (strlen($keywords) < 3) {
        $error['4'] = $AdminView->General->note('Твърде малко ключови думи!');
    }
    // Ако дължината на $keywords е по-малка от 500 символа
    if (strlen($keywords) > 500) {
        $error['5'] = $AdminView->General->note('Твърде много ключови думи!');
    }
    // Ако няма грешки
    if (!$error) {
		if ($logo['tmp_name']) {
            // Ако тъпът на герба е .PNG
            if ($logo['type'] == 'image/png') {
                // Създаваме променлива, с която указваме пътя къде да се качи изображението
                $path = '../images/header-logo.png';
                // Ако изображението е качено
                if (move_uploaded_file($logo['tmp_name'], $path)) {
                    // Ако изоражението не е качено
                } else {
                    $error[]=$AdminView->General->note('Емблемата не е обновена!');
                }
            }
            // Ако емблемата не е в .PNG формат
            else {
                $error[]=$AdminView->General->note('Ембеламата трябва да е в .PNG формат!');
            }
        }
		if(!$error)
		{
			// Обновява настройките
			$AdminView->General->bg_q('UPDATE settings SET name="' . $name . '",town="'.$town.'",email="'.$email.'",address="'.$address.'",description="' . $description . '",keywords="' . $keywords . '",date="' . $date . '",time="' . $time . '" WHERE type=1');
			$AdminView->General->alert('Обновяването е успешно!');
		}
    }
}
// Ако е натисна бутона "Задай като резервен запис"
elseif(isset($_POST['setdefault']))
{
    // Променливи
    $name = $AdminView->General->escape($_POST['name']); // Взима името на училището
    $town = $AdminView->General->escape($_POST['town']); // Взима селището на училището
    $email = $AdminView->General->escape($_POST['email']); // Взима главния имейл на сайта
    $address = $AdminView->General->escape($_POST['address']); // Взима адреса на сайта
    $description = $AdminView->General->escape($_POST['description']); // Взима описанието на сайта
    $keywords = $AdminView->General->escape($_POST['keywords']); // Взима ключовите думи на сайта
    $date = $AdminView->General->escape($_POST['date']); // Взима датата
    $time = $AdminView->General->escape($_POST['time']); // Взима часът
    // Ако дължината на $name е по-малка от 4 символа
    if (strlen($name) < 4) {
        $error[] = $AdminView->General->note('Твърде кратко заглавие!');
    }
    // Ако дължината на $name е повече от 1500 символа
    if (strlen($name) > 100) {
        $error[] = $AdminView->General->note('Твърде дълго заглавие!');
    }
    // Ако дължината на $town е по-малка от 4 символа
    if (strlen($town) < 3) {
        $error[] = $AdminView->General->note('Твърде кратко селище!');
    }
    // Ако дължината на $town е повече от 1500 символа
    if (strlen($town) > 200) {
        $error[] = $AdminView->General->note('Твърде дълго селище!');
    }
    // Ако главният имейл е невалиден
    if (!@eregi("^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$", $email)) {
        $error[] = $AdminView->General->note('Невалидна е-поща!');
    }
    // Ако дължината на $address е по-малка от 2 символа
    if (strlen($address) < 3) {
        $error[] = $AdminView->General->note('Твърде кратък адрес!');
    }
    // Ако дължината на $address е повече от 1500 символа
    if (strlen($address) > 200) {
        $error[] = $AdminView->General->note('Твърде дълъг адрес!');
    }
    // Ако дължината на $description е по-малка от 5 символа
    if (strlen($description) < 5) {
        $error['3'] = $AdminView->General->note('Твърде кратко описание!');
    }
    // Ако дължината на $keywords е по-малка от 3 символа
    if (strlen($keywords) < 3) {
        $error['4'] = $AdminView->General->note('Твърде малко ключови думи!');
    }
    // Ако дължината на $keywords е по-малка от 500 символа
    if (strlen($keywords) > 500) {
        $error['5'] = $AdminView->General->note('Твърде много ключови думи!');
    }
    // Ако няма грешки
    if (!$error) {
        // Обновява настройките
        $AdminView->General->bg_q('UPDATE settings SET name="' . $name . '",town="'.$town.'",email="'.$email.'",address="'.$address.'",description="' . $description . '",keywords="' . $keywords . '",date="' . $date . '",time="' . $time . '" WHERE type=2');
        $AdminView->General->alert('Данните са записани успешно!');
    }
   
}
// Ако има заявка през POST['default']
elseif (isset($_POST['default'])) {
    // Избира най-първите настройки на сайта
    $defaultR = $AdminView->General->bg_q('SELECT * FROM settings WHERE type=2');
    // Взима най-първите настройки на сайта
    $default = mysql_fetch_assoc($defaultR);
    $AdminView->General->bg_q('UPDATE settings SET name="' . $default['name'] . '",town="'.$default['town'].'",email="'.$default['email'].'",address="'.$default['address'].'",description="' . $default['description'] . '",keywords="' . $default['keywords'] . '",date="' . $default['date'] . '",time="' . $default['time'] . '" WHERE type=1');
    $AdminView->General->alert('Настройките са възстановени успешно!');
}
// Ако има POST['default] или няма POST['update']
if ($_POST['default'] || !$_POST['update']) {
    // Избира настройките
    $rs = $AdminView->General->bg_q('SELECT * FROM settings WHERE type=1');
    // Взема настройките
    $row = mysql_fetch_assoc($rs);
    // Променливи (самите настройки)
    $name = $row['name'];
    $town = $row['town'];
    $email = $row['email'];
    $address = $row['address'];
    $description = $row['description'];
    $keywords = $row['keywords'];
    $date = $row['date'];
    $time = $row['time'];
}
// Формата
echo '<form method="POST" enctype="multipart/form-data"><table>
    <tr><td colspan="2" align="center"><b>Информация за училището</b></td></tr>
    <tr><td>Име:</td><td><input type="text" name="name" value="'. stripcslashes($name).'"></td></tr>
    <tr><td>Селище:</td><td><input type="text" name="town" value="'.stripcslashes($town).'"></td></tr>
    <tr><td>Е-поща:</td><td><input type="text" name="email" value="'.stripcslashes($email).'"></td></tr>
    <tr><td>Адрес:</td><td><textarea  rows="5" cols="30" name="address">'.stripcslashes($address).'</textarea></td></tr>
	<tr><td>Герб на училището:</td><td><input type="file" name="logo"></td></td>
    <tr><td colspan="2" align="center"><b>Настройка на сайта</b></td></tr>
    <tr><td>Описание на сайта:</td><td><textarea rows="5" cols="30" name="description">' . stripcslashes($description) . '</textarea></td></tr>
    <tr><td>Ключови думи:</td><td><textarea rows="5" cols="30"name="keywords">' . stripcslashes($keywords). '</textarea></td></tr>
    <tr><td>Формат на датата:</td><td>';
$eDate = '<label><input type="radio" name="date" value="d M Y">' . date('d M Y') . '</label><br /><label><input type="radio" name="date" value="d.m.Y">' . date('d.m.Y') . '</label>';
$eDate = str_replace('value="' . $date . '"', 'value="' . $date . '" checked', $eDate);
echo $eDate;
echo '</td></tr>
    <tr><td>Формат на часа:</td><td>';
$eTime = '<label><input type="radio" name="time" value="H:i">' . date('H:i') . '</label><br /><label><input type="radio" name="time" value="g:i A">' . date('g:i A') . '</label>';
$eTime = str_replace('value="' . $time . '"', 'value="' . $time . '" checked', $eTime);
echo $eTime;
echo '</td></tr>
    <tr><td><input type="submit" name="update" value="Обнови"></td><td><input type="submit" name="setdefault" value="Задай като резервен запис"> <input type="submit" name="default" value="Възстанови"></td></tr>
    </table></form>';
//Показваме футъра
$AdminView->footer();
?>