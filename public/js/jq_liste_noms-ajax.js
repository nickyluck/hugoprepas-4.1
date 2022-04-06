function lier_liste(type_liste)
{
    liste = '#liste_'+type_liste;
    $(liste).change(function(){
		var ref = $(this);
		var valeur = ref.val();
		var suivant = ref.parent().next("p").find("select");
		var type_liste = ref.attr('id');

		var script = $("form").attr('action');

		$.post(script,{'ajax' : 'liste', 'type_liste' : type_liste, 'valeur' : valeur},function(data){
			suivant.find("option").remove();
			suivant.append("<option value='0,z,0'> --- </option>");
			$('personne', data).each(function(){
				var id = $(this).find('id').text();
				var cat = $(this).find('cat').text();
				var nom = $(this).find('nom').text();
				var graine = $(this).find('graine').text();	
				suivant.append("<option value='" + id + "," + cat + "," + graine +"'> " + nom +"</option>");
			});
      });
	
    });
}

$(document).ready(function(){
  lier_liste('matieres'); 
  lier_liste('classes');
});

