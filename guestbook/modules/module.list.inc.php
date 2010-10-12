<?php
/**
 * Guestbook Addon
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @author redaxo[at]koalashome[dot]de Sven (Koala) Eichler
 * @package redaxo4
 * @version $Id: module.list.inc.php,v 1.22 2010/10/12 19:28:57 koala_s Exp $
 */

// Dateifunktionen zur Statusbearbeitung einbinden
include_once ($REX['INCLUDE_PATH'].'/addons/guestbook/functions/function_gbook_file.inc.php');

/**
 * gbook_list_input
 *
 * @param $elementsPerPage
 * @param $paginationsPerPage
 * @param $dateFormat
 * @param $emailFormat
 * @param $encryptEmails
 * @param $status
 * @return
 */
function gbook_list_input($elementsPerPage, $paginationsPerPage, $dateFormat, $emailFormat, $encryptEmails, $status) {

$css_breite = '200px';
?>
  <fieldset>
    <legend>G&#228;stebuch Einstellungen</legend>

    <p>
      <label for="VALUE[1]" style="width:<?php echo $css_breite; ?>">Eintr&#228;ge pro Seite:</label>
      <input type="text" id="VALUE[1]" name="VALUE[1]" value="<?php echo $elementsPerPage ?>" size="2" maxlength="2" />
    </p>

    <p>
      <label for="VALUE[2]" style="width:<?php echo $css_breite; ?>">Anzahl anzuzeigender Seiten:</label>
      <input type="text" id="VALUE[2]" name="VALUE[2]" value="<?php echo $paginationsPerPage ?>" size="2" maxlength="2" />
    </p>
    <p>
      <label for="VALUE[5]" style="width:<?php echo $css_breite; ?>">Email-Adressen verschl&#252;sseln:</label>
      <select name="VALUE[5]" id="VALUE[5]">
       <option value="0" <?php echo $encryptEmails == '0' ? 'selected="selected"' : '' ?>>Nein</option>
       <option value="1" <?php echo $encryptEmails == '1' ? 'selected="selected"' : '' ?>>Ja</option>
      </select>
    </p>
    <p>
      <label for="VALUE[6]" style="width:<?php echo $css_breite; ?>">Ver&#246;ffentlichung erst nach Freigabe:</label>
      <select name="VALUE[6]" id="VALUE[6]">
       <option value="1" <?php echo $status == '1' ? 'selected="selected"' : '' ?>>Nein</option>
       <option value="0" <?php echo $status == '0' ? 'selected="selected"' : '' ?>>Ja</option>
      </select>
    </p>
    <p>
      <label for="VALUE[3]" style="width:<?php echo $css_breite; ?>">Datums-Format:</label>
      <input type="text" name="VALUE[3]" id="VALUE[3]" value="<?php echo $dateFormat ?>" />
    </p>
    <p>siehe <a href="http://php.net/strftime">PHP Manual - strftime()</a></p>

    <p>
      <label for="VALUE[4]" style="width:<?php echo $css_breite; ?>">Email-Adressen-Format:</label>
      <input type="text" name="VALUE[4]" id="VALUE[4]" value="<?php echo $emailFormat ?>" size="30" />
    </p>
    <p>
      Beispiel:<br />
      max.mustermann@nowhere.no<br />
      %to% == max.mustermann<br />
      %domain% == nowhere<br />
      %tldomain% == no<br />
      <br />
      Format-Beispiele:<br />
      %to%@%domain%.%tldomain%<br />
      %to%[AT]%domain%[DOT]%tldomain%<br />
      %to%*AT*%domain%*DOT*%tldomain%
    </p>
  </fieldset>
  <div class="Modulversion">($Revision: 1.22 $ - $RCSfile: module.list.inc.php,v $)</div>
    <?php


}

/**
 * gbook_list_output
 *
 * @param $elementsPerPage
 * @param $paginationsPerPage
 * @param $dateFormat
 * @param $emailFormat
 * @param $encryptEmails
 * @param $status
 * @return
 */
