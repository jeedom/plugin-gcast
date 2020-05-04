Gcast
=====

Description
-----------

Mit dem Gcast-Plugin können Sie eine Verbindung zwischen Ihrem Google-Assistenten und herstellen
Jeedom. Es wird möglich sein, Ihr Google Home / Google Mini zu verwenden
TTS machen oder mit Jeedom über Interaktionen interagieren

Configuration
-------------

Plugin Konfiguration
=======================

Nach dem Herunterladen des Plugins müssen Sie es aktivieren und die IP eingeben
von Ihrem Google-Assistenten. Dieses Plugin ermöglicht es einem Google zu sprechen
Besetzung und Kontrolle der Lautstärke. Es fungiert auch als Brücke
für Interaktionen und Google Home.

IFTTT für TTS-Rückgabe konfigurieren
=========================================

Ohne IFTTT kann Ihr Google-Assistent nicht mit Jeedom austauschen.

**Hier sind die wenigen Konfigurationsschritte :**

-   Se connecter ou s'inscrire sur IFTTT : <https://ifttt.com> (oder über
    mobile App)

-   Registerkarte "Meine Applets" und dann "Neues Applet""

-   Klicken Sie auf "+ This" und wählen Sie "Google Assistant" (verknüpfen Sie Ihr Google
    Assistent des IFTTT, falls noch nicht geschehen)

-   Wählen Sie den Auslöser "Sagen Sie eine Phrase mit einer Textzutat"

**Beispiel für die Konfiguration des ersten Teils des Applets :**

-   **Was willst du sagen?** : dis à jeedom \$

    > **Tip**
    >
    > Sie müssen unbedingt '\ $' am Ende Ihres Satzes setzen

-   **Was ist eine andere Art, es zu sagen? (optional)** : maison \$

-   **Und noch anders? (optional)** : jarvis \$

-   **Was soll der Assistent als Antwort sagen??** : Je
    rennt mich

    > **Tip**
    >
    > Hier ist es der Satz, den Ihr Google-Assistent beantwortet
    > bevor es Ihre Anfrage bearbeitet

-   **Language** : French

-   Klicken Sie auf "+ That" und wählen Sie "Webhooks" (aktivieren Sie den Dienst, wenn nicht
    noch nicht fertig)

-   Wählen Sie den einzigen verfügbaren Auslöser : Stellen Sie eine Webanfrage

**Beispiel für die Konfiguration des zweiten Teils des Applets :**

-   **URL** : Sie müssen die auf der Seite von angegebene Rückgabe-URLs einfügen
    Ihre Ausrüstung

    > **Tip**
    >
    > Die Rückgabe-URLs muss geändert werden : ***ID\_ AUSRÜSTUNG*** doit
    > durch die ID Ihres Google-Assistenten ersetzt werden (Klicken Sie auf
    > "Erweiterte Konfiguration "auf Ihrer Geräteseite für
    > kenne die ID) und * query = XXXX * by query = {{TextField}}

    > **Important**
    >
    > URLs muss externe URLs sein
    > [https://mon\_dns.com/plugins/gcast/core/php/gcastApi.php?apikey = xxxxxxMA\_CLE\_APIxxxxxxxx & id = 142 & query = {{TextField}}](https://mon_dns.com/plugins/gcast/core/php/gcastApi.php?apikey=xxxxxxMA_CLE_APIxxxxxxxx&id=142&query={{TextField}})

-   **Method** : GET

-   **Inhaltstyp** : Anwendung / json

-   **Body** : {{TextField}}

Alles was Sie tun müssen, ist auf "Speichern" zu klicken und Ihre Interaktionen zu genießen
zwischen Google Assistant und Jeedom !

Die Verwendung von ASK ist sogar möglich
