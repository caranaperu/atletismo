<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para los records de diverso tipo sean nacionales , mundiales,etc
 *
 *
 * @author $Author: aranape $
 * @since 17-May-2012
 * @version $Id: recordsController.php 307 2014-07-16 02:17:13Z aranape $
 *
 * $Date: 2014-07-15 21:17:13 -0500 (mar, 15 jul 2014) $
 * $Rev: 307 $
 */
class recordsController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    private function readRecords() {

        try {
            if ($this->validateInputData($this->DTO, 'records', 'records_validation', 'v_records', 'getRecords') === TRUE) {
                $this->DTO->addParameter('id', $this->input->get_post('records_id'));
                $this->DTO->addParameter('records_id', $this->input->get_post('records_id'));
                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));

                // Ir al Bussiness Object
                $recordsService = new RecordsBussinessService();
                $recordsService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function deleteRecords() {
        try {
            if ($this->validateInputData($this->DTO, 'records', 'records_validation', 'v_records', 'delRecords') === TRUE) {
                $this->DTO->addParameter('records_id', $this->input->get_post('records_id'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $recordsService = new RecordsBussinessService();
                $recordsService->executeService('delete', $this->DTO);
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
    private function fetchRecords() {
        try {
            // Ir al Bussiness Object
            $recordsService = new RecordsBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);

            $recordsService->executeService('list', $this->DTO);
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addRecords() {
        try {
            if ($this->validateInputData($this->DTO, 'records', 'records_validation', 'v_records', 'addRecords') === TRUE) {

                // Seteamos parametros en el DTO
                $this->DTO->addParameter('records_tipo_codigo', $this->input->get_post('records_tipo_codigo'));
                $this->DTO->addParameter('atletas_resultados_id', $this->input->get_post('atletas_resultados_id'));
                $this->DTO->addParameter('categorias_codigo', $this->input->get_post('categorias_codigo'));
                $this->DTO->addParameter('records_id_origen', $this->input->get_post('records_id_origen'));
                $this->DTO->addParameter('records_protected', $this->input->get_post('records_protected'));

                $this->DTO->addParameter('activo', $this->input->get_post('activo')); // Siempre se agrega
                // Ir al Bussiness Object
                $recordsService = new RecordsBussinessService();
                $recordsService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updRecords() {
        try {
            if ($this->validateInputData($this->DTO, 'records', 'records_validation', 'v_records', 'updRecords') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('records_id', $this->input->get_post('records_id'));
                $this->DTO->addParameter('records_tipo_codigo', $this->input->get_post('records_tipo_codigo'));
                $this->DTO->addParameter('atletas_resultados_id', $this->input->get_post('atletas_resultados_id'));
                $this->DTO->addParameter('categorias_codigo', $this->input->get_post('categorias_codigo'));
                $this->DTO->addParameter('records_id_origen', $this->input->get_post('records_id_origen'));
                $this->DTO->addParameter('records_protected', $this->input->get_post('records_protected'));
                
                $this->DTO->addParameter('activo', $this->input->get_post('activo')); // Siempre se agrega
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                // Ir al Bussiness Object
                $recordsService = new RecordsBussinessService();
                $recordsService->executeService('update', $this->DTO);
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
        $this->parseParameters(['records_', 'records_tipo_', 'competencias_pruebas_', 'categorias_codigo_']);

        // ya que podria no haberse enviado y estar no definido
        $ligasclubesId = $this->fixParameter('records_id', 'null', NULL);

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
                $this->readRecords();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchRecords();
            }
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addRecords();
        } else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updRecords();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->deleteRecords();
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
