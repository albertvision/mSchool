<?php
/*-------------------------------------------------------+
| mSchool V2
| Copyright (C) 2011 - 2012 Христо Димитров & Ясен Георгиев
| http://www.mschool.shareit.bg/
+--------------------------------------------------------+
| Име на файла: admin/links.php
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
$AdminView->top('Линкове');

// Ако GET параметър act и стойността му е new
if(isset($_GET['act'])=='new')
{
    // Ако има заявка през POST
    if(isset($_POST['add']))
    {
        // Променливи
        $name=$AdminView->General->escape(trim($_POST['name'])); // Вземаме стойността на полето name
        $description=$AdminView->General->escape(trim($_POST['description'])); // Вземаме стойността на полето description
        $link=mysql_real_escape_string(trim($_POST['link'])); // Вземаме стойността на полето link
        // Ако дължината на $name е по-малка от 3 символа
        if(strlen($name)<3)
        {
            $error['1']=$AdminView->General->note('Твърде кратко име на линка!');
        }
        // Ако дължината на $description е по-малка от 5 символа
        if(strlen($description)<5)
        {
            $error['2']=$AdminView->General->note('Твърде кратко име на линка!');
        }
        // Ако линка е невалиден
        if(preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $link)==0)
        {
            $error['3']=$AdminView->General->note('Невалиден линк!');
        }
        // Ако няма никакви грешки
        if(!$error)
        {
            // Записваме лина
            $AdminView->General->bg_q('INSERT INTO links(name,description,link,added) VALUES("'.$name.'","'.$description.'","'.$link.'",'.  time().')');
            // Препращаме към links.php
            $AdminView->General->redirect('links.php');
        }
    }
    // Формата
    echo '<form method="POST"><table>
            <tr><td>Заглавие:</td><td><input type="text" name="name" value="'.$name.'"></td></tr>
            <tr><td>Описание:</td><td><input type="text" name="description" value="'.$description.'"></td></tr>
            <tr><td>Линк:</td><td><input type="text" name="link" value="'.$link.'"></td></tr>
            <tr><td><input type="submit" name="add" value="Добави"></td><td></td></tr>
        </table></form>';
}
// Ако GET параметър edit
elseif(isset($_GET['edit']))
{
    $id=intval($_GET['edit']); // Създаваме променливата $id, която е равна на GET параметъра edit
    // Вземаме линка с УН, който е равен на $id
    $rs=$AdminView->General->bg_q('SELECT * FROM links WHERE id='.$id);
    // Вземаме информацията за линк
    $row=mysql_fetch_assoc($rs);
    // Ако не е открит линк
    if(mysql_num_rows($rs)==0)
    {
        echo 'Линкът не съществува';
    }
    // Ако обаче ако е открит
    else
    {
        // Ако има заявка през POST
        if(isset($_POST['add']))
        {
            // Променливи
            $name=$AdminView->General->escape(trim($_POST['name'])); // Вземаме стойността на полето name
            $description=$AdminView->General->escape(trim($_POST['description'])); // Вземаме стойността на полето description
            $link=mysql_real_escape_string(trim($_POST['link'])); // Вземаме стойността на полето link
            // Ако дължината на $name е по-малка от 3 символа
            if(strlen($name)<3)
            {
                $error['1']=$AdminView->General->note('Твърде кратко име на линка!');
            }
            // Ако дължината на $description е по-малка от 5 символа
            if(strlen($description)<5)
            {
                $error['2']=$AdminView->General->note('Твърде кратко име на линка!');
            }
            // Ако линка е невалиден
            if(preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $link)==0)
            {
                $error['3']=$AdminView->General->note('Невалиден линк!');
            }
            // Ако няма никакви грешки
            if(!$error)
            {
                // Обновява линка
                $AdminView->General->bg_q('UPDATE links SET name="'.$name.'",description="'.$description.'",link="'.$link.'" WHERE id='.$row['id']);
                // Препраща към links.php?edit=$id
                $AdminView->General->redirect('links.php?edit='.$id);
            }
        }
        //Формата
        echo '<form method="POST"><table>
            <tr><td>Заглавие:</td><td><input type="text" name="name" value="'.$row['name'].'"></td></tr>
            <tr><td>Описание:</td><td><input type="text" name="description" value="'.$row['description'].'"></td></tr>
            <tr><td>Линк:</td><td><input type="text" name="link" value="'.$row['link'].'"></td></tr>
            <tr><td><input type="submit" name="add" value="Промени"></td><td><input type="button" value="Назад" onClick="location.href=\'links.php\'"></td></tr>
            </table></form>';
    }
    echo '<br />';
}
// Ако има GET параметър remove
elseif(isset($_GET['remove'])) {
    // Създаваме променливата $id, която е равна на GET параметъра remove
    $id=intval($_GET['remove']);
    // Вземаме линка с УН, който е равен на $id
    $rs=$AdminView->General->bg_q('SELECT * FROM links WHERE id='.$id);
    // Ако не е открит линк
    if(mysql_num_rows($rs)==0)
    {
        echo 'Линкът не съществува!';
    }
    // Ако обаче е открит
    else
    {
        echo 'Сигурни ли сте че искате да изтриете линка? След това няма да можете да го възстановите!<br />
            <form method="POST"><input type="submit" name="yes" value="Да"><input type="submit" name="no" value="Не"</form>';
        // Ако е натиснат бутона "Да"
        if(isset($_POST['yes']))
        {
            // Изтрива резултата
            $AdminView->General->bg_q('DELETE FROM links WHERE id = '.$id.' ');
            // Препраща към links.php
            $AdminView->General->redirect('links.php');
        }
        // Ако е натиснат бутона "Не"
        elseif(isset($_POST['no']))
        {
            // Препраща към links.php
            $AdminView->General->redirect('links.php');
        }
    }
    echo '<br />';
}

// Ако не е избрано сортиране
if(!isset($_GET['sortby']) || !isset($_GET['orderby']))
{
    // Избира всички линкове
    $rs=$AdminView->General->bg_q('SELECT * FROM links');
}
// Ако е избрано, си ги сортира
else
{
    $sort=$_GET['sortby'];
    $order=$_GET['orderby'];
    $rs=$AdminView->General->bg_q('SELECT * FROM links ORDER BY id '.$order);
}
echo '<form method="GET">Сортирай по: <select name="sortby"><option value="id">Уникален номер</option><option value="name">Заглавие</option><option value="description">Описание</option><option value="link">Линк</option><option value="added">Добавяне</option></select> >>> <select name="orderby"><option value="ASC">Възходящо</option><option value="DESC">Низходящо</option></select> <input type="submit" value="Давай"> <input type="button" value="Добави нов" onClick="location.href=\'links.php?act=new\'"></form>';
echo '<table><tr><td>УН:</td><td>Име:</td><td>Описание:</td><td>Линк:</td><td>Дата на добавяне:</td><td colspan="2">Опции:</td></tr>';
// Ако не са открити линкове
if(mysql_num_rows($rs)==0)
{
    echo '<tr><td colspan="13" align="center">Няма добавени линкове!</td><tr>';
}
// Създава цикъл, който обхожда всички линкове
while($row=  mysql_fetch_assoc($rs))
{
    echo '<tr><td>'.$row['id'].'</td><td>'.$row['name'].'</td><td>'.$row['description'].'</td><td>'.$row['link'].'</td><td>'.date($AdminView->settings['date'].' в '.$AdminView->settings['time'], $row['added']).'</td><td><a href="links.php?edit='.$row['id'].'">Редактирай</a></td><td><a href="links.php?remove='.$row['id'].'">Изтрий</a></td></tr>';
}
echo '</table>';
//Показваме футъра
$AdminView->footer();
?>