function lier_clavier_tableau(){
	$("table td input:text").keypress(function(event){
		var touche = event.keyCode;
		var position = $(this).attr("id");
		
		//event.preventDefault();

		if (position != "bouton_valider"){
			var coord = position.split(",");
			var X = coord[0]; var Y = coord[1];
			var nX = X;var nY = Y;
			
			switch (touche){
			case 40:
				nX = parseInt(X) + 1;
				break;
			case 38 :
				nX = parseInt(X) - 1;
				break;
			case 37 :
				nY = parseInt(Y) - 1;
				break;
			case 39 :
				nY = parseInt(Y) + 1;
				break;
			}
			var nouv_id = nX+","+nY;
			if ($("input#"+nouv_id)) {
				//$("input#"+nouv_id, document.forms[0]).focus();
				document.getElementById(nouv_id).focus();
			}
			else if (touche == 40) {
				document.getElementById("bouton_valider").focus();
			}
		}
		else{
		if (touche == 38) {
				document.getElementById("1,1").focus();
			}
		}
		return true;
	})
}

$(document).ready(
	lier_clavier_tableau
);