function gbook_list_output($elementsPerPage, $paginationsPerPage, $dateFormat, $emailFormat, $encryptEmails, $status)
{
  global $REX;

  // hier beliebige mail encrypt funktion einbinden
  include_once ($REX['INCLUDE_PATH'].'/addons/guestbook/encryptions/mailcrypt2.php');

  // wenn Template-Klasse noch nicht eingebunden, dann hole sie jetzt rein
  if (!class_exists ('Template')) {
    include_once ($REX['INCLUDE_PATH'].'/addons/guestbook/classes/template.inc.php');
  }
  //$_ROOT['template'] = $REX['INCLUDE_PATH'].'/addons/guestbook/templates/';



  // Ausgabe nur im Frontend
  if ($REX['REDAXO'] != true) {
    $page = empty ($_GET['page']) ? 0 : intval($_GET['page']);

    $qry = 'SELECT * FROM '.TBL_GBOOK.' WHERE status = "1" ORDER BY id DESC LIMIT '. ($page * $elementsPerPage).', '.$elementsPerPage;
//    $qry = 'SELECT * FROM '.TBL_GBOOK.' WHERE status = "'. $status .'" ORDER BY id DESC LIMIT '. ($page * $elementsPerPage).', '.$elementsPerPage;
    $sql = new rex_sql();
//    $sql->debugsql = true;
    $data = $sql->getArray($qry);


    // Gesamtanzahl Eintraege ermitteln
    $qry_gesamt = 'SELECT * FROM '.TBL_GBOOK.' WHERE status = "1"';
    $sql_gesamt = new rex_sql();
    $sql_gesamt->getArray($qry_gesamt);
    $EintragsanzahlGesamt = $sql_gesamt->getRows();
    

    /* create Template instance called $t */
    $t = new Template(GBOOK_TEMPLATEPATH, "remove");
    //$t->debug = 7;
    //$start_dir = 'gb_frontend_output.html';
    $start_dir = 'gb_frontend_output2.html';

    /* lese Template-Datei */
    $t->set_file(array("start" => $start_dir));


    if (is_array($data)) {

      $GB_SEITEN = gbook_pagination($page, $elementsPerPage, $paginationsPerPage);
      $t->set_var(array("GB_SEITEN"   => $GB_SEITEN
                        ));

      $t->set_block("start", "EintragsUebersicht", "EintragsUebersicht_s");

      $NR = $EintragsanzahlGesamt - ($page * $elementsPerPage);
      foreach ($data as $row) {

        $url = strpos($row['url'], 'http://') === false ? 'http://'.$row['url'] : $row['url'];
        $row['url'] = empty ($row['url']) ? '-' : '<a href="'.$url.'">'.$row['url'].'</a>';
        $row['created'] = strftime( $dateFormat, $row['created']);

        $maillabel = gbook_formatemail($row['email'], $emailFormat);
        if ($encryptEmails == '1')
        {
          $maillabel = gbook_encryptmail($maillabel);
          $row['email'] = gbook_encryptmail($row['email']);
        }

        $AUTHOR_VALUE = stripslashes( $row['author']);

//        $row['email'] = empty ($row['email']) ? '-' : '<a href="mailto:'.$row['email'].'">'.$maillabel.'</a>';
        $row['email'] = empty ($row['email']) ? $AUTHOR_VALUE : '<a href="mailto:'.$row['email'].'">'.$AUTHOR_VALUE.'</a>';

        $EMAIL_VALUE = $row['email'];
        $HOMEPAGE_VALUE = $row['url'];
        $WOHNORT_VALUE = $row['city'];
        $DATUM_VALUE = $row['created'];
        $NACHRICHT_VALUE = nl2br( stripslashes( str_replace('  ', ' &#160;', $row['message'])));


        // hat der Admin eine Antwort verfasst?
        if ( trim( $row['reply']) != '') {
          $ANTWORT_VALUE = nl2br( stripslashes( str_replace('  ', ' &#160;', $row['reply'])));
          $ANTWORT_VORHANDEN_BEGINN = '';
          $ANTWORT_VORHANDEN_ENDE = '';
        } else {
          $ANTWORT_VALUE = '';
          $ANTWORT_VORHANDEN_BEGINN = '{*';
          $ANTWORT_VORHANDEN_ENDE = '*}';
        }

        $t->set_var(array("AUTHOR_VALUE"   => $AUTHOR_VALUE,
                          "EMAIL_VALUE"   => $EMAIL_VALUE,
                          "HOMEPAGE_VALUE"   => $HOMEPAGE_VALUE,
                          "WOHNORT_VALUE"   => $WOHNORT_VALUE,
                          "DATUM_VALUE"   => $DATUM_VALUE,
                          "NACHRICHT_VALUE"   => $NACHRICHT_VALUE,
                          "ANTWORT_VALUE"   => $ANTWORT_VALUE,
                          "ANTWORT_VORHANDEN_BEGINN"   => $ANTWORT_VORHANDEN_BEGINN,
                          "ANTWORT_VORHANDEN_ENDE"   => $ANTWORT_VORHANDEN_ENDE,
                          "NR" => $NR--
                          ));

        $t->parse("EintragsUebersicht_s", "EintragsUebersicht", true);

      } // foreach ($data as $row)

      // Einträge vorhanden sind, brauchen wir keinen "Einträge nicht vorhanden"-Hinweis
      $t->set_var(array("EINTRAEGE_BEGINN" => '',
                        "EINTRAEGE_ENDE"   => '',
                        "KEINE_EINTRAEGE_BEGINN" => '{*',
                        "KEINE_EINTRAEGE_ENDE"   => '*}'
                        ));

    } else { // if (is_array($data))

      // Einträge keine vorhanden sind, brauchen wir "Einträge nicht vorhanden"-Hinweis
      $t->set_var(array("EINTRAEGE_BEGINN" => '{*',
                        "EINTRAEGE_ENDE"   => '*}',
                        "KEINE_EINTRAEGE_BEGINN" => '',
                        "KEINE_EINTRAEGE_ENDE"   => ''
                        ));
    } // if (is_array($data))

    // komplette Seite ausgeben
    $t->pparse("output", "start");

  } else {
   // Ausgabe im Backend
?>


  <p class="rex-info">Die&#160;Eintr&#228;ge sind nur im Backend sichtbar!</p>
  <h6>Konfiguration:</h6>
  <p>Eintr&#228;ge pro Seite: <span class="rex-em"><?php echo $elementsPerPage ?></span></p>
  <p>Anzahl anzuzgeigender Seiten: <span class="rex-em"><?php echo $paginationsPerPage ?></span></p>
  <p>Emailverschl&#252;sselung: <span class="rex-em"><?php echo $encryptEmails == '1' ? 'Ja' : 'Nein' ?></span></p>
  <p>Ver&#246;ffentlichung erst nach Freigabe: <span class="rex-em"><?php echo $status == '0' ? 'Ja' : 'Nein' ?></span></p>
  <p>Datumsformat: <span class="rex-em"><?php echo $dateFormat ?></span></p>
  <p>Emailformat: <span class="rex-em"><?php echo $emailFormat ?></span></p>

 <?php


  }

}


