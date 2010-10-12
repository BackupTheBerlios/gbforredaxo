<?php
/**
 * Guestbook Addon 
 * @author redaxo[at]koalashome[dot]de Sven (Koala) Eichler
 * @package redaxo4
 * @version $Id: settings.inc.php,v 1.1 2010/10/12 19:38:23 koala_s Exp $
 */

/**
 * Spalte-User-Schlagworte
 */
// Module
//guestbook_63_Eintragsliste
//guestbook_63_Formular
// Action
//guestbook_63_StatusPerDatei
// DB-Tabelle
//`%TABLE_PREFIX%63_gbook`


$modul = rex_post('modul', 'array', NULL);
$template = rex_post('template', 'array', NULL);
$action = rex_post('action', 'array', NULL);
$func = rex_request('func', 'string');
$error = false;


/**
 * Dateninsert/Update
 */
if ($func == 'insert' or $func == 'update') {
  // wird FALSE wenn eine Aktion ausgefuehrt werden konnte
  $error_no_action = true;
  
  // AddOn-Name
  $mypage = 'guestbook'; // only for this file


  // TODO: class.install muss vorausgesetzt werden schon bei der Installation des Guestbook
  // Dies ist nur eine Notlösung!
  if (!class_exists ('install335')) {
    include_once ($REX['INCLUDE_PATH'].'/addons/class.install/classes/class.install335.inc.php');
  }
  
  $mi = new install335($mypage);
  $mi->get_debugsql = false; // default: false

  
  if (isset ($modul) and is_array ($modul) and count ($modul) > 0) {
    $error_no_action = false;
    foreach ($modul as $key => $name) { 
      switch ($name) {
        case 'guestbook_63_Formular':
          $mi->eingabe = 'modul_formular_eingabe.php';
          $mi->ausgabe = 'modul_formular_ausgabe.php';
          $mi->name = 'Gaestebuch - Formular';
          $mi->schluesselname = 'A63_Formular_Zur_Dateneingabe_321'; // A = Addon, 335 = Addon-ID, beliebiger, einmaliger Name
          if ($func == 'insert') {
            $mi->insertupdate = 'Insert';
          } else if ($func == 'update') {
            $mi->insertupdate = 'Update';
          }
          $mi->typ = 'Modul';
          $installations_status = $mi->install();
          // sollte bei der Installation irgendwo ein Fehler aufgetreten sein ... 
          if ($installations_status === true) {
            $mi->cleanVars();
          }
        break;
        
        case 'guestbook_63_Eintragsliste':
          $mi->eingabe = 'modul_eintragsliste_eingabe.php';
          $mi->ausgabe = 'modul_eintragsliste_ausgabe.php';
          $mi->name = 'Gaestebuch - Eintragsliste';
          $mi->schluesselname = 'A63_uebersicht_Zur_Datenausgabe_456'; // A = Addon, 335 = Addon-ID, beliebiger, einmaliger Name
          if ($func == 'insert') {
            $mi->insertupdate = 'Insert';
          } else if ($func == 'update') {
            $mi->insertupdate = 'Update';
          }
          $mi->typ = 'Modul';
          $installations_status = $mi->install();
          // sollte bei der Installation irgendwo ein Fehler aufgetreten sein ... 
          if ($installations_status === true) {
            $mi->cleanVars();
          }
        break;
        
        default:
  
        break;
      }
    } // foreach ($modul as $key => $name)
  } // if (isset ($modul) and is_array ($modul) and count ($modul) > 0) {
  
  
  if (isset ($action) and is_array ($action) and count ($action) > 0) {
    $error_no_action = false;
    foreach ($action as $key => $name) { 
      switch ($name) {
        case 'guestbook_63_StatusPerDatei':
  //        $mi->preview   = 'action_preview.php';
  //        $mi->preview_status  = 'edit'; // Edit only
          $mi->presave   = 'action_statusperdatei.php';
          $mi->presave_status  = 'add,edit'; // Add, Edit oder Delete
  //        $mi->postsave  = 'action_postsave.php';
  //        $mi->postsave_status = 'add,edit'; // Add, Edit oder Delete
          $mi->name = 'Gaestebuch - Eintragsliste StatusPerDatei';
          $mi->schluesselname = 'A63_guestbook_63_StatusPerDatei'; // A = Addon, 335 = Addon-ID, beliebiger, einmaliger Name
          if ($func == 'insert') {
            $mi->insertupdate = 'Insert';
          } else if ($func == 'update') {
            $mi->insertupdate = 'Update';
          }
          $mi->typ = 'Action';
          $installations_status = $mi->install();
          // sollte bei der Installation irgendwo ein Fehler aufgetreten sein ... 
          if ($installations_status === true) {
            $mi->cleanVars();
          }
        break;
        
        default:
        break;
      }
    } // foreach ($action as $key => $name)
  } // if (isset ($action) and is_array ($action) and count ($action) > 0) {
  
  
  

  $error = $mi->lasterror();
  
  if ($error != '' and strlen($error) > 3) {
    echo rex_warning('Fehler: '.$error,'rex-warning');
  } else if ($error_no_action === true) {
    // Wurde ein Button betaetigt aber keine Checkbox aktiviert gibt es diese Fehlermeldung
    echo rex_warning($I18N_CLAI->msg('keineAktionAusgewaehlt'),'rex-warning');
  } else {
    echo rex_warning($I18N_CLAI->msg('Installation_erfolgreich'),'rex-info');
  }
}


