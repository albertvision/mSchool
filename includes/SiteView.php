<?php
/*-------------------------------------------------------+
| mSchool V2
| Copyright (C) 2011 - 2012 Христо Димитров & Ясен Георгиев
| http://www.mschool.shareit.bg/
+--------------------------------------------------------+
| Име на файла: includes/SiteView.php
+--------------------------------------------------------+
| Тази програма се реализира като свободен софтуер
| под лиценза Affero GPL. Вие можете да разпространявате
| и/или да модифицирате mSchool V2 под правилата на GNU/GPL.
| Лиценза можете да го прочете във файла license.txt.
| Ако искате да премахнете текста кой е създал системата,
| трява да се свържeте със създателите на mSchool V2.
+--------------------------------------------------------*/

include_once 'General.php';
class SiteView {
    // Правим няколко променливи публични, за да имаме достъп до тях от другите странизи
    public $global;
    public $settings;
    public $url;
    public $link;
    public $General;
    // Тази публична функция се изпънява автоматично при извиването на класа
    public function __construct() {
	//Казваме не PHP да показва САМО грешки от типа E_ERROR, E_WARNING или E_PARSE
        error_reporting(E_ERROR | E_WARNING | E_PARSE);
	// Стартираме сесия
        session_start();
	// Подреждаме кода
        ob_start();
	// Извикваме класа General()
	$this->General=new General();
	// Правим проверка дали конфигурационния файл е празен
        if (filesize('config.php') == 0) {
            // Ако е, препраща към папката install/
            $this->General->redirect('install/');
        }
        $this->General->db_c(); //Вземаме връзката за MySQL
    }
    
    // Чрез тази функция извикваме всичко преди съдържанието. Взимаме чрез променливата $title заглавието на страницата
    function top($title) {
        //Променливи
        $this->settings_r=$this->General->bg_q('SELECT * FROM settings WHERE type=1'); //Казваме на MySQL да избере всичко от таблицата settings където type=1
        $this->settings=mysql_fetch_assoc($this->settings_r); //Показваме резултатите от таблицата settings
        $this->url=$_SERVER['REQUEST_URI']; //Вземаeме текущата страница
        $this->link=str_replace('http://localhost','',$this->settings['url']);//  Взима текущия URL адрес
        // Извикваме функция htmlTags() със заглавието задаено по-отгоре от функцията top()
        $this->htmlTags1($title);
	// Следващите няколко реда служат за селектиране на страницата, в който се намираме в менюто
        if($this->url==$this->link) //Ако адресът е равен на /
        {
            $page='<a href="'.$this->link.'" >Начало</a>';
        }
        else //Ако обаче не е така
        {
            $page='<a href="'.$this->link.'index.php" >Начало</a>';
        }
        if($this->url==$this->link.'posts.php?id='.$_GET['id']) // Ако адресът е равен на posts.php?id=някакъв GET параметър
        {
            $page.='<a href="'.$this->link.'posts.php?id='.$_GET['id'].'" >Публикаци</a>';
        }
        else //Ако не е така
        {
            $page.='<a href="'.$this->link.'posts.php" >Публикаци</a>';
        }
        $rs=$this->General->bg_q('SELECT * FROM pages'); //Казваме да се избере всичко от таблицата pages в MySQL
        while ($row= mysql_fetch_assoc($rs)) //Правим цикъл, с който показваме всички страници от MyQL
        {
            // Ако сме отворили началната страница казваме да не се визуализира
            if($row['title']=='index')
            {
                $page.='';
            }
            else // Ако не сме отворили друга страница, тя ще се покаже в менюто
            {
                $page.='<a href="'.$this->link.'page.php?id='.$row['id'].'" >'.$row['title'].'</a>';
            }
        }
        $page.= '
            <a href="'.$this->link.'contacts.php" >Контакти</a>';
        $page=str_replace('<a href="'.$this->url.'" >', '<a href="'.$this->url.'" class="active">', $page); // Казваме да се селектира текущата страница
	// Показваме всичките страници
        echo $page;
	//Показваме лявото меню чрез функцията htmlTags2()
        $this->htmlTags2();
        $rs2=$this->General->bg_q('SELECT * FROM links'); //Избираме всички връзки
        while($row2=  mysql_fetch_assoc($rs2)) //Правим цикъл, за да се визуализират
        {
            echo '<li><a href="'.$row2['link'].'" title="'.$row2['description'].'">'.$row2['name'].'</a></li>'; //За всяка връзка се показва този HTML код
        } 
        // Показваме десния панел
        $this->htmlTags3();
        if($_SESSION['il']) //Ако имаме стартирана сесия
        {
	// Се показват линковете за администратора
            echo '<li><a href="admin/">Админ панел</a></li><li><a href="admin/posts.php">Публкации</a></li><li><a href="admin/pages.php">Страници</a></li><li><a href="admin/links.php">Връзки</a></li><li><a href="admin/users.php">Потребители</a></li><li><a href="logout.php?page='.$_SERVER['REQUEST_URI'].'">Изход</a></li>';
        }
        else //Ако обаче не е така
        {
            echo '<li><a href="login.php">Вход в системата</a></li><li><a href="http://mschool.shareit.bg" title="Сайтът на mSchool">Сайтът на mSchool</a></li>'; 
        }
	// Извикваме функцията htmlTags4(), с която се показват кодовете преди същинското съдържание
        $this->htmlTags4();
    }
    
