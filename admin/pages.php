<?php
/*-------------------------------------------------------+
| mSchool V2
| Copyright (C) 2011 - 2012 Христо Димитров & Ясен Георгиев
| http://www.mschool.shareit.bg/
+--------------------------------------------------------+
| Име на файла: admin/pages.php
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
$AdminView->top('Страници');

// Ако GET параметър act и стойността му е new
if(isset($_GET['act'])=='new')
{
    // Ако има заявка през POST
    if(isset($_POST['add']))
    {
        // Променливи
        $name=mysql_real_escape_string(trim($_POST['name'])); // Вземаме стойността на полето name
        $tags=mysql_real_escape_string(trim($_POST['tags'])); // Вземаме стойността на полето tags
        $post=mysql_real_escape_string(trim($_POST['post'])); // Вземаме стойността на полето post
        // Ако дължината на $name е по-малка от 3 символа
        if(strlen($name)<3)
        {
            $error['1']=$AdminView->General->note('Твърде кратко име на страницата!');
        }
        // Ако дължината на $name е повече от 255 символа
        if(strlen($name)>255)
        {
            $error['2']=$AdminView->General->note('Твърде дълго име на страницата!');
        }
        // Ако дължината на $post е по-малка от 5 символа
        if(strlen($post)<5)
        {
            $error['content']=$AdminView->General->note('Твърде кратко съдържание!');
        }
        // Ако няма грешки
        if(!$error)
        {
            // Добавя страницата
            $AdminView->General->bg_q('INSERT INTO pages (user_id,title,content,tags,added) VALUES('.$_SESSION['ui']['id'].',"'.$AdminView->purifier->purify($name).'","'.$AdminView->purifier->purify($post).'","'.$AdminView->purifier->purify($tags).'",'.  time().')');
            // Препраща към pages.php
            $AdminView->General->redirect('pages.php');
        }
    }
    // Формата
    echo '<form method="POST"><table>
            <tr><td>Име:</td><td><input type="text" name="name" value="'.$name.'"></td></tr>
            <tr><td>Ключови думи:</td><td><input type="text" name="tags" value="'.$tags.'"></td></tr>
            <tr><td colspan="2"><textarea id="elm2" name="post">'.$post.'</textarea></td></tr>
            <tr><td><input type="submit" name="add" value="Добави"></td><td></td></tr>
        </table></form>';
}
// Ако GET параметър edit
elseif(isset($_GET['edit']))
{
    $id=intval($_GET['edit']); // Създаваме променливата $id, която е равна на GET параметъра edit
    // Вземаме страницата с УН, който е равен на $id
    $rs=$AdminView->General->bg_q('SELECT * FROM pages WHERE id='.$id);
    // Вземаме информацията за страницата
    $row=mysql_fetch_assoc($rs);
    // Ако не е открита страница
    if(mysql_num_rows($rs)==0)
    {
        echo 'Страницата не съществува!';
    }
    // Ако обаче е открита
    else
    {
        // Ако има заявка през POST
        if(isset($_POST['add']))
        {
            // Променливи
			// Ако редактираме началната страница
			if($id==1)
			{
			    $name='index';
			}
			else //Ако редактираме друга страница
			{
				$name=mysql_real_escape_string(trim($_POST['name'])); // Вземаме стойността на полето name
			}
            $tags=mysql_real_escape_string(trim($_POST['tags'])); // Вземаме стойността на полето tags
            $post=mysql_real_escape_string(trim($_POST['post'])); // Вземаме стойността на полето post
            // Ако дължината на $name е по-малка от 3 символа
            if(strlen($name)<3)
            {
                $error['1']=$AdminView->General->note('Твърде кратко име на страницата!');
            }
            // Ако дължината на $name е повече от 255 символа
            if(strlen($name)>255)
            {
                $error['2']=$AdminView->General->note('Твърде дълго име на страницата!');
            }
            // Ако дължината на $post е по-малка от 5 символа
            if(strlen($post)<5)
            {
                $error['content']=$AdminView->General->note('Твърде кратко съдържание!');
            }
            // Ако няма грешки
            if(!$error)
            {
                // Обновява страницата
                $AdminView->General->bg_q('UPDATE pages SET title="'.$AdminView->purifier->purify($name).'",content="'.$AdminView->purifier->purify($post).'",tags="'.$AdminView->purifier->purify($tags).'" WHERE id='.$row['id']);
                // Препраща към pages.php?edit=$id
                $AdminView->General->redirect('pages.php?edit='.$id);
            }
        }
        // Ако УН на страницата е 1, забранява се редактирането на заглавието ѝ!
        if($row['id']==1)
        {
            $disabled='disabled';
        }
        // Форма
        echo '<form method="POST"><table>
            <tr><td>Име:</td><td><input type="text" name="name" '.$disabled.' value="'.$row['title'].'"></td></tr>
            <tr><td>Ключови думи:</td><td><input type="text" name="tags" value="'.$row['tags'].'"></td></tr>
            <tr><td colspan="2"><textarea id="elm2" name="post">'.$row['content'].'</textarea></td></tr>
            <tr><td><input type="submit" name="add" value="Промени"></td><td><input type="button" value="Назад" onClick="location.href=\'pages.php\'"></td></tr>
            </table></form>';
    }
    echo '<br />';
}
// Ако има GET параметър remove
elseif(isset($_GET['remove'])){
    // Създаваме променливата $id, която е равна на GET параметъра remove
    $id=intval($_GET['remove']);
    // Вземаме страницата с УН, който е равен на $id
    $rs=$AdminView->General->bg_q('SELECT * FROM pages WHERE id='.$id);
    // Ако не е открита страница
    if(mysql_num_rows($rs)==0)
    {
        echo 'Страницата не съществува!';
    }
    // Ако обаче е открита
    else
    {
        echo 'Сигурни ли сте че искате да изтриете страницата? След това няма да можете да я възстановите!<br />
            <form method="POST"><input type="submit" name="yes" value="Да"><input type="submit" name="no" value="Не"</form>';
        // Ако е натиснат бутона "Да"
        if($_POST['yes'] && $id!=1)
        {
            // Изтрива страницата
            $AdminView->General->bg_q('DELETE FROM pages WHERE id = '.$id.' ');
            // Препраща към pages.php
            $AdminView->General->redirect('pages.php');
        }
        // Ако е натиснат бутона "Не"
        elseif($_POST['no'])
        {
            // Препраща към pages.php
            $AdminView->General->redirect('pages.php');
        }
        elseif($id==1)
        {
            $AdminView->General->redirect('pages.php');
        }
    }
    echo '<br />';
}
// Ако не е избрано сортиране
if(!$_GET['sortby'] || !$_GET['orderby'])
{
    // Избира всички страници
    $rs=$AdminView->General->bg_q('SELECT * FROM pages');
}
// Ако е избрано сортиране, страниците се подреждат
else
{
    $sort=$_GET['sortby'];
    $order=$_GET['orderby'];
    $rs=$AdminView->General->bg_q('SELECT * FROM pages ORDER BY id '.$order);
}
echo '<form method="GET">Сортирай по: <select name="sortby"><option value="id">Уникален номер</option><option value="name">Заглавие</option><option value="tags">Ключови думи</option><option value="added">Добавяне</option></select> >>> <select name="orderby"><option value="ASC">Възходящо</option><option value="DESC">Низходящо</option></select> <input type="submit" value="Давай"> <input type="button" value="Добави нова" onClick="location.href=\'pages.php?act=new\'"></form>';
echo '<table><tr><td>УН:</td><td>Име:</td><td>Ключови думи:</td><td>Автор:</td><td>Дата на добавяне:</td><td colspan="2">Опции:</td></tr>';
// Ако няма страници
if(mysql_num_rows($rs)==0)
{
    echo '<tr><td colspan="13" align="center">Няма добавени страници!</td><tr>';
}
// Създава цикъл, който обхожда всички страници
while($row=  mysql_fetch_assoc($rs))
{
    // Избира потребителя, чийто УН е равен на user_id в таблицата
    $rs2=$AdminView->General->bg_q('SELECT * FROM users WHERE id='.$row['user_id']);
    // Вземаме всичката информация за потребителя
    $row2=mysql_fetch_assoc($rs2);
    // Ако в MySQL "title" е "index", то тогава "index" се преименува на "Начална страница"
    if($row['title']=='index')
    {
        $row['title']='Начална страница';
    }
    echo '<tr><td>'.$row['id'].'</td><td>'.$row['title'].'</td><td>'.$row['tags'].'</td><td>'.$row2['name'].' '.$row2['lname'].' <b>['.$row2['username'].']</b></td><td>'.date($AdminView->settings['date'].' в '.$AdminView->settings['time'], $row['added']).'</td><td><a href="pages.php?edit='.$row['id'].'">Редактирай</a></td><td><a href="pages.php?remove='.$row['id'].'">Изтрий</a></td></tr>';
}
echo '</table>';
//Показваме футъра
$AdminView->footer();
?>