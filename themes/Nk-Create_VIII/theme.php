<?php 
include_once 'themes/Nk-Create_VIII/menu/menu.php';
function top() 
{
	global $nuked, $user, $file, $topMenu;
	
	$sql = mysql_query("SELECT ip FROM " . USER_TABLE . " WHERE ip ='" . $user[3] . "'");
	$nb_ip = mysql_num_rows($sql);
	list($ip_suers) = mysql_fetch_array($sql);
	if($nb_ip == 0) {	
            $dbuUserIpUpdate = ' UPDATE '.USER_TABLE.' 
                                 SET ip = "' . $user[3] . '"
                                 WHERE id = "' . $user[0] . '"';
            $dbeUserIpUpdate = mysql_query($dbuUserIpUpdate);
		}
	else if($user[3] != $ip_suers) {
            $dbuUserIpUpdate = ' UPDATE '.USER_TABLE.' 
                                 SET ip = "' . $user[3] . '"
                                 WHERE id = "' . $user[0] . '"';
            $dbeUserIpUpdate = mysql_query($dbuUserIpUpdate);
	}
            $dbuUserTimeUpdate = ' UPDATE '.USER_TABLE.' 
                                 SET lastDate = "' . time() . '"
                                 WHERE id = "' . $user[0] . '"';
            $dbeUserTimeUpdate = mysql_query($dbuUserTimeUpdate);
?>
<!DOCTYPE html>
<html lang="fr"> 
<head>
	<title><?php echo $nuked['name'] . ' - ' . $nuked['slogan']; ?></title>
	<meta charset="Windows-1252">
	<meta name="author" content="Stive">
	<meta name="keywords" lang="fr" content="<?php echo $nuked['keyword']; ?>" />
	<meta name="description" content="Communaute entraide codage Php, Mysql, Jquery, ajax" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
    <link rel="icon" type="image/png" href="themes/Nk-Create_VIII/img/favicon.png" />
    <link rel="stylesheet" type="text/css" href="themes/Nk-Create_VIII/css/bootstrap.css">
    <link rel=stylesheet href="themes/Nk-Create_VIII/css/bootstrap-responsive.css">
    <link rel="stylesheet" type="text/css" href="themes/Nk-Create_VIII/css/login.css">
    <link rel="stylesheet" type="text/css" href="themes/Nk-Create_VIII/css/flexslider.css">
    <link rel="stylesheet" type="text/css" href="themes/Nk-Create_VIII/css/prettyPhoto.css">
    <link rel=stylesheet href="themes/Nk-Create_VIII/css/style.css">
<?php
if(file_exists("themes/Nk-Create_VIII/css/modules/" . $_REQUEST['file'] . ".css")) {
echo '<link rel="stylesheet" type="text/css" href="themes/Nk-Create_VIII/css/modules/' . $_REQUEST['file'] . '.css">';
}
?> 
    <!--[if lt IE 9]>
    <link href="themes/Nk-Create_VIII/css/ie8.css" rel="stylesheet" type="text/css" />
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script type="text/javascript" src="themes/Nk-Create_VIII/js/jquery-1.9.0.js"></script>
    <script type="text/javascript" src="themes/Nk-Create_VIII/js/main.js"></script>
    <script type="text/javascript" src="themes/Nk-Create_VIII/js/jquery.flexslider.js"></script>
    <script type="text/javascript" src="themes/Nk-Create_VIII/js/jquery.prettyPhoto.js"></script>
    <script type="text/javascript" src="themes/Nk-Create_VIII/js/tinynav.min.js"></script>
    <script type="text/javascript" src='themes/Nk-Create_VIII/js/jquery.placeholder.min.js'></script>
    <script type="text/javascript" src='themes/Nk-Create_VIII/js/bootstrap.min.js'></script>
    <script type="text/javascript" src='themes/Nk-Create_VIII/js/jquery.ticker.js'></script>
<?php
if(file_exists("themes/Nk-Create_VIII/js/modules/" . $_REQUEST['file'] . ".js")) {
echo '<script type="text/javascript" src="themes/Nk-Create_VIII/js/modules/' . $_REQUEST['file'] . '.js"></script>';
}
?> 
</head>
<body>
<header>
    <div class='container'>
        <div class='row menu-line'>
            <div class='span7'>
                <nav>
                    <ul>
					<?php
					foreach($topMenu as $name => $links) {
					?>
						<li><a href="<?php echo $links; ?>"><?php echo $name; ?></a></li>
					<?php
					}
					?>
                    </ul>
                </nav>
            </div>
            <div class='span3 social-links'>
                <ul>                    
                    <li><a href="https://www.facebook.com/Nk.Create" class='facebook'>Facebook</a></li>
                    <li><a href="#" class='googleplus'>Google+</a></li>
                    <li><a href="#" class='pinterest'>Paypal</a></li>
                </ul>
            </div>
            <div class='span2 search-form'>
                <form action="index.php?file=Search&amp;op=mod_search" method="post">
					<div>
                         <input class='span2' type="text" name="main" placeholder="Recherche..." />
                         <input type="submit" name="submit" class="submit" value="<?php echo _INSEARCH; ?>" />
                    </div>
                </form>
            </div>
        </div>
        <div class='row breaking-news'>
            <div class='span2 title'>
               <span>Infos rapide</span>
            </div>
            <div class='span10 header-news'>
                <ul id="js-news" class="js-hidden">
                    <li class="news-item">Suite &agrave; des probl&egrave;mes internes</li>
                    <li class="news-item">Toutes les fonctionnalités du site</li>
                    <li class="news-item">Ne sont pas activer</li> 
                </ul>
            </div>
        </div>
        <div class='row logo-line'>
            <div class='span3 logo'>
                <a href="index.php">
                    <figure>
                        <img src="themes/Nk-Create_VIII/img/logo.png" alt="" />
                    </figure>
                </a>
            </div>
            <div class='span5 offset4 advertising hidden-phone'>
                <script type="text/javascript">
				<!--
					google_ad_client = "ca-pub-5176882397524933";
					google_ad_slot = "7801512555";
					google_ad_width = 468;
					google_ad_height = 60;
                //-->
                </script>
                <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
            </div>
        </div>
        <div class='row main-nav'>
            <div class='span12'>
                <nav>
                    <ul>
                        <li class='first-child'>
                            <div class='inner'>
                                <a href="index.php">Home</a>
                            </div>
                        </li>
                        
                        <li>
                            <div class='inner'>
                                <a href="#">Communaut&eacute;</a>
                                <div class='dropdown first-level'>
                                    <ul>
                                        <li>
                                            <div class='inner'>
                                                <a href="index.php?file=Forum">Forum</a>
                                            </div>
                                        </li>
                                        <li>
                                            <div class='inner'>
                                                <a href="index.php?file=Members">Membres</a>
                                            </div>
                                        </li>
                                        <li>
                                            <div class='inner'>
                                                <a href="index.php?file=Sections">Tutoriels</a>
                                            </div>
                                        </li>
                                        <li>
                                            <div class='inner'>
                                                <a href="Images">Images</a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        
                        
                        <li>
                            <div class='inner'>
                                <a href="index.php?file=Download">T&eacute;l&eacute;chargements</a>
                                <div class='dropdown first-level'>
                                    <ul>
                                        <li>
                                            <div class='inner'>
                                                <a href="index.php?file=Download">Modules</a>
                                            </div>
                                        </li>
                                        <li>
                                            <div class='inner'>
                                                <a href="index.php?file=Download">Patchs</a>
                                            </div>
                                        </li>
                                        <li>
                                            <div class='inner'>
                                                <a href="index.php?file=Suggest&amp;module=Download">Proposition</a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        
                        <li>
                            <div class='inner'>
                                <a href="#">Nos sites favoris</a>
                                <div class='dropdown first-level'>
                                    <ul>
                                        <li>
                                            <div class='inner'>
                                                <a href="http://www.nuked-klan.org">Nuked-Klan</a>
                                            </div>
                                        </li>
                                        <li>
                                            <div class='inner'>
                                                <a href="http://www.palacewar.eu">PalaceWaR</a>
                                            </div>
                                        </li>
                                        <li>
                                            <div class='inner'>
                                                <a href="http://www.nkhelp.fr">NkHelp</a>
                                            </div>
                                        </li>
                                        <li>
                                            <div class='inner'>
                                                <a href="index.php?file=Contact">Vous-ici ?</a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>  
                    	<li><div class='inner'><a href="index.php?file=Porno">+ 18ans</a></div></li>                                      
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>

<?php
if($_REQUEST['file'] == 'News') { $span = 'span8'; }
elseif($_REQUEST['file'] == 'Forum') { $span = 'span12'; }
else{ $span = 'span12'; }
?>

<div id="main">
    <div class='container'>
        <div class='row'>
            <div class='content <?php echo $span; ?> blog-style'>

<?php
}
function footer()
{
	global $nuked, $op, $file, $page, $user, $language, $visiteur;
?>

            </div>
<?php
if($_REQUEST['file'] == 'News') {
?>
            <aside class='span4'>
            
              	<?php get_blok('gauche'); ?>

                <div class="widget">
                    <div class='inner'>
                        <h3>Suivez-nous sur Facebook</h3>
                        <div class="follow-us">
                        <iframe src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2FNk.Create&amp;width=292&amp;height=258&amp;show_faces=true&amp;colorscheme=light&amp;stream=false&amp;border_color=%23fff&amp;header=false&amp;appId=225987124123858" style="border:none; overflow:hidden; width:292px; height:258px;"></iframe>
                        </div>
                    </div>
                </div>
  	    		
				<?php get_blok('droite'); ?>

            </aside>
<?php
}
?>
        </div>
    </div>
</div>

<div class='sub-footer'>
    <div class='container'>
        <div class='row'>
            <div class='span8 copyright'>
                Copyright &copy; <a href="http://www.nk-create.be">Nk-Create</a>. Template Developed by TeoThemes.
            </div>
            <div class='span2 social-links'>
                <ul>
                    <li><a href="https://www.facebook.com/Nk.Create" class='facebook'>Facebook</a></li>
                    <li><a href="#" class='googleplus'>Google+</a></li>
                    <li><a href="#" class='pinterest'>Paypal</a></li>
                </ul>
            </div>
            <div class='span2 copyright' style="text-align:right;">
                <a href="http://www.nuked-klan.org">Propuslé par Nuked-Klan</a>
            </div>
        </div>
        <a href='#' class='back-to-top'>Scroll Top</a>
    </div>
</div>

          <script type="text/javascript">(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/fr_FR/all.js#xfbml=1";
            fjs.parentNode.insertBefore(js, fjs);
          }(document, 'script', 'facebook-jssdk'));
          </script>
          <script type="text/javascript">
          	window.___gcfg = {lang: 'fr'};
          	(function() {
				var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
				po.src = 'https://apis.google.com/js/plusone.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
          	})();
