<?php
/*-------------------------------------------------------+
| mSchool V2
| Copyright (C) 2011 - 2012 Христо Димитров & Ясен Георгиев
| http://www.mschool.shareit.bg/
+--------------------------------------------------------+
| Име на файла: includes/General.php
+--------------------------------------------------------+
| Тази програма се реализира като свободен софтуер
| под лиценза Affero GPL. Вие можете да разпространявате
| и/или да модифицирате mSchool V2 под правилата на GNU/GPL.
| Лиценза можете да го прочете във файла license.txt.
| Ако искате да премахнете текста кой е създал системата,
| трява да се свържeте със създателите на mSchool V2.
+--------------------------------------------------------*/

class General {
    public $_cpass;
    public $version='V2.5';
    
    public function __construct() {
		date_default_timezone_set('Europe/Sofia');
    }
    //Тази функция извиква JS alert
    public function alert($note) {
        echo '<script type="text/javascript">alert("' . $note . '")</script>';
    }

    //Тази функция е пренадзначена за препращане
    public function redirect($link) {
        header('Location: ' . $link);
    }

    //Тази функция показва даден текст като съобщение
    function note($msg) {
        echo '<center><div class="note">' . $msg . '</div></center><br />';
    }

    //Функция за генериране на пароли
    function crypt_pass($password) {
        //Криптираме я в SHA256 с ключова дума mSchool
        $c_pass = hash_hmac('SHA256', $password, 'mSchool');
        //Връщаме резултата от криптирането
        return $c_pass;
    }
    
    public function escape($string) {
        return mysql_real_escape_string($string);
    }

    //Функция за свързване с MySQL и избиране на БД
    function db_c() {
        //Вземаме съдържанието на config.php
        if(count(explode('admin',$_SERVER['SCRIPT_FILENAME']))>=2){
            include '../config.php';
        }
        else{
            include 'config.php';
        }
        //Свързваме се към MySQL Сървъра
        mysql_connect($host, $user, $password) or die('Ne moga da se svurja s MySQL!');
        //Избираме база с данни
        mysql_select_db($db) or die('Ne moga da izbera baza s danni!');
    }

    //Функция, която казва да записва и чете в MySQL с енкодинг UTF8
    function bg_q($q, $msg = null) {
        mysql_query('SET NAMES utf8');
        $q1 = mysql_query($q);
        //Ако има грешка със заявката към БД
        if (mysql_error()) {
            //Грешката се записва в БД
            mysql_query('INSERT INTO logger(log,ip,page,timestamp) VALUES("' . mysql_real_escape_string(mysql_error()) . '","' . mysql_real_escape_string($_SERVER['REMOTE_ADDR']) . '","' . mysql_real_escape_string($_SERVER['REQUEST_URI']) . '",' . time() . ')');
            echo '<b>Критична грешка! Моля, свържете с <a href="mailto:avbincco@gmail.com">нас</a>!</b>';
        }
        // Ако няма грешка със заявката към БД
        else {
            echo $msg;
            return $q1;
        }
        echo mysql_error()."<br>".$q;
    }

}

?>
