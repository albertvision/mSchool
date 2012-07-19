<?php
/*-------------------------------------------------------+
| mSchool V2
| Copyright (C) 2011 - 2012 Христо Димитров & Ясен Георгиев
| http://www.mschool.shareit.bg/
+--------------------------------------------------------+
| Име на файла: includes/AdminView.php
+--------------------------------------------------------+
| Тази програма се реализира като свободен софтуер
| под лиценза Affero GPL. Вие можете да разпространявате
| и/или да модифицирате mSchool V2 под правилата на GNU/GPL.
| Лиценза можете да го прочете във файла license.txt.
| Ако искате да премахнете текста кой е създал системата,
| трява да се свържeте със създателите на mSchool V2.
+--------------------------------------------------------*/

include_once 'General.php';
include_once 'purify/HTMLPurifier.auto.php';
class AdminView {

    public $General;
    public $settings;
	public $purifier;
    public function __construct() {
        error_reporting(E_ERROR | E_WARNING | E_PARSE);
        session_start();
        ob_start();
        $this->General = new General();
        if (filesize('../config.php') == 0) {
            // Препраща към папката install/
            $this->General->redirect('../install/');
        }
		$this->General->db_c(); //Вземаме връзката за MySQL
		if(!$_SESSION['ui']) {
			$this->General->redirect('../');
		}
		// Конфигурираме HTML Purify
		$config = HTMLPurifier_Config::createDefault();
		$config->set('Core.Encoding', 'UTF-8'); // Даваме енкодинг UTF-8
		$config->set('HTML.Doctype', 'HTML 4.01 Transitional'); // Казваме да използва HTML 4.01 Transtitional
		$this->purifier = new HTMLPurifier($config);

    }
    
    public function top($title) {
        // Променливи
        $this->settings_r=$this->General->bg_q('SELECT * FROM settings WHERE type=1'); //Казваме на MySQL да избере всичко от таблицата settings където type=1
        $this->settings=mysql_fetch_assoc($this->settings_r); //Показваме резултатите от таблицата settings
        //HTML на сайта
        $this->htmlTags($title);
    }
    
    public function footer() {
        echo '
            </div><br /><br />
            <div id="footer">
                <div class="copyright">&copy; mSchool '.date('Y').' · Проектът се разработва от Христо Димитров и Ясен Георгиев</div>
                <div class="update"><a href="http://mschool.shareit.bg/?p=31">Провери за обновявания</a></div>
            </div>
            </div>
        </body>
    </html>';
    }
    
    private function htmlTags($title) {
        echo '<!DOCTYPE html>
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <title>' . $title . ' - Админ панел</title>        
            <link rel="stylesheet" type="text/css" href="styles.css" />
            <link rel="stylesheet" type="text/css" href="mbar.css" />
            <link rel="shortcut icon" href="../images/favicon.ico" />
            <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
            <script type="text/javascript" src="js/simple-dropdown-menu.js"></script>
            <script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
            <script type="text/javascript">
                <!--
                // O2k7 skin
                tinyMCE.init({
                    // General options
                    language : "bg",
                    mode : "exact",
                    elements : "elm2",
                    theme : "advanced",
                    skin : "o2k7",
                    plugins : "lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,autosave",

                    // Theme options
                    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
                    theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,|,insertdate,inserttime,preview,|,forecolor,backcolor",
                    theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
                    theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,restoredraft",
                    theme_advanced_toolbar_location : "top",
                    theme_advanced_toolbar_align : "left",
                    theme_advanced_statusbar_location : "bottom",
                    theme_advanced_resizing : true,

                    // Example content CSS (should be your site CSS)
                    content_css : "css/content.css",

                    // Drop lists for link/image/media/template dialogs
                    template_external_list_url : "lists/template_list.js",
                    external_link_list_url : "lists/link_list.js",
                    external_image_list_url : "lists/image_list.js",
                    media_external_list_url : "lists/media_list.js",

                    // Replace values for the template plugin
                    template_replace_values : {
                            username : "Some User",
                            staffid : "991234"
                    }
                });
                -->
            </script>
        </head>
        <body>
        <div id="container">
            <div id="head">
                <div class="tbar">
                    <div class="title">
                        ' . $title . ' - mSchool '.$this->General->version.'
                    </div>
                    <div class="buttons"><a href="#" onmouseover="document.getElementById(\'close\').src=\'images/3.png\'; return true" onmouseout="document.getElementById(\'close\').src=\'images/2.png\'; return true" onclick="document.getElementById(\'close\').src=\'images/4.png\'; window.close();"><img src="images/2.png" alt="Затвори mSchool" id="close"></a></div>
                </div>
                <div class="mbar">
                    <ul class="main">	
                        <li>
                            <a href="#" class="menu">Файл</a>
                            <ul class="sub">
                                <li>
                                    <a href="#">Нов</a>
                                    <ul class="sub">
                                        <li><a href="posts.php?act=new">Публикация</a></li>
                                        <li><a href="pages.php?act=new">Страница</a></li>
                                        <li><a href="links.php?act=new">Линкове</a></li>
                                        <li><a href="users.php?act=new">Потребител</a></li>
                                    </ul>
                                </li>
                                <li><a href="#" onClick="window.close()" class="last">Изход</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="main">	
                        <li>
                            <a href="#" class="menu">Отиди</a>
                            <ul class="sub">
                                <li><a href="index.php">Начало</a></li>
                                <li><a href="../" target="_blank">Към сайта</a></li>
                                <li><a href="posts.php">Публикации</a></li>
                                <li><a href="pages.php">Страници</a></li>
                                <li><a href="links.php">Линкове</a></li>
                                <li><a href="users.php">Потребители</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="main">	
                        <li>
                            <a href="#" class="menu">Настройки</a>
                            <ul class="sub">
                                <li><a href="settings.php">Главни</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="main">	
                        <li>
                            <a href="#" class="menu">Помощ</a>
                            <ul class="sub">
                                <li><a href="http://mschool.shareit.bg/">Сайт</a></li>
                                <li><a href="about.php">За проекта</a></li>
                            </ul>
                        </li>
                    </ul>
                    &thinsp;
                </div>
            </div>
            <div id="content">
            <div id="foo"></div>
            ';
    }

}

?>
