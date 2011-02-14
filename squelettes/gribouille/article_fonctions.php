<?php

// Compatibilite 1.9.2
if (version_compare($GLOBALS['spip_version_code'],'1.9300','<'))
	include_spip('gribouille/compat_gribouille');


// utiliser par exemple, pour afficher une ligne de diff :
//  [<small> (#ID_ARTICLE|affiche_diff{#ID_VERSION,'diff'}|supprimer_tags|couper{50})</small>]
function affiche_diff($id_article, $id_version, $format='complet') {
	include_spip('inc/suivi_versions');

	$textes = revision_comparee($id_article, $id_version, $format);


	$ret = '';
	foreach ($textes as $champ => $texte) {
		$texte = propre_diff($texte);

		if ($champ == 'titre')
			$texte = "<h1>$texte</h1>";
		else
			$texte = "<div class='$k'>$texte</div>";

		$ret .= "\n<hr/>\n". $texte;
	}

	return $ret;
}

function affiche_auteur_diff($auteur) {

	// Si c'est un nombre, c'est un auteur de la table spip_auteurs
	if ($auteur == intval($auteur)
	AND $s = sql_query("SELECT * FROM spip_auteurs WHERE id_auteur="._q($auteur))
	AND $t = sql_fetch($s)) {
		return typo($t['nom']);
	} else {
		return $auteur;
	}
}


// Creation d'un nouvel article du WIKI -- cf. inc-entete
if (_request('ajouter_page_wiki')!==NULL
AND (!preg_match(",http://,",_request('ajouter_page_wiki'))) // pas d'url en titre de page, non mais
AND _request('id_rubrique')!==NULL
AND (!_request('pas_de_robot_merci'))
AND _request('id_rubrique') == $GLOBALS['contexte']['id_rubrique']) {
	$id_rubrique = intval($_POST['id_rubrique']);
	$id_article = null;

	// on verifie d'abord qu'un article de ce titre n'existe pas deja
	$s = sql_query("SELECT id_article FROM spip_articles WHERE titre="
	._q(_request('ajouter_page_wiki'))." OR url_propre="
	._q(_request('ajouter_page_wiki')));
	if ($t = sql_fetch($s)) {
		$id_article = $t['id_article'];
	} else {
		include_spip('inc/autoriser');
		if (autoriser('publierdans', 'rubrique', $id_rubrique)) {
			include_spip('action/editer_article');
			$id_article = insert_article($id_rubrique);
			include_spip('inc/modifier');
			$r = modifier_contenu('article', $id_article,
				array('champs' => array('titre', 'statut')),
				array(
					'titre' => _request('ajouter_page_wiki'),
					'statut' => 'publie' # spip 1.9.2
				)
			);
			# pour SPIP 1.9.3
			if (function_exists('instituer_article'))
				instituer_article($id_article,array('statut' => 'publie'));
		}
	}

	if (!$id_article)
		die("Erreur : creation d'article interdite");

	charger_generer_url();
	include_spip('inc/headers');
	redirige_par_entete(generer_url_article($id_article, '&'));
}

define ('RUBRIQUE_WIKI_OK', true);

?>
