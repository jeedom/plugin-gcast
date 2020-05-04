Gcast
=====

Description
-----------

El complemento Gcast permite establecer un enlace entre su Asistente de Google y
Jeedom Será posible usar su Google Home / Google Mini para
hacer TTS o interactuar con Jeedom a través de interacciones

Configuration
-------------

Configuración del plugin
=======================

Después de descargar el complemento, debe activarlo e ingresar la IP
desde tu Asistente de Google. Este complemento permite que Google hable
emitir y controlar su volumen. También actúa como un puente.
para interacciones y Google Home.

Configurar IFTTT para el retorno de TTS
=========================================

Sin IFTTT, su Asistente de Google no podrá intercambiar con Jeedom.

**Estos son los pocos pasos de configuración. :**

-   Se connecter ou s'inscrire sur IFTTT : <https://ifttt.com> (o vía
    aplicación móvil)

-   Pestaña "Mis Applets" y luego "Nuevo Applet"

-   Haga clic en "+ Esto", elija Asistente de Google (enlace su Google
    Asistente de IFTTT si aún no lo ha hecho)

-   Elija el activador "Diga una frase con un ingrediente de texto"

**Ejemplo de configuración de la primera parte del Applet :**

-   **Que quieres decir?** : dis à jeedom \$

    > **Tip**
    >
    > Debe poner absolutamente '\ $' al final de su oración

-   **¿Cuál es otra forma de decirlo?? (optional)** : maison \$

-   **Y de otra manera? (optional)** : jarvis \$

-   **¿Qué quiere que diga el Asistente en respuesta??** : Je
    me corre

    > **Tip**
    >
    > Aquí está la oración que responderá tu Asistente de Google
    > antes de que procese su solicitud

-   **Language** : French

-   Haga clic en "+ Eso", elija Webhooks (active el servicio si no
    no hecho)

-   Elija el único disparador disponible : Hacer una solicitud web

**Ejemplo de configuración de la segunda parte del Applet :**

-   **URL** : Debe pegar la URL de retorno indicada en la página de
    tu equipo

    > **Tip**
    >
    > La URL de retorno debe cambiarse : ***ID\_ EQUIPO*** doit
    > ser reemplazado por la ID de su Asistente de Google (Haga clic en
    > "Configuración avanzada "en la página de su equipo para
    > conoce el ID) y * query = XXXX * por query = {{TextField}}

    > **Important**
    >
    > La URL debe ser externa.
    > [https://mon\_dns.com/plugins/gcast/core/php/gcastApi.php?apikey = xxxxxxMA\_CLE\_APIxxxxxxxx & id = 142 & query = {{TextField}}](https://mon_dns.com/plugins/gcast/core/php/gcastApi.php?apikey=xxxxxxMA_CLE_APIxxxxxxxx&id=142&query={{TextField}})

-   **Method** : GET

-   **Tipo de contenido** : aplicación / json

-   **Body** : {{TextField}}

Todo lo que tiene que hacer es hacer clic en "Guardar" y disfrutar de sus interacciones.
entre el Asistente de Google y Jeedom !

El uso de ASK es incluso posible
