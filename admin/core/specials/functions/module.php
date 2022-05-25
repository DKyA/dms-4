<?php

class Module {

    // private $raw_data;
    public $load_order;
    public $element;
    public $data;
    public $attributes;
    public $others;

    public function __construct($module) {
        // $this -> raw_data = $module;
        $this -> load_order = [];

            foreach($module as $att_k => $att_v) {
                if ($att_k == 'component') {
                    $this -> element = id_to_html($att_v);
                    continue;
                }
                if ($att_k == 'data') {
                    $this -> data = $att_v;
                    continue;
                }
                if ($att_k == 'data') {
                    $this -> attributes = $att_v;
                    continue;
                }
                if ($att_k == 'subcomponents') {
                    $this -> load_order[$att_k] = new Module($att_v);
                    continue;
                }
                $this -> others[$att_k] = $att_v;
            }
        }

}
