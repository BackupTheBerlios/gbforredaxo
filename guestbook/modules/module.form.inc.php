<?php
/**
 * Guestbook Addon
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @author redaxo[at]koalashome[dot]de Sven (Koala) Eichler
 * @package redaxo4
 * @version $Id: module.form.inc.php,v 1.33 2010/10/12 19:32:39 koala_s Exp $
 */

// Dateifunktionen zur Statusbearbeitung einbinden
include_once ($REX['INCLUDE_PATH'].'/addons/guestbook/functions/function_gbook_file.inc.php');
//
include_once ($REX['INCLUDE_PATH'].'/addons/guestbook/functions/function_gbook_postcheck.inc.php');


/**
 * gbook_form_input
 *
 * @param Admin-EMail
 * @param Danke-Text
 * @param DebugLevel  Verschiedene Stufen zur Debugausgabe (vorerst nur per EMail)
 *
 */
function gbook_form_input($notificationEmail, $danke_text, $debuglevel, $formular_an_aus) {
  /*if (empty($notificationEmail)) {
    global $REX;
    $notificationEmail = $REX['ERROR_EMAIL'];
  } */
  if (!isset ($danke_text) or $danke_text == '') {
    $danke_text = 'Danke f&#252;r Ihren Eintrag!'."\n".'Die von Ihnen eingegebenen Daten wurden erfolgreich gespeichert.'."\n".'
      Vor der Ver&#246;ffentlichung wird der Eintrag durch den Webmaster gepr&#252;ft. Freigegeben werden nur unbedenkliche Eintr&#228;ge.';
  }

?>
    <label for="VALUE[1]">Email Benachrichtigungsadresse: (email@domain.de,post@domain.de)</label>
    <input type="text" id="VALUE[1]" name="VALUE[1]" value="<?php echo $notificationEmail ?>" class="inp100" />
    <p>siehe <a href="http://www.php.net/manual/de/function.mail.php">PHP Manual - mail() - to-Parameter</a></p>
    <br />
    <label for="VALUE[2]">Danke-Text:</label>
    <textarea name="VALUE[2]" class="inp100" rows="6" cols="80"/><?php echo $danke_text ?></textarea>
    <br /><br />
    <label for="VALUE[3]">Debug-Modus:</label>
    <select name="VALUE[3]" id="VALUE[3]">
     <option value="0" <?php echo $debuglevel == '0' ? 'selected="selected"' : '' ?>>Aus</option>
     <option value="1" <?php echo $debuglevel == '1' ? 'selected="selected"' : '' ?>>Ein</option>
    </select>
    <br />
    <p>Hiermit werden diverse Informationen mit der EMail versand, die zu Debugzwecken n&#252;tzlich sein k&#246;nnen.
    Aber beachte das es sich dabei um sehr viele Informationen handeln kann und diese Informationen
    aus Sicherheitsgr&#252;nden nie &#246;ffentlich zug&#228;nglich sein sollten!</p>
    <br /><br />
    <label for="VALUE[4]">Formlar Ein oder Aus:</label>
    <select name="VALUE[4]" id="VALUE[4]">
     <option value="0" <?php echo $formular_an_aus == '0' ? 'selected="selected"' : '' ?>>Aus</option>
     <option value="1" <?php echo $formular_an_aus == '1' ? 'selected="selected"' : '' ?>>Ein</option>
    </select>
    <br />
		<p>Ist "Aus" eingestellt, erscheint nur der Danke-Text nach einem G&#228;stebucheintrag.<br />
		Ist "Ein" eingestellt, erscheint der Danke-Text <strong>und</strong> das Formular nach einem G&#228;stebucheintrag.</p>

<div class="Modulversion">($Revision: 1.33 $ - $RCSfile: module.form.inc.php,v $)</div>

<?php
}



/**
 * Prüft den Inhalt der übergebenen Variable.
 *
 * Ist die Variable leer und darf sie für MySQL NULL sein,
 * so gib ein NULL zurück. Sonst gib den Wert in Anführungszeichen
 * zurück.
 *
 * @param mixed Variable
 * @param mixed Defaultwert (NULL)
 * @return string
 */
function checkPostVarForMySQL($var, $default = '') {
  if (isset ($var) and $var != '') {
//    $var = '"'.htmlspecialchars($var).'"';
    $var = '"'.strip_tags($var).'"';
  } elseif (isset ($default) and $default != '' and isset ($var) and $var == '') {
    $var = $default;
  } else {
    $var = '';
  }
  return $var;
}


