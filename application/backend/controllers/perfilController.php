<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para las operaciones CRUD de los tipos de documentos de tramite.
 *
 *
 * @author  $Author: aranape $
 * @since   06-FEB-2013
 * @version $Id: perfilController.php 395 2014-01-11 09:22:31Z aranape $
 * @history ''
 *
 * $Date: 2014-01-11 04:22:31 -0500 (sáb, 11 ene 2014) $
 * $Rev: 395 $
 */
class perfilController extends app\common\controller\TSLAppDefaultCRUDController {

    public function __construct() {
        parent::__construct();
    }

    protected function setupData() {

        $this->setupOpts = [
            "validateOptions" => [
                "fetch" => [],
                "read" => ["langId" => 'perfil', "validationId" => 'perfil_validation', "validationGroupId" => 'v_perfil', "validationRulesId" => 'getPerfil'],
                "add" => ["langId" => 'perfil', "validationId" => 'perfil_validation', "validationGroupId" => 'v_perfil', "validationRulesId" => 'addPerfil'],
                "del" => ["langId" => 'perfil', "validationId" => 'perfil_validation', "validationGroupId" => 'v_perfil', "validationRulesId" => 'delPerfil'],
                "upd" => ["langId" => 'perfil', "validationId" => 'perfil_validation', "validationGroupId" => 'v_perfil', "validationRulesId" => 'updPerfil']
            ],
            "paramsList" => [
                "fetch" => [],
                "read" => ['perfil_id', 'verifyExist'],
                "add" => ['perfil_codigo', 'perfil_descripcion', 'sys_systemcode', 'activo'],
                "del" => ['perfil_id', 'versionId'],
                "upd" => ['perfil_id','perfil_codigo', 'perfil_descripcion', 'sys_systemcode', 'versionId', 'activo'],
            ],
            "paramsFixableToNull" => ['perfil_', 'sys_'],
            "paramsFixableToValue" => ["perfil_codigo" => ["valueToFix" => 'null', "valueToReplace" => NULL, "isID" => true]],
            "paramToMapId" => 'perfil_id'
        ];
    }

    protected function getBussinessService() {
        return new PerfilBussinessService();
    }

    protected function preExecuteOperation($operationCode) {
        if ($operationCode == 'add') {
            $constraints = NULL;

            // Si el parametro copy from perfil esta seteado generamos constraints para post procesar
            $copyFromPerfil = $this->input->get_post('prm_copyFromPerfil');
            if (isset($copyFromPerfil) && is_string($copyFromPerfil) && strlen($copyFromPerfil) > 0) {
                $constraints = &$this->DTO->getConstraints();
                // En este caso basta la asignacion directa
                $constraints->addParameter('prm_copyFromPerfil', $copyFromPerfil);
            }
        }
    }
}
