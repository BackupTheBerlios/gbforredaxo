<?php

/**
 * Guestbook Addon 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @author redaxo[at]koalashome[dot]de Sven (Koala) Eichler
 * @package redaxo4
 * @version $Id: entries.inc.php,v 1.10 2010/10/12 19:38:46 koala_s Exp $
 */
 
//------------------------------> Parameter

$Basedir = dirname(__FILE__);

$entry_id = rex_request('entry_id','integer');
$mode =  rex_get('mode','string');
$func = rex_request('func', 'string');

$entry_id = !empty($entry_id) ? (int) $entry_id : 0;
$mode = !empty($mode) ? (string) $mode: '';

//DBO($func);
//if (rex_get('func','string') == 'status')
if ($func == 'status')
{
  $status = $mode == 'online_it' ? 1 : 0; 
  $qry = 'UPDATE '. TBL_GBOOK .' SET status="'. $status .'" WHERE id='. $entry_id;
  
  $sql = new rex_sql();
  //$sql->debugsql = true;
  $sql->setQuery($qry);
  
  $func = '';
}

//------------------------------> Eintragsliste
//if (rex_get('func','string','') == '')
if ($func == '')
{
  require_once $Basedir.'/../../addon_framework/classes/list/class.rex_list.inc.php';

  /**
   *  Liste anlegen 
   */
  $sql = 'SELECT * FROM '.TBL_GBOOK;

  // Standard sortierung nach id absteigend
  // Standard author ist shortcut
  //$list = new rexlist($sql, 'id', 'desc', 'author');
  $list = new rexList($sql, 'id', 'desc', 'author');
  $list->setLabel($I18N_A63->msg('label_list'));
//  $list->debug = true;

  /**
   *  Spalten aus dem SQL-ResultSet anlegen 
   */
  $colId = new resultColumn('id', $I18N_A63->msg('label_id'));
  $colAuthor = new resultColumn('author', $I18N_A63->msg('label_author'));
  $colMsg = new resultColumn('message', $I18N_A63->msg('label_message'), 'truncate');
  $colUrl = new resultColumn('url', $I18N_A63->msg('label_url'), 'url');
//  $colUrl = new resultColumn('url', $I18N_A63->msg('label_url'));
  $colCity = new resultColumn('city', $I18N_A63->msg('label_city'));
  $colCreated = new resultColumn('created', $I18N_A63->msg('label_created'), 'strftime', 'datetime');

  // ID zentrieren
  $colId->setBodyAttributes('style="text-align: center;"');

  /**
   *  Statische Spalten anlegen 
   */
  //Status
  $colStatus = new staticColumn('status', $I18N_A63->msg('label_status'));
  $colStatus->addCondition('status', '1', '<span class="rex-online">'. $I18N_A63->msg('status_online') .'</span>', array ('page' =>'guestbook', 'func' => 'status', 'mode' => 'offline_it', 'entry_id' => '%id%'));
  $colStatus->addCondition('status', '0', '<span class="rex-offline">'. $I18N_A63->msg('status_offline') .'</span>', array ('page' =>'guestbook', 'func' => 'status', 'mode' => 'online_it', 'entry_id' => '%id%'));
  
  // Antworten link
  $colAction = new staticColumn($I18N_A63->msg('reply'), $I18N_A63->msg('label_action'));
  
  
  /**
   *  Links auf die Spalten legen 
   */
  // Parameter "func" mit dem Wert "edit"
  // Parameter "entry_id" mit dem Wert "id" aus dem Resultset ("%id%")
  $colAuthor->setParams(array ('page' =>'guestbook', 'func' => 'edit', 'entry_id' => '%id%'));
  // Parameter "func" mit dem Wert "reply"
  // Parameter "entry_id" mit dem Wert "id" aus dem Resultset ("%id%")
  $colAction->setParams(array ('page' =>'guestbook', 'func' => 'edit', 'entry_id' => '%id%', '' => '#reply'));

  /**
   *  Optionen auf Spalten setzen
   *  M�gliche Optionen: OPT_NONE, OPT_SEARCH, OPT_SORT, OPT_FILTER, OPT_ALL
   */
  // Spalte "id" ist nicht durchsuchbar
  $colId->delOption(OPT_SEARCH | OPT_SORT);
  // Spalte "created" ist nicht durchsuchbar
  $colCreated->delOption(OPT_SEARCH);

  /**
   *  Spalten zur Anzeige hinzuf�gen 
   */
  $list->addColumn($colId);
  $list->addColumn($colAuthor);
  $list->addColumn($colMsg);
  $list->addColumn($colUrl);
  $list->addColumn($colCity);
  $list->addColumn($colCreated);
  $list->addColumn($colStatus);
  $list->addColumn($colAction);
  
  /**
   * Toolbars hinzuf�gen
   */
  $browseBar = new browseBar();
  // Add-Button Ausblenden
  $browseBar->setAddButtonStatus(false);
  $list->addToolbar($browseBar, 'top', 'half');
  $list->addToolbar(new searchBar(), 'top', 'half');
  $list->addToolbar(new statusBar(), 'bottom', 'half');
  $list->addToolbar(new maxElementsBar(), 'bottom', 'half');

  /**
   *  Tabelle anzeigen 
   */
  $list->show(false);
}
//------------------------------> Formular
//if (rex_get('func','string') == 'edit' || rex_get('func','string') == 'add')
if ($func == 'edit' || $func == 'add')
{
  require_once $Basedir.'/../../addon_framework/classes/form/class.rex_form.inc.php';
  //DBO($_POST);
  /** Reihenfolge muss eingehalten werden! */

  //------------------------------> Form

  $form = & new rexForm('structure_form');
  $form->setApplyUrl('index.php?page=guestbook');
  $form->setEditMode($entry_id != '');
  //$form->debug = true;

  //------------------------------> Hidden Fields

  $fieldFunc = & new hiddenField('func');
  $fieldFunc->setValue('edit');

  $fieldEntryId = & new hiddenField('entry_id');
  $fieldEntryId->setValue($entry_id);
  
  //------------------------------> Fields[Allgemein]

  
  $fieldAuthor = & new textField('author', $I18N_A63->msg('label_author'));
  $fieldAuthor->addValidator('notEmpty', $I18N_A63->msg('miss_author'));

  $fieldMsg = & new textAreaField('message', $I18N_A63->msg('label_message'), array ('style' => 'height: 100px'));
  $fieldMsg->addValidator('notEmpty', $I18N_A63->msg('miss_message'));

  $fieldUrl = & new textField('url', $I18N_A63->msg('label_url'));
  $fieldUrl->addValidator('isURL', $I18N_A63->msg('incorect_url'), true);

  $fieldEmail = & new textField('email', $I18N_A63->msg('label_email'));
  $fieldEmail->addValidator('isEmail', $I18N_A63->msg('incorect_email'), true);

  $fieldCity = & new textField('city', $I18N_A63->msg('label_city'));

  $fieldStatus = & new selectField('status', $I18N_A63->msg('label_status'));
  $fieldStatus->addAttribute('size', '1');
  $fieldStatus->addValidator('notEmpty', $I18N_A63->msg('miss_status'));
  $fieldStatus->addOption($I18N_A63->msg('status_online'), '1');
  $fieldStatus->addOption($I18N_A63->msg('status_offline'), '0');
  
  $fieldCreated = & new readOnlyField('created', $I18N_A63->msg('label_created'));
  $fieldCreated->setFormatType('strftime');
  $fieldCreated->setFormat('datetime');
  $fieldCreated->activateSave(true);

  //------------------------------> Fields[Antworten]

  $fieldReply = & new textAreaField('reply', $I18N_A63->msg('label_reply'), array ('style' => 'height: 100px'));

  //------------------------------> Set conditional Field Values
  
  //if (rex_get('func','string') == 'add')
  if ($func == 'add')
  {
    $fieldCreated->setValue( time());
  }
  
  //------------------------------> Add Fields: Section[Allgemein]

  $sectionCommon = & new rexFormSection(TBL_GBOOK, $I18N_A63->msg('label_form'), array ('id' => $entry_id));
  $sectionCommon->addField($fieldAuthor);
  $sectionCommon->addField($fieldMsg);
  $sectionCommon->addField($fieldUrl);
  $sectionCommon->addField($fieldEmail);
  $sectionCommon->addField($fieldCity);
  $sectionCommon->addField($fieldStatus);
  $sectionCommon->addField($fieldCreated);

  //------------------------------> Add Fields: Section[Antworten]

  $sectionReply = & new rexFormSection(TBL_GBOOK, $I18N_A63->msg('label_form_reply'), array ('id' => $entry_id));
  $sectionReply->setAnchor('reply');
  $sectionReply->addField($fieldReply);

  //------------------------------> Sections

  $form->addSection($sectionCommon);
  $form->addSection($sectionReply);

  //------------------------------> Add Fields: Form

  $form->addField($fieldFunc);
  $form->addField($fieldEntryId);
  
  //------------------------------> Show Form

  $form->show();
}
