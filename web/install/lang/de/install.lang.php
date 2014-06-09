<?php
/* Copyright (C) 2013-2014  Stephan Kreutzer
 *
 * This file is part of automated_digital_publishing_server.
 *
 * automated_digital_publishing_server is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License version 3 or any later version,
 * as published by the Free Software Foundation.
 *
 * automated_digital_publishing_server is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License 3 for more details.
 *
 * You should have received a copy of the GNU Affero General Public License 3
 * along with automated_digital_publishing_server. If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * @file $/web/install/lang/de/install.lang.php
 * @author Stephan Kreutzer
 * @since 2013-09-14
 */



define("LANG_PAGETITLE", "Installation");

// -- Step 0 -------------------------------------------------------------------

define("LANG_STEP0_HEADER", "Installation");
define("LANG_STEP0_INTROTEXT", "„automated_digital_publishing_server“ ist ein Software-Paket, mit welchem „automated_digital_publishing“ als Online-Dienst auf einem Server betrieben werden kann. Da es sich bei Letzterem um eine native Anwendung handelt, die auf einem lokalen Rechner ausführbar ist, nutzt das Server-Paket gewöhnliche System-Calls und stellt über die Grundfunktionalität des nativen Pakets hinaus lediglich eine Web-Oberfläche zur Bedienung desselben bereit. Dementsprechend ist für den Betrieb aber auch eine Hosting-Umgebung erforderlich, welche die Ausführung von nativen Programmen per System-Call durch den Webserver zulässt. „automated_digital_publishing_server“ ist <a href=\"http://de.wikipedia.org/wiki/Freie_Software\">freie Software</a> gemäß der <a href=\"http://www.gnu.org/philosophy/free-sw.de.html\">Definition</a> der <a href=\"http://www.fsf.org/\">Free Software Foundation</a> (Freie-Software-Stiftung, siehe auch die europäische <a href=\"http://www.fsfe.org\">Free Software Foundation Europe</a>) infolge der Lizenzierung unter der GNU Affero General Public License 3 oder jeder späteren Version dieser Lizenz. Im nächsten Schritt des Installationsvorgangs wird der vollständige Lizenztext angezeigt, welchem Sie zustimmen, sobald Sie die Software verwenden. Ein kurzer Überblick über die eingeräumten Nutzungsrechte: ".
                                 "<ul>".
                                 "  <li>Es ist Ihnen gestattet, die Software auszuführen. Wenn die Software so ausgeführt wird, dass Teilnehmer eines Netzwerkes deren Nutzung veranlassen können (z.B. in Form eines Dienstes), sind Sie verpflichtet, eine Möglichkeit zu schaffen, über welche Teilnehmer dieses Netzwerkes die Software, welche Sie ausführen, erhalten können (Sie müssen dabei die Bedingungen für die Weitergabe erfüllen).</li>".
                                 "  <li>Es ist Ihnen gestattet, die Software weiterzugeben, wenn Sie die Software unter GNU AGPL 3 oder jeder späteren Version dieser Lizenz weitergeben.</li>".
                                 "  <li>Es ist Ihnen gestattet, die Software zu verändern. Sie sind verpflichtet, Ihre Veränderungen unter GNU AGPL 3 oder jeder späteren Version dieser Lizenz zu lizenzieren. Es ist Ihnen gestattet, die veränderte Software auszuführen und weiterzugeben (Sie müssen dabei die Bedingungen für das Ausführen und die Weitergabe erfüllen).</li>".
                                 "</ul>".
                                 "Jedoch: ".
                                 "<ul>".
                                 "  <li>Werte in der Datenbank müssen nicht mit dem Quellcode veröffentlicht werden, solange sie durch Benutzer-Interaktion generiert wurden und nicht für die interne Funktionsweise der Software verantwortlich sind.</li>".
                                 "  <li>Es dürfen eigene Werke von der Software verlinken lassen, ohne diese Werke unter GNU AGPL 3 oder einer späteren Version dieser Lizenz veröffentlichen zu müssen, solange diese Werke nicht in die Software integriert sind oder für die Ausführung einer Funktion der Software benötigt werden.</li>".
                                 "  <li>Wenn Sie Werte und Einstellungen in der Software ändern, die ausschließlich für die visuelle Darstellung verwendet werden (also nicht Teil eines Algorithmusses sind, und wenn Sie die Art und Weise, wie die Software auf diese Werte zugreift, nicht verändern), dann haben Sie lediglich die Konfiguration angepasst und keine eigentliche Veränderung vorgenommen. In diesem speziellen Fall sind Sie nicht dazu verpflichtet, die Anpassungen unter der GNU AGPL 3 oder einer späteren Version der Lizenz zu veröffentlichen, was es Ihnen gestattet, eigene individuelle Erscheinungsformen der Software zu gestalten.</li>".
                                 "  <li>Freie Software erlaubt beides, kommerzielle Nutzung und unentgeltliche Nutzung. Bei freier Software geht es in erster Linie um Freiheit und digitale Grundrechte, nicht um den Preis (Freiheit im Sinne von „Redefreiheit“ und nicht im Sinne von „Freibier“ – unentgeltliche Nutzung ist ein möglicher Seiteneffekt).</li>".
                                 "</ul>".
                                 "Weitere Anmerkungen und Ratschläge: ".
                                 "<ul>".
                                 "  <li>Wenn Sie die Software verändern, sind Sie dazu verpflichtet, Ihre Anpassungen sehr deutlich zu kennzeichnen.</li>".
                                 "  <li>Wenn Sie die Software verändern, wäre es nett von Ihnen, das primäre Repository des Projekts (sofern vorhanden), die Autoren der Software (siehe AUTHORS-Datei) über Ihre Aktivitäten zu informieren. Sie sind allerdings nicht dazu verpflichtet.</li>".
                                 "  <li>Diese Software wurde initial entwickelt, um Zusammenarbeit zu fördern. Sie sollten ebenfalls über eine ähnliche Ausrichtung Ihrer Aktivitäten nachdenken, anstatt Protektionismus zu befördern. Die Lizenz für diese Software wurde gewählt, um uns daran zu hindern, Ihnen Schaden zuzufügen, und um Sie daran zu hindern, Anderen Schaden zuzufügen (insbesondere ihren Benutzern und Lizenznehmern). Die Lizenz verhindert effektiv, dass Sie Anderen die digitalen Rechte vorenthalten, welche allen Computer-Benutzern naturgemäß zustehen, sowohl auf der technischen als auch auf der rechtlichen Ebene. Sie sollten nicht einmal versuchen, die Lizenzbestimmungen umgehen zu wollen, zumal selbige ohnehin für Sie die größtmöglichen Vorteile bewirken. Wenn Ihnen die Lizenzbestimmungen nicht zusagen, können Sie sich auch frei dafür entscheiden, die Software nicht zu benutzen und eine restriktiv lizenzierte „Alternative“ suchen.</li>".
                                 "  <li>Wenn Sie einen Lizenzverstoß entdecken (oder einen Fall, bei dem es sich um einen Lizenzverstoß handeln könnte), kontaktieren Sie bitte die Autoren der Software. Freiheit kann nur geschützt werden, wenn sie aktiv gegen ihre Gegner verteidigt wird. Keine Sorge, wir haben nicht vor, der Person, welche den Lizenzverstoß begangen hat, Schwierigkeiten zu bereiten, viel lieber würden wir eine Einhaltung der Lizenzbestimmungen bevorzugen.</li>".
                                 "  <li>Falls Sie auf einen Fehler in der Software stoßen oder zusätzliche Features wünschen, können Sie eine Issue dafür im primären Repository (sofern vorhanden, momentan kann <a href=\"https://github.com/skreutzer/automated_digital_publishing_server/issues\">dieser Ort</a> als das Issue-System des primären Repositorys betrachtet werden) anlegen oder die Autoren kontaktieren, wenn Sie die Änderungen selbst entweder nicht durchführen wollen oder können. Wenn Sie die Änderungen selbst vornehmen, wäre es nett von Ihnen, uns eine Benachrichtigung über die vorgenommenen Änderungen zukommen zu lassen.</li>".
                                 "</ul>".
                                 "Features der Software: ".
                                 "<ul>".
                                 "  <li>Benutzerverwaltung</li>".
                                 "  <li>Installationsroutine</li>".
                                 "  <li>Internationalisierung (Mehrsprachigkeit)</li>".
                                 "</ul>");
