<?php 
// -------------------------------------------------------------------------//
// Nuked-KlaN - PHP Portal                                                  //
// http://www.nuked-klan.org                                                //
// -------------------------------------------------------------------------//
// This program is free software. you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License.           //
// -------------------------------------------------------------------------//
defined('INDEX_CHECK') or die ('You can\'t run this file alone.');

global $user, $nuked, $language, $cookie_forum;

include('modules/Forum/template.php');

$visiteur = $user ? $user[1] : 0;
$user_last_visit = (empty($user[4])) ? time() : $user[4];

$date_jour = nkDate(time());
$your_last_visite = nkDate($user_last_visit);

$nb = nbvisiteur();

	$sql_users = mysql_query("SELECT id FROM " . USER_TABLE);
    $nb_users = mysql_num_rows($sql_users);
	
	$sql_post = mysql_query("SELECT id FROM " . FORUM_MESSAGES_TABLE);
    $nb_post = mysql_num_rows($sql_post);
	if ($nb_post > 1){ $s = s; }
	
    $lastPost = mysql_query("SELECT MAX(id) from " . FORUM_MESSAGES_TABLE . "");
    $idmax1 = mysql_result($lastPost, 0, "MAX(id)");
	
    $req_last = mysql_query("SELECT id, titre, thread_id, forum_id FROM " . FORUM_MESSAGES_TABLE . " WHERE id = '" . $idmax1 . "'");
    list($mess_id_last, $titre_last, $thid_last, $forid_last) = mysql_fetch_array($req_last);
	
	$link_post_last = "index.php?file=Forum&amp;page=viewtopic&amp;forum_id=" . $forid_last . "&amp;thread_id=" . $thid_last . "#" . $mess_id_last;
	
	$sql_lastmember = mysql_query('SELECT pseudo FROM ' . USER_TABLE . ' ORDER BY date DESC LIMIT 0, 1');
    list($lastmember) = mysql_fetch_array($sql_lastmember);

    if ($_REQUEST['cat'] != "")
    {
    	$sql_cat = mysql_query("SELECT nom FROM " . FORUM_CAT_TABLE . " WHERE id = '" . $_REQUEST['cat'] . "'");
    	list($cat_name) = mysql_fetch_row($sql_cat);
    	$cat_name = printSecuTags($cat_name); 
    	$nav = "&nbsp;-&gt; <b>" . $cat_name . "</b>";    
	} 
?>
<div id="fullwidth_main">
	<div class="singal_main">
		<div class="first-obj">
			<div class="first">
				<div class="cols1 clearfix">
					<div class="col1"><img src="themes/Nk-Create_VIII/images/icon08.gif" alt="" width="29" height="26" /></div>
					<div class="col2">Bienvenue sur le Forum Nk-Create merci de respecter le <a href="http://www.nk-create.be/index.php?file=Forum&amp;page=viewtopic&amp;forum_id=9&amp;thread_id=1#1">r&egrave;glements</a><br /> L&rsquo;inscription est n&eacute;cessaire pour pouvoir participe au forum</div>
				</div>
			</div>
		</div>
