<?php

$config['v_paises'] = array(
    'getPaises' => array(
        array(
            'field' => 'paises_codigo',
            'label' => 'lang:paises_codigo',
            'rules' => 'required|alpha_numeric|xss_clean|max_length[15]'
        )
    ),
    'updPaises' => array(
        array(
            'field' => 'paises_codigo',
            'label' => 'lang:paises_codigo',
            'rules' => 'required|alpha_numeric|xss_clean|max_length[15]'
        ),
        array(
            'field' => 'paises_descripcion',
            'label' => 'lang:paises_descripcion',
            'rules' => 'required|xss_clean|max_length[120]'
        ),
        array(
            'field' => 'paises_entidad',
            'label' => 'lang:paises_entidad',
            'rules' => 'required|is_bool|xss_clean'
        ),
        array(
            'field' => 'regiones_codigo',
            'label' => 'lang:regiones_codigo',
            'rules' => 'required|alpha_numeric|xss_clean|max_length[15]'
        ),
        array(
            'field' => 'versionId',
            'label' => 'lang:versionId',
            'rules' => 'required|integer|xss_clean'
        )
    ),
    'delPaises' => array(
        array(
            'field' => 'paises_codigo',
            'label' => 'lang:paises_codigo',
            'rules' => 'required|alpha_numeric|xss_clean|max_length[15]'
        ),
        array(
            'field' => 'versionId',
            'label' => 'lang:versionId',
            'rules' => 'required|integer|xss_clean'
        )
    ),
    'addPaises' => array(
        array(
            'field' => 'paises_codigo',
            'label' => 'lang:paises_codigo',
            'rules' => 'required|alpha_numeric|xss_clean|max_length[15]'
        ),
        array(
            'field' => 'paises_descripcion',
            'label' => 'lang:paises_descripcion',
            'rules' => 'required|xss_clean|max_length[120]'
        ),
        array(
            'field' => 'paises_entidad',
            'label' => 'lang:paises_entidad',
            'rules' => 'required|is_bool|xss_clean'
        ),
        array(
            'field' => 'regiones_codigo',
            'label' => 'lang:regiones_codigo',
            'rules' => 'required|alpha_numeric|xss_clean|max_length[15]'
        )
    )
);
?>