define("LANG_STEP0_PROCEEDTEXT", "Weiter");

// -- Step 1 -------------------------------------------------------------------

define("LANG_STEP1_HEADER", "Lizenzvertrag");
define("LANG_STEP1_PROCEEDTEXT", "Zustimmen");

// -- Step 2 -------------------------------------------------------------------

define("LANG_STEP2_HEADER", "Systemüberprüfung");
define("LANG_STEP2_SAFEMODEON", "In der <tt>php.ini</tt>-Konfigurationsdatei des Webservers ist nicht <tt>safe_mode=Off</tt> eingestellt, womit System-Calls verhindert werden. Achtung: ein Wechsel zu <tt>safe_mode=Off</tt> kann zu erheblichen Sicherheitsrisiken führen, wenn auch noch andere Web-Anwendungen auf dem Server ausgeführt werden!");
define("LANG_STEP2_JAVAVERSIONCHECK_PRE", "Es wurde versucht, die JavaVM per System-Call aufzurufen, mit folgendem Ergebnis: ");
define("LANG_STEP2_JAVAVERSIONCHECK_POST", "Wenn obenstehend keine Angaben zur auf dem Server installierten JavaVM angezeigt werden, ist eine solche entweder nicht installiert oder es liegt eine Fehlkonfiguration vor. Eine Fortsetzung der Installation würde dann zu einem unbrauchbaren Ergebnis führen.");
define("LANG_STEP2_ADPMISSING", "„automated_digital_publishing“ ist nicht installiert. Hierfür muss die Software in Form der ausführbaren Programme in das <tt>\$/automated_digital_publishing/</tt>-Verzeichnis kopiert werden, ohne dass die dort untergebrachten Dateien von „automated_digital_publishing_server“ überschrieben werden. Einigen Verzeichnissen muss womöglich Schreibberechtigung eingerichtet werden.");
define("LANG_STEP2_ADPNOSETUP", "„automated_digital_publishing“ wurde nicht eingerichtet. Hierfür muss <tt>\$/automated_digital_publishing/workflows/setup1</tt> ausgeführt werden. Für die von <tt>setup1</tt> hinterlegten Dateien muss eine Leseberechtigung vorliegen.");
define("LANG_STEP2_CONTINUE", "Weiter");
define("LANG_STEP2_RETRY", "Erneut versuchen");