<?php
    if ($_REQUEST['cat'] != "")
    {
    	$main = mysql_query("SELECT nom, id FROM " . FORUM_CAT_TABLE . " WHERE '" . $visiteur . "' >= niveau AND id = '" . $_REQUEST['cat'] . "'");
	} 
	else
{
	    	$main = mysql_query("SELECT nom, id FROM " . FORUM_CAT_TABLE . " WHERE " . $visiteur . " >= niveau ORDER BY ordre, nom");
	} 

	while (list($nom_cat, $cid) = mysql_fetch_row($main))
	{
    	$nom_cat = printSecuTags($nom_cat);
	
?>
    	<div class="cats-obj">
    		<div class="cat">
				<div class="box01">
					<div class="cols1 clearfix">
						<div class="col1"><div class="t1"><?php echo $nom_cat; ?></div></div>
						<div class="col3">
							<a class="collapse" ><img src="themes/Nk-Create_VIII/images/collapse.png" alt="" width="27" height="27" /></a>
							<a  class="expand"><img src="themes/Nk-Create_VIII/images/expand.png" alt="" width="27" height="27" /></a>
						</div>
					</div>
				</div>

				<div class="posts">
					<div class="box02">
						<div class="cols2 clearfix">
							<div class="col1">Forums</div>
							<div class="col2">Dernier message</div>
						</div>
					</div>
					<ul class="posts">
<?php
    $sql = mysql_query("SELECT nom, comment, id from " . FORUM_TABLE . " WHERE cat = '" . $cid . "' AND '" . $visiteur . "' >= niveau ORDER BY ordre, nom");
    while (list($nom, $comment, $forum_id) = mysql_fetch_row($sql))
    {
        $nom = printSecuTags($nom);

        $req2 = mysql_query("SELECT forum_id from " . FORUM_THREADS_TABLE . " WHERE forum_id = '" . $forum_id . "'");
        $num_post = mysql_num_rows($req2);

        $req3 = mysql_query("SELECT forum_id from " . FORUM_MESSAGES_TABLE . " WHERE forum_id = '" . $forum_id . "'");
        $num_mess = mysql_num_rows($req3);

        $req4 = mysql_query("SELECT MAX(id) from " . FORUM_MESSAGES_TABLE . " WHERE forum_id = '" . $forum_id . "'");
        $idmax = mysql_result($req4, 0, "MAX(id)");

        $req5 = mysql_query("SELECT id, titre, thread_id, date, auteur, auteur_id FROM " . FORUM_MESSAGES_TABLE . " WHERE id = '" . $idmax . "'");
        list($mess_id, $titre, $thid, $date, $auteur, $auteur_id) = mysql_fetch_array($req5);
        $auteur = nk_CSS($auteur);
		
          if ($user) {
               $visits = mysql_query("SELECT user_id, forum_id FROM " . FORUM_READ_TABLE . " WHERE user_id = '" . $user[0] . "' AND forum_id LIKE '%" . ',' . $forum_id . ',' . "%' ");
               $results = mysql_fetch_assoc($visits);
               if ($num_post > 0 && strrpos($results['forum_id'], ',' . $forum_id . ',') === false) {
                $img = "<img width=\"28\" height=\"28\" src=\"modules/Forum/images/forum_new.png\" title=\"\" alt=\"\" />";
            } 
            else
            {
                $img = "<img width=\"28\" height=\"28\" src=\"modules/Forum/images/forum.png\" alt=\"\" />";
            } 
        } 
        else
        {
            $img = "<img width=\"28\" height=\"28\" src=\"modules/Forum/images/forum.png\" alt=\"\" />";
        } 
		
        $heure = date("H:i:s", $date);
        $jours = date("d/m/Y", $date);
?>
						<li>
							<div class="cols3 clearfix">
								<div class="col1"><?php echo $img; ?></div>
								<div class="col2">
									<div class="t2"><?php echo " <a href=\"index.php?file=Forum&amp;page=viewforum&amp;forum_id=" . $forum_id . "\">" . $nom . ""?></a></div>
									<div><?php echo $comment; ?></div>
								</div>
								<div class="col3">
<?php
        if(empty($titre)) {
?>
									<div style="line-height:38px;padding-top:8px">Pas de Messages</div>
<?php
        } else {
			$sql_page = mysql_query("SELECT thread_id FROM " . FORUM_MESSAGES_TABLE . " WHERE thread_id = '" . $thid . "'");
			$nb_rep = mysql_num_rows($sql_page);

			if ($nb_rep > $nuked['mess_forum_page']) {
           		$topicpages = $nb_rep / $nuked['mess_forum_page'];
            	$topicpages = ceil($topicpages);
            	$link_post = "index.php?file=Forum&amp;page=viewtopic&amp;forum_id=" . $forum_id . "&amp;thread_id=" . $thid . "&amp;p=" . $topicpages . "#" . $mess_id;
			} 
        	else
        	{
            	$link_post = "index.php?file=Forum&amp;page=viewtopic&amp;forum_id=" . $forum_id . "&amp;thread_id=" . $thid . "#" . $mess_id;
        	} 
?>
									<div><a href="<?php echo $link_post; ?>"><strong><?php echo $titre; ?></strong></a></div>
									<div class="by">par: <a href="index.php?file=Members&amp;op=detail&amp;autor=<?php echo $auteur; ?>"><?php echo $auteur; ?></a></div>
									<div><img src="themes/Nk-Create_VIII/images/icon10.png" alt="" width="14" height="14" /> <?php echo $jours; ?> 
				    				     <img src="themes/Nk-Create_VIII/images/icon11.png" alt="" width="15" height="14" /> <?php echo $heure; ?>
								    </div>
<?php
        }
?>
								</div>
							</div>
						</li>
<?php
	}
?>
					</ul>
				</div>
    		</div>
    	</div>
<?php 
} 
?>
    	<div class="going-obj">
	    	<div class="cat">
				<div class="box01">
					<div class="cols1 clearfix">
						<div class="col1"><div class="t1">Statistiques</div></div>
					</div>
				</div>
				<div class="posts">
					<div class="box02">
						<div class="cols2 clearfix">
							<div class="col1">Informations sur le Forum</div>
						</div>
					</div>
					<ul class="posts">
						<li>
							<div class="cols3 clearfix">
								<div class="col1"><img src="themes/Nk-Create_VIII/images/icon13.gif" alt="" width="32" height="21" /></div>
								<div class="col2">
									<div class="t2">Utilisateurs actifs</div>
									<div>Il y a <strong><?php echo $nb[0]; ?></strong> Utilisateurs en ligne, 
												<strong><?php echo $nb[1]; ?></strong> Membres et 
												<strong><?php echo $nb[2]; ?></strong> Administrateurs
									</div>
									<div><?php echo _LASTVISIT . " : " . $your_last_visite; ?></div>
								</div>
							</div>
						</li>
						<li class="last">
							<div class="cols3 clearfix">
								<div class="col1"><img src="themes/Nk-Create_VIII/images/icon14.gif" alt="" width="30" height="26" /></div>
								<div class="col2">
									<div class="t2">Statistique du Forum</div>
									<div>Discussions: <strong><?php echo $nb_post; ?></strong> Posts: <strong><?php echo $nb_users; ?></strong> Membres</div>
									<div>Bienvenue &agrave; notre nouveau membre 
										<a href="index.php?file=Members&amp;op=detail&amp;autor=<?php echo $lastmember; ?>"><?php echo $lastmember; ?></a>
									</div>
								</div>
							</div>
						</li>
					</ul>
				</div>
	    	</div>
    	</div>
	</div>
</div>