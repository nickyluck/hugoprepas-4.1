function copier_semestre(col,copier){
	for(ligne=1;ligne<49;ligne++){
		var id_droite = ligne+","+col;
		var id_gauche = ligne+","+(col-1);
		if(document.getElementById(id_gauche) && document.getElementById(id_droite)){
			if(copier){
				document.getElementById(id_droite).value = document.getElementById(id_gauche).value;
			}
			else{
				document.getElementById(id_droite).value = "";
			}
		}
	}
	return false;
}