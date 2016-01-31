<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para las operaciones CRUD de las marcas
 *
 * @author $Author: aranape@gmail.com $
 * @since 17-May-2012
 * @version $Id: usuariosController.php 61 2015-08-23 22:59:12Z aranape@gmail.com $
 *
 * $Date: 2015-08-23 17:59:12 -0500 (dom, 23 ago 2015) $
 * $Rev: 61 $
 */
class usuariosController extends app\common\controller\TSLAppDefaultCRUDController {


    public function __construct() {
        parent::__construct();
    }

    protected function setupData() {

        $this->setupOpts = [
            "validateOptions" => [
                "fetch" => [],
                "read" => ["langId" => 'usuarios', "validationId" => 'usuarios_validation', "validationGroupId" => 'v_usuarios', "validationRulesId" => 'getUsuarios'],
                "add" => ["langId" => 'usuarios', "validationId" => 'usuarios_validation', "validationGroupId" => 'v_usuarios', "validationRulesId" => 'addUsuarios'],
                "del" => ["langId" => 'usuarios', "validationId" => 'usuarios_validation', "validationGroupId" => 'v_usuarios', "validationRulesId" => 'delUsuarios'],
                "upd" => ["langId" => 'usuarios', "validationId" => 'usuarios_validation', "validationGroupId" => 'v_usuarios', "validationRulesId" => 'delUsuarios']
            ],
            "paramsList" => [
                "fetch" => [],
                "read" => ['usuarios_id', 'verifyExist'],
                "add" => ['usuarios_code', 'usuarios_password', 'usuarios_nombre_completo', 'usuarios_admin', 'activo'],
                "del" => ['usuarios_id', 'versionId'],
                "upd" => ['usuarios_id', 'usuarios_code', 'usuarios_password', 'usuarios_nombre_completo', 'usuarios_admin', 'versionId', 'activo']
            ],
            "paramsFixableToNull" => ['usuarios_id'],
            "paramsFixableToValue" => ["usuarios_id" => ["valueToFix" => 'null', "valueToReplace" => NULL, "isID" => true]],
            "paramToMapId" => 'usuarios_id'
        ];
    }

    protected function getBussinessService() {
        return new UsuariosBussinessService();
    }

}
