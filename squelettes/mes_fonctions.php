<?php
function titreCourtUnmot ($texte) { // pour la navigation de premier niveau, affiche le premier mot du titre de la rubrique, permet de gérer un titre court.
  $texte = ereg_replace("[[:space:]]{2,}"," ",$texte); //supprime les doubles espaces -- ou plus -- dans le titre
  $menu1 = explode(" ",$texte);
  $texte = $menu1[0];
  return $texte;
}

function arrondi ($texte) {
    $arrondi = ceil ($texte);
    return $arrondi;
}

// au cas où le serveur tourne avec PHP 5, sinon le filtre |array_merge plante.
function array_merge5 ($array1, $array2) {
return array_merge ((array)$array1, (array)$array2);
}

// pour le tri alphabétique repris à partir de http://www.spip-contrib.net/Tri-alphabetique-tout-en-SPIP


//squelettes/mes_fonctions.php
//extrait la première lettre et la passe en majuscules
function onelettre($texte) {
    $texte = $texte{0}; // première lettre
    // remplacement des caractères accentués
    // exemple trouvé là: 
    // http://be.php.net/manual/fr/function.strtr.php#52098
    $texte = strtr($texte, "\xA1\xAA\xBA\xBF\xC0\xC1\xC2\xC3\xC5\xC7\xC8\xC9\xCA\xCB\xCC\xCD\xCE\xCF\xD0\xD1\xD2\xD3\xD4\xD5\xD8\xD9\xDA\xDB\xDD\xE0\xE1\xE2\xE3\xE5\xE7\xE8\xE9\xEA\xEB\xEC\xED\xEE\xEF\xF0\xF1\xF2\xF3\xF4\xF5\xF8\xF9\xFA\xFB\xFD\xFF", "!ao?AAAAACEEEEIIIIDNOOOOOUUUYaaaaaceeeeiiiidnooooouuuyy");
    $texte = strtr($texte, array("\xC4"=>"Ae", "\xC6"=>"AE", "\xD6"=>"Oe", "\xDC"=>"Ue", "\xDE"=>"TH", "\xDF"=>"ss", "\xE4"=>"ae", "\xE6"=>"ae", "\xF6"=>"oe", "\xFC"=>"ue", "\xFE"=>"th"));
    $texte = strtoupper($texte); // tout en majuscules
    //if($texte!='&')
    return $texte;
}

function critere_parsansL($idb, &$boucles, $crit) { // retire el " le los l' la le les the et tri après FROM TRIM(LEADING \'lâ€™ Â«\'  FROM TRIM(LEADING \'Le Â« \' FROM TRIM(LEADING \' \'  FROM TRIM(LEADING \'&amp\'
    $not = $crit->not;
    $boucle = &$boucles[$idb];
    $id = $boucle->primary;
    $boucle->order[] = "'TRIM(LEADING \'Le Â«\' FROM TRIM(LEADING \'le \'FROM TRIM(LEADING \'los \'  FROM TRIM(LEADING \'la \' FROM TRIM(LEADING \'lâ€™ Â«\'  FROM TRIM(LEADING \'Le Â« \'   FROM TRIM(LEADING \'La Â« \' FROM TRIM(LEADING \'lâ€™\' FROM TRIM(LEADING \'Â«\' FROM TRIM(LEADING \'&nbsp;\' FROM TRIM(LEADING \'les \' FROM LOWER(nom_site))))))))))))'";
}

//squelettes/mes_fonctions.php
/* on retire les le la etc */
function sansle($texte) {
    $pattern[0]  = "#^Les |La |Le |Lo |The[[:space:]]?#";   
    $pattern[1]  = "#^Lâ€™?#"; //apostrophe utf8
    $pattern[3] = "#^&\#171;?#"; //guillemet
    $pattern[5] = "#^&?#"; //&
    $pattern[2]  = "#^&nbsp;?#"; //espace
    $pattern[6] = "#^[[:space:]]?#"; //&
    $pattern[4]  = "#Â«#"; //guillemet La Â«  
    $texte = preg_replace($pattern, '', $texte); 
    return $texte;
}


/* on ne garde que le la l'*/
function quele($texte){
    $txtsanse=sansle(trim($texte));
    if ($txtsanse!=trim($texte))
        $texte= str_replace("$txtsanse","","$texte");
    else $texte='';
    return trim($texte); 
}

function onelettrebis($chaine) {
    $chaine=sansle($chaine); 
    $chaine=filtrer_entites($chaine); // si il y a des fois des accents en dur qui trainent
    $chaine = unicode2charset(utf_8_to_unicode($chaine), 'iso-8859-1'); // on code en html ISO
    $a = "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
    $b = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
    $chaine=strtr($chaine, $a, $b); // on retire les accents
    $chaine=strtoupper($chaine); // on passe en majuscules 
    /// si débute par le fameux Œ ou œ
    $pattern = "^(Œ|œ)";
    if (eregi($pattern,$chaine,$regs)) 
        $chaine='O';//$chaine= $regs[0]; //on va renvoyer la lettre O
    else 
        $chaine = $chaine{0}; 
    return $chaine ;
}


?>