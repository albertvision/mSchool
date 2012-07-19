<?php
/*-------------------------------------------------------+
| mSchool V2
| Copyright (C) 2011 - 2012 Христо Димитров & Ясен Георгиев
| http://www.mschool.shareit.bg/
+--------------------------------------------------------+
| Име на файла: pages.php
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
//Ако няма GET парамтър
if (!isset($_GET)) {
    // Препраща към index.php
    $SiteView->General->redirect('index.php');
} else {
    // Казваме да искара HTML-а преди съдържанието
    $SiteView->top('Страници');
    
    $id = $_GET['id']; // Създаваме променливата $id, която е равна на GET параметъра id
    $rs = $SiteView->General->bg_q('SELECT * FROM pages WHERE id=' . $id); // Избираме страницата, чийто УН e стойността на променливата $id
    // Ако няма намерена страницата с УН, който е равен на $id
    if (mysql_num_rows($rs) == 0) {
        // Препраща към index.php
        $SiteView->General->redirect('index.php');
    }
    // Вземаме всичката информация за страницата
    $row = mysql_fetch_assoc($rs);
    // Казваме, че $cont е равен на $row['content'];
    $cont = $row['content'];
    // В $cont търсим наклонена черта, заместваме я с нищо
    $cont = str_replace('\\', '', $cont);
    // Показваме съдържанието на страницата
    echo '<h2>' . $row['title'] . '</h2><br />' . $cont;
    // Показваме футъра
    $SiteView->footer();
}
?>