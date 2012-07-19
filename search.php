<?php
/*-------------------------------------------------------+
| mSchool V2
| Copyright (C) 2011 - 2012 Христо Димитров & Ясен Георгиев
| http://www.mschool.shareit.bg/
+--------------------------------------------------------+
| Име на файла: search.php
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
//Ако няма GET параметър
if (!isset($_GET)) {
    // Казваме да искара HTML-а преди съдържанието
    $SiteView->top('Търсене');
    // Показваме форма
    echo '<h2>Търсене:</h2><br />
        <form method="GET">
        <input type="text" name="search" style="width: 80%;" /> <span class="art-button-wrapper"><span class="art-button-l"></span><span class="art-button-r"></span><input type="submit" value="Търси" class="art-button"></span> 
        </form>';
}
// Ако обаче има GET параметър
else {
    // Казваме да създаде променлива $s, която да е равна на GET параметъра seacrh
    $s = $_GET['search'];
    // Ако стойността на $s е нищо
    if ($s == '') {
        // Препращаме към search.php?search=Всичко
        $SiteView->General->redirect('search.php?search=Всичко');
    }
    // Казваме да искара HTML-а преди съдържанието
    $SiteView->top('Резултати: ' . $s);
    echo '<h2>Резултати за "' . $s . '":</h2>';
    // Ако $s е Всичко
    if ($s == 'Всичко') {
        // Избираме всички постове
        $rs = $SiteView->General->bg_q('SELECT * FROM posts');
    }
    //Ако обаче не е така
    else {
        //Избираме всички постове, в чийто име, съдържание или тагове се съдържа стойността на $s
        $rs = $SiteView->General->bg_q('SELECT * FROM posts WHERE name LIKE "%' . $s . '%" OR content LIKE "%' . $s . '%" OR tags LIKE "%' . $s . '%"');
    }
    // Ако броят на резултатите е нулев
    if (mysql_num_rows($rs) == 0) {
        echo '<b>Няма резултати!</b>';
    }
    // Ако имаме един или повече резултата правим цикъл, който обхожда всички резултати
    while ($row = mysql_fetch_assoc($rs)) {
        $r = '<br /><h3><a href="posts.php?id=' . $row['id'] . '">' . $row['name'] . '</a></h3>' . $row['content'] . '<br />';
        $r = str_replace($s, '<b><font color="#FF0000">' . $s . '</font></b>', $r);
        echo $r;
    }
}

//Показваме футъра
$SiteView->footer();
?>