// -- Step 3 -------------------------------------------------------------------

define("LANG_STEP3_HEADER", "Datenbankeinstellungen");
define("LANG_STEP3_REQUIREMENTS", "Diese Software benötigt einen laufenden MySQL-Datenbankserver. Füllen Sie bitte die Datenbank-Verbindungseinstellungen aus. Selbige werden in die Datei <tt>\$/libraries/database_connect.inc.php</tt> des Installationsverzeichnisses der Software geschrieben.");
define("LANG_STEP3_HOSTDESCRIPTION", "Adresse des Datenbankservers");
define("LANG_STEP3_USERNAMEDESCRIPTION", "Datenbank-Benutzername");
define("LANG_STEP3_PASSWORDDESCRIPTION", "Passwort dieses Datenbank-Benutzers");
define("LANG_STEP3_DATABASENAMEDESCRIPTION", "Name der Datenbank, welche die Tabellen enthalten wird");
define("LANG_STEP3_TABLEPREFIXDESCRIPTION", "Präfix für Datenbanktabellen (kann leer gelassen werden, wenn keine Namenskollisionen zu erwarten sind – Präfix endet üblicherweise mit einem Unterstrich '_')");
define("LANG_STEP3_SAVETEXT", "Einstellungen speichern");
define("LANG_STEP3_EDITTEXT", "Einstellungen editieren");
define("LANG_STEP3_PROCEEDTEXT", "Einstellungen bestätigen");
define("LANG_STEP3_DBCONNECTSUCCEEDED", "Verbindung zur Datenbank konnte erfolgreich hergestellt werden!");
// Corresponding with LANG_STEP4_DBCONNECTFAILED.
define("LANG_STEP3_DBCONNECTFAILED", "Verbindung zur Datenbank konnte nicht hergestellt werden. Fehlerbeschreibung: ");
// Corresponding with LANG_STEP4_DBCONNECTFAILEDNOERRORINFO.
define("LANG_STEP3_DBCONNECTFAILEDNOERRORINFO", "Keine Fehlerdetails!");
define("LANG_STEP3_DATABASECONNECTFILECREATEFAILED", "Der Versuch, <tt>\$/libraries/database_connect.inc.php</tt> anzulegen, ist fehlgeschlagen!");
define("LANG_STEP3_DATABASECONNECTFILEISWRITABLE", "<tt>\$/libraries/database_connect.inc.php</tt> kann geschrieben werden!");
define("LANG_STEP3_DATABASECONNECTFILEISNTWRITABLE", "<tt>\$/libraries/database_connect.inc.php</tt> kann nicht geschrieben werden! Entweder bestehen keine Schreibrechte auf das Verzeichnis <tt>\$/libraries/</tt>, sodass die Datei nicht angelegt werden kann, oder die Datei <tt>\$/libraries/database_connect.inc.php</tt> existiert bereits und die Berechtigung zum Schreiben der Datei wurde nicht eingeräumt. Womöglich müssen Sie die Berechtigungen für das Verzeichnis <tt>\$/libraries/</tt> und/oder die potentiell existierende Datei <tt>\$/libraries/database_connect.inc.php</tt> per FTP-Programm (CHMOD-Befehl) oder per Remote-Verbindung auf dem Server manuell einrichten. Vergessen Sie nicht, die Berechtigungen wieder zurückzusetzen, nachdem die Installation abgeschlossen wurde.");
define("LANG_STEP3_DATABASECONNECTFILEWRITABLEOPENFAILED", "<tt>\$/libraries/database_connect.inc.php</tt> schien schreibbar zu sein, sie konnte allerdings nicht geöffnet werden!");
define("LANG_STEP3_DATABASECONNECTFILEWRITEFAILED", "<tt>\$/libraries/database_connect.inc.php</tt> schien schreibbar zu sein, konnte erfolgreich geöffnet werden, jedoch ist das tatsächliche Schreiben fehlgeschlagen!");
define("LANG_STEP3_DATABASECONNECTFILEWRITESUCCEEDED", "<tt>\$/libraries/database_connect.inc.php</tt> konnte erfolgreich geschrieben werden!");
define("LANG_STEP3_DATABASECONNECTFILEISNTREADABLE", "<tt>\$/libraries/database_connect.inc.php</tt> kann nicht gelesen werden! Entweder bestehen keine Leserechte auf das Verzeichnis <tt>\$/libraries/</tt>, sodass auf die Datei nicht zugegriffen werden kann, oder der Datei <tt>\$/libraries/database_connect.inc.php</tt> fehlt die Leseberechtigung. Womöglich müssen Sie die Berechtigungen für das Verzeichnis <tt>\$/libraries/</tt> und/oder die Datei <tt>\$/libraries/database_connect.inc.php</tt> per FTP-Programm (CHMOD-Befehl) oder sonstigem Datenübertragungsprogramm auf dem Server manuell einrichten. Vergessen Sie nicht, die Berechtigungen wieder zurückzusetzen, nachdem die Installation abgeschlossen wurde.");
define("LANG_STEP3_DATABASECONNECTFILEISREADABLE", "Geschriebene <tt>\$/libraries/database_connect.inc.php</tt> kann gelesen werden!");
define("LANG_STEP3_DATABASECONNECTFILEDOESNTEXIST", "<tt>\$/libraries/database_connect.inc.php</tt> existiert nicht!");

