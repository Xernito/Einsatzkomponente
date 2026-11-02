# Einsatzkomponente für Joomla 6

Verwaltung und Darstellung von Einsatzberichten und Einsatzstatistiken für Feuerwehren (Joomla-Komponente `com_einsatzkomponente`).

Dieser Zweig (`joomla6`) ist die Portierung der Joomla-3-Version von [veenmeyer/Einsatzkomponente](https://github.com/veenmeyer/Einsatzkomponente) auf **Joomla 6.x**.

## Voraussetzungen

* Joomla 6.0 oder neuer (getestet mit 6.2.0)
* PHP 8.3 oder neuer
* MySQL 8.0.13+ / MariaDB 10.4+

## Installation

ZIP-Datei mit dem Inhalt dieses Repositorys (Wurzel = `einsatzkomponente.xml`) im Backend unter *System → Erweiterungen → Installieren* hochladen. Nach der Installation öffnet sich der Installationsmanager, der die Standarddaten (Kategorien, Einsatzarten, Karten-Konfiguration) anlegt.

> **Beta-Hinweis:** Die Portierung ist nur auf einer Testinstallation geprüft. Vor dem Einsatz Backup von Webspace und Datenbank anlegen.

## Update von Joomla 3

Ein direktes Update von Joomla 3 auf Joomla 6 ist nicht möglich. Ablauf: Datenbank-Backup, Joomla auf 6.x bringen (bzw. neue Joomla-6-Seite aufbauen, Tabellen `#__eiko_*` übernehmen), diese Version installieren. `administrator/sql/updates/mysql/5.0.0.sql` stellt die Tabellen auf utf8mb4 und NULL-fähige Datumsfelder um.

## Änderungen gegenüber der Joomla-3-Version

* Namespace `Einsatzkomponente\Component\Einsatzkomponente`, MVC über Service-Provider
* Verzeichnisstruktur `src/`, `tmpl/`, `forms/`, `services/`; Medien unter `media/com_einsatzkomponente` (Backend-Dateien unter `media/.../admin`)
* Backend-Listen mit Joomla-Search-Tools statt Sidebar-Filtern (Datumsfilter und Filter „Erstellt von/Bearbeitet von“ entfallen vorerst)
* WebAssetManager statt `addScript`/`addStyleSheet`, Bootstrap 5
* Update-Server der Joomla-3-Version entfernt (er würde Joomla-3-Pakete anbieten)
* Validierungsschlüssel: Anfrage an den Server des Autors nur noch, wenn im Backend ein Schlüssel eingetragen ist

## Lizenz

GNU General Public License Version 2 oder später (laut Manifest). Autor des Originals: Ralf Meyer, https://einsatzkomponente.de
