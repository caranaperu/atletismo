<?php

$config['v_perfil'] = array(
// Para la lectura de un contribuyente basado eb su id
    'getPerfil' => array(
        array(
            'field' => 'perfil_id',
            'label' => 'lang:perfil_id',
            'rules' => 'required|integer|xss_clean'
        )
    ),
    'updPerfil' => array(
        array(
            'field' => 'perfil_id',
            'label' => 'lang:perfil_id',
            'rules' => 'required|integer|xss_clean'
        ),
        array(
            'field' => 'sys_systemcode',
            'label' => 'lang:sys_systemcode',
            'rules' => 'required|onlyValidText|xss_clean|max_length[10]'
        ),
        array(
            'field' => 'perfil_codigo',
            'label' => 'lang:perfil_codigo',
            'rules' => 'required|onlyValidText|xss_clean|max_length[15]'
        ),
        array(
            'field' => 'perfil_descripcion',
            'label' => 'lang:perfil_descripcion',
            'rules' => 'required|onlyValidText|xss_clean|max_length[120]'
        ),
        array(
            'field' => 'versionId',
            'label' => 'lang:versionId',
            'rules' => 'required|integer|xss_clean'
        )
    ),
    'delPerfil' => array(
        array(
            'field' => 'perfil_id',
            'label' => 'lang:perfil_id',
            'rules' => 'required|integer|xss_clean'
        ),
        array(
            'field' => 'versionId',
            'label' => 'lang:versionId',
            'rules' => 'required|integer|xss_clean'
        )
    ),
    'addPerfil' => array(
        array(
            'field' => 'sys_systemcode',
            'label' => 'lang:sys_systemcode',
            'rules' => 'required|onlyValidText|xss_clean|max_length[10]'
        ),
        array(
            'field' => 'perfil_codigo',
            'label' => 'lang:perfil_codigo',
            'rules' => 'required|onlyValidText|xss_clean|max_length[15]'
        ),
        array(
            'field' => 'perfil_descripcion',
            'label' => 'lang:perfil_descripcion',
            'rules' => 'required|onlyValidText|xss_clean|max_length[120]'
        )
    )
);
?>