<?php
/*-------------------------------------------------------+
| mSchool V2
| Copyright (C) 2011 - 2012 Христо Димитров & Ясен Георгиев
| http://www.mschool.shareit.bg/
+--------------------------------------------------------+
| Име на файла: 404.php
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
$SiteView->top('Страницата не е намерена!');
//Съдържание
$SiteView->errorPage('<h2>Страницата не е намерена!</h2><br /><b>Изглежда, че страницата, който търсете не съществува!</b>');
//Показваме футъра
$SiteView->footer();
?>