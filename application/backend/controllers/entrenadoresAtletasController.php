<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para la relacion entrenador-atleta.
 *
 *
 * @author $Author: aranape $
 * @since 17-May-2012
 * @version $Id: entrenadoresAtletasController.php 54 2014-03-02 05:45:36Z aranape $
 *
 * $Date: 2014-03-02 00:45:36 -0500 (dom, 02 mar 2014) $
 * $Rev: 54 $
 */
class entrenadoresAtletasController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    private function readEntrenadoresAtletas() {

        try {
            if ($this->validateInputData($this->DTO, 'entrenadoresatletas', 'entrenadoresatletas_validation', 'v_entrenadoresatletas', 'getEntrenadoresAtletas') === TRUE) {
                $this->DTO->addParameter('id', $this->input->get_post('entrenadoresatletas_id'));
                $this->DTO->addParameter('entrenadoresatletas_id', $this->input->get_post('entrenadoresatletas_id'));
                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));

                // Ir al Bussiness Object
                $entrenadoresAtletasService = new EntrenadoresAtletasBussinessService();
                $entrenadoresAtletasService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function deleteEntrenadoresAtletas() {
        try {
            if ($this->validateInputData($this->DTO, 'entrenadoresatletas', 'entrenadoresatletas_validation', 'v_entrenadoresatletas', 'delEntrenadoresAtletas') === TRUE) {
                $this->DTO->addParameter('entrenadoresatletas_id', $this->input->get_post('entrenadoresatletas_id'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $entrenadoresAtletasService = new EntrenadoresAtletasBussinessService();
                $entrenadoresAtletasService->executeService('delete', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    /**
     * Leer la documentacion de la clase.
     */
    private function fetchEntrenadoresAtletas() {
        try {
            // Ir al Bussiness Object
            $entrenadoresAtletasService = new EntrenadoresAtletasBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);

            $entrenadoresAtletasService->executeService('list', $this->DTO);
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addEntrenadoresAtletas() {
        try {
            if ($this->validateInputData($this->DTO, 'entrenadoresatletas', 'entrenadoresatletas_validation', 'v_entrenadoresatletas', 'addEntrenadoresAtletas') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('entrenadores_codigo', $this->input->get_post('entrenadores_codigo'));
                $this->DTO->addParameter('atletas_codigo', $this->input->get_post('atletas_codigo'));
                $this->DTO->addParameter('entrenadoresatletas_desde', $this->input->get_post('entrenadoresatletas_desde'));
                $this->DTO->addParameter('entrenadoresatletas_hasta', $this->input->get_post('entrenadoresatletas_hasta'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo')); // Siempre se agrega
                // Ir al Bussiness Object
                $entrenadoresAtletasService = new EntrenadoresAtletasBussinessService();
                $entrenadoresAtletasService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updEntrenadoresAtletas() {
        try {
            if ($this->validateInputData($this->DTO, 'entrenadoresatletas', 'entrenadoresatletas_validation', 'v_entrenadoresatletas', 'updEntrenadoresAtletas') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('entrenadoresatletas_id', $this->input->get_post('entrenadoresatletas_id'));
                $this->DTO->addParameter('entrenadores_codigo', $this->input->get_post('entrenadores_codigo'));
                $this->DTO->addParameter('atletas_codigo', $this->input->get_post('atletas_codigo'));
                $this->DTO->addParameter('entrenadoresatletas_desde', $this->input->get_post('entrenadoresatletas_desde'));
                $this->DTO->addParameter('entrenadoresatletas_hasta', $this->input->get_post('entrenadoresatletas_hasta'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo')); // Siempre se agrega
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                // Ir al Bussiness Object
                $entrenadoresAtletasService = new EntrenadoresAtletasBussinessService();
                $entrenadoresAtletasService->executeService('update', $this->DTO);
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
        $this->parseParameters(['entrenadoresatletas_', 'entrenadores_', 'atletas_']);

        // ya que podria no haberse enviado y estar no definido
        $ligasclubesId = $this->fixParameter('entrenadoresatletas_id', 'null', NULL);

        // Vemos si esta definido el tipo de suboperacion
        $operationId = $this->input->get_post('_operationId');
        if (isset($operationId) && is_string($operationId)) {
            $this->DTO->setSubOperationId($operationId);
        }


        // Se setea el usuario
        $this->DTO->setSessionUser($this->getUser());

        $op = $_REQUEST['op'];
        if (!isset($op) || $op == 'fetch') {
            // Si la suboperacion es read o no esta definida y se ha definido la pk se busca un registro unico
            // de lo contrario se busca en forma de resultset
            if (isset($ligasclubesId) && ($operationId == 'read' || !isset($operationId) || $operationId === FALSE)) {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_READ);
                $this->readEntrenadoresAtletas();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchEntrenadoresAtletas();
            }
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addEntrenadoresAtletas();
        } else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updEntrenadoresAtletas();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->deleteEntrenadoresAtletas();
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
