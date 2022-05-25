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
            if ($att_k == 'attributes') {
                $this -> attributes = $this -> inner_attributes($att_v);
                continue;
            }
            if ($att_k == 'subcomponents') {
                foreach ($att_v as $sub_k => $sub_v) {
                    $this -> load_order[$sub_k] = new Module($sub_v);
                }

                continue;
            }
            $this -> others[$att_k] = $att_v;
        }
    }

    private function inner_attributes($attributes) {
        $res = [
            'class' => '',
            'type' => '',
            'rel' => '',
            'target' => '',
            'src' => '',
            'width' => '',
            'height' => '',
            'id' => '', // Technicky vzato nepoužíváno. Ale může se stát, že budu potřebovat hodně custom id.
            'method' => '',
            'level' => ''
        ];

        foreach ($attributes as $k => $v) {

            switch($k) {
                case 'required':
                    if ($v) {
                        $res[$k] = $k;
                    }
                case 'type':
                    $res['class'] = "--{$v}";
                    $res[$k] = "{$k}='{$v}'";
                case 'level':
                    if ($v) {
                        $res[$k] = $v;
                    }
                    else {
                        $res[$k] = 1;
                    }
                case 'style' | 'class' | 'rel':
                    $res[$k] = $v;
                default:
                    $res[$k] = "{$k}='{$v}'";
            }

        }

        return $res;

    }

}
