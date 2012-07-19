<?php
/*-------------------------------------------------------+
| mSchool V2
| Copyright (C) 2011 - 2012 Христо Димитров & Ясен Георгиев
| http://www.mschool.shareit.bg/
+--------------------------------------------------------+
| Име на файла: admin/index.php
+--------------------------------------------------------+
| Тази програма се реализира като свободен софтуер
| под лиценза Affero GPL. Вие можете да разпространявате
| и/или да модифицирате mSchool под правилата на GNU/GPL.
| Лиценза можете да го прочете във файла license.txt.
| Ако искате да премахнете текста кой е създал системата,
| трява да се свържeте със създателите на mSchool.
+--------------------------------------------------------*/

// Вземаме съдържанието на ../include/adminview.php
include '../includes/AdminView.php';
$AdminView=new AdminView();
// Казваме да искара HTML-а преди съдържанието
$AdminView->top('Начало');

// Избираме всички постове
$rs = $AdminView->General->bg_q('SELECT * FROM posts');
$nr = mysql_num_rows($rs);
// Избираме всички страници
$rs2 = $AdminView->General->bg_q('SELECT * FROM pages');
$nr2 = mysql_num_rows($rs2);
// Избираме всички линкове
$rs3 = $AdminView->General->bg_q('SELECT * FROM links');
$nr3 = mysql_num_rows($rs3);
// Избираме всички потребители
$rs4 = $AdminView->General->bg_q('SELECT * FROM users');
$nr4 = mysql_num_rows($rs4);
// Съдържание
echo '
<table class="home"><tr><td>
<div class="box">
<div class="title">Статистика</div>
<div class="content">Брой постове: ' . $nr . '<br />
Брой страници: ' . $nr2 . '<br />
Брой линкове: ' . $nr3 . '<br />
Брой потребители: ' . $nr4 . '</div>
</div></td><td>
<div class="box"><div class="title">Отиди</div>
<div class="content"><a href="posts.php">Виж всички постове</a><br />
<a href="pages.php">Виж всички страници</a><br />
<a href="links.php">Виж всички линкове</a><br />
<a href="users.php">Виж всички потребители</a></div>
</div></td></tr>
<tr><td>
<div class="box"><div class="title">Добави линк</div>
<div class="content"><form method="POST" action="links.php?act=new"><table>
<tr><td>Заглавие:</td><td><input type="text" name="name"></td></tr>
<tr><td>Описание:</td><td><input type="text" name="description"></td></tr>
<tr><td>Линк:</td><td><input type="text" name="link"></td></tr>
<tr><td><input type="submit" name="add" value="Добави"></td><td></td></tr>
</table></form>
</div></div></td>
<td>
<div class="box"><div class="title">Добави потребител</div>
<div class="content"><form method="POST" action="users.php?act=new"><table>
<tr><td>Потребителско име:</td><td><input type="text" name="username"></td></tr>
<tr><td>Парола:</td><td><input type="password" name="password"></td></tr>
<tr><td>Повтори паролата:</td><td><input type="password" name="spassword"></td></tr>
<tr><td>Е-поща:</td><td><input type="text" name="email"></td></tr>
<tr><td>Имена:</td><td><input type="text" name="names"></td></tr>
<tr><td><input type="submit" name="add" value="Добави"></td><td></td></tr>
</table></form>
</div></div>
</td></tr>
<tr><td colspan="2">
<div class="box"><div class="title">Добави пост</div>
<div class="content"><form method="POST" action="posts.php?act=new"><table>
<tr><td>Име:</td><td><input type="text" name="name"></td></tr>
<tr><td>Ключови думи:</td><td><input type="text" name="tags"></td></tr>
<tr><td colspan="2"><textarea id="elm2" name="post"></textarea></td></tr>
<tr><td><input type="submit" name="add" value="Добави"></td><td></td></tr>
</table></form>
</div></div></td></tr>
</table>
';

//Показваме футъра
$AdminView->footer();
?>