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
| и/или да модифицирате mSchool V2 под правилата на GNU/GPL.
| Лиценза можете да го прочете във файла license.txt.
| Ако искате да премахнете текста кой е създал системата,
| трява да се свържeте със създателите на mSchool V2.
+--------------------------------------------------------*/

// Вземаме съдържанието на include/siteview.php
include 'includes/SiteView.php';
$SiteView=new SiteView();
// Казваме да искара HTML-а преди съдържанието
$SiteView->top('Начало');
// Самото съдържание
$SiteView->General->db_c(); //Вземаме връзката за MySQL
//Променливи
$ui = $_SESSION['ui'];
$rs = $SiteView->General->bg_q('SELECT * FROM pages WHERE title="index"');
$row = mysql_fetch_assoc($rs);
//Показваме началната страница
echo '<h2>Начало</h2>' . $row['content'];
//Показваме футъра
$SiteView->footer();
?>