    // Създаваме публична функция footer(), която се показа след съдържанието
    public function footer()
    {
	// Извикваме същинския футър
        $this->htmlTags5();
    }
    
    // Създаваме лична функция htmlTags1(). Тя е видима само за този клас.
    private function htmlTags1($title)
    {
        echo '<!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="BG" xml:lang="bg">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>'.$title.' | '.$this->settings['name'].' - '.$this->settings['town'].'</title>
            <meta name="description" content="'.$this->settings['description'].'" />
            <meta name="keywords" content="'.$this->settings['keywords'].'" />
            <meta name="generator" content="mSchool '.$this->General->version.'" />
            <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
            <link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
            <!-- SEO тагове -->
            <meta name="robots" content="all" />
            <meta name="robots" content="index, follow">
            <meta name="rating" content="general" />
            <meta name="coverage" content="worldwide" />
            <meta name="googlebot" content="index, follow" />
            <!-- Край на SEO таговете -->
        </head>
        <body>
            <div id="container">
                <!-- Меню -->
                <div id="menu">';        
    }
    
    // Лична функция htmlTags2(). Чрез нея се показва левия панел.
    private function htmlTags2()
    {
        echo '</div>
                <!-- Край на менюто -->
                <!-- Хедър -->
                <div id="header">
                    <!-- Лого -->
                    <div class="logo"></div>
                    <!-- Край на логото -->
                    <div class="school-info">
                        <div class="name"><a href="index.php">'.$this->settings['name'].'</a></div>
                        <div class="town">'.$this->settings['town'].'</div>
                    </div>
                </div>
                <div id="content">
                    <!-- Ляв панел -->
                    <div class="left-bar">
                        <!-- Панел 1 -->
                        <div class="panel">
                            <div class="title">Час / Календар</div>
                            <div class="content">
                                <object width="130" height="130" data="anaclock.swf">
                                    <param value="anaclock.swf" name="movie" />
                                    <param name="wmode" value="transparent">
                                    <embed width="130" height="130" menu="false" wmode="transparent" bgcolor="#FFFFFF" quality="high" src="anaclock.swf" />
                                </object>
                            </div>
                        </div>
                        <!-- Край на панел 1 -->
                        <!-- Панел 2 -->
                        <div class="panel">
                            <div class="title">Линкове</div>
                            <div class="content">
                                <ul>';
    }
    // Личната функция htmlTags3(). Чрез нея се показват HTML таговете преди съдържанието
    private function htmlTags3()
    {
        echo '
                                </ul>
                            </div>
                        </div>
                        <!-- Край на панел 2 -->
                    </div>
                    <!-- Край на левия панел -->
                    <!-- Десен панел -->
                    <div class="right-bar">
                        <!-- Панел 1 -->
                        <div class="panel">
                            <div class="title">Търсачка</div>
                            <div class="content"><form method="get" action="search.php"><input type="text" name="search" /><input type="submit" value="Търси"></form></div>
                        </div>
                        <!-- Край на панел 1 -->
                        <!-- Панел 2 -->
                        <div class="panel">
                            <div class="title">Служебни</div>
                            <div class="content">
                                <ul>
                                ';
    }
    // Тази лична функция htmlTags4() е за десния панел
    private function htmlTags4() {
        echo '                  
                                </ul>
                            </div>
                        </div>
                        <!-- Край на панел 2 -->
                    </div>
                    <!-- Край на десния панел -->
                    <!-- Съдържание -->
                    <div class="main-content">';
    }
    private function htmlTags5()
    {
        echo '
                    </div>
                    <!-- Край на съдържанието -->
                </div>
                <!-- Футър -->
                <div id="footer">
                    <div class="copyright">&copy; '.$this->settings['name'].' - '.$this->settings['town'].' '.date('Y').'<br />Всички права запазени!</div>
                </div>
                <!-- Създадено от -->
                <div id="createdby">Сайтът използва mSchool '.$this->General->version.'. Системата е създадена от Христо Димитров &amp; Ясен Георгиев. Тя се реализира като свободен софтуер под лиценза <a href="http://www.fsf.org/licensing/licenses/agpl-3.0.html">GNU Affero GPL</a> V3</div>
            </div>
        </body>
    </html>';
    }
}

?>
