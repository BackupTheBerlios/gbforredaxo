<?php
/**
 * Guestbook Addon 
 * @author redaxo[at]koalashome[dot]de Sven (Koala) Eichler
 * @package redaxo3
 * @version $Id: function_gbook_postcheck.inc.php,v 1.1 2006/06/25 16:04:21 koala_s Exp $
 */


/**
 * Dummyfunktion
 * Diese Funktion wird noch nicht benutzt.
 * Bleibt aber f�r evtl. sp�ter Verwendung drin.
 */
function logBadRequest() {
  return true;
}

/**
 * Pr�ft Formulversand und �bergebene Variablen
 * 
 * 
 * @todo Fehleranzeige verbessern (die()-Aufruf ist hier nicht gut)
 * 
 * @link http://www.alt-php-faq.org/local/115  How do I stop spammers using header injection with my PHP Scripts?
 * @param string/array  postvars - zu pr�fende POST-Variablen 
 * @param string/array  domainname - erlaubte Domainnamen (ist kein Name angegeben, wird diese Pr�fung ignoriert)
 * @return bool TRUE or FALSE 
 */
function gbook_formularPostCheck($postvars, $domainname = false) {

  // wenn keine zu pr�fenden POST-Variablen �bergeben wurden, gibts ein FALSE zur�ck
  // irgend etwas sollte schon zum pr�fen vorhanden sein, wenn diese Funktion aufgerufen wird
  if (!isset ($postvars) or $postvars == '') {
    return false;
  }


  // First, make sure the form was posted from a browser. 
  // For basic web-forms, we don't care about anything 
  // other than requests from a browser:     
  if(!isset($_SERVER['HTTP_USER_AGENT'])){ 
    die("Forbidden - You are not authorized to view this page"); 
    exit; 
  } 
  
  // Make sure the form was indeed POST'ed: 
  //  (requires your html form to use: action="post")  
  if(!$_SERVER['REQUEST_METHOD'] == "POST"){ 
    die("Forbidden - You are not authorized to view this page"); 
    exit;     
  } 
  

  /**
   * Dies nur ein Entwicklungs-Hack.
   * Wenn kein Domainname �bergeben wurde, ignoriere diese Pr�fung.
   *
   * Hier muss noch eine L�sung her, wie Domainnamen sinnvoll �bergeben werden k�nnen,
   * in Bezug auf Entwicklungsumgebung/Produktivumgebung.
   * 
   */
  if ($domainname !== false) {
    // Host names from where the form is authorized 
    // to be posted from:  
    if (!is_array ($domainname)) {
      $authHosts = array($domainname); 
    } else {
      //$authHosts = array("domain.com", "domain2.com", "domain3.com"); 
      $authHosts = $domainname; 
    }
    
    // Where have we been posted from? 
    $fromArray = parse_url(strtolower($_SERVER['HTTP_REFERER'])); 
    
    // Test to see if the $fromArray used www to get here. 
    $wwwUsed = strpos($fromArray['host'], "www."); 
    
    // Make sure the form was posted from an approved host name. 
    if(!in_array(($wwwUsed === false ? $fromArray['host'] : substr(stristr($fromArray['host'], '.'), 1)), $authHosts)){     
      logBadRequest(); 
      header("HTTP/1.0 403 Forbidden"); 
      exit;     
    } 
  } // if ($domainname !== false) 

  
  // Attempt to defend against header injections: 
  $badStrings = array("Content-Type:", 
                       "MIME-Version:", 
                       "Content-Transfer-Encoding:", 
                       "bcc:", 
                       "cc:"); 
  

  if (!is_array ($postvars)) {
    $_postvarcheck = array ($postvars);
  }
  
  // Loop through each POST'ed value and test if it contains 
  // one of the $badStrings: 
  foreach($_postvarcheck as $k => $v) {
    foreach($badStrings as $v2) {
      if(strpos($v, $v2) !== false) {
        logBadRequest(); 
        header("HTTP/1.0 403 Forbidden"); 
        exit; 
      } 
    } 
  }     
  
  // Made it past spammer test, free up some memory 
  // and continue rest of script:     
  unset($k, $v, $v2, $badStrings, $authHosts, $fromArray, $wwwUsed); 
  
  // wenn alles gut ging
  return true;
}

?>