/**
 * gbook_pagination
 *
 * @param $currentPage
 * @param $elementsPerPage
 * @param $paginationsPerPage
 * @param int   status  1=online 0=offline
 * @return string komplette Seitennavigation
 */
function gbook_pagination($currentPage, $elementsPerPage, $paginationsPerPage) {

  $qry = 'SELECT count(*) rowCount FROM '.TBL_GBOOK .' WHERE '.TBL_GBOOK .'.status = "1"';
  $sql = new rex_sql();
  $data = $sql->getArray($qry);

  $oneSidePaginations = floor($paginationsPerPage / 2);
  //var_dump( $oneSidePaginations);
  $rowCount = $data[0]['rowCount'];
  //var_dump( $rowCount);
  $pageCount = ceil($rowCount / $elementsPerPage) + 1;
  //var_dump( $pageCount);
  if ($currentPage <= $oneSidePaginations) {
    (int) $start = 1;
  } else {
    (int) $start = $currentPage - $oneSidePaginations;
  }
  //var_dump( $start);
  //DebugOut($pageCount);

  $str = '';

  if ($currentPage != 0) {
    $str .= "\n".gbook_paginationurl(0, '&laquo;','Anfang')."\n";
  }

  // Seitenzahlen in ein Array speichern
  $seiten_array = array();

  // erste Seite
 // $seiten_array[] = gbook_paginationurl($start, $start +1, $start +1);

  for ($i = 0; $i <= $paginationsPerPage -1; $i ++) {
    if ($start == $pageCount) {
      break;
    }
    if ($currentPage == $start -1) {
      $seiten_array[] = gbook_paginationurl($start -1, $start , $start, 1);
    } else {
      $seiten_array[] = gbook_paginationurl($start -1, $start , $start);
    }
    $start ++;
  }

  // Arrayinhalt umdrehen und Seitenzahlen in eine Variable zurückschreiben
  // und ein Trennzeichen einfügen, damit man das im Seitenquelltext besser lesen kann
  //$str .= implode ("\n", array_reverse ($seiten_array));
  $str .= implode ("\n", $seiten_array);

  // zeige den Sprung zum Ende nur, wenn noch nicht alle Links zum anklicken zu sehen sind
  if ($currentPage != ($pageCount -3) and $currentPage != ($pageCount -2) and ($pageCount -2) > $currentPage) {
    $str .= "\n".gbook_paginationurl($pageCount -2, '&raquo;','Ende')."\n";
  }
  return $str;
}




