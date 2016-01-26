<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para las operaciones CRUD de las niveles del entrenador.
 *
 * @author $Author: aranape $
 * @since 17-May-2012
 * @version $Id: entrenadoresNivelController.php 7 2014-02-11 23:55:54Z aranape $
 *
 * $Date: 2014-02-11 18:55:54 -0500 (mar, 11 feb 2014) $
 * $Rev: 7 $
 */
class entrenadoresNivelController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    private function readEntrenadoresNivel() {

        try {
            if ($this->validateInputData($this->DTO, 'entrenadores_nivel', 'entrenadores_nivel_validation', 'v_entrenadores_nivel', 'getEntrenadoresNivel') === TRUE) {
                $this->DTO->addParameter('id', $this->input->get_post('entrenadores_nivel_codigo'));
                $this->DTO->addParameter('entrenadores_nivel_codigo', $this->input->get_post('entrenadores_nivel_codigo'));
                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));

                // Ir al Bussiness Object
                $entrenadoresNivelService = new EntrenadoresNivelBussinessService();
                $entrenadoresNivelService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function fetchEntrenadoresNivel() {
        try {
            // Ir al Bussiness Object
            $entrenadoresNivelService = new EntrenadoresNivelBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);

            $entrenadoresNivelService->executeService('list', $this->DTO);
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addEntrenadoresNivel() {
        try {

            if ($this->validateInputData($this->DTO, 'entrenadores_nivel', 'entrenadores_nivel_validation', 'v_entrenadores_nivel', 'addEntrenadoresNivel') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('entrenadores_nivel_codigo', $this->input->get_post('entrenadores_nivel_codigo'));
                $this->DTO->addParameter('entrenadores_nivel_descripcion', $this->input->get_post('entrenadores_nivel_descripcion'));
                $this->DTO->addParameter('activo', true); // Siempre se agrega vivo
                // Ir al Bussiness Object
                $entrenadoresNivelService = new EntrenadoresNivelBussinessService();
                $entrenadoresNivelService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updateEntrenadoresNivel() {
        try {

            if ($this->validateInputData($this->DTO, 'entrenadores_nivel', 'entrenadores_nivel_validation', 'v_entrenadores_nivel', 'updEntrenadoresNivel') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('entrenadores_nivel_codigo', $this->input->get_post('entrenadores_nivel_codigo'));
                $this->DTO->addParameter('entrenadores_nivel_descripcion', $this->input->get_post('entrenadores_nivel_descripcion'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo'));

                // Ir al Bussiness Object
                $entrenadoresNivelService = new EntrenadoresNivelBussinessService();
                $entrenadoresNivelService->executeService('update', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function deleteEntrenadoresNivel() {
        try {
            if ($this->validateInputData($this->DTO, 'entrenadores_nivel', 'entrenadores_nivel_validation', 'v_entrenadores_nivel', 'delEntrenadoresNivel') === TRUE) {
                $this->DTO->addParameter('entrenadores_nivel_codigo', $this->input->get_post('entrenadores_nivel_codigo'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $entrenadoresNivelService = new EntrenadoresNivelBussinessService();
                $entrenadoresNivelService->executeService('delete', $this->DTO);
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
        // Algunas librerias envia el texto null en casos de campos sin datos lo ponemos a NULL
        $this->parseParameters('entrenadores_nivel_');
        // ya que podria no haberse enviado y estar no definido
        $entrenadoresNivelId = $this->fixParameter('entrenadores_nivel_codigo', 'null', NULL);

        // Vemos si esta definido el tipo de suboperacion
        $operationId = $this->input->get_post('_operationId');
        if (isset($operationId) && is_string($operationId)) {
            $this->DTO->setSubOperationId($operationId);
        }


        // Se setea el usuario
        $this->DTO->setSessionUser($this->getUser());

        // Leera los datos del tipo de contribuyentes por default si no se envia
        // una operacion especifica.
        $op = $_REQUEST['op'];
        if (!isset($op) || $op == 'fetch') {
            // Si la suboperacion es read o no esta definida y se ha definido la pk se busca un registro unico
            // de lo contrario se busca en forma de resultset
            if (isset($entrenadoresNivelId) && ($operationId == 'read' || !isset($operationId) || $operationId === FALSE)) {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_READ);
                $this->readEntrenadoresNivel();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchEntrenadoresNivel();
            }
        } else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updateEntrenadoresNivel();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->deleteEntrenadoresNivel();
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addEntrenadoresNivel();
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
