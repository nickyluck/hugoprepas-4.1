function verif_choix(Id,defaut,message){
	var choix = document.getElementById(Id).value;
	
	if (choix == defaut){
		alert(message); return false;
	}
	else {
		return true;
	}
}

function verif_choix_radio(Id,message,valeur){
	var bouton = document.getElementById(Id);

	for(i=0;i<=3;i++){
	  if(document.question.concerne[i].checked){
	      VarRecup = document.question.concerne[i].value;
	  }
	}

	alert(VarRecup); return false;

	/**/
}

function dispChoix(bouton){
  var choisi = '';

  for (var i=0; i<bouton.length;i++){
    if (bouton[i].checked){
      choisi = bouton[i].value;
    }
  }
  if (choisi == ''){
	alert("Choisissez une filière."); return false;
  }
  else {
	return true;
  }
}

