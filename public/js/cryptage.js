function crypter(frm){
	if (frm.motdepasse.value) {
		var ch = frm.identite.value;
		var tab = ch.split(",");
		var graine = tab[2];
		frm.motdepasse_md5.value = calcMD5(graine + frm.motdepasse.value);
		frm.motdepasse.value = "";
		return true;
	};
}