</script>
		  <script type="text/javascript">
          	var _gaq = _gaq || [];
          	_gaq.push(['_setAccount', 'UA-25332959-6']);
          	_gaq.push(['_setDomainName', 'web-trick.eu']);
          	_gaq.push(['_trackPageview']);
          	(function() {
          		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
          	})();
		  </script>
</body>
</html>
<?php
		
exit();
}
function news($data) 
{ 
	 $posted = "<a href=\"index.php?file=Members&amp;op=detail&amp;autor=" . urlencode($data['auteur']) . "\">" . $data['auteur'] . "</a>";
	 ($data['nb_comment'] == 0) ? $s = '': $s = 's';
	 $comment = "<a href=\"index.php?file=News&amp;op=index_comment&amp;news_id=" . $data['id'] . "\">" . $data['nb_comment'] . " Comment".$s."</a>";
	 $ReadMore = "<a class=\"readmore\" title=\"" . $data['titre'] . "\" href=\"index.php?file=News&amp;op=index_comment&amp;news_id=" . $data['id'] . "\">Lire la suite</a>";
	 $ending = "<p style=\"text-align: right;\"><a href=\"index.php?file=News&amp;op=index_comment&amp;news_id=" . $data['id'] . "\">  ... [Suite]</a></p>";
	 $data['texte'] = nkCutText($data['texte'], 270, $ending);
	 $data['cat'] = htmlentities($data['cat']);
	 $title = "<a href=\"index.php?file=News&amp;op=index_comment&amp;news_id=" . $data['id'] . "\">" . $data['titre'] . "</a>";
?>
                <article>
                    <div class='inner'>
                        <div class='text'>
                            <div class='inner-border'>
                                <div class="title"><?php echo $title; ?></div>
                                <div class='description'>
                                    <div class='date'>
										<?php echo $data['nb_comment']; ?> commentaire<?php echo $s; ?>, <?php echo $data['date']; ?>, by <?php echo $data['auteur']; ?>
                                        <div class="hidden-phone" style="float:right;" >
                                            <div class="g-plusone" data-size="medium" data-align="right" data-href="index.php?file=News&amp;op=index_comment&amp;news_id=<?php echo $data['id']; ?>"></div>
                                        	<div class="fb-like" data-href="index.php?file=News&amp;op=index_comment&amp;news_id=<?php echo $data['id']; ?>" data-send="false" data-layout="button_count" data-width="100" data-show-faces="true">
                                            </div>
                                        </div>
                                    </div>
                                    <div class='excerpt'>
                                        <?php echo $data['texte']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
<?php  
}
function block_gauche($block) 
{ 
?>
                <div class="widget">
                    <div class='inner'>
                        <?php echo $block['content']; ?>
                    </div>
                </div>
<?php 
}
function block_droite($block) 
{ 
?>
                <div class="widget">
                    <div class='inner'>
                        <h3><?php echo $block['titre']; ?></h3>
                        <div class="BlockContent">
                        	<?php echo $block['content']; ?>
                        </div>
                    </div>
                </div>
<?php 
}
function block_centre($block) 
{  

?>

<?php 
}
function block_bas($block) 
{ 
?>

<?php 
}
function opentable() 
{  
?>
<?php 
}
function closetable() 
{ 
?>

<?php  
}
     function nkCutText($text, $length, $ending = '...', $exact = false) {
        if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
            return $text;
        }
        preg_match_all('/(<.+?>)?([^<>]*)/is', $text, $matches, PREG_SET_ORDER);
        $total_length = 0;
        $arr_elements = array();
        $truncate     = '';
        foreach($matches as $element) {
            if (!empty($element[1])) {
                if(preg_match('/^<\s*.+?\/\s*>$/s', $element[1])) {
                } elseif(preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $element[1], $element2)) {
                    $pos = array_search($element2[1], $arr_elements);
                    if($pos !== false) {
                        unset($arr_elements[$pos]);
                    }
                } elseif(preg_match('/^<\s*([^\s>!]+).*?>$/s', $element[1], $element2)) {
                    array_unshift($arr_elements,
                    strtolower($element2[1]));
                }
                $truncate .= $element[1];
            }
            $content_length = strlen(preg_replace('/(&[a-z]{1,6};|&#[0-9]+;)/i', ' ', $element[2]));
            if ($total_length >= $length) {
                break;
            } elseif ($total_length+$content_length > $length) {
                $left = $total_length>$length?$total_length-$length:$length-$total_length;
                $entities_length = 0;
                if(preg_match_all('/&[a-z]{1,6};|&#[0-9]+;/i', $element[2], $element3, PREG_OFFSET_CAPTURE)) {
                    foreach($element3[0] as $entity) {
                        if($entity[1]+1-$entities_length <= $left) {
                            $left--;
                            $entities_length += strlen($entity[0]);
                        } else break;
                    }
                }
                $truncate .= substr($element[2], 0, $left+$entities_length);
                break;
            } else {
                $truncate .= $element[2];
                $total_length += $content_length;
            }
        }
        if (!$exact) {
            $spacepos = strrpos($truncate, ' ');
            if (isset($spacepos)) {
                $truncate = substr($truncate, 0, $spacepos);
            }
        }
        $truncate .= $ending;
        foreach($arr_elements as $element) {
            $truncate .= '</' . $element . '>';
        }
        return $truncate;
    }
	
?>