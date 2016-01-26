<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para las operaciones CRUD de los paises.
 *
 * @author $Author: aranape $
 * @since 17-May-2012
 * @version $Id: recordsTipoController.php 294 2014-06-30 22:28:42Z aranape $
 *
 * $Date: 2014-06-30 17:28:42 -0500 (lun, 30 jun 2014) $
 * $Rev: 294 $
 */
class recordsTipoController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    private function readRecordsTipo() {

        try {
            if ($this->validateInputData($this->DTO, 'recordstipo', 'recordstipo_validation', 'v_records_tipo', 'getRecordsTipo') === TRUE) {
                $this->DTO->addParameter('id', $this->input->get_post('records_tipo_codigo'));
                $this->DTO->addParameter('records_tipo_codigo', $this->input->get_post('records_tipo_codigo'));
                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));

                // Ir al Bussiness Object
                $recordsTipoService = new RecordsTipoBussinessService();
                $recordsTipoService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function fetchRecordsTipo() {
        try {
            // Ir al Bussiness Object
            $recordsTipoService = new RecordsTipoBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);

            $recordsTipoService->executeService('list', $this->DTO);
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addRecordsTipo() {
        try {

            if ($this->validateInputData($this->DTO, 'recordstipo', 'recordstipo_validation', 'v_records_tipo', 'addRecordsTipo') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('records_tipo_codigo', $this->input->get_post('records_tipo_codigo'));
                $this->DTO->addParameter('records_tipo_descripcion', $this->input->get_post('records_tipo_descripcion'));
                $this->DTO->addParameter('records_tipo_abreviatura', $this->input->get_post('records_tipo_abreviatura'));
                $this->DTO->addParameter('records_tipo_tipo', $this->input->get_post('records_tipo_tipo'));
                $this->DTO->addParameter('records_tipo_clasificacion', $this->input->get_post('records_tipo_clasificacion'));
                $this->DTO->addParameter('records_tipo_peso', $this->input->get_post('records_tipo_peso'));
                $this->DTO->addParameter('records_tipo_protected', $this->input->get_post('records_tipo_protected'));

                $this->DTO->addParameter('activo', true); // Siempre se agrega vivo
                // Ir al Bussiness Object
                $recordsTipoService = new RecordsTipoBussinessService();
                $recordsTipoService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updateRecordsTipo() {
        try {

            if ($this->validateInputData($this->DTO, 'recordstipo', 'recordstipo_validation', 'v_records_tipo', 'updRecordsTipo') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('records_tipo_codigo', $this->input->get_post('records_tipo_codigo'));
                $this->DTO->addParameter('records_tipo_descripcion', $this->input->get_post('records_tipo_descripcion'));
                $this->DTO->addParameter('records_tipo_abreviatura', $this->input->get_post('records_tipo_abreviatura'));
                $this->DTO->addParameter('records_tipo_tipo', $this->input->get_post('records_tipo_tipo'));
                $this->DTO->addParameter('records_tipo_clasificacion', $this->input->get_post('records_tipo_clasificacion'));
                $this->DTO->addParameter('records_tipo_peso', $this->input->get_post('records_tipo_peso'));
                $this->DTO->addParameter('records_tipo_protected', $this->input->get_post('records_tipo_protected'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo'));

                // Ir al Bussiness Object
                $recordsTipoService = new RecordsTipoBussinessService();
                $recordsTipoService->executeService('update', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function deleteRecordsTipo() {
        try {
            if ($this->validateInputData($this->DTO, 'recordstipo', 'recordstipo_validation', 'v_records_tipo', 'delRecordsTipo') === TRUE) {
                $this->DTO->addParameter('records_tipo_codigo', $this->input->get_post('records_tipo_codigo'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $recordsTipoService = new RecordsTipoBussinessService();
                $recordsTipoService->executeService('delete', $this->DTO);
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
        $this->parseParameters(['records_tipo_']);
        // ya que podria no haberse enviado y estar no definido
        $recordsTipoId = $this->fixParameter('records_tipo_codigo', 'null', NULL);

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
            if (isset($recordsTipoId) && ($operationId == 'read' || !isset($operationId) || $operationId === FALSE)) {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_READ);
                $this->readRecordsTipo();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchRecordsTipo();
            }
        } else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updateRecordsTipo();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->deleteRecordsTipo();
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addRecordsTipo();
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
