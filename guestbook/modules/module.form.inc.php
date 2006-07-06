<?php
/**
 * Guestbook Addon 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: module.form.inc.php,v 1.14 2006/07/06 21:40:03 koala_s Exp $
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
function gbook_form_input($notificationEmail, $danke_text, $debuglevel) {
  if (empty($notificationEmail)) {
    global $REX;
    $notificationEmail = $REX['ERROR_EMAIL'];
  }
  if (!isset ($danke_text) or $danke_text == '') {
    $danke_text = 'Danke f�r Ihren Eintrag!'."\n".'Die von Ihnen eingegebenen Daten wurden erfolgreich gespeichert.'."\n".'
      Vor der Ver�ffentlichung wird der Eintrag durch den Webmaster gepr�ft. Freigegeben werden nur unbedenkliche Eintr�ge.';
  }

?>
    <label for="VALUE[1]">Email Benachrichtigungsadresse: (email@domain.de,post@domain.de)</label>
    <input type="text" id="VALUE[1]" name="VALUE[1]" value="<?php echo $notificationEmail ?>" class="inp100" />
    <p>siehe <a href="http://www.php.net/manual/de/function.mail.php">PHP Manual - mail() - to-Parameter</a></p>
    <br />
    <label for="VALUE[2]">Danke-Text:</label>    
    <textarea name="VALUE[2]" class="inp100" rows="6" /><?php echo $danke_text ?></textarea>
    <br /><br />
    <label for="VALUE[3]">Debug-Modus:</label>
    <select name="VALUE[3]" id="VALUE[3]">
     <option value="0" <?php echo $debuglevel == '0' ? 'selected="selected"' : '' ?>>Aus</option>
     <option value="1" <?php echo $debuglevel == '1' ? 'selected="selected"' : '' ?>>Ein</option>
    </select>
    <br />
    <p>Hiermit werden diverse Informationen mit der EMail versand, die zu Debugzwecken n�tzlich sein k�nnen.
    Aber beachte das es sich dabei um sehr viele Informationen handeln kann und diese Informationen 
    aus Sicherheitsgr�nden nie �ffentlich zug�nglich sein sollten!</p>
    

<div class="Modulversion">($Revision: 1.14 $ - $RCSfile: module.form.inc.php,v $)</div>

<?php
}



/**
 * Pr�ft den Inhalt der �bergebenen Variable.
 * 
 * Ist die Variable leer und darf sie f�r MySQL NULL sein,
 * so gib ein NULL zur�ck. Sonst gib den Wert in Anf�hrungszeichen
 * zur�ck.
 * 
 * @param mixed Variable
 * @param mixed Defaultwert (NULL)
 * @return string
 */
