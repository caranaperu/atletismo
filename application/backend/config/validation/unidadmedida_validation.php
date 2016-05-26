<?php

$config['v_unidadmedida'] = array(
    'getUnidadMedida' => array(
        array(
            'field' => 'unidad_medida_codigo',
            'label' => 'lang:unidad_medida_codigo',
            'rules' => 'required|alpha|xss_clean||max_length[8]'
        )
    ),
    'updUnidadMedida' => array(
        array(
            'field' => 'unidad_medida_codigo',
            'label' => 'lang:unidad_medida_codigo',
            'rules' => 'required|alpha|xss_clean|max_length[8]'
        ),
        array(
            'field' => 'unidad_medida_descripcion',
            'label' => 'lang:unidad_medida_descripcion',
            'rules' => 'required|xss_clean|max_length[80]'
        ),
        array(
            'field' => 'unidad_medida_regex_e',
            'label' => 'lang:unidad_medida_regex_e',
            'rules' => 'required|xss_clean|max_length[60]'
        ),
        array(
            'field' => 'unidad_medida_regex_m',
            'label' => 'lang:unidad_medida_regex_m',
            'rules' => 'required|xss_clean|max_length[60]'
        ),
        array(
            'field' => 'unidad_medida_tipo',
            'label' => 'lang:unidad_medida_tipo',
            'rules' => 'required|xss_clean|max_length[1]'
        ),
        array(
            'field' => 'versionId',
            'label' => 'lang:versionId',
            'rules' => 'required|integer|xss_clean'
        )
    ),
    'delUnidadMedida' => array(
        array(
            'field' => 'unidad_medida_codigo',
            'label' => 'lang:unidad_medida_codigo',
            'rules' => 'required|alpha|xss_clean|max_length[8]'
        ),
        array(
            'field' => 'versionId',
            'label' => 'lang:versionId',
            'rules' => 'required|integer|xss_clean'
        )
    ),
    'addUnidadMedida' => array(
        array(
            'field' => 'unidad_medida_codigo',
            'label' => 'lang:unidad_medida_codigo',
            'rules' => 'required|alpha|xss_clean|max_length[8]'
        ),
        array(
            'field' => 'unidad_medida_descripcion',
            'label' => 'lang:unidad_medida_descripcion',
            'rules' => 'required|xss_clean|max_length[80]'
        ),
        array(
            'field' => 'unidad_medida_regex_e',
            'label' => 'lang:unidad_medida_regex_e',
            'rules' => 'required|xss_clean|max_length[60]'
        ),
        array(
            'field' => 'unidad_medida_regex_m',
            'label' => 'lang:unidad_medida_regex_m',
            'rules' => 'required|xss_clean|max_length[60]'
        ),
        array(
            'field' => 'unidad_medida_tipo',
            'label' => 'lang:unidad_medida_tipo',
            'rules' => 'required|xss_clean|max_length[1]'
        )
    )
);
?>