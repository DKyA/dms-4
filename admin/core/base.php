<?php

require $path['core'] . 'db/attacher.php';
require $path['core'] . 'libs/texts.php';

require $path['core'] . 'utiles/include_all.php';
run('core', 'update');
run('core', 'utiles');

require $path['core'] . 'page_info.php';

require $path['html'] . 'base/head.html';

require $path ['core'] . 'struct.php';

require $path['html'] . 'base/foot.html';

$db = null;