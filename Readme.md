# 4. Generace DMS pokusu.
    - Minulý byl opuštěn po několika zásadních chybách nejen v koncepci
    - Ty chyby znemožňovaly rychlou opravu.
    - Byla nutnost to předělat.
# Poučení
    - V podstatě obecně = 2x přemýšlet
    - Koncept se ale ukazuje být poměrně funkční.


## Considerations - Forms:
    - Security flaw: Je nesmysl pouštět všechny form inputy z jednoho místa a mít tak univerzální přístup
    - Ano, je možné udělat dva uzavřené okruhy. Ale vždycky je potřeba si nějak uchovat a nezávisle ověřit ze které stránky to jede
    - A jestli na to má uživatel pravomoc.

## Considerations - Specials design:
    - Asi by bylo dobrý nějakým způsobem vkládat komponenty l-component i od specials / navbar-free věcí.
    - Asi by bylo dobrý pro to udělat nějakou logiku

## Considerations - Order on page:
    - Asi by bylo dobrý mít možnost nějakým způsobem specifikovat co se zobrazí jako první a co jako poslední

## Idea:
    - Neudělat z Update() věci nějaké tlačítko v nastavení?
    - Aby tam byla nějaká safety net...

## Reminder:
    - V každém případě udělat 1 formulář = 1 kategorie (zvlášť data a attributes!!!)