Gcast
=====

Description
-----------

The Gcast plugin makes it possible to establish a link between your Google Assistant and
Jeedom. It will be possible to use your Google Home / Google Mini to
do TTS or interact with Jeedom via interactions

Configuration
-------------

Plugin configuration
=======================

After downloading the plugin you must activate it and enter the IP
from your Google Assistant. This plugin allows a google to speak
cast and control its volume. It also acts as a bridge
for interactions and Google Home.

Configuring IFTTT for TTS return
=========================================

Without IFTTT, your Google Assistant will not be able to exchange with Jeedom.

**Here are the few configuration steps :**

-   Se connecter ou s'inscrire sur IFTTT : <https://ifttt.com> (or via
    mobile app)

-   "My Applets" tab then "New Applet"

-   Click on "+ This", choose Google Assistant (link your Google
    Assistant to IFTTT if not already done)

-   Choose the trigger "Say a phrase with a text ingredient"

**Example of configuration of the first part of the Applet :**

-   **What do you want to say?** : dis à jeedom \$

    > **Tip**
    >
    > You must absolutely put '\ $' at the end of your sentence

-   **What's another way to say it? (optional)** : maison \$

-   **And another way? (optional)** : jarvis \$

-   **What do you want the Assistant to say in response?** : Je
    runs me

    > **Tip**
    >
    > Here it is the sentence that your Google Assistant will answer
    > before it processes your request

-   **Language** : French

-   Click on "+ That", choose Webhooks (activate the service if not
    not already done)

-   Choose the only trigger available : Make a web request

**Example of configuration of the second part of the Applet :**

-   **URL** : You must paste the return url indicated in the page of
    your equipment

    > **Tip**
    >
    > Return url must be changed : ***ID\_ EQUIPMENT*** doit
    > be replaced by the ID of your Google Assistant (Click on
    > "Advanced configuration "on your equipment page for
    > know the ID) and * query = XXXX * by query = {{TextField}}

    > **Important**
    >
    > Url must be external url
    > [https://mon\_dns.com/plugins/gcast/core/php/gcastApi.php?apikey = xxxxxxMA\_CLE\_APIxxxxxxxx & id = 142 & query = {{TextField}}](https://mon_dns.com/plugins/gcast/core/php/gcastApi.php?apikey=xxxxxxMA_CLE_APIxxxxxxxx&id=142&query={{TextField}})

-   **Method** : GET

-   **Content type** : application / json

-   **Body** : {{TextField}}

All you have to do is click on "Save" and enjoy your interactions
between Google Assistant and Jeedom !

The use of ASK is even possible
