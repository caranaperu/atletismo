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
class perfilController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    private function readPerfil() {

        try {
            if ($this->validateInputData($this->DTO, 'perfil', 'perfil_validation', 'v_perfil', 'getPerfil') === TRUE) {
                $this->DTO->addParameter('id', $this->input->get_post('perfil_id'));
                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));

                // Ir al Bussiness Object
                $PerfilService = new PerfilBussinessService();
                $PerfilService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function fetchPerfiles() {
        try {
            // Ir al Bussiness Object
            $PerfilService = new PerfilBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);
            $PerfilService->executeService('list', $this->DTO);
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addPerfil() {
        try {

            if ($this->validateInputData($this->DTO, 'perfil', 'perfil_validation', 'v_perfil', 'addPerfil') === TRUE) {

                $constraints = NULL;

                // Si el parametro copy from perfil esta seteado generamos constraints para post procesar
                $copyFromPerfil = $this->input->get_post('prm_copyFromPerfil');
                if (isset($copyFromPerfil) && is_string($copyFromPerfil) && strlen($copyFromPerfil) > 0) {
                    $constraints = &$this->DTO->getConstraints();
                    // En este caso basta la asignacion directa
                    $constraints->addParameter('prm_copyFromPerfil', $copyFromPerfil);
                }

                // Seteamos parametros en el DTO
                $this->DTO->addParameter('perfil_codigo', $this->input->get_post('perfil_codigo'));
                $this->DTO->addParameter('perfil_descripcion', $this->input->get_post('perfil_descripcion'));
                $this->DTO->addParameter('sys_systemcode', $this->input->get_post('sys_systemcode'));
                $this->DTO->addParameter('activo', true); // Siempre se agrega
                // Ir al Bussiness Object
                $PerfilService = new PerfilBussinessService();
                $PerfilService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updPerfil() {
        try {


            if ($this->validateInputData($this->DTO, 'perfil', 'perfil_validation', 'v_perfil', 'updPerfil') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('id', $this->input->get_post('perfil_id'));
                $this->DTO->addParameter('perfil_codigo', $this->input->get_post('perfil_codigo'));
                $this->DTO->addParameter('perfil_descripcion', $this->input->get_post('perfil_descripcion'));
                $this->DTO->addParameter('sys_systemcode', $this->input->get_post('sys_systemcode'));

                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo'));

                // Ir al Bussiness Object
                $PerfilService = new PerfilBussinessService();
                $PerfilService->executeService('update', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function deletePerfil() {
        try {
            if ($this->validateInputData($this->DTO, 'perfil', 'perfil_validation', 'v_perfil', 'delPerfil') === TRUE) {
                $this->DTO->addParameter('id', $this->input->get_post('perfil_id'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $PerfilService = new PerfilBussinessService();
                $PerfilService->executeService('delete', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    /**
     * Pagina index para este controlador , maneja todos los casos , lectura, lista
     * etc.
     */
    public function index() {
        $this->parseParameters('perfil_', 'sys_');
        // ya que podria no haberse enviado y estar no definido
        $perfilId = $this->fixParameter('perfil_codigo', 'null', NULL);

        // Vemos si esta definido el tipo de suboperacion
        $operationId = $this->input->get_post('_operationId');
        if (isset($operationId) && is_string($operationId)) {
            $this->DTO->setSubOperationId($operationId);
        }

        // Se setea el usuario
        $this->DTO->setSessionUser($this->getUser());

        // Leera los datos del tipo de contribuyentes por default si no se envia
        // una operacion especifica.
        $op = $this->input->get('op');
        if (!isset($op) || $op == 'fetch') {
            // Si la suboperacion es read o no esta definida y se ha definido la pk se busca un registro unico
            // de lo contrario se busca en forma de resultset
            if (isset($perfilId) && ($operationId == 'read' || !isset($operationId) || $operationId === FALSE)) {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_READ);
                $this->readPerfil();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchPerfiles();
            }
        } else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updPerfil();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->deletePerfil();
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addPerfil();
        } else {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage(70000, 'Operacion No Conocida', null);
            $outMessage->addProcessError($processError);
        }

        // Envia los resultados a traves del DTO
        //$this->responseProcessor->process($this->DTO);
        $data['data'] = &$this->responseProcessor->process($this->DTO);
        $this->load->view($this->getView(), $data);
    }

}
