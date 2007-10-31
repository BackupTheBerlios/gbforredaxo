<?php
/**
 * Guestbook Addon
 *
 * Diese Datei dient dazu, einige Grundeinstellungen während der
 * Installation des Addons vorzunehmen.
 *
 * @author redaxo[at]koalashome[dot]de Sven (Koala) Eichler
 * @package redaxo4
 * @version $Id: install.php,v 1.11 2007/10/31 17:43:18 koala_s Exp $
 */

/**
 * Vorgehensweise
 * Die install.sql muss bereits ausgeführt worden sein und die Module und
 * Action somit bereits in der Datenbank stehen.
 * Als erstes werden die IDs des Modules "Gästebuch - Eintragsliste" und der
 * Action "Gästebuch - Eintragsliste StatusPerdatei" ausgelesen und dann nachgesehen,
 * ob es dazu schon eine Zuweisung in der Tabelle rex_module_action gibt.
 * Ist das nicht der Fall, werden die IDs entsprechend eingetragen.
 *
 * Die automatische Zuweisung zwischen Action und Modul ist damit erledigt.
 * Im Fehlerfalle muss eine Meldung ausgegeben werden.
 * Die Action könnte dann evtl. per Hand noch zugewiesen werden.
 *
 *
 * @param   string  Name des Modules (auf richtige Schreibweise achten!)
 * @param   string  Name der Action (auf richtige Schreibweise achten!)
 * @return  mixed   TRUE oder ein Fehlertext
 */
function installAction2Modul_63($modul_name, $action_name) {
  global $REX;

  if (!isset ($modul_name) or $modul_name == '' or !isset ($action_name) or $action_name == '') {
    return 'installAction2Modul: Keinen Modul- oder Aktionname übergeben.';
  }

  /**
   * Diese Abfrage gibt zurück
   * - wenn es bereits eine Verküpfung in der Tabelle rex_module_action gibt:
   * m_id  a_id  mod_action_m_id   mod_action_a_id
   *  42     9       true             true
   *
   * - gibt es noch keine Verknüpfung, sieht die Rückgabe so aus:
   * m_id  a_id  mod_action_m_id   mod_action_a_id
   *  42     9       false             false
   *
   * m_id und a_id sind von MySQL vergebene IDs und entsprechen nicht diesem Beispiel hier!
   *
   */
  $qry = 'SELECT `'.$REX['TABLE_PREFIX'].'module`.`id` AS m_id, `'.$REX['TABLE_PREFIX'].'action`.`id` AS a_id,
            IF(`'.$REX['TABLE_PREFIX'].'module_action`.`module_id` != 0, "true", "false") AS mod_action_m_id,
            IF(`'.$REX['TABLE_PREFIX'].'module_action`.`action_id` != 0, "true", "false") AS mod_action_a_id
          FROM (`'.$REX['TABLE_PREFIX'].'module` , `'.$REX['TABLE_PREFIX'].'action`)
          LEFT JOIN `'.$REX['TABLE_PREFIX'].'module_action` ON ( `'.$REX['TABLE_PREFIX'].'module_action`.`module_id` = `'.$REX['TABLE_PREFIX'].'module`.`id`
            AND `'.$REX['TABLE_PREFIX'].'module_action`.`action_id` = `'.$REX['TABLE_PREFIX'].'action`.`id` )
          WHERE `'.$REX['TABLE_PREFIX'].'module`.`name` = "'.$modul_name.'"
            AND `'.$REX['TABLE_PREFIX'].'action`.`name` = "'.$action_name.'"
          LIMIT 1';

  $sql = new rex_sql();
  //$sql->debugsql = true;
  $data = $sql->getArray($qry);


  if (is_array($data) and $sql->getRows() == 1) {
    foreach ($data as $row) {
      // prüfe IDs auf vorhandensein
      // sind diese IDs in dieser Kombination noch nicht in der Verknüpfungstabelle
      // dann können sie dort eingetragen werden
      if ($row['mod_action_m_id'] == 'false' and $row['mod_action_a_id'] == 'false') {
        $qry = 'INSERT INTO `'.$REX['TABLE_PREFIX'].'module_action` ( `id` , `module_id` , `action_id` )
                VALUES (NULL , "'.$row['m_id'].'", "'.$row['a_id'].'")';
        $sql2 = new rex_sql();
        //$sql->debugsql = true;
        $sql2->setQuery($qry);
        if (!$REX['a63_sql_compare']) { $sql2->freeResult(); }
      } else {
        return 'installAction2Modul_63: Es exitiert bereits eine Zuweisung zwischen dem Modul "'.$modul_name.'" und der Aktion "'.$action_name.'".';
      }
    }
  } else {
    if (!$REX['a63_sql_compare']) { $sql2->freeResult(); }
    return 'installAction2Modul_63: Fehler in der Datenbankabfrage. Ist der Modulname "'.$modul_name.'" und der Aktionname "'.$action_name.'" richtig?';
  }
  if (!$REX['a63_sql_compare']) { $sql2->freeResult(); }
  return true;
} // installAction2Modul()


?>