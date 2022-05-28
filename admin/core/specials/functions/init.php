<?php

require $path['core'] . "specials/confs/{$PI->ref}.php";
// Now I have templates AND modules

require $path['core'] . 'specials/functions/module.php';
// Tady bude možnost udělat si nějaký parent a z toho dědit na jednotlivé. Ale to udělám spíš až budu mít představu u db
$layout = [];

foreach ($modules as $key => $module) {
    $layout[$key] = new Module($module);
}

require $path['core'] . 'apply_components.php';
// Okej, tohle je potřeba trošku víc promyslet a to se mi nechce dělat po obědě... :()


if ($PI -> info['special']) {
    if (!isset($template)) {
        $template = 'blob_middle';
    }
    $file = "{$path['components']}templates/{$template}.php";
    creator($file , '<?php apply_module($layout);?>');
    require $file;
}