echo '
<div class="rex-addon-output">
  <h2>Konfiguration</h2>
  <div class="rex-addon-content">
<!--  <div class="rex-addon-editmode"> -->

  <form action="index.php" method="post">
    <fieldset>
      <input type="hidden" name="page" value="guestbook" />
      <input type="hidden" name="subpage" value="settings" />
      <input type="hidden" name="func" value="update" />
      <p class="rex-chckbx">
        <!-- <input type="checkbox" id="install_modul1" name="install_modul[guestbook_63_Eintragsliste]" value="install_modul1" /> -->
        <input type="checkbox" id="install_modul1" name="modul[]" value="guestbook_63_Eintragsliste" />
        <label class="rex-lbl-rght" for="install_modul1">Modul "Gaestebuch - Eintragsliste" re-installieren</label>
      </p>

      <p class="rex-chckbx">
        <input type="checkbox" id="install_modul2" name="modul[]" value="guestbook_63_Formular" />
        <label class="rex-lbl-rght" for="install_modul2">Modul "Gaestebuch - Formular" re-installieren</label>
      </p>
      
      <p class="rex-chckbx">
        <input type="checkbox" id="install_action1" name="action[]" value="guestbook_63_StatusPerDatei" />
        <label class="rex-lbl-rght" for="install_action1">Action "Gaestebuch - Eintragsliste StatusPerDatei" re-installieren</label>
      </p>
      
      
      <p class="rex-chckbx">
        <input type="checkbox" id="tabelle1" name="tabelle[]" value="guestbook_63_gbook" />
        <label class="rex-lbl-rght" for="tabelle1">Datenbanktabelle re-installieren</label>
      </p>
      
      <div class="rex-clear"></div>
      <p>
        <input type="submit" class="rex-sbmt" name="sendit" value="'.$I18N_A63->msg('Re_install_starten').'" />
      </p>
    </fieldset>
  </form>
  </div>
</div>
  ';

echo '
<div class="rex-addon-output">
  <h2>Insert</h2>
  <div class="rex-addon-content">
<!--  <div class="rex-addon-editmode"> -->

  <form action="index.php" method="post">
    <fieldset>
      <input type="hidden" name="page" value="guestbook" />
      <input type="hidden" name="subpage" value="settings" />
      <input type="hidden" name="func" value="insert" />
      <p class="rex-chckbx">
        <input type="checkbox" id="install_modul1" name="modul[]" value="guestbook_63_Eintragsliste" />
        <label class="rex-lbl-rght" for="install_modul1">Modul "Gaestebuch - Eintragsliste" installieren</label>
      </p>
      
      <div class="rex-clear"></div>
      <p>
        <input type="submit" class="rex-sbmt" name="sendit" value="'.$I18N_A63->msg('Install_starten').'" />
      </p>
    </fieldset>
  </form>
  </div>
</div>
  ';



// Testbeispiel
echo '
<div class="rex-addon-output">
  <h2>Konfiguration</h2>
  <div class="rex-addon-content"> 
<!--  <div class="rex-addon-editmode"> -->

  <form action="index.php" method="post">
        <fieldset>
          <p class="rex-chckbx">
            <input type="checkbox" id="Test1" name="Test1" value="test1" />
            <label class="rex-lbl-rght" for="Test1">Ich gehoere zu einer Testcheckbox</label>
          </p>
          <div class="rex-clear"></div>

          <p>
            <input type="submit" class="rex-sbmt" name="sendit" value="Test" />
          </p>
        </fieldset>
  </form>
  </div>
</div>
';





?>