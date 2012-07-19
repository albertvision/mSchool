<?php
/*-------------------------------------------------------+
| mSchool V2
| Copyright (C) 2011 - 2012 Христо Димитров & Ясен Георгиев
| http://www.mschool.shareit.bg/
+--------------------------------------------------------+
| Име на файла: admin/posts.php
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
$AdminView->top('Публикации');

// Ако GET параметър act и стойността му е new
if (isset($_GET['act']) == 'new') {
    // Ако има заявка през POST
    if (isset($_POST['add'])) {
        // Променливи
        $name = $AdminView->General->escape(trim($_POST['name'])); // Вземаме стойността на полето name
        $tags = $AdminView->General->escape(trim($_POST['tags'])); // Вземаме стойността на полето tags
        $content = mysql_real_escape_string(trim($_POST['post'])); // Вземаме стойността на полето post
        // Ако дължината на $name е по-малка от 3 символа
        if (strlen($name) < 3) {
            $error['1'] = $AdminView->General->note('Твърде кратко име на страницата!');
        }
        // Ако дължината на $name е повече от 255 символа
        if (strlen($name) > 255) {
            $error['2'] = $AdminView->General->note('Твърде дълго име на страницата!');
        }
        // Ако дължината на $post е по-малка от 5 символа
        if (strlen($content) < 5) {
            $error['content'] = $AdminView->General->note('Твърде кратко съдържание!');
        }
        // Ако няма грешки
        if (!$error) {
            // Създава поста
            $AdminView->General->bg_q('INSERT INTO posts (user_id,name,content,tags,added) VALUES(' . $_SESSION['ui']['id'] . ',"' . $AdminView->purifier->purify($name) . '","' . $AdminView->purifier->purify($content) . '","' . $AdminView->purifier->purify($tags) . '",' . time() . ')');
            // Препраща към posts.php
            $AdminView->General->redirect('posts.php');
        }
    }
    // Формата
    echo '<form method="POST"><table>
            <tr><td>Име:</td><td><input type="text" name="name" value="' . $name . '"></td></tr>
            <tr><td>Ключови думи:</td><td><input type="text" name="tags" value="' . $tags . '"></td></tr>
            <tr><td colspan="2"><textarea id="elm2" name="post">' . $content . '</textarea></td></tr>
            <tr><td><input type="submit" name="add" value="Добави"></td><td></td></tr>
        </table></form>';
}
// Ако GET параметърът е edit
elseif (isset($_GET['edit'])) {
    // Създаваме променливата $id, която е равна на GET параметъра edit
    $id = intval($_GET['edit']);
    // Вземаме публикацията с УН, който е равен на $id
    $rs = $AdminView->General->bg_q('SELECT * FROM posts WHERE id=' . $id);
    // Вземаме информацията за публикация
    $row = mysql_fetch_assoc($rs);
    // Ако не е открита публикация
    if (mysql_num_rows($rs) == 0) {
        echo 'Постът не съществува';
    }
    // Ако обаче е открита
    else {
        // Ако има заявка през POST
        if (isset($_POST['edit'])) {
            // Променливи
            $name = $AdminView->General->escape(trim($_POST['name'])); // Вземаме стойността на полето name
            $tags = $AdminView->General->escape(trim($_POST['tags'])); // Вземаме стойността на полето tags
            $content = mysql_real_escape_string(trim($_POST['post'])); // Вземаме стойността на полето post
            // Ако дължината на $name е по-малка от 3 символа
            if (strlen($name) < 3) {
                $error['1'] = $AdminView->General->note('Твърде кратко име на страницата!');
            }
            // Ако дължината на $name е повече от 255 символа
            if (strlen($name) > 255) {
                $error['2'] = $AdminView->General->note('Твърде дълго име на страницата!');
            }
            // Ако дължината на $post е по-малка от 5 символа
            if (strlen($content) < 5) {
                $error['content'] = $AdminView->General->note('Твърде кратко съдържание!');
            }
            // Ако няма грешки
            if (count($error) == 0) {
                // Обновява поста
                $AdminView->General->bg_q('UPDATE posts SET name="' . $AdminView->purifier->purify($name) . '",content="' . $AdminView->purifier->purify($content) . '",tags="' . $AdminView->purifier->purify($tags) . '" WHERE id=' . $row['id']);
                // Препраща към posts.php?edit=$id
                $AdminView->General->redirect('posts.php?edit=' . $id);
            }
        }
        // Ако няма зяавка през POST
        else
        {
            // Променливи
            $name=$row['name'];
            $tags=$row['tags'];
            $content=$row['content'];
        }
        // Формата
        echo '<form method="POST"><table>
            <tr><td>Име:</td><td><input type="text" name="name" value="' . $row['name'] . '"></td></tr>
            <tr><td>Ключови думи:</td><td><input type="text" name="tags" value="' . $row['tags'] . '"></td></tr>
            <tr><td colspan="2"><textarea id="elm2" name="post">' . $row['content'] . '</textarea></td></tr>
            <tr><td><input type="submit" name="edit" value="Промени"></td><td><input type="button" value="Назад" onClick="location.href=\'posts.php\'"></td></tr>
            </table></form>';
    }
    echo '<br />';
}
// Ако има GET параметър remove
elseif (isset($_GET['remove'])) {
    // Създаваме променливата $id, която е равна на GET параметъра remove
    $id = intval($_GET['remove']);
    // Вземаме публикацията с УН, който е равен на $id
    $rs = $AdminView->General->bg_q('SELECT * FROM posts WHERE id=' . $id, '');
    // Ако не е открит пост с УН = $id
    if (mysql_num_rows($rs) == 0) {
        echo 'Постът не съществува!';
    }
    // Ако обаче е открит
    else {
        echo 'Сигурни ли сте че искате да изтриете поста? След това няма да можете да го възстановите!<br />
            <form method="POST"><input type="submit" name="yes" value="Да"><input type="submit" name="no" value="Не"></form>';
        // Ако е натиснат бутона "Да"
        if ($_POST['yes']) {
            // Изтрива поста
            $AdminView->General->bg_q('DELETE FROM posts WHERE id = ' . $id . ' ');
            // Препраща към posts.php
            $AdminView->General->redirect('posts.php');
        }
        // Ако е натиснат бутона "Не"
        elseif ($_POST['no']) {
            // Препраща към posts.php
            $AdminView->General->redirect('posts.php');
        }
    }
    echo '<br />';
}
// Ако не е избрано сортиране
if (!$_GET['sortby'] || !$_GET['orderby']) {
    // Избира всички постове
    $rs = $AdminView->General->bg_q('SELECT * FROM posts');
}
// Ако е избрано сортиране, то тогава постовете се подреждат
else {
    $sort = $_GET['sortby'];
    $order = $_GET['orderby'];
    $rs = $AdminView->General->bg_q('SELECT * FROM posts ORDER BY id ' . $order, '');
}
echo '<form method="GET">Сортирай по: <select name="sortby"><option value="id">Уникален номер</option><option value="name">Име</option><option value="tags">Ключови думи</option><option value="added">Добавяне</option></select> >>> <select name="orderby"><option value="ASC">Възходящо</option><option value="DESC">Низходящо</option></select> <input type="submit" value="Давай"> <input type="button" value="Добави нов" onClick="location.href=\'posts.php?act=new\'"></form>';
echo '<table><tr><td>УН:</td><td>Име:</td><td>Ключови думи:</td><td>Автор:</td><td>Дата на добавяне:</td><td colspan="2">Опции:</td></tr>';
// Ако няма постове
if (mysql_num_rows($rs) == 0) {
    echo '<tr><td colspan="13" align="center">Няма добавени постове!</td><tr>';
}
// Създава цикъл, който обхожда всички постове
while ($row = mysql_fetch_assoc($rs)) {
    // Избира потребителя, чийто УН е равен на user_id в таблицата
    $rs2 = $AdminView->General->bg_q('SELECT * FROM users WHERE id=' . $row['user_id']);
    // Вземаме всичката информация за потребителя
    $row2 = mysql_fetch_assoc($rs2);

    echo '<tr><td>' . $row['id'] . '</td><td>' . $row['name'] . '</td><td>' . $row['tags'] . '</td><td>' . $row2['names'] . ' <b>[' . $row2['username'] . ']</b></td><td>' . date($AdminView->settings['date'] . ' в ' . $AdminView->settings['time'], $row['added']) . '</td><td><a href="posts.php?edit=' . $row['id'] . '">Редактирай</a></td><td><a href="posts.php?remove=' . $row['id'] . '">Изтрий</a></td></tr>';
}
echo '</table>';
//Показваме футъра
$AdminView->footer();
?>