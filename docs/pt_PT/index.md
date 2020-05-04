Gcast
=====

Description
-----------

O plug-in Gcast possibilita estabelecer um link entre seu Assistente do Google e
Jeedom. É possível usar sua Página inicial do Google / Google Mini para
faça TTS ou interaja com o Jeedom por meio de interações

Configuration
-------------

Configuração do plugin
=======================

Depois de baixar o plugin, você deve ativá-lo e inserir o IP
do seu Assistente do Google. Este plugin permite que o Google fale
lançar e controlar seu volume. Também atua como uma ponte
para interações e a Página inicial do Google.

Configurando o IFTTT para Retorno TTS
=========================================

Sem a IFTTT, seu Assistente do Google não poderá trocar com o Jeedom.

**Aqui estão as poucas etapas de configuração :**

-   Se connecter ou s'inscrire sur IFTTT : <https://ifttt.com> (ou via
    aplicativo para celular)

-   Guia "Meus Applets" e depois "Novo Applet"

-   Clique em "+ Este", escolha Google Assistant (vincule seu Google
    Assistente do IFTTT, se ainda não o tiver feito)

-   Escolha o gatilho "Diga uma frase com um ingrediente de texto"

**Exemplo de configuração da primeira parte do Applet :**

-   **O que você quer dizer?** : dis à jeedom \$

    > **Tip**
    >
    > Você deve absolutamente colocar '\ $' no final da sua frase

-   **Outra maneira de dizer isso? (optional)** : maison \$

-   **E de outra maneira? (optional)** : jarvis \$

-   **O que você quer que o Assistente diga em resposta?** : Je
    me corre

    > **Tip**
    >
    > Aqui está a frase que seu Assistente do Google responderá
    > antes de processar sua solicitação

-   **Language** : French

-   Clique em "+ Isso", escolha Webhooks (ative o serviço, se não estiver
    ainda não está pronto)

-   Escolha o único gatilho disponível : Faça uma solicitação da web

**Exemplo de configuração da segunda parte do Applet :**

-   **URL** : Você deve colar o URL  de retorno indicado na página de
    seu equipamento

    > **Tip**
    >
    > O URL  de retorno deve ser alterado : ***ID\_ EQUIPAMENTO*** doit
    > ser substituído pelo ID do seu Assistente do Google (clique em
    > "Configuração avançada "na página do seu equipamento para
    > conhecer o ID) e * query = XXXX * por query = {{TextField}}

    > **Important**
    >
    > O URL  deve ser externo
    > [https://mon\_dns.com/plugins/gcast/core/php/gcastApi.php?apikey = xxxxxxMA\_CLE\_APIxxxxxxxx & id = 142 & query = {{TextField}}](https://mon_dns.com/plugins/gcast/core/php/gcastApi.php?apikey=xxxxxxMA_CLE_APIxxxxxxxx&id=142&query={{TextField}})

-   **Method** : GET

-   **Tipo de conteúdo** : application / json

-   **Body** : {{TextField}}

Tudo o que você precisa fazer é clicar em "Salvar" e aproveitar suas interações
entre o Google Assistant e o Jeedom !

O uso do ASK é ainda possível
