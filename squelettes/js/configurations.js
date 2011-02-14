$(document).ready(function(){
    // ================================================
    // ajout dynamique menu deroulant dans rubrique 1022
    // necessite le plugin jquery Select box manipulation
    // http://plugins.jquery.com/project/selectboxes 
    // ================================================
    
    
    function chargement() {
        if ($("#rubrique1022 select option")) {
            $(this).parent().children("div.chargement").fadeOut("slow", function(){
                $(this).parent().children("select:hidden,input:hidden").fadeIn("slow");
            });
        }
    }
    
    $("#rubrique1022 .tri select").hide();
    $("#rubrique1022 .tri input").hide();
    $("#rubrique1022 .chargementMot").fadeIn("slow");
    $("#rubrique1022 .chargementDate").fadeIn("slow");
    $("#rubrique1022 #date").ajaxAddOption("spip.php", {"page":"ajax-revuePresse_menuDate"}, false, chargement);
    $("#rubrique1022 #mot").ajaxAddOption("spip.php", {"page":"ajax-revuePresse_menuMot"}, false, chargement);
    

    // ================================================
    // Configuration du menu principal 
    // ================================================
    $("#menu").superfish({
        hoverClass	: "over",
        pathClass	: "overideThisToUse", /*new to Superfish v1.2a*/
        delay		: 100,
        animation	: {opacity:"show"},
        speed		: "normal"
    });

    // ================================================
    // Ouvrir une nouvelle fenêtre pour les liens externes
    // ================================================
    // repris de http://www.jquery.info/spip.php?article12 ...avec quelques modifications supplémentaires.
    $("a.spip_out")
    .each(function(i){
        $(this)
        .after(
            $(this.cloneNode(false))
            .html("<img src='squelettes/img/iconeNouvelleFenetre.png' alt='nouvelle fenêtre' class='nouvelleFenetre' />")
            .attr({ href: this.href, title: "ouvrir le lien dans une nouvelle fenêtre", target: "_blank" })
        );
    });
    
    // ================================================
    // Configuration du menu secondaire
    // ================================================
    
    $("#hierarchie").addClass("js");
    $("#hierarchie li:not(.on)").prepend('<img src="squelettes/img/flecheRouge-jquery.gif" class="ouvrir" alt="afficher les sous-rubriques" title="afficher les sous-rubriques">');
    $("#hierarchie li.on").prepend('<img src="squelettes/img/flecheViolet-jquery.gif" class="ouvrir" alt="afficher les sous-rubriques" title="afficher les sous-rubriques">');
    $("#hierarchie li:not(.on) ul").hide();
    $("#hierarchie li img.ouvrir").click(function(){
        var moi = $(this);
        if (moi.parent().is(".niveau2")) {
            if (moi.parent().is(".on")) {
                moi.parent().removeClass("on").find("ul").hide("slow");
            }
            else {
                $("li.on").removeClass("on").find("ul").hide("slow");
                moi.parent().addClass("on").find("ul").eq(0).show("slow");
            }
        }
        else {
            if(moi.parent().is(".on")) {
                moi.parent().removeClass("on").find("ul").eq(0).hide("slow");
            }
            else {
                moi.parent().addClass("on").find("ul").eq(0).show("slow");
            }
        }
    });
    
    
    // ================================================
    // Menu de tri des FAQ, annuaires...
    // ================================================
    $("form.tri p.spip_bouton").remove();
    
    
    
    // ================================================
    // Configurations pour le glossaire des mots-clés
    // ================================================
    
    // on ajoute une classe de mise en forme spécifique car js est activé au niveau du navigateur
    $('#menuGlossaire').addClass("jsActif");
    
    // si url par défaut du glossaire 
    var urlDefaut = location.href;
    if (urlDefaut.match(/glossaire$/g)) { //url par défaut du glossaire
        $('#menuGlossaire li:eq(0)').addClass('selected');
    }
    
    // si on clique sur une catégorie
    $('a.groupeGlossaire.on').parent().addClass('selected');
    
    // si on clique sur le mot-clé 
    //$('li.motsGlossaire a.on').parent().parent().parent().addClass('selected');
    
    // menu glossaire : ajustement de la hauteur de la page en fonction de celle de la liste des mots-clés affichée 
    var hauteurGlossaire = $("#menuGlossaire").height();
    var hauteurMots = $("ul.motsGroupe").height();
    if (hauteurGlossaire < hauteurMots) {
        $("#menuGlossaire").animate({height:hauteurMots},"slow");
    }
    else if (hauteurGlossaire > hauteurMots) {
        $("menuGlossaire").animate({height:hauteurGlossaire},"slow");
    }
    
    // pages glossaire : le texte du mot-clé (définition) est masqué à partir du cinquième paragraphe
    if ($("#glossaire #principalArticleReferences div.texte p").length > 4) {
        $("#glossaire #principalArticleReferences div.texte p:gt(3)").hide("slow");
        $('<div></div>')
        .insertAfter("div.texte p:last")
        .addClass("texteCache")
        .append('<p><img src="squelettes/img/afficher-texte.gif" width="140" height="23" alt="Afficher l\'intégralité du texte"><p>');
        $("div.texteCache p").click(function(){
            $("div.texte p:gt(3)").show("slow");
            $(this).parent().hide("slow");
        })
    }
});