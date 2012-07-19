<?php
/*-------------------------------------------------------+
| mSchool V2
| Copyright (C) 2011 - 2012 Христо Димитров & Ясен Георгиев
| http://www.mschool.shareit.bg/
+--------------------------------------------------------+
| Име на файла: posts.php
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
//include 'includes/General.php';
$SiteView=new SiteView();
//$General=new General();
// Ако има GET параметър id
if (isset($_GET['id'])) {
    // Казваме да създаде променлива $id, която да е равна на GET параметъра ID
    $id = intval($_GET['id']);
    // Избираме поста, чийто УН e стойността на променливата $id
    $rs = $SiteView->General->bg_q('SELECT * FROM posts WHERE id='.$id);
	if(mysql_num_rows(mysql_query('SELECT * FROM posts WHERE id='.$id))!=0)
	{
		// Вземаме всичката информация за поста
		$row = mysql_fetch_assoc($rs);
		// Казваме да искара HTML-а преди съдържанието
		$SiteView->top($row['name']);
		// Избира потребителя, чийто УН е равен на user_id в таблицата
		$user_rs = $SiteView->General->bg_q('SELECT * FROM users WHERE id=' . $row['user_id']);
		// Вземаме всичката информация за потребителя
		$user = mysql_fetch_assoc($user_rs);
		//Показваме самият пост
		echo '<h2><a href="posts.php"><<</a> ' . $row['name'] . '</h2><div class="art-postheadericons art-metadata-icons"><img src="images/postdateicon.png" width="16" height="16" alt="" />' . date($SiteView->settings['date'] . ' в ' . $SiteView->settings['time'], $row['added']) . ' | <img src="images/postauthoricon.png" width="18" height="18" alt="" /> Автор: ' . $user['names'] . ' <b>[' . $user['username'] . ']</b>
		</div>' . $row['content'] . '<div class="art-postfootericons art-metadata-icons"><img src="images/posttagicon.png" width="18" height="18" alt="" /> Тагове: <b>' . $row['tags'] . '</b></div>';
	}
	else
	{
		$SiteView->top('Публикацията не е намерена');
		echo '<h2>Публикацията не е намерена</h2>';
	}
}
//Ако няма GET параметър
if (!$_GET) {
    // Казваме да искара HTML-а преди съдържанието
    $SiteView->top('Публикации');
    // Избираме всички постове и ги сортираме по най-скорощно добавяне
    $rs = $SiteView->General->bg_q('SELECT * FROM posts ORDER BY added DESC');
    // Правим един цикъл, с който обхождаме всички постове
    while ($row = mysql_fetch_assoc($rs)) {
        // Избира потребителя, чийто УН е равен на user_id в таблицата
        $user_rs = $SiteView->General->bg_q('SELECT * FROM users WHERE id=' . $row['user_id']);
        // Вземаме всичката информация за потребителя в променлива
        $user = mysql_fetch_assoc($user_rs);
        // Показва поста
        echo '<h2><a href="posts.php?id=' . $row['id'] . '">' . $row['name'] . '</a></h2>
            <div class="art-postheadericons art-metadata-icons"><img src="images/postdateicon.png" width="16" height="16" alt="" />' . date($SiteView->settings['date'].' в '.$SiteView->settings['time'], $row['added']) . ' | <img src="images/postauthoricon.png" width="18" height="18" alt="" /> Автор: ' . $user['names'] . ' <b>[' . $user['username'] . ']</b></div>
            ' . $row['content'] . '
            <div class="art-postfootericons art-metadata-icons"><img src="images/posttagicon.png" width="18" height="18" alt="" /> Тагове: <b>' . $row['tags'] . '</b></div><hr />';
    }
}
$SiteView->footer();
?>