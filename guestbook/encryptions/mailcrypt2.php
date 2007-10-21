<?php
/**
 * Guestbook Addon 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: mailcrypt2.php,v 1.2 2007/10/21 00:03:26 koala_s Exp $
 */
  
/** 
 * Dies ist die mailcrypt-Funktion Version 2, um deine eMail-Adressen vor 
 * Spammern zu schützen, die Webseiten nach Adressen durchsuchen.
 * Dieses Skript wird von www.silentcreek.de kostenlos zur Verfügung gestellt.
 * Nachfolgend ist die Funktion mailcrypt deklaliert. Du solltest nichts an der
 * Funktion ändern. Die Funktion verschlüsselt eMail-Adressen in ASCII-Code. 
 * Und zwar sowohl dezimale Schreibweise als auch hexadezimale Schreibweise 
 * gemischt.
 * Siehe readme.txt für Details zur Verwendung. Viel Spaß, creek
 */
function gbook_encryptmail($email)
{
	// Zeichensatz für Symbole und Zeichen wird definiert
	$ascii["."] = "&#x2e;";
	$ascii[":"] = "&#58;";
	$ascii["-"] = "&#x2d;";
	$ascii["_"] = "&#95;";
	$ascii["@"] = "&#x40;";
	// Zeichensatz für Ziffern wird definiert
	$ascii["0"] = "&#x30;";
	$ascii["1"] = "&#49;";
	$ascii["2"] = "&#x32;";
	$ascii["3"] = "&#51;";
	$ascii["4"] = "&#x34;";
	$ascii["5"] = "&#53;";
	$ascii["6"] = "&#x36;";
	$ascii["7"] = "&#55;";
	$ascii["8"] = "&#x38;";
	$ascii["9"] = "&#57;";
	// Zeichensatz für Kleinbuchstaben wird definiert
	$ascii["a"] = "&#x61;";
	$ascii["b"] = "&#98;";
	$ascii["c"] = "&#x63;";
	$ascii["d"] = "&#100;";
	$ascii["e"] = "&#x65;";
	$ascii["f"] = "&#102;";
	$ascii["g"] = "&#x67;";
	$ascii["h"] = "&#104;";
	$ascii["i"] = "&#x69;";
	$ascii["j"] = "&#106;";
	$ascii["k"] = "&#x6b;";
	$ascii["l"] = "&#108;";
	$ascii["m"] = "&#x6d;";
	$ascii["n"] = "&#110;";
	$ascii["o"] = "&#x6f;";
	$ascii["p"] = "&#112;";
	$ascii["q"] = "&#x71;";
	$ascii["r"] = "&#114;";
	$ascii["s"] = "&#x73;";
	$ascii["t"] = "&#116;";
	$ascii["u"] = "&#x75;";
	$ascii["v"] = "&#118;";
	$ascii["w"] = "&#x77;";
	$ascii["x"] = "&#120;";
	$ascii["y"] = "&#x79;";
	$ascii["z"] = "&#122;";
	// Zeichensatz für kleine Umlaute und Sonderzeichen
	$ascii["ä"] = "&#xe4;";
	$ascii["ö"] = "&#246;";
	$ascii["ü"] = "&#xfc;";
	$ascii["ß"] = "&#223;";
	// Zeichensatz für Großbuchstaben wird definiert
	$ascii["A"] = "&#x41;";
	$ascii["B"] = "&#66;";
	$ascii["C"] = "&#x43;";
	$ascii["D"] = "&#68;";
	$ascii["E"] = "&#x45;";
	$ascii["F"] = "&#70;";
	$ascii["G"] = "&#x47;";
	$ascii["H"] = "&#72;";
	$ascii["I"] = "&#x49;";
	$ascii["J"] = "&#74;";
	$ascii["K"] = "&#x4b;";
	$ascii["L"] = "&#76;";
	$ascii["M"] = "&#x4d;";
	$ascii["N"] = "&#78;";
	$ascii["O"] = "&#x4f;";
	$ascii["P"] = "&#80;";
	$ascii["Q"] = "&#x51;";
	$ascii["R"] = "&#82;";
	$ascii["S"] = "&#x53;";
	$ascii["T"] = "&#84;";
	$ascii["U"] = "&#x55;";
	$ascii["V"] = "&#86;";
	$ascii["W"] = "&#x57;";
	$ascii["X"] = "&#88;";
	$ascii["Y"] = "&#x59;";
	$ascii["Z"] = "&#90;";
	// Zeichensatz für große Umlaute wird definiert
	$ascii["Ä"] = "&#196;";
	$ascii["Ö"] = "&#xd6;";
	$ascii["Ü"] = "&#220;";
	// Zeichen der eMail-Adresse werden zerlegt und gezählt
	$_splitted = chunk_split($email, 1, "#,#");
	$arr_email = explode("#,#", $_splitted);
	$_chars = count($arr_email) - 1;
	// Ausgabe wird vorbereitet
	$output = "";
	// Nun wird jedes Zeichen einzeln durchlaufen und für jedes Zeichen wird der entsprechende ASCII-Code in die Ausgabe-Variable geschrieben
	for ($i = 0; $i < $_chars; $i ++)
	{
		// Wenn kein entsprechender ASCII-Code vorliegt, wird das Zeichen unverschlüsselt zurückgegeben
		if (empty ($ascii[$arr_email[$i]]))
		{
			$ascii[$arr_email[$i]] = $arr_email[$i];
		};
		$output = $output.$ascii[$arr_email[$i]];
	};
	return $output;
};
?>