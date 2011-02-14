// gribouille.js
;(function($){

// si le selecteur generique n'est pas la on ne fait rien
if ($.fn.Autocomplete)
$(document).ready(function() {
	$('input[@name=recherche]')
	.Autocomplete({
		'source': 'spip.php?page=gribouille_selecteur_recherche',
		'delay': 300,
		'autofill': false,
		'helperClass': 'autocompleter',
		'selectClass': 'selectAutocompleter',
		'minchars': 1,
		'mustMatch': false,
		'cacheLength': 20,
		'onSelect': function(li) {
			if (li.url) {
				window.location = li.url;
			}
		}
	});
});

})(jQuery);
