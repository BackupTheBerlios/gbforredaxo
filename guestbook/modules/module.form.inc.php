<?php

/**
 * Guestbook Addon 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: module.form.inc.php,v 1.1 2006/06/13 20:26:09 koala_s Exp $
 */
 
function gbook_form_input($notificationEmail)
{
  if(empty($notificationEmail))
  {
    global $REX;
    $notificationEmail = $REX['ERROR_EMAIL'];
  }
?>
    Email Benachritigungsadresse: (email@domain.de,post@domain.de)
    <br />
    <input type="text" name="VALUE[1]" value="<?php echo $notificationEmail ?>" class="inp100" />
    <br />
    siehe <a href="http://www.php.net/manual/de/function.mail.php" target="_blank">PHP Manual - mail() - to-Parameter</a>
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
 * 
 * 
 */
function gbook_form_output($notificationEmail)
{
  global $REX;

  // wenn Template-Klasse noch nicht eingebunden, dann hole sie jetzt rein
  if (!class_exists ('Template')) {
    include_once ($REX['INCLUDE_PATH'].'/addons/guestbook/classes/template.inc.php');
  }
  $_ROOT['template'] = $REX['INCLUDE_PATH'].'/addons/guestbook/templates/';


  /* create Template instance called $t */
  $t = new Template(".", "remove");
  //$t->debug = 7;
  //$start_dir = $_ROOT['template'].'gb_frontend_output.html';
  $start_dir = $_ROOT['template'].'gb_frontend_form.html';

  /* lese Template-Datei */
  $t->set_file(array("start" => $start_dir));


  if (($errorfields = validFields()) === true) {
    $author_value   = checkPostVarForMySQL($_POST['name']);
    $message_value  = checkPostVarForMySQL($_POST['text']);
    $url_value      = checkPostVarForMySQL($_POST['url'],'NULL');
    $email_value    = checkPostVarForMySQL($_POST['email'],'NULL');
    $city_value     = checkPostVarForMySQL($_POST['city'],'NULL');
    
    

    //$qry = 'INSERT INTO '.TBL_GBOOK.' SET  author = "'.$author.'", message = "'.$message.'", url ="'.$url.'", email="'.$email.'", city="'.$city.'", created = UNIX_TIMESTAMP()';
    $qry = 'INSERT INTO '.TBL_GBOOK.' SET author = '.$author_value.', message = '.$message_value.', 
            url = '.$url_value.', email = '.$email_value.', city = '.$city_value.', 
            created = UNIX_TIMESTAMP()';
    $sql = new sql();
    //$sql->debugsql = true;
    $sql->query($qry);
    
    echo'
      <p class="info">Danke für Ihren Eintrag!</p>
      <p>Die von Ihnen eingegebenen Daten wurden erfolgreich gespeichert.<br />Vor der Veröffentlichung wird der Eintrag durch den Webmaster geprüft. Freigegeben werden nur unbedenkliche Einträge.</p>
    ';

    if ($notificationEmail != '') {
      $author = htmlspecialchars($_POST['name']);
      $message = htmlspecialchars($_POST['text']);
      $url = htmlspecialchars($_POST['url']);
      $email = htmlspecialchars($_POST['email']);
      $city = htmlspecialchars($_POST['city']);
      
      $betreff = 'Neuer Eintrag im Gästebuch';
      $nachricht = 'Eintrag: '.$author."\r\n";
      $nachricht .= 'Homepage: '.$url."\n";
      $nachricht .= 'eMail: '.$email."\n".'Nachricht: '.$message;
    
      $header = 'From: '. $notificationEmail ."\r\n" .
         'Reply-To: '. $notificationEmail ."\r\n" .
         'X-Mailer: PHP/' . phpversion();
    
      mail($notificationEmail, $betreff, $nachricht, $header);
    }
  } else { // if (($errorfields = validFields()) === true)
    $error = '';
    $name = '';
    $email = '';
    $url = '';
    $city = '';
    $text = '';

    if (!empty ($_POST['gbook_save']))
    {
      // var_dump($_POST);
      // Felder mit Werten füllen
      $name = $_POST['name'];
      $email = $_POST['email'];
      $url = $_POST['url'];
      $city = $_POST['city'];
      $text = $_POST['text'];

      $error .= '<ul class="error">';

      foreach ($errorfields as $fieldname)
      {
        $error .= '<li>Pflichtfeld "'.ucwords($fieldname).'" bitte korrekt ausf&uuml;llen!</li>';
      }

      $error .= '</ul>';
    } // if (!empty ($_POST['gbook_save']))
?>

<form name="gbook" class="gbook" action="index.php" method="post">
  <input type="hidden" name="article_id" value="<?php echo $GLOBALS['article_id'] ?>" /> 
  <input type="hidden" name="clang" value="<?php echo $GLOBALS['clang'] ?>" /> 
  <?php echo $error ?>
  <p>
    <label for="gbook_name">Name*</label>
    <input type="text" id="gbook_name" name="name" value="<?php echo $name ?>" maxlength="255" />
  </p>
  <p>
    <label for="gbook_email">Email</label>
    <input type="text" id="gbook_email" name="email" value="<?php echo $email ?>" maxlength="255" />
  </p>
  <p>
    <label for="gbook_url">Homepage</label>
    <input type="text" id="gbook_url" name="url" value="<?php echo $url ?>" maxlength="255" />
  </p>
  <p>
    <label for="gbook_city">Wohnort</label>
    <input type="text" id="gbook_city" name="city" value="<?php echo $city ?>" maxlength="255" />
  </p>
  <p>
    <label for="gbook_text">Text*</label>
    <textarea id="gbook_text" name="text" cols="0" rows="0"><?php echo $text ?></textarea>
  </p>
  <p class="buttons">
    <input class="button" type="submit" name="gbook_save" value="eintragen" />
    <input class="button" type="reset" value="zur&uuml;cksetzen" />
  </p>
  <p class="hint">
    * Pflichtfelder
  </p>
</form>

<?php

  }
}



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
  
  // URL Syntax Prüfung
  if ($_POST['url'] != '' && 
      !preg_match('!^http(s)?://[\w-]+\.[\w-]+(\S+)?$!i',$_POST['url'])) 
  {
    $failed[] = 'url';
  }

  return empty ($failed) ? true : $failed;
}

?>