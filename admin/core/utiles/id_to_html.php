<?php

function id_to_html($id) {
    if ($id == 'a' || $id == 'link') return 'link';
    if ($id == 'p' || $id == 'text') return 'text';
    if ($id == 'h' || $id == 'headline') return 'headline';
    return $id;
}