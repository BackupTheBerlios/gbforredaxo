<!-- /**
 * Guestbook Addon
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @author redaxo[at]koalashome[dot]de Sven (Koala) Eichler
 * @package redaxo4
 * @version $Id: _readme.txt,v 1.11 2008/01/27 19:22:38 koala_s Exp $
 */ -->
<a href="?page=addon&amp;spage=help&amp;addonname=guestbook&amp;mode=changelog">Changelog</a>

<strong>Beschreibung:</strong>

Einfaches G�stebuch mit Eingabe-Formular und Redaxo-Seitiger Administration.
Original von Markus Staab (http://www.public-4u.de)

Anpassungen und Herausgabe der G�stebuchversion V2 durch Sven (Koala) Eichler.
Bei Fragen wende dich bitte an "redaxo [at] koalashome [punkt) de".

<em>Das G�stebuch ist seit version GB V2.1 RC1 nur noch mit REDAXO 4.0 kompatibel 
und ben�tigt mind. die AddOn Framework Version RC6!</em>


<strong>Download:</strong>

<a href="http://www.redaxo.de/18-0-addons.html">REDAXO Addon-Sammlung</a>


<strong>Installation:</strong>

- Unter "redaxo/include/addons" einen Ordner "guestbook" anlegen
  <strong>Wichtig: ( Der Name des Ordners muss "guestbook" lauten!)</strong>

- Alle Dateien des Archivs nach "redaxo/include/addons/guestbook" entpacken

- das Verzeichnis "guestbook/config/" und die darin enthaltene Datei "status.txt"
  m�ssen Schreibrechte erhalten (dies wird w�hrend der Installation NICHT gepr�ft)!

- Im Redaxo AddOn Manager das Plugin installieren

- Im Redaxo AddOn Manager das Plugin aktivieren

- Dem Benutzer das recht "guestbook[]" verleihen

- Die Module "Gaestebuch - Formular" und "Gaestebuch - Eintragsliste" in die ensprechenden Artikel einf�gen

- CSS auf die eigene Seite anpassen
  Weiteres zur CSS-Installation findest du weiter unten.
  
- fertig ;)

- Die Ausgabe-Template k�nnen im Ordner templates/ angepasst werden.
  Zu beachten sind hier lediglich die Platzhalter f�r die Datenausgabe.



<strong>CSS-Installation:</strong>

Die CSS-Datei sollte sich nach der Installation im Verzeichnis <em>files/tmp_/guestbook_63/guestbook.css</em> befinden.
Du hast nun drei M�glichkeiten.
- Im Template, welches die Seitenheader enth�lt, eine zus�tzliche Zeile eingef�gen.
  Irgendwo zwischen &lt;head&gt; und &lt;/head&gt; ist diese Zeile einzuf�gen:
  <strong>&lt;?php echo rex_register_extension_point('PAGE_HEADER_FRONTEND', ''); ?&gt;</strong>
  Damit wird ein neuer Extensionpoint angelegt �ber den die CSS-Datei eingebunden wird.

- Du kannst die CSS-Datei auch direkt im Header einbinden.
  Mittels dieser Zeile:
  <strong>&lt;link rel="stylesheet" type="text/css" href="./files/tmp_/guestbook_63/guestbook.css" /&gt;</strong>

- Oder du kopierst alles notwendige in eine andere der CSS-Datei die bereits eingebunden wird. 




<strong>Update von einer fr�heren Version:</strong>

- Alle G�stebuch Module l�schen (Dabei gehen die G�stebuch-Eintr�ge nicht verloren!)

- Weiter: siehe <strong>Installation</strong>



<strong>Credits:</strong>
- muadib2000 vom REDAXO-Forum (behilflich bei Suche nach Problemen mit MySQL 5)

- andre.5tz vom REDAXO-Forum

- <a href="http://www.blumbeet.de">Thomas Blum (tbaddade) vom REDAXO-Team</a>

- PEN vom REDAXO-Forum

    Vielen dank an alle die Bugs gemeldet oder Verbesserungsvorschl�ge gegeben haben.