function checkPostVarForMySQL($var, $default = '') {
  if (isset ($var) and $var != '') {
    $var = '"'.htmlspecialchars($var).'"';
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
function gbook_form_output($notificationEmail, $danke_text, $debuglevel) {
  global $REX;

  // wenn Template-Klasse noch nicht eingebunden, dann hole sie jetzt rein
  if (!class_exists ('Template')) {
    include_once ($REX['INCLUDE_PATH'].'/addons/guestbook/classes/template.inc.php');
  }
  //$_ROOT['template'] = $REX['INCLUDE_PATH'].'/addons/guestbook/templates/';


  /* create Template instance called $t */
  $t = new Template(".", "remove");
  //$t->debug = 7;
  $start_dir = GBOOK_TEMPLATEPATH.'gb_frontend_form.html';

  /* lese Template-Datei */
  $t->set_file(array("start" => $start_dir));

  // gbook_formularPostCheck($postvars, $domainname = false)
  if (($errorfields = validFields()) === true and gbook_formularPostCheck(array ($_POST['name'],$_POST['text'],$_POST['url'],$_POST['email'],$_POST['city']) )) {
    $author_value   = checkPostVarForMySQL($_POST['name']);
    $message_value  = checkPostVarForMySQL($_POST['text']);
    $url_value      = checkPostVarForMySQL($_POST['url'],'NULL');
    $email_value    = checkPostVarForMySQL($_POST['email'],'NULL');
    $city_value     = checkPostVarForMySQL($_POST['city'],'NULL');
    
    
    // Thema Sicherheit:
    // $status ist endweder 1, 0 oder false
    // die Funktion gbook_readStatusFromFile() l��t keine andere R�ckgabe zu
    $status = gbook_readStatusFromFile();
    if ($status === false) {
      echo 'Fehler.';
      $status_db = '';
    } else {
      $status_db = 'status = "'.$status.'",';
    }

    //$qry = 'INSERT INTO '.TBL_GBOOK.' SET  author = "'.$author.'", message = "'.$message.'", url ="'.$url.'", email="'.$email.'", city="'.$city.'", created = UNIX_TIMESTAMP()';
    $qry = 'INSERT INTO '.TBL_GBOOK.' SET '.$status_db.' author = '.$author_value.', message = '.$message_value.', 
            url = '.$url_value.', email = '.$email_value.', city = '.$city_value.', 
            created = UNIX_TIMESTAMP()';
    $sql = new sql();
    //$sql->debugsql = true;
    $sql->query($qry);
    

    if (!isset ($danke_text)) {
      $danke_text = '';
    }

    $t->set_var(array("DANKE_TEXT_VALUE" => $danke_text,
                      "FEHLERMELDUNG_VALUE" => '',
                      "ARTICLE_ID_VALUE" => $GLOBALS['article_id'],
                      "CLANG_VALUE" => $GLOBALS['clang'],
                      "NAME_VALUE" => '',
                      "EMAIL_VALUE" => '',
                      "URL_VALUE" => '',
                      "WOHNORT_VALUE" => '',
                      "TEXT_VALUE" => ''
                  ));

    // EMail an Admin
    if ($notificationEmail != '') {
      
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
      
      
      $host = !strstr($REX['SERVER'], 'http://') && !strstr($REX['SERVER'], 'https://') ? 'http://'.$REX['SERVER'] : $REX['SERVER'];
      if($host{strlen($host)-1} != '/')
      {
        $host .= '/';
      }
      $server = $host .'/redaxo';

      $author = htmlspecialchars($_POST['name']);
      $message = htmlspecialchars($_POST['text']);
      $url = htmlspecialchars($_POST['url']);
      $email = htmlspecialchars($_POST['email']);
      $city = htmlspecialchars($_POST['city']);
      
      $betreff = 'Neuer G�stebucheintrag f�r '. $host;
      $nachricht = 'Im G�stebuch f�r die Webseite "'.$host.'" wurde ein neuer Eintrag erstellt.'."\r\n\r\n";
      $nachricht .= 'Name: '.$author. "\r\n";
      $nachricht .= 'Homepage: '.$url. "\r\n";
      $nachricht .= 'eMail: '.$email. "\r\n";
      $nachricht .= 'Wohnort: '.$city. "\r\n\r\n";
      $nachricht .= 'Nachricht: '.$message. "\r\n\r\n\r\n";
      //$nachricht .= 'Hinweis: Dieser Eintrag wurde bei der Einstellung "Ver�ffentlichung nach Freigabe" deaktiviert gespeichert und erscheint erst dann in Ihren G�stebuch, wenn Sie den Eintrag aktiviert haben. Zum Log-In Bereich geht es unter '.$server."\r\n";
    
      // DebugInfo anh�ngen, falls gew�nscht
      $nachricht .= $debug_inhalt; 
      $header = 'From: '. $notificationEmail ."\r\n" .
         'Reply-To: '. $notificationEmail ."\r\n" .
         'X-Mailer: PHP/' . phpversion();
    
      mail ($notificationEmail, $betreff, $nachricht, $header);
    }
    
    
  } else { // if (($errorfields = validFields()) === true)
    $error = '';
    $name = '';
    $email = '';
    $url = '';
    $city = '';
    $text = '';

    if (!empty ($_POST['gbook_save'])) {
      // var_dump($_POST);
      // Felder mit Werten f�llen
      $name = $_POST['name'];
      $email = $_POST['email'];
      $url = $_POST['url'];
      $city = $_POST['city'];
      $text = $_POST['text'];

      $error .= '<ul class="error">';

      foreach ($errorfields as $fieldname) {
        $error .= '<li>Pflichtfeld "'.ucwords($fieldname).'" bitte korrekt ausf&uuml;llen!</li>';
      }

      $error .= '</ul>';
    } // if (!empty ($_POST['gbook_save']))
    
    
    $t->set_var(array("DANKE_TEXT_VALUE" => '',
                      "FEHLERMELDUNG_VALUE" => $error,
                      "ARTICLE_ID_VALUE" => $GLOBALS['article_id'],
                      "CLANG_VALUE" => $GLOBALS['clang'],
                      "NAME_VALUE" => $name,
                      "EMAIL_VALUE" => $email,
                      "URL_VALUE" => $url,
                      "WOHNORT_VALUE" => $city,
                      "TEXT_VALUE" => $text
                  ));
    
  } // else { // if (($errorfields = validFields()) === true)


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

  // Email Syntax Pr�fung
  if ($_POST['email'] != '' && 
     !(
      !(preg_match('!@.*@|\.\.|\,|\;!', $_POST['email']) ||
      !preg_match('!^.+\@(\[?)[a-zA-Z0-9\.\-]+\.([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$!', $_POST['email'])))
     )
  {
    $failed[] = 'email';
  }
  
  // URL Syntax Pr�fung
  if ($_POST['url'] != '' && 
      !preg_match('!^http(s)?://[\w-]+\.[\w-]+(\S+)?$!i',$_POST['url'])) 
  {
    $failed[] = 'url';
  }

  return empty ($failed) ? true : $failed;
}

?>