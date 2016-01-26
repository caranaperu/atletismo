<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para las operaciones CRUD de las regiones atleticas
 *
 * @author $Author: aranape $
 * @since 17-May-2012
 * @version $Id: regionesController.php 270 2014-06-27 20:21:34Z aranape $
 *
 * $Date: 2014-06-27 15:21:34 -0500 (vie, 27 jun 2014) $
 * $Rev: 270 $
 */
class regionesController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    private function readRegiones() {

        try {
            if ($this->validateInputData($this->DTO, 'regiones', 'regiones_validation', 'v_regiones', 'getRegiones') === TRUE) {
                $this->DTO->addParameter('id', $this->input->get_post('regiones_codigo'));
                $this->DTO->addParameter('regiones_codigo', $this->input->get_post('regiones_codigo'));
                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));

                // Ir al Bussiness Object
                $regionesService = new RegionesBussinessService();
                $regionesService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function fetchRegiones() {
        try {
            // Ir al Bussiness Object
            $regionesService = new RegionesBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);

            $regionesService->executeService('list', $this->DTO);
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addRegiones() {
        try {

            if ($this->validateInputData($this->DTO, 'regiones', 'regiones_validation', 'v_regiones', 'addRegiones') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('regiones_codigo', $this->input->get_post('regiones_codigo'));
                $this->DTO->addParameter('regiones_descripcion', $this->input->get_post('regiones_descripcion'));
                $this->DTO->addParameter('activo', true); // Siempre se agrega vivo
                // Ir al Bussiness Object
                $regionesService = new RegionesBussinessService();
                $regionesService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updateRegiones() {
        try {

            if ($this->validateInputData($this->DTO, 'regiones', 'regiones_validation', 'v_regiones', 'updRegiones') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('regiones_codigo', $this->input->get_post('regiones_codigo'));
                $this->DTO->addParameter('regiones_descripcion', $this->input->get_post('regiones_descripcion'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo'));

                // Ir al Bussiness Object
                $regionesService = new RegionesBussinessService();
                $regionesService->executeService('update', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function deleteRegiones() {
        try {
            if ($this->validateInputData($this->DTO, 'regiones', 'regiones_validation', 'v_regiones', 'delRegiones') === TRUE) {
                $this->DTO->addParameter('regiones_codigo', $this->input->get_post('regiones_codigo'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $regionesService = new RegionesBussinessService();
                $regionesService->executeService('delete', $this->DTO);
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
        $this->parseParameters('regiones_');
        // ya que podria no haberse enviado y estar no definido
        $regionesId = $this->fixParameter('regiones_codigo', 'null', NULL);

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
            if (isset($regionesId) && ($operationId == 'read' || !isset($operationId) || $operationId === FALSE)) {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_READ);
                $this->readRegiones();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchRegiones();
            }
        } else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updateRegiones();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->deleteRegiones();
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addRegiones();
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
