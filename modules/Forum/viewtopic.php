<?php 
// -------------------------------------------------------------------------//
// Nuked-KlaN - PHP Portal                                                  //
// http://www.nuked-klan.org                                                //
// -------------------------------------------------------------------------//
// This program is free software. you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License.           //
// -------------------------------------------------------------------------//
defined('INDEX_CHECK') or die;

global $nuked, $user, $language, $theme;

translate("modules/Forum/lang/" . $language . ".lang.php");
include("modules/Forum/template.php");

opentable();

$visiteur = $user ? $user[1] : 0;

$ModName = basename(dirname(__FILE__));
$level_access = nivo_mod($ModName);
if ($visiteur >= $level_access && $level_access > -1)
{
	define('EDITOR_CHECK', 1);

    $sql = mysql_query("SELECT nom, moderateurs, cat, level FROM " . FORUM_TABLE . " WHERE '" . $visiteur . "' >= niveau AND id = '" . $_REQUEST['forum_id'] . "'");
    $level_ok = mysql_num_rows($sql);

    $sql2 = mysql_query("SELECT titre, view, closed, annonce, last_post, auteur_id, sondage FROM " . FORUM_THREADS_TABLE . " WHERE forum_id = '" . $_REQUEST['forum_id'] . "' AND id = '" . $_REQUEST['thread_id'] . "'");
    $topic_ok = mysql_num_rows($sql2);

     // No user access
     if ($level_ok == 0) {
          echo "<br /><br /><div style=\"text-align: center;\">" . _NOACCESSFORUM . "</div><br /><br />";
     }
     // No topic exists
     else if ($topic_ok == 0) {
          echo "<br /><br /><div style=\"text-align: center;\">" . _NOTOPICEXIST . "</div><br /><br />";
     }
     // User access
     else {

          if ($user) {

               $SQL = "SELECT id FROM " . FORUM_THREADS_TABLE . " WHERE forum_id = " . (int) $_GET['forum_id'] . " ";
               $req = mysql_query($SQL) or die(mysql_error());
               $thread_table = array();
               while ($res = mysql_fetch_assoc($req)) {
                    $thread_table[] = $res['id'];
            } 

               $visit = mysql_query("SELECT user_id, thread_id, forum_id FROM " . FORUM_READ_TABLE . " WHERE user_id = '" . $user[0] . "'") or die(mysql_error());
               $user_visit = mysql_fetch_assoc($visit);
               $tid = substr($user_visit['thread_id'], 1); // Thread ID
               $fid = substr($user_visit['forum_id'], 1); // Forum ID
               if (!$user_visit || strrpos($user_visit['thread_id'], ',' . $_GET['thread_id'] . ',') === false || strrpos($user_visit['forum_id'], ',' . $_GET['forum_id'] . ',') === false) {

                    if (strrpos($user_visit['thread_id'], ',' . $_GET['thread_id'] . ',') === false)
                         $tid .= $_GET['thread_id'] . ',';

                    $read = false;
                    foreach ($thread_table as $thread) {
                         if (strrpos(',' . $tid, ',' . $thread . ',') === false){
                              $read = true;
        }
    } 

                    if (strrpos($user_visit['forum_id'], ',' . $_GET['forum_id'] . ',') === false && $read === false)
                         $fid .= $_GET['forum_id'] . ',';

                    // Insertion SQL du read
                    mysql_query("REPLACE INTO " . FORUM_READ_TABLE . " ( `user_id` , `thread_id` , `forum_id` ) VALUES ( '" . $user[0] . "' , '," . $tid . "' , '," . $fid . "' )") or die(mysql_error());
            } 
        }

        list($nom, $modos, $cat, $level) = mysql_fetch_array($sql);
        $nom = printSecuTags($nom);

        $sql_cat = mysql_query("SELECT nom FROM " . FORUM_CAT_TABLE . " WHERE id = '" . $cat . "'");
        list($cat_name) = mysql_fetch_array($sql_cat);
        $cat_name = printSecuTags($cat_name);

        if ($user && $modos != "" && strpos($modos, $user[0]) !== false)
        {
            $administrator = 1;
        } 
        else
        {
            $administrator = 0;
        } 

        list($titre, $read, $closed, $annonce, $lastpost, $topic_aid, $sondage) = mysql_fetch_array($sql2);
        $titre = printSecuTags($titre);
        $titre = nk_CSS($titre);

        $upd = mysql_query("UPDATE " . FORUM_THREADS_TABLE . " SET view = view + 1 WHERE forum_id = '" . $_REQUEST['forum_id'] . "' AND id = '" . $_REQUEST['thread_id'] . "'");

        $sql_next = mysql_query("SELECT id FROM " . FORUM_THREADS_TABLE . " WHERE last_post > '" . $lastpost. "' AND forum_id = '" . $_REQUEST['forum_id'] . "' ORDER BY last_post LIMIT 0, 1");
        list($nextid) = mysql_fetch_array($sql_next);

        if ($nextid != "")
        {
            $next = "<small><a href=\"index.php?file=Forum&amp;page=viewtopic&amp;forum_id=" . $_REQUEST['forum_id'] . "&amp;thread_id=" . $nextid . "\">" . _NEXTTHREAD . "</a> &gt;</small>";
        } 

        $sql_last = mysql_query("SELECT id FROM " . FORUM_THREADS_TABLE . " WHERE last_post < '" . $lastpost . "' AND forum_id = '" . $_REQUEST['forum_id'] . "' ORDER BY last_post DESC LIMIT 0, 1");
        list($lastid) = mysql_fetch_array($sql_last);

        if ($lastid != "")
        {
            $prev = "<small>&lt; <a href=\"index.php?file=Forum&amp;page=viewtopic&amp;forum_id=" . $_REQUEST['forum_id'] . "&amp;thread_id=" . $lastid . "\">" . _LASTTHREAD . "</a>&nbsp;</small>";
        } 
		

            $nb_mess_for_mess = $nuked['mess_forum_page'];
	
            $dbsForumCount = ' SELECT thread_id 
			                   FROM ' . FORUM_MESSAGES_TABLE . ' 
							   WHERE thread_id = "' . $_REQUEST['thread_id'] .'"';
            $dbeForumCount = mysql_query($dbsForumCount);
            $dbcForumCount = mysql_num_rows($dbeForumCount);

        if (!$_REQUEST['p']) $_REQUEST['p'] = 1;
        $start = $_REQUEST['p'] * $nb_mess_for_mess - $nb_mess_for_mess;

        if ($_REQUEST['highlight'] != "")
        {
            $url_page = "index.php?file=Forum&amp;page=viewtopic&amp;forum_id=" . $_REQUEST['forum_id'] . "&amp;thread_id=" . $_REQUEST['thread_id'] . "&amp;highlight=" . urlencode($_REQUEST['highlight']);
        } 
        else
        {
            $url_page = "index.php?file=Forum&amp;page=viewtopic&amp;forum_id=" . $_REQUEST['forum_id'] . "&amp;thread_id=" . $_REQUEST['thread_id'];
        } 

?>
<div id="fullwidth_main">
	<div class="singal_main">
		<div class="new-obj">
			<div class="cols1 clearfix">
				<div class="floatleft">
	
<?php
        if ($level == 0 || $visiteur >= $level || $administrator == 1)
        {
            if ($closed == 0 || $administrator == 1 || $visiteur >= admin_mod("Forum"))
            {
?>
				<a class="new" href="index.php?file=Forum&amp;page=post&amp;forum_id=<?php echo "" . $_REQUEST['forum_id'] . "&amp;thread_id=" . $_REQUEST['thread_id'] . ""; ?>">
					<?php echo _REPLY; ?>
				</a>
<?php
            } 
        } 
?>	
				</div>
				<div class="floatright">
<?php
    				if ($dbcForumCount > $nb_mess_for_mess) {
?>
    <ul class="pages">
<?php
    					numberForum($dbcForumCount, $nb_mess_for_mess, $url_page);
?>
    </ul>
<?php
    				}
?>
				</div>
			</div>
		</div>
<!-- Global du post  -->
		<div class="posts-obj" id="Forum">
			<div class="box01">
				<div class="cols1 clearfix">
					<div class="col1"></div>
					<div class="col3"></div>
				</div>
			</div>
<!-- Global du post 2 -->
			<div class="posts">
<!-- Global du post 2 -->
<!-- DEbut du post  -->
<?php		
        $sql4 = mysql_query("SELECT id, titre, auteur, auteur_id, auteur_ip, txt, date, edition, usersig, file  FROM " . FORUM_MESSAGES_TABLE . " WHERE thread_id = '" . $_REQUEST['thread_id'] . "' ORDER BY date ASC limit " . $start . ", " . $nb_mess_for_mess."");
        while (list($mess_id, $title, $auteur, $auteur_id, $auteur_ip, $txt, $date, $edition, $usersig, $fichier) = mysql_fetch_row($sql4))
        {

            $title = printSecuTags($title);            

            if ($_REQUEST['highlight'] != "")
            { 
                $string = trim($_REQUEST['highlight']);
                $string = printSecuTags($string);
                $title = str_replace($string, '<span style="color: #FF0000">' . $string . '</span>', $title);

                $search = explode(" ", $string);
                for($i = 0; $i < count($search); $i++)
                {
                    $tab = preg_split("`(<\w+.*?>)`", $txt, -1, PREG_SPLIT_DELIM_CAPTURE);

                    foreach ($tab as $key=>$val)
                    {
                        if (preg_match("`^<\w+`", $val)) $tab[$key] = $val;
                        else $tab[$key] = preg_replace("/$search[$i]/","<span style=\"color: #FF0000;\"><b>$0</b></span>", $val);
                    }

                    $txt = implode($tab);
                } 
            }

            if (strftime("%d %m %Y", time()) ==  strftime("%d %m %Y", $date)) $date = _FTODAY . "&nbsp;" . strftime("%H:%M", $date);
            else if (strftime("%d", $date) == (strftime("%d", time()) - 1) && strftime("%m %Y", time()) == strftime("%m %Y", $date)) $date = _FYESTERDAY . "&nbsp;" . strftime("%H:%M", $date);    
            else $date = _THE . ' ' . nkDate($date);

            $tmpcnt++ % 2 == 1 ? $color = $color1 : $color = $color2;
			
                $sq_user = mysql_query("SELECT pseudo, niveau, rang, avatar, signature, date, email, icq, msn, aim, yim, url, country, lastDate FROM " . USER_TABLE . " WHERE id = '" . $auteur_id . "'");
                $test = mysql_num_rows($sq_user);
                list($autor, $user_level, $rang, $avatar, $signature, $date_member, $email, $icq, $msn, $aim, $yim, $homepage, $country, $lastDate) = mysql_fetch_array($sq_user);
				
				$dbsForumCountMsg = '  SELECT id
                               FROM '.FORUM_MESSAGES_TABLE.'
                               WHERE auteur = "'.$autor.'"';
				$dbeForumCountMsg = mysql_query($dbsForumCountMsg);
				$nb_post = mysql_num_rows($dbeForumCountMsg);
				
				    $date_member = nkDate($date_member, TRUE);	
				
                    if ($rang > 0 && $nuked['forum_rank_team'] == "on")
                    {
                        $sql_rank_team = mysql_query("SELECT titre FROM " . TEAM_RANK_TABLE . " WHERE id = '" . $rang . "'");
                        list($rank_name) = mysql_fetch_array($sql_rank_team);
                        $rank_name = printSecuTags($rank_name);
                        $rank_image = "";
                    } 
                    else
                    {
                        if ($user_level >= admin_mod("Forum"))
                        {
                            $user_rank = mysql_query("SELECT nom, image FROM " . FORUM_RANK_TABLE . " WHERE type = 2");
                        } 
                        else if ($auteur_modo == 1)
                        {
                            $user_rank = mysql_query("SELECT nom, image FROM " . FORUM_RANK_TABLE . " WHERE type = 1");
                        } 
                        else
                        {
                            $user_rank = mysql_query("SELECT nom, image FROM " . FORUM_RANK_TABLE . " WHERE '" . $nb_post . "' >= post AND type = 0 ORDER BY post DESC LIMIT 0, 1");
                        } 

                        list($rank_name, $rank_image) = mysql_fetch_array($user_rank);
                        $rank_name = printSecuTags($rank_name);
                    } 
					
                    if ($avatar != "")
                    {
                        if ($avatar_resize == "off") $ar_ok = 0;
                        else if (preg_match("`http://`i", $avatar) && $avatar_resize == "local") $ar_ok = 0;
                        else  $ar_ok = 1;    
                        
                        if ($ar_ok == 1) $style = "style=\"border: 0; overflow: auto; max-width: " . $avatar_width . "px;  width: expression(this.scrollWidth >= " . $avatar_width . "? '" . $avatar_width . "px' : 'auto');\"";
                        else $style = "style=\"boder:0;\"";
                        
                        $showAvatar = "<img src=\"" . checkimg($avatar) . "\" " . $style . "alt=\"\" /><br />\n";
                    } 
                    else{
                        $showAvatar = " <img src=\"themes/Nk-Create_VIII/images/p115x115-1.gif\" alt=\"\" /><br />\n";
                    }
                    if ($visiteur >= admin_mod("Forum") || $administrator == 1) {
						$auteurIp = $auteur_ip;
					} else {
						$auteurIp = 'invisible';
					}
					
					if (empty($lastDate)) {
						$lastDate = 'inconnu';
					} else {
						$lastDate = nkDate($lastDate);
					}
			
?>
				<div class="box02">
					<div class="cols2 clearfix">
						<div class="col1"><?php echo "" . _POSTEDON . " " . $date . ""; ?></div>
						<div class="col2"># <?php echo $mess_id; ?></div>
					</div>
				</div>
				<div class="cols3 clearfix">
					<div class="col1">
						<div class="col1-inner">
							<div class="name">
								<a href="index.php?file=Members&amp;op=detail&amp;autor=<?php echo urlencode($autor); ?>"><?php echo $autor; ?></a>
							</div>
							<div class="role"><?php echo $rank_name; ?></div>
							<div class="photo"><?php echo $showAvatar; ?></div>
							<ul class="info">
								<li><strong><?php echo _REGISTERED; ?> : </strong> <?php echo $date_member; ?></li>
								<li><strong>derni&egrave;re visite : </strong> <?php echo $lastDate; ?></li>
								<li><strong><?php echo _MESSAGES; ?> : </strong> <?php echo $nb_post; ?></li>

								<li><strong>Avertissement :</strong> 0(0%)</li>
								<li><strong>Remerciement :</strong> 0</li>
								<li><strong>IP :</strong> <?php echo $auteurIp; ?></li>
							</ul>
							<div class="icons">
								<a href="index.php?file=Members&amp;op=detail&amp;autor=<?php echo urlencode($autor); ?>" title="<?php echo _SEEPROFIL; ?>">
									<img src="themes/Nk-Create_VIII/images/icon28.jpg" alt="" width="14" height="16" />
								</a>
								<a href="#" title="">
									<img src="themes/Nk-Create_VIII/images/icon29.jpg" alt="" width="14" height="16" />
								</a>
								<a href="#" title="Remerciement">
									<img src="themes/Nk-Create_VIII/images/icon30.jpg" alt="" width="14" height="16" />
								</a>
								<a href="#" title="Signaler ce message">
									<img src="themes/Nk-Create_VIII/images/icon31.jpg" alt="" width="14" height="16" />
								</a>
							</div>
						</div>
					</div>
					<div class="col2">
						<div class="t1"><?php echo $title; ?></div>
						<div class="detail" id="img_resize_forum">
							<?php echo $txt; ?>
						</div>
						<div class="cols01 clearfix">
							<div class="col01">
	<?php if($annulé == 'ok') {
	?>
	<a href="default.htm"><img src="themes/Nk-Create_VIII/images/facebook2.gif" alt="" width="6" height="13" /></a>
	<a href="default.htm"><img src="themes/Nk-Create_VIII/images/twitter2.gif" alt="" width="13" height="13" /></a>
	<a href="default.htm"><img src="themes/Nk-Create_VIII/images/rss2.gif" alt="" width="13" height="13" /></a>
	<a href="default.htm"><img src="themes/Nk-Create_VIII/images/googleplus2.gif" alt="" width="13" height="13" /></a>
	<a href="default.htm"><img src="themes/Nk-Create_VIII/images/flickr2.gif" alt="" width="15" height="13" /></a>
	<a href="default.htm"><img src="themes/Nk-Create_VIII/images/linkedin2.gif" alt="" width="11" height="13" /></a>
	<a href="default.htm"><img src="themes/Nk-Create_VIII/images/pinterest2.gif" alt="" width="13" height="13" /></a>
	<a href="default.htm"><img src="themes/Nk-Create_VIII/images/skype2.gif" alt="" width="13" height="13" /></a>
	<a href="default.htm"><img src="themes/Nk-Create_VIII/images/yahoo2.gif" alt="" width="12" height="13" /></a>
	<?php
	}
	?>
							</div>
							<div class="col02">
<?php
            if ($closed == 0 && $administrator == 1 || $visiteur >= admin_mod("Forum") || $visiteur >= $level)
            {
?>
				<a class="quote" href="index.php?file=Forum&amp;page=post&amp;forum_id=<?php echo "" . $_REQUEST['forum_id'] . "&amp;thread_id=" . $_REQUEST['thread_id'] . "&amp;mess_id=" . $mess_id . "&amp;do=quote"?>"><?php echo _REPLYQUOTE; ?></a>
<?php
            }

            if ($user && $auteur_id == $user[0] && $closed == 0 || $visiteur >= admin_mod("Forum") || $administrator == 1)
            {
?>
               <a data-mess_id="<?php echo $mess_id; ?>" class="jQueryEditForum quote" href="index.php?file=Forum&amp;page=post&amp;forum_id=<?php echo "" . $_REQUEST['forum_id'] . "&amp;mess_id=" . $mess_id . "&amp;do=edit"?>"><?php echo _EDITMESSAGE; ?></a>
<?php
            } 

            if ($visiteur >= admin_mod("Forum") || $administrator == 1)
            {
?>
                <a class="quote" href="index.php?file=Forum&amp;op=del&amp;mess_id=<?php echo "" . $mess_id . "&amp;forum_id=" . $_REQUEST['forum_id'] . "&amp;thread_id=" . $_REQUEST['thread_id'] . "" ?>"><?php echo _DELMESSAGE;?></a>
<?php
            } 
?>	
							</div>
						</div>
					</div>
				</div>
<?php
        } 	
?>
<!-- fin du post  -->
<!-- Global du post 2 -->
			</div>
<!-- Global du post 2 -->
<!-- Global du post  -->
		</div>
<!-- Global du post  -->
		<div class="reply-obj">
			<div class="t1">R&eacute;ponse rapide</div>
			<div class="t2">R&eacute;pondre &agrave; ce sujet</div>
			<div class="box01">
				<form method="post" action="index.php?file=Forum&amp;op=quickReply" enctype="multipart/form-data">
					<div>
                    	<textarea id="e_basic" name="txt" cols="70" rows="15"></textarea>
                        <input type="hidden" name="thread_id" value="<?php echo $_REQUEST['thread_id']; ?>" />
                        <input type="hidden" name="forum_id" value="<?php echo $_REQUEST['forum_id']; ?>" />
                    </div>
					<div class="cols1 clearfix">
                        <div class="col1"><input type="checkbox" name="usersig" value="1" /> Montrer la signature</div>
                        <div class="col2"><input type="checkbox" name="emailnotify" value="1" /> M&#39;avertir lorsqu&#39;une r&eacute;ponse est post&eacute;e</div>
                        <div class="col3"><input class="go" type="submit" value="Soumettre" /></div>
					</div>
				</form>
			</div>
		</div>
<!-- fin full + signal main -->
		</div>
	</div>
</div>
<?php

        echo "<script type=\"text/javascript\">\nMaxWidth = document.getElementById('Forum').offsetWidth - 300;\n</script>\n";

        echo '<script type="text/javascript">
            <!--
                var Img = document.getElementById("img_resize_forum").getElementsByTagName("img");
                var NbrImg = Img.length;
                for(var i = 0; i < NbrImg; i++){
                    if (Img[i].width > MaxWidth){
                        Img[i].style.height = Img[i].height * MaxWidth / Img[i].width+"px";
                        Img[i].style.width = MaxWidth+"px";
                    }
                }
            -->
        </script>';
    } 
} 
else if ($level_access == -1)
{
    echo "<br /><br /><div style=\"text-align: center;\">" . _MODULEOFF . "<br /><br /><a href=\"javascript:history.back()\"><b>" . _BACK . "</b></a></div><br /><br />";
} 
else if ($level_access == 1 && $visiteur == 0)
{
    echo "<br /><br /><div style=\"text-align: center;\">" . _USERENTRANCE . "<br /><br /><b><a href=\"index.php?file=User&amp;op=login_screen\">" . _LOGINUSER . "</a> | <a href=\"index.php?file=User&amp;op=reg_screen\">" . _REGISTERUSER . "</a></b></div><br /><br />";
} 
else
{
    echo "<br /><br /><div style=\"text-align: center;\">" . _NOENTRANCE . "<br /><br /><a href=\"javascript:history.back()\"><b>" . _BACK . "</b></a></div><br /><br />";
} 
closetable();

?>
