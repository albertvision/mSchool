<?php
/*-------------------------------------------------------+
| mSchool V2
| Copyright (C) 2011 - 2012 Христо Димитров & Ясен Георгиев
| http://www.mschool.shareit.bg/
+--------------------------------------------------------+
| Име на файла: admin/users.php
+--------------------------------------------------------+
| Тази програма се реализира като свободен софтуер
| под лиценза GNU/GPL. Вие можете да разпространявате
| и/или да модифицирате mSchool под правилата на GNU/GPL.
| Лиценза можете да го прочете във файла license.txt.
| Ако искате да премахнете текста кой е създал системата,
| трява да се свържeте със създателите на mSchool.
+--------------------------------------------------------*/

// Вземаме съдържанието на ../include/adminview.php
include '../includes/adminview.php';
$AdminView=new AdminView();
// Казваме да искара HTML-а преди съдържанието
$AdminView->top('Потребители');

// Ако GET параметър act и стойността му е new
if(isset($_GET['act'])=='new')
{
    // Ако има заявка през POST
    if(isset($_POST['add']))
    {
        // Променливи
        $username=$AdminView->General->escape(trim($_POST['username'])); // Вземаме стойността на полето username
        $password=$AdminView->General->escape(trim($_POST['password'])); // Вземаме стойността на полето password
        $spassword=$AdminView->General->escape(trim($_POST['spassword'])); // Вземаме стойността на полето spassword
        $cryptPass=$AdminView->General->crypt_pass($password); // Криптира паролата
        $email=$AdminView->General->escape(trim($_POST['email'])); // Вземаме стойността на полето email
        $names=$AdminView->General->escape(trim($_POST['names'])); // Вземаме стойността на полето names
        // Ако дължината на $username е по-малка от 3 символа
        if(strlen($username)<3)
        {
            $error[]=$AdminView->General->note('Твърде кратко потребителско име!');
        }
        // Ако дължината на $username е повече от 255 символа
        if(strlen($username)>255)
        {
            $error[]=$AdminView->General->note('Твърде дълго потребителско име!');
        }
        // Ако дължината на $password е по-малка от 5 символа
        if(strlen($password)<5)
        {
            $error[]=$AdminView->General->note('Твърде кратка парола!');
        }
        // Ако дължината на $password е повече от 15 символа
        if(strlen($password)>15)
        {
            $error[]=$AdminView->General->note('Твърде дълга парола!');
        }
        // Ако двете пароли не съвпадат
        if($password!=$spassword)
        {
            $error[]=$AdminView->General->note('Паролите не съвпадат!');
        }
        // Валидираме е-пощата
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $error[] = $AdminView->General->note('Въвели сте невалидна е-поща');
        }
        // Ако дължината на $name е по-малка от 3 символа
        if(strlen($names)<3)
        {
            $error[]=$AdminView->General->note('Твърде кратки имена!');
        }
        // Ако дължината на $name е повече от 255 символа
        if(strlen($names)>255)
        {
            $error[]=$AdminView->General->note('Твърде дълги имена!');
        }
        // Ако няма грешки
        if(!$error)
        {
            // Търси в MySQL дали има акаунти с потребителско име равно на променливата $username
            $rs=$AdminView->General->bg_q('SELECT * FROM users WHERE username="'.$username.'"');
            $row=mysql_fetch_assoc($rs);
            // Ако няма потребители с потребителско име равно на $username
            if(mysql_num_rows($rs)==0)
            {
                // Добавя потребителя
                $AdminView->General->bg_q('INSERT INTO users (username,password,email,names,ip,registered) VALUES("'.$username.'","'.$cryptPass.'","'.$email.'","'.$names.'","'.$_SERVER['REMOTE_ADDR'].'",'.time().')');
                // Препраща към users.php
                $AdminView->General->redirect('users.php');
            }
            //Ако има акаунти с потребителското име равно на $username
            else
            {
                $AdminView->General->alert('Вече съществува акаунт с това потребителско име!');
            }
        }
    }
    // Формата
    echo '<form method="POST"><table>
            <tr><td>Потребителско име:</td><td><input type="text" name="username" value="'.$username.'"></td></tr>
            <tr><td>Парола:</td><td><input type="password" name="password" value="'.$password.'"></td></tr>
            <tr><td>Повтори паролата:</td><td><input type="password" name="spassword" value="'.$spassword.'"></td></tr>
            <tr><td>Е-поща:</td><td><input type="text" name="email" value="'.$email.'"></td></tr>
            <tr><td>Имена:</td><td><input type="text" name="names" value="'.$names.'"></td></tr>
            <tr><td><input type="submit" name="add" value="Добави"></td><td></td></tr>
        </table></form>';
}
// Ако има GET параметър edit
elseif(isset($_GET['edit']))
{
    $id=intval($_GET['edit']); // Създаваме променливата $id, която е равна на GET параметъра edit
    // Вземаме потребителя, чийто УН, който е равен на $id
    $rs=$AdminView->General->bg_q('SELECT * FROM users WHERE id='.$id, '');
    // Вземаме информацията за потребителя
    $row=mysql_fetch_assoc($rs);
    // Ако не е открит потребител
    if(mysql_num_rows($rs)==0)
    {
        echo 'Страницата не съществува!';
    }
    // Ако обаче е открих
    else
    {
        // Ако има заявка през POST
        if(isset($_POST['edit']))
        {
            // Променливи
            $username=$row['username']; // Вземаме потребителското име
            $password=$AdminView->General->escape(trim($_POST['password'])); // Вземаме стойността на полето password
            $spassword=$AdminView->General->escape(trim($_POST['spassword'])); // Вземаме стойността на полето spassword
            $cryptPass=$AdminView->General->crypt_pass($password); // Криптира паролата
            $email=$AdminView->General->escape(trim($_POST['email'])); // Вземаме стойността на полето email
            $names=$AdminView->General->escape(trim($_POST['names'])); // Вземаме стойността на полето names
            // Ако сме въвели парола
            if($password != '')
            {
                // Ако дължината на $password е по-малка от 5 символа
                if(strlen($password)<5)
                {
                    $error[]=$AdminView->General->note('Твърде кратка парола!');
                }
                // Ако дължината на $password е повече от 15 символа
                if(strlen($password)>15)
                {
                    $error[]=$AdminView->General->note('Твърде дълга парола!');
                }
                // Ако двете пароли не съвпадат
                if($password!=$spassword)
                {
                    $error[]=$AdminView->General->note('Паролите не съвпадат!');
                }
            }
            else // Ако не сме въвели парола, обаче
            {
                $password=$row['password'];
                $cryptPass=$password;
            }
            // Валидираме е-пощата
            if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $error[] = $AdminView->General->note('Въвели сте невалидна е-поща');
            }
            // Ако дължината на $name е по-малка от 3 символа
            if(strlen($names)<3)
            {
                $error[]=$AdminView->General->note('Твърде кратки имена!');
            }
            // Ако дължината на $name е повече от 255 символа
            if(strlen($names)>255)
            {
                $error[]=$AdminView->General->note('Твърде дълги имена!');
            }
            // Ако няма грешки
            if(!$error)
            {
                // Обновява потребителя
                $AdminView->General->bg_q('UPDATE users SET username="'.$username.'",password="'.$cryptPass.'",email="'.$email.'",names="'.$names.'" WHERE id='.$row['id']);
                // Препраща към users.php?edit=$id
                $AdminView->General->redirect('users.php?edit='.$id);
            }
        }
        // Формата
        echo '<form method="POST"><table>
            <tr><td>Потребителско име:</td><td><input type="text" name="username" disabled value="'.$row['username'].'"></td></tr>
            <tr><td>Парола:</td><td><input type="password" name="password" value="'.$password.'"></td></tr>
            <tr><td>Повтори паролата:</td><td><input type="password" name="spassword" value="'.$spassword.'"></td></tr>
            <tr><td>Е-поща:</td><td><input type="email" name="email" value="'.$row['email'].'"></td></tr>
            <tr><td>Имена:</td><td><input type="text" name="names" value="'.$row['names'].'"></td></tr>
            <tr><td><input type="submit" name="edit" value="Промени"></td><td><input type="button" value="Назад" onClick="location.href=\'users.php\'"></td></tr>
            </table></form>';
    }
    echo '<br />';
}
// Ако има GET параметър remove
elseif(isset($_GET['remove'])){
    $id=intval($_GET['remove']); // Създаваме променливата $id, която е равна на GET параметъра edit
    // Вземаме потребителя, чийто УН, който е равен на $id
    $rs=$AdminView->General->bg_q('SELECT * FROM users WHERE id='.$id);
    // Ако не е открит потребител
    if(mysql_num_rows($rs)==0)
    {
        echo 'Потребителят не съществува!';
    }
    // Ако е открит потребителя
    else
    {
        echo 'Сигурни ли сте че искате да изтриете потребителя? След това не можете да ro възстановите!<br />
            <form method="POST"><input type="submit" name="yes" value="Да"><input type="submit" name="no" value="Не"></form>';
        // Ако е натиснат бутона "Да"
        if($_POST['yes'])
        {
            // Изтрива потребителя
            $AdminView->General->bg_q('DELETE FROM users WHERE id = '.$id.' ');
            // Препраща към users.php
            $AdminView->General->redirect('users.php');
        }
        // Ако е натиснат бутона "Не"
        elseif($_POST['no'])
        {
            // Препраща към users.php
            $AdminView->General->redirect('users.php');
        }
    }
    echo '<br />';
}
// Ако не е избрано сортиране
if(!$_GET['sortby'] || !$_GET['orderby'])
{
    $rs=$AdminView->General->bg_q('SELECT * FROM users');
}
// Ако е избрано сортиране, потребителите се подреждат
else
{
    $sort=$_GET['sortby'];
    $order=$_GET['orderby'];
    $rs=$AdminView->General->bg_q('SELECT * FROM users ORDER BY id '.$order);
}
echo '<form method="GET">Сортирай по: <select name="sortby"><option value="id">УН</option><option value="usernamename">Потребителско име</option><option value="email">Е-поща</option><option value="names">Имена</option><option value="registered">Регистрация</option></select> >>> <select name="orderby"><option value="ASC">Възходящо</option><option value="DESC">Низходящо</option></select> <input type="submit" value="Давай"> <input type="button" value="Добави нов" onClick="location.href=\'users.php?act=new\'"></form>';
echo '<table><tr><td>УН</td><td>Потребителско име:</td><td>Парола (хеширана):</td><td>Е-поща:</td><td>Имена:</td><td>Регистрация:</td><td colspan="2">Опции:</td></tr>';
// Ако не са открити добавени потребители
if(mysql_num_rows($rs)==0)
{
    echo '<tr><td colspan="13" align="center">Няма добавени потребили!</td><tr>';
}
// Създава цикъл, който обхожда всички потребители
while($row=  mysql_fetch_assoc($rs))
{
    echo '<tr><td>'.$row['id'].'</td><td>'.$row['username'].'</td><td>'.$row['password'].'</td><td><a href="mailto:'.$row['email'].'">'.$row['email'].'</a></td><td>'.$row['names'].'</td><td>'.date($AdminView->settings['date'].' в '.$AdminView->settings['time'], $row['registered']).'</td><td><a href="users.php?edit='.$row['id'].'">Редактирай</a></td><td><a href="users.php?remove='.$row['id'].'">Изтрий</a></td></tr>';
}
echo '</table>';
//Показваме футъра
$AdminView->footer();
?>