// -- Step 3 -------------------------------------------------------------------

define("LANG_STEP4_HEADER", "Einrichtung");
define("LANG_STEP4_INITIALIZETEXT", "Einrichten");
define("LANG_STEP4_INITIALIZATIONDESCRIPTION", "Die Software muss nun initial eingerichtet werden. Dabei werden unter anderem die Tabellen in der Datenbank erzeugt.");
define("LANG_STEP4_CHECKBOXDESCRIPTIONDROPEXISTINGTABLES", "Bereits existierende Tabellen löschen (Achtung: kann nicht rückgängig gemacht werden!)");
define("LANG_STEP4_CHECKBOXDESCRIPTIONKEEPEXISTINGTABLES", "Tabellen nur neu anlegen, wenn sie noch nicht vorhanden sind (behält bestehende Tabellen unverändert bei)");
// Corresponding with LANG_STEP3_DBCONNECTFAILED.
define("LANG_STEP4_DBCONNECTFAILED", "Verbindung zur Datenbank konnte nicht hergestellt werden. Fehlerbeschreibung: ");
// Corresponding with LANG_STEP3_DBCONNECTFAILEDNOERRORINFO.
define("LANG_STEP4_DBCONNECTFAILEDNOERRORINFO", "Keine Fehlerdetails!");
define("LANG_STEP4_DBOPERATIONFAILED", "Datenbankoperation fehlgeschlagen. Fehlerbeschreibung: ");
define("LANG_STEP4_DBOPERATIONFAILEDNOERRORINFO", "Keine Fehlerdetails!");
define("LANG_STEP4_DBCOMMITFAILED", "Bestätigen der Datenbankoperationen fehlgeschlagen!");
define("LANG_STEP4_DBOPERATIONSUCCEEDED", "Einrichtung erfolgreich vorgenommen!");
define("LANG_STEP4_COMPLETETEXT", "Einrichtung abschließen");

// -- Step 4 -------------------------------------------------------------------

define("LANG_STEP5_HEADER", "Fertig!");
define("LANG_STEP5_COMPLETETEXT", "Installation erfolgreich abgeschlossen! Die Installationsroutine wird nun versuchen, sich selbst zu löschen. Wenn dies nicht gelingen sollte (Sie sich also nicht auf der Hauptseite anmelden können und wieder zur Installation umgeleitet werden), müssen Sie das Verzeichnis <tt>\$/install/</tt> manuell löschen, mindestens jedoch die Datei <tt>\$/install/install.php</tt>. Anschließend sollte das Anmeldeformular zugänglich sein.");
define("LANG_STEP5_EXITTEXT", "Beenden");


?>