/**
 * gbook_paginationurl
 *
 * @param int     $page
 * @param         $label
 * @param string  title_name -
 * @param bool    aktuelle Seite (1 oder 0)
 * @return string Link auf nächste Seite
 */
function gbook_paginationurl($page, $label = null, $title_name = '', $aktuelleSeite = 0)
{
  global $REX;
  if ($label === null)
  {
    $label = $page;
  }
  $class_aktuell = 'a63-pagination';
  if ($aktuelleSeite) {
    $class_aktuell = 'a63-pagination_aktuell';
  }
  //$link = '<li class="'.$class_aktuell.'"><a href="?article_id='.$GLOBALS['article_id'].'&amp;page='.$page.'" title="Seite '.$title_name.'" name="Seite '.$title_name.'">';
  $_art_id = & $REX['ARTICLE_ID'];
  $link = '<li class="'.$class_aktuell.'">';
  //$link .= '<a href="?article_id='.$_art_id.'&amp;page='.$page.'" title="Seite '.$title_name.'" name="Seite '.$title_name.'">';
  // Vorschlag zur Nutzung von realURL: http://forum.redaxo.de/sutra72140.html#72140
  $link .= '<a href="'.rex_getUrl($_art_id, '', array('page' => $page)).'" title="Seite '.$title_name.'" name="Seite '.$title_name.'">';
  $link .= $label.'</a></li>';
  return $link;
}



/**
 * gbook_formatemail
 *
 * @param $email
 * @param $format
 * @return array
 */
function gbook_formatemail($email, $format)
{
  $iATPos = strpos($email, '@');
  $iDotPos = strrpos($email, '.');

  $to = substr($email, 0, $iATPos);
  $domain = substr($email, $iATPos +1, $iDotPos - $iATPos -1);
  $tldomain = substr($email, $iDotPos +1);

  return str_replace(array ('%to%', '%domain%', '%tldomain%'), array ($to, $domain, $tldomain), $format);
}