/**
 * gbook_form_output
 *
 * @param Admin-EMail
 * @param Danke-Text
 * @param DebugLevel  Verschiedene Stufen zur Debugausgabe (vorerst nur per EMail)
 *
 */
function gbook_form_output($notificationEmail, $danke_text, $debuglevel, $formular_an_aus) {
  global $REX;


  // vordefinieren einiger Variablen
  $error  = '';
  $name   = '';
  $email  = '';
  $url    = 'http://';
  $city   = '';
  $text   = '';
  if (!isset ($danke_text)) {
    $danke_text = '';
  }




  /**
   * Um Spameinträge zu erschweren wurden die Feldnamen 'email' und 'url'
   * im Formular untereinander getauscht. Diese müssen nun zurückgetauscht werden.
   * Der normale Benutzer sollte davon nichts bemerken.
   */
  if (isset ($_POST['email']) and $_POST['email'] != '') {
    $url_temp = $_POST['email'];
  } else {
    $url_temp = '';
  }
  if (isset ($_POST['url']) and $_POST['url'] != '') {
    $email_temp = $_POST['url'];
  } else {
    $email_temp = '';
  }
  // gib den POST-Variablen die richtigen Werte
  $_POST['url'] = $url_temp;
  $_POST['email'] = $email_temp;


	// Wird true, wenn eine Eintrag erfolgreich geschrieben wurde
	$Eintrag_geschrieben = false;

	
	if (!isset ($_POST['name'])) { $_POST['name'] = ''; }
  if (!isset ($_POST['text'])) { $_POST['text'] = ''; }
  if (!isset ($_POST['url'])) { $_POST['url'] = ''; }
  if (!isset ($_POST['email'])) { $_POST['email'] = ''; }
  if (!isset ($_POST['city'])) { $_POST['city'] = ''; }
  
	
  // gbook_formularPostCheck($postvars, $domainname = false)
  if (($errorfields = validFields()) === true and gbook_formularPostCheck(array ($_POST['name'],$_POST['text'],$_POST['url'],$_POST['email'],$_POST['city']) )) {
    $author_value   = checkPostVarForMySQL($_POST['name']);
    $message_value  = checkPostVarForMySQL($_POST['text']);
    // wurde keine URL angegeben, entferne die "HTTP://"-Vorgabe
    if ($_POST['url'] == 'http://') { $_POST['url'] = ''; }
    $url_value      = checkPostVarForMySQL($_POST['url'],'NULL');
    $email_value    = checkPostVarForMySQL($_POST['email'],'NULL');
    $city_value     = checkPostVarForMySQL($_POST['city'],'NULL');


    // Thema Sicherheit:
    // $status ist endweder 1, 0 oder false
    // die Funktion gbook_readStatusFromFile() läßt keine andere Rückgabe zu
    $status = gbook_readStatusFromFile();
    if ($status === false) {
      echo 'Fehler bei Statusermittlung des Eintrages aufgetreten. Setze Defaultwert 0. ';
      $status_db = 'status = "0",';
    } else {
      $status_db = 'status = "'.$status.'",';
    }

    //$qry = 'INSERT INTO '.TBL_GBOOK.' SET  author = "'.$author.'", message = "'.$message.'", url ="'.$url.'", email="'.$email.'", city="'.$city.'", created = UNIX_TIMESTAMP()';
    $qry = 'INSERT INTO '.TBL_GBOOK.' SET '.$status_db.' author = '.$author_value.', message = '.$message_value.',
            url = '.$url_value.', email = '.$email_value.', city = '.$city_value.',
            created = UNIX_TIMESTAMP()';
    $sql = new rex_sql();
    //$sql->debugsql = true;
    $sql->setQuery($qry);

		$Eintrag_geschrieben = true;

    // EMail an Admin
    if ($notificationEmail != '') {

      // DEBUG-Informationen zusammenstellen
      $debug_inhalt = '';
      if ($debuglevel == 1) {
        $debug_inhalt = "\r\n\r\n ==== DEBUG-INFORMATIONEN ==== \r\n";
        if (isset($_POST) and count($_POST) != 0) {
          $debug_inhalt .= "\n === POST ===\n";
          foreach($_POST as $key => $wert) {
            $debug_inhalt .= $key.': '.$wert."\n";
          }
        }
        if (isset($_GET) and count($_GET) != 0) {
          $debug_inhalt .= "\n === GET ===\n";
          foreach($_GET as $key => $wert) {
            $debug_inhalt .= $key.': '.$wert."\n";
          }
        }
        if (isset($_SERVER) and count($_SERVER) != 0) {
          $debug_inhalt .= "\n === SERVER ===\n";
          foreach($_SERVER as $key => $wert) {
            $debug_inhalt .= $key.': '.$wert."\n";
          }
        }
      } // if ($debuglevel == 1)



      $mail_host = !strstr($REX['SERVER'], 'http://') && !strstr($REX['SERVER'], 'https://') ? 'http://'.$REX['SERVER'] : $REX['SERVER'];
      if($mail_host{strlen($mail_host)-1} != '/')
      {
        $mail_host .= '/';
      }
      $mail_server = $mail_host .'/redaxo';

/*      $mail_author = htmlspecialchars(rex_post('name', 'string'));
      $mail_message = htmlspecialchars(rex_post('text', 'string'));
      $mail_url = htmlspecialchars(rex_post('url', 'string'));
      $mail_email = htmlspecialchars(rex_post('email', 'string'));
      $mail_city = htmlspecialchars(rex_post('city', 'string'));
*/
      $mail_author = strip_tags(rex_post('name', 'string'));
      $mail_message = strip_tags(rex_post('text', 'string'));
      $mail_url = strip_tags(rex_post('url', 'string'));
      $mail_email = strip_tags(rex_post('email', 'string'));
      $mail_city = strip_tags(rex_post('city', 'string'));
      
      $mail_betreff = 'Neuer Gästebucheintrag für '. $mail_host;
      $mail_nachricht = 'Im Gästebuch für die Webseite "'.$mail_host.'" wurde ein neuer Eintrag erstellt.'."\r\n\r\n";
      $mail_nachricht .= 'Name: '.$mail_author. "\r\n";
      $mail_nachricht .= 'Homepage: '.$mail_url. "\r\n";
      $mail_nachricht .= 'eMail: '.$mail_email. "\r\n";
      $mail_nachricht .= 'Wohnort: '.$mail_city. "\r\n\r\n";
      $mail_nachricht .= 'Nachricht: '.$mail_message. "\r\n\r\n\r\n";
      //$nachricht .= 'Hinweis: Dieser Eintrag wurde bei der Einstellung "Ver�ffentlichung nach Freigabe" deaktiviert gespeichert und erscheint erst dann in Ihren G�stebuch, wenn Sie den Eintrag aktiviert haben. Zum Log-In Bereich geht es unter '.$server."\r\n";

      // DebugInfo anhängen, falls gewünscht
      $mail_nachricht .= $debug_inhalt;
      $header  = 'MIME-Version: 1.0'."\r\n";
      //$header .= 'Content-type: text/plain; charset=iso-8859-1'."\r\n";
      $header .= 'Content-type: text/plain; charset=utf-8'."\r\n";
      $header .= 'Content-Transfer-Encoding: 8bit'."\r\n";
      $header .= 'X-Mailer: PHP/' . phpversion()."\r\n";
      $header .= 'From: '. $notificationEmail ."\r\n";
//      $header .= 'Bcc: foo@david.koala'."\r\n";

      if (class_exists('rex_mailer')) {
      	$mail = new rex_mailer();
        $mail->AddAddress($notificationEmail); 
        $mail->Sender = $notificationEmail;    
        $mail->From = $notificationEmail; 
        //$mail->FromName = "REX_VALUE[8] |".$REX['SERVERNAME']; 
        $mail->Subject = $mail_betreff;
        $mail->Body = $mail_nachricht; 
        $mail->Send();  // Versenden
      } else {
        // Fallback        
        mail ($notificationEmail, $mail_betreff, $mail_nachricht, $header);
      }
    }


  } else { // if (($errorfields = validFields()) === true)

    // der Danke-Text erscheint nur nach dem erfolgreichen absenden des Formulares
    $danke_text = '';

    // Wurde eine falsche Eingabe festgestellt, fülle die Eingabefelder wieder
    // mit den ursprünglichen Werten und gibt eine Fehlernachricht aus.
    if (!empty ($_POST['gbook_save'])) {
      // var_dump($_POST);
      // Felder mit Werten füllen
      $name = $_POST['name'];
      $email = $_POST['email'];
      $url = $_POST['url'];
      $city = $_POST['city'];
      $text = $_POST['text'];

      $error = '<ul class="error">';

      foreach ($errorfields as $fieldname) {
        $error .= '<li>Pflichtfeld "'.ucwords($fieldname).'" bitte korrekt ausf&uuml;llen!</li>';
      }

      $error .= '</ul>';
    } // if (!empty ($_POST['gbook_save']))



  } // else { // if (($errorfields = validFields()) === true)

    // AUSGABE der Seite

    // wenn Template-Klasse noch nicht eingebunden, dann hole sie jetzt rein
    if (!class_exists ('Template')) {
      include_once ($REX['INCLUDE_PATH'].'/addons/guestbook/classes/template.inc.php');
    }
    //$_ROOT['template'] = $REX['INCLUDE_PATH'].'/addons/guestbook/templates/';


    /* create Template instance called $t */
    $t = new Template(GBOOK_TEMPLATEPATH, "remove");
    //$t->debug = 7;
    $danketext_templ = 'gb_frontend_danketext.html';
    $formular_templ = 'gb_frontend_form.html';
    $frontend_templ = 'gb_frontend.html';

    /* lese Template-Datei */
    $t->set_file(array('danketext' => $danketext_templ,
    									 'formular' => $formular_templ,
    									 'start' => $frontend_templ));

		// Danketext
    $t->set_var(array("DANKE_TEXT_VALUE" => $danke_text
                  ));
		// Formular
    //$adresse = rex_geturl($GLOBALS['article_id']);
    $adresse = rex_geturl(& $REX['ARTICLE_ID']);
    $t->set_var(array("FEHLERMELDUNG_VALUE" => $error,
                      "ADRESSE_VALUE" => $adresse,
                      "NAME_VALUE" => $name,
                      "EMAIL_VALUE" => $email,
                      "URL_VALUE" => $url,
                      "WOHNORT_VALUE" => $city,
                      "TEXT_VALUE" => $text
                  ));


    // Teilseite zusammensetzen
    if (trim ($danke_text) != '') {
      $danke_text_value = $t->parse("output", "danketext");
    } else {
      $danke_text_value = '';
    }

		// soll nur der Danke-Text ausgegeben werden, erstelle keine Formularseite
		if ($formular_an_aus == 0 and $Eintrag_geschrieben) {
			$formular_value = '';
		} else {
	    // Teilseite zusammensetzen
	    $formular_value = $t->parse("output", 'formular');
		}


		// Seite zusammensetzen
    $t->set_var(array("DANKE_TEXT" => $danke_text_value,
    									'FORMULAR' => $formular_value
                  ));



    /* create Template instance called $t */
//    $t = new Template(GBOOK_TEMPLATEPATH, "remove");
    //$t->debug = 7;
//    $start_dir = 'gb_frontend_form.html';

    /* lese Template-Datei */
/*    $t->set_file(array("start" => $start_dir));

    $t->set_var(array("DANKE_TEXT_VALUE" => $danke_text,
                      "FEHLERMELDUNG_VALUE" => $error,
                      "ARTICLE_ID_VALUE" => $GLOBALS['article_id'],
                      "CLANG_VALUE" => $GLOBALS['clang'],
                      "NAME_VALUE" => $name,
                      "EMAIL_VALUE" => $email,
                      "URL_VALUE" => $url,
                      "WOHNORT_VALUE" => $city,
                      "TEXT_VALUE" => $text
                  ));
*/
    // komplette Seite ausgeben
    $t->pparse("output", "start");
} // gbook_form_output($notificationEmail, $danke_text)



/**
 * validFields
 *
 */
function validFields() {
  if (empty ($_POST['gbook_save']))
  {
    return false;
  }

  $failed = array ();
  $reqfields = array ('name', 'text');

  foreach ($reqfields as $name)
  {
    if (empty ($_POST[$name]))
    {
      $failed[] = $name;
    }
  }

  // Email Syntax Prüfung
  if ($_POST['email'] != '' &&
     !(
      !(preg_match('!@.*@|\.\.|\,|\;!', $_POST['email']) ||
      !preg_match('!^.+\@(\[?)[a-zA-Z0-9\.\-]+\.([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$!', $_POST['email'])))
     )
  {
    $failed[] = 'email';
  }
  
  if ($_POST['city'] == 'USA, New York' || $_POST['city'] == 'New York') {
  	$failed[] = 'city';
  }
  
  
  // URL Syntax Prüfung
  if ($_POST['url'] == 'http://') {
    $url_temp = '';
  } else {
    $url_temp = $_POST['url'];
  }
  if ($url_temp != '' &&
      !preg_match('!^http(s)?://[\w-]+\.[\w-]+(\S+)?$!i',$url_temp))
  {
    $failed[] = 'url';
  }

  return empty ($failed) ? true : $failed;
}
