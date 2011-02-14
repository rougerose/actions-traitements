GRIBOUILLE
==========

Gribouille est un squelette qui tente de refaire (en pur SPIP) ce qu'on
avait avec spikini.


INSTALLATION
============

* installer et activer "les crayons"

* mettre ce répertoire sous squelettes/gribouille/

* repérer le numéro NN du secteur qui sera "gribouille", et/ou contiendra
  des sous-rubriques de gribouillage.

* avec SPIP 1.9.2:
 ------------------
  dans squelettes/ recopier les fichiers :
    - gribouille/article.html
    - gribouille/rubrique.html
    - gribouille/article_fonctions.php
    - gribouille/rubrique_fonctions.php
  sous le nom article-NN.html, rubrique-NN.html
  article-NN_fonctions.php et  rubrique-NN_fonctions.php

* avec SPIP 1.9.3 dev, c'est plus simple :
 ------------------
 Faire un article-NN.html contenant exactement :
 [(#INCLURE{fond=gribouille/article}{env})]
 Faire un rubrique-NN.html contenant exactement :
 [(#INCLURE{fond=gribouille/rubrique}{env})]



CONFIGURATION
=============


Autorisations
-------------

Ce squelette se base sur l'interface normale des autorisations de SPIP, qui
veut que seuls les administrateurs ont le droit de publier/modifier un
article. Pour un wiki ouvert à un public plus large, il faudra créer un schéma
d'autorisations adapté à la situation.


Les autorisations exploitées par ce squelette sont les deux suivantes :

	autoriser('modifier', 'article', $id_article)
et
	autoriser('publierdans', 'rubrique', $id_rubrique)


Pour (par exemple) ouvrir à tous les visiteurs (enregistrés ou non) la possibilité d'éditer un article, et réserver aux seuls rédacteurs la possibilité de créer une nouvelle page, on créera deux fonctions d'autorisation suivantes (à installer dans mes_options.php, cf. fichier d'exemple).


Il faut aussi signaler au plugin crayons qu'un simple visiteur peut avoir
des droits d'édition : pour cela ajouter aussi dans ecrire/mes_options la
fonction suivante :

function analyse_droits_rapide() {
    return true;
}


[Ces fonctions pourraient à terme faire l'objet d'un plugin configurable]



Cantonner ces articles
----------------------

Si l'on veut éviter que ces articles ne s'affichent ailleurs (RSS,
page d'accueil etc), une possibilité est d'utiliser un plugin ad hoc
(par exemple accès restreint), en lui disant d'exclure les rubriques
consacrées aux wiki. Il faut alors désactiver ce plugin sur les deux
squelettes spécialisés (TODO).




Mise à jour depuis spikini
--------------------------

Si vous utilisiez déjà spikini, celui-ci dispose maintenant d'une fonction
d'import vers SPIP. Il suffit d'aller sur la page d'accueil du spikini, et
d'ajouter ?importer=oui dans l'URL, puis de préciser le numéro de la rubrique
vers laquelle importer les données. Attention la procédure est très lourde
et risque d'échouer sur de gros sites spikini si le temps alloué à php est
faible.

Les adresses des pages sont ensuite perdues, mais il est possible de rediriger
automatiquement /spikini/ vers l'adresse de la rubrique où les données ont été
importées.

