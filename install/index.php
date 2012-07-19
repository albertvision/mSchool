<?php
/*-------------------------------------------------------+
| mSchool V1.02
| Copyright (C) 2011 - 2012 Христо Димитров & Ясен Георгиев
| http://www.mschool.shareit.bg/
+--------------------------------------------------------+
| Име на файла: install/index.php
+--------------------------------------------------------+
| Тази програма се реализира като свободен софтуер
| под лиценза Affero GPL. Вие можете да разпространявате
| и/или да модифицирате mSchool под правилата на GNU/GPL.
| Лиценза можете да го прочете във файла licene.txt.
| Ако искате да премахнете текста кой е създал системата,
| трява да се свържeте със създателите на mSchool.
+--------------------------------------------------------*/

function note($message)
{
    echo $message.'<br />';
}
function escape($string)
{
    return htmlspecialchars(mysql_real_escape_string($string));
}
error_reporting(0);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Инсталация на mSchool</title>
<link rel="stylesheet" type="text/css" href="style.css" /> 
</head>
<body>
<center>
<div id="container"><img src="logo.png" alt="Лого на mSchool"/>
<div class="content">
<?php
if(isset($_POST['go']))
{
    $mysql['host'] = $_POST['mysql_host'];
    $mysql['username'] = $_POST['mysql_username'];
    $mysql['password'] = $_POST['mysql_password'];
    $mysql['db'] = $_POST['mysql_db'];
    $mysql['create_db'] = $_POST['mysql_create_db'];
    $admin['username'] = escape($_POST['admin_username']);
    $admin['password'] = escape($_POST['admin_password']);
    $admin['repeat_password'] = escape($_POST['admin_repeat_password']);
    $admin['email'] = escape($_POST['admin_email']);
    $admin['names'] = escape($_POST['admin_names']);
    $school['name'] = escape($_POST['school_name']);
    $school['town'] = escape($_POST['school_town']);
    $school['email'] = escape($_POST['school_email']);
    $school['address'] = escape($_POST['school_address']);
    $site['url'] = str_replace('install/','',$_SERVER['REQUEST_URI']);
    $site['url'] = 'http://'.$_SERVER['HTTP_HOST'].str_replace('index.php','',$site['url']);
    $site['path'] = str_replace('/install','',$_SERVER['SCRIPT_FILENAME']);
    $site['path'] = str_replace('index.php','',$site['path']);
    $site['description'] =escape( $_POST['site_description']);
    $site['keywords'] = escape($_POST['site_keywords']);
    if(strlen($mysql['host'])<4) {
        $mysql['error']['host']='Въвели сте твърде кратък сървър!';
    }
    if(strlen($mysql['username'])<4) {
        $mysql['error']['username']='Въвели сте твърде кратко потребителско име!';
    }
    if(strlen($mysql['db'])==0) {
        $mysql['error']['db']='Въвели сте твърде кратка база с данни!';
    }
    if(!mysql_connect($mysql['host'], $mysql['username'], $mysql['password']))
    {
        $mysql['error']['connect'] = 'Свързването към MySQL е <b>неуспешно</b>!';
    }
    if(!mysql_select_db($mysql['db']) && !$mysql['create_db'])
    {
        $mysql['error']['db'] = 'Избирането на базата с данни е <b>неуспешно</b>!';
    }
    if(strlen($admin['username'])<4)
    {
        $admin['error']['username']='Въвели сте твърде кратко потребителско име!';
    }
    if(strlen($admin['password'])<6)
    {
        $admin['error']['password']='Въвели сте твърде кратка парола!';
    }
    if($admin['password']!=$admin['repeat_password'])
    {
        $admin['error']['repeat_password'] = 'Двете пароли не съвпадат!';
    }
    if(!filter_var($admin['email'], FILTER_VALIDATE_EMAIL))
    {
        $admin['error']['email'] = 'Въвели сте невалидна е-поща';
    }
    if(strlen($admin['names'])<4)
    {
        $admin['error']['name']='Въвели сте твърде кратки имена!';
    }
    if(strlen($school['name'])<2)
    {
        $school['error']['name']='Въвели сте твърде кратко име!';
    }
    if(strlen($school['town'])<4)
    {
        $school['error']['town']='Въвели сте твърде кратко селище!';
    }
    if(!filter_var($school['email'], FILTER_VALIDATE_EMAIL))
    {
        $school['error']['email'] = 'Въвели сте невалидна е-поща!';
    }
    if(strlen($school['address'])<4)
    {
        $school['error']['address']='Въвели сте твърде кратък адрес!';
    }
    if(strlen($site['description'])<5)
    {
        $site['error']['description']='Въвели сте твърде кратко описание!';
    }
    if(strlen($site['keywords'])<3)
    {
        $site['error']['keywords']='Въвели сте твърде малко ключови думи!';
    }
    if($mysql['error'] || $admin['error'] || $site['error'])
    {
        echo '<h2>Възникнаха грешки:</h2>';
    }
    if($mysql['error'])
    {
        echo '<b>MySQL Грешки:</b><br />';
        foreach($mysql['error'] as $value)
        {
            echo $value.'<br />';
        }
        echo '<br />';
        $error['mysql']=TRUE;
    }
    if($admin['error'])
    {
        echo '<b>Грешки при главния администратор:</b><br />';
        foreach($admin['error'] as $value)
        {
            echo $value.'<br />';
        }
        echo '<br />';
        $error['admin']=TRUE;
    }
    if($school['error'])
    {
        echo '<b>Грешки при информацията на сайта:</b><br />';
        foreach($school['error'] as $value)
        {
            echo $value.'<br />';
        }
        echo '<br />';
        $error['site']=TRUE;
    }
    if($site['error'])
    {
        echo '<b>Грешки при настройката на сайта:</b><br />';
        foreach($site['error'] as $value)
        {
            echo $value.'<br />';
        }
        
        echo '<br />';
        $error['site']=TRUE;
    }
    if($error)
    {
        echo '<button onClick="location.href=\'index.php\'">Назад</button>';
    }
    if(!$error)
    {
        mysql_connect($mysql['host'], $mysql['username'], $mysql['password']);
        if($mysql['create_db']=='on')
        {
            mysql_query("CREATE DATABASE IF NOT EXISTS ".$mysql['db'].";");
        }
        mysql_select_db($mysql['db']);
        file_put_contents('../config.php', "<?php
\$host='" . $mysql['host'] . "'; //MySQL Сървър
\$user='" . $mysql['username'] . "'; //MySQL Потребител
\$password='" . $mysql['password'] . "'; //MySQL Парола
\$db='" . $mysql['db'] . "'; //База с данни
?>");
        $db[]="
            CREATE TABLE IF NOT EXISTS `links` (
              `id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'УН',
              `name` varchar(255) NOT NULL COMMENT 'Заглавие',
              `description` varchar(550) NOT NULL COMMENT 'Описание',
              `link` text NOT NULL COMMENT 'Линк',
              `added` int(18) NOT NULL COMMENT 'Добавен в TIMESTAMP',
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Линкове' AUTO_INCREMENT=1 ;";
        $db[]="
            INSERT INTO `links` (`name`, `description`, `link`, `added`) VALUES
            ('Социалната мрежа ShareIT', 'Българската социалната мрежа ShareIT', 'http://shareit.bg', 0),
            ('WEBLOZ', 'Национално състезание по уеб разработване &quot;WEBLOZ&quot; - гр. Лозница', 'http://webloz.net', 0);";
        $db[]="
            CREATE TABLE IF NOT EXISTS `logger` (
              `id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'УН',
              `log` text NOT NULL COMMENT 'Самият проблем',
              `page` varchar(550) NOT NULL COMMENT 'Страница където се е случил проблемът',
              `ip` varchar(15) NOT NULL COMMENT 'IP, при когото се е случил проблемът',
              `timestamp` int(18) NOT NULL COMMENT 'Времето в TIMESTAMP',
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Логове с грешки' AUTO_INCREMENT=1 ;";
        $db[]="
            CREATE TABLE IF NOT EXISTS `pages` (
              `id` int(7) NOT NULL AUTO_INCREMENT,
              `user_id` int(7) NOT NULL,
              `title` varchar(250) NOT NULL,
              `content` text NOT NULL,
              `tags` varchar(255) NOT NULL,
              `added` int(18) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Страници' AUTO_INCREMENT=1 ;";
        $db[]="
            INSERT INTO `pages` (`id`, `user_id`, `title`, `content`, `tags`, `added`) VALUES
            (1, 1, 'index', '<b>Здравейте! Това е началната страница! За да я промените отидете в админ панел -> страници -> Редактиране на Начална страница. Това е всичко!</b>', 'начална, примерна, страница', 0);";
        $db[]="
            CREATE TABLE IF NOT EXISTS `posts` (
              `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Номера на поста',
              `user_id` int(3) NOT NULL,
              `name` varchar(250) NOT NULL,
              `content` text NOT NULL,
              `tags` varchar(250) NOT NULL,
              `added` int(16) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Публикации' AUTO_INCREMENT=1 ;";
        $db[]="
            INSERT INTO `posts` (`id`, `user_id`, `name`, `content`, `tags`, `added`) VALUES
            (1, 1, 'Примерен пост', '<b>Здравейте! Това е съдържанието на един примерен пост. За да го редактирате или изтриете, отидете в Админ панел -> Публикации -> Редактиране/Изтриване. Това е всичко! До скоро</b>', 'примерен, пост, публикация', 0);";
        $db[]="
            CREATE TABLE IF NOT EXISTS `settings` (
            `name` varchar(1500) NOT NULL COMMENT 'Име на училището',
            `town` varchar(550) NOT NULL COMMENT 'Селище на училището',
            `email` varchar(550) NOT NULL COMMENT 'Е-поща на училището',
            `address` text NOT NULL COMMENT 'Адрес на училището',
            `description` text NOT NULL COMMENT 'Описание на сайта',
            `keywords` varchar(500) NOT NULL COMMENT 'Ключови думи на сайта',
            `url` varchar(1500) NOT NULL COMMENT 'Адрес на сайта',
            `path` varchar(1500) NOT NULL COMMENT 'Път към сайта',
            `date` varchar(50) NOT NULL DEFAULT 'd M Y' COMMENT 'Формат на датата',
            `time` varchar(50) NOT NULL DEFAULT 'H:i' COMMENT 'Формат на часа',
            `type` int(1) NOT NULL COMMENT 'Дали настройката е по подразбиране'
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Това е таблицата за настройки на сайта';";
        $db[]="
            INSERT INTO `settings` (`name`, `town`, `email`, `address`, `description`, `keywords`, `url`, `path`, `date`, `time`, `type`) VALUES
            ('".$school['name']."', '".$school['town']."', '".$school['email']."', '".$school['address']."', '".$site['description']."', '".$site['keywords']."', '".$site['url']."', '".$site['path']."', 'd M Y', 'H:i', 1),
            ('".$school['name']."', '".$school['town']."', '".$school['email']."', '".$school['address']."', '".$site['description']."', '".$site['keywords']."', '".$site['url']."', '".$site['path']."', 'd M Y', 'H:i', 2);";
        $db[]="
            CREATE TABLE IF NOT EXISTS `users` (
                `id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'Уникален номер',
                `username` varchar(255) NOT NULL COMMENT 'Потребителско име',
                `password` varchar(100) NOT NULL COMMENT 'Парола на потребителя',
                `email` varchar(1500) NOT NULL COMMENT 'Е-поща на потребителя',
                `names` varchar(1500) NOT NULL COMMENT 'Имена на потребителя',
                `ip` varchar(255) NOT NULL COMMENT 'IP на потребителя',
                `lastLogin` int(18) NOT NULL COMMENT 'Последен вход на потребителя',
                `registered` int(18) NOT NULL COMMENT 'Регистрация на потребителя',
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Потребители' AUTO_INCREMENT=1;";
        $db[]="
            INSERT INTO `users` (`id`,`username`, `password`, `email`, `names`, `ip`, `lastLogin`, `registered`) VALUES
            (1,'".$admin['username']."', '".hash_hmac('SHA256', $admin['password'], 'mSchool')."', '".$admin['email']."', '".$admin['names']."', '".$_SERVER['REMOTE_ADDR']."', 0, ".time().");
            ";
        mysql_query('SET NAMES utf8');
        foreach($db as $value)
        {
            mysql_query($value);
        }
        if(mysql_error())
        {
            echo '<h2>MySQL Грешки:</h2>'.mysql_error().'<br />';
            echo '<button onClick="location.href=\'index.php\'">Назад</button>';
        }
        else
        {
            echo '<h2>Инсталацията е успешна!</h2>
               <h4>ВАЖНО: Инсталационната програма е изтрита, за да предотвратят хакерски атаки!</h4>
                <button onClick="location.href=\'../\'">Към сайта</button> <button onClick="location.href=\'../admin\'">Към админ панела</button>';
            $dir=opendir(str_replace('index.php','',$_SERVER['SCRIPT_FILENAME']));
            while($file=readdir($dir))
            {
                if($file!='.' && $file!='..')
                {
                    unlink($file);
                }
            }
            rmdir(str_replace('index.php','',$_SERVER['SCRIPT_FILENAME']));
        }
    }
}
else
{
echo '<h2>Инсталационна програма на mSchool</h2>';
if(!file_exists('../config.php'))
{
    echo '<h4>Файлът <i>config.php</i> не съществува! Моля, създайте го и обновете тази страница.</h4><button onClick="location.href=\'\'">Обнови страницата</button>';
}
elseif(filesize('../config.php') != 0)
{
    echo '<h4>Инсталацията  е извършена! Ако не е, изтрийте съдържанието на файла <i>config.php</i> и обновете страницата.</h4><button onClick="location.href=\'\'">Обнови страницата</button> <button onClick="location.href=\'../\'">Назад към сайта</button>';
}
else
{
?>
Трябва да въведете нужните настройки, за да работи сайта Ви!
<form method="POST">
<table>
<tr><td colspan="2" class="title">MySQL Настройки<hr /></td></tr>
<tr><td>MySQL Хост:</td><td><input type="text" name="mysql_host" value="localhost"></td></tr>
<tr><td>MySQL Акаунт:</td><td><input type="text" name="mysql_username"></td></tr>
<tr><td>MySQL Парола</td><td><input type="password" name="mysql_password"></td></tr>
<tr><td>База с дани:</td><td><input type="text" name="mysql_db"></td></tr>
<tr><td>Създай БД:</td><td><input type="checkbox" name="mysql_create_db" checked></td></tr>
<tr><td colspan="2" class="title">Настройки на главния администратор<hr /></td></tr>
<tr><td>Потребителско име:</td><td><input type="text" name="admin_username"></td></tr>
<tr><td>Парола:</td><td><input type="password" name="admin_password"></td></tr>
<tr><td>Повтори паролата:</td><td><input type="password" name="admin_repeat_password"></td></tr>
<tr><td>Е-поща:</td><td><input type="text" name="admin_email"></td></tr>
<tr><td>Вашите имена:</td><td><input type="text" name="admin_names"></td></tr>
<tr><td colspan="2" class="title">Информация за училището<hr /></td></tr>
<tr><td>Име:</td><td><input type="text" name="school_name"></td></tr>
<tr><td>Селище:</td><td><input type="text" name="school_town"></td></tr>
<tr><td>Е-поща:</td><td><input type="text" name="school_email"></td></tr>
<tr><td>Адрес:</td><td><textarea rows="5" cols="40" name="school_address"></textarea></td></tr>
<tr><td colspan="2" class="title">Настройки на сайта<hr /></td></tr>
<tr><td>Описание:</td><td><textarea rows="5" cols="40" name="site_description"></textarea></td></tr>
<tr><td>Ключови думи:<br /><i>/разделяй със запетая/</i></td><td><textarea rows="5" cols="40" name="site_keywords"></textarea></td></tr>
<tr><td><input type="submit" name="go" value="Напред"</td></tr>
</table>
</form>
<?}
}?>
</div>
<div class="footer">
    &copy mSchool <?echo date('Y');?> · Проектът се разработва от Христо Димитров & Ясен Георгиев
</div>
</div>
</center>
</body>
</html>
