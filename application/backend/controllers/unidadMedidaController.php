<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para las operaciones CRUD de las unidades de medida.
 *
 * @author $Author: aranape $
 * @since 17-May-2012
 * @version $Id: unidadMedidaController.php 136 2014-04-07 00:31:52Z aranape $
 *
 * $Date: 2014-04-06 19:31:52 -0500 (dom, 06 abr 2014) $
 * $Rev: 136 $
 */
class unidadMedidaController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    private function readUnidadMedida() {

        try {
            if ($this->validateInputData($this->DTO, 'unidadmedida', 'unidadmedida_validation', 'v_unidadmedida', 'getUnidadMedida') === TRUE) {
                $this->DTO->addParameter('id', $this->input->get_post('unidad_medida_codigo'));
                $this->DTO->addParameter('unidad_medida_codigo', $this->input->get_post('unidad_medida_codigo'));
                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));

                // Ir al Bussiness Object
                $unidadMedidaService = new UnidadMedidaBussinessService();
                $unidadMedidaService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function fetchUnidadMedida() {
        try {
            // Ir al Bussiness Object
            $unidadMedidaService = new UnidadMedidaBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);

            $unidadMedidaService->executeService('list', $this->DTO);
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addUnidadMedida() {
        try {

            if ($this->validateInputData($this->DTO, 'unidadmedida', 'unidadmedida_validation', 'v_unidadmedida', 'addUnidadMedida') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('unidad_medida_codigo', $this->input->get_post('unidad_medida_codigo'));
                $this->DTO->addParameter('unidad_medida_descripcion', $this->input->get_post('unidad_medida_descripcion'));
                $this->DTO->addParameter('unidad_medida_regex_e', $this->input->get_post('unidad_medida_regex_e'));
                $this->DTO->addParameter('unidad_medida_regex_m', $this->input->get_post('unidad_medida_regex_m'));
                $this->DTO->addParameter('unidad_medida_tipo', $this->input->get_post('unidad_medida_tipo'));
                $this->DTO->addParameter('activo', true); // Siempre se agrega vivo
                // Ir al Bussiness Object
                $unidadMedidaService = new UnidadMedidaBussinessService();
                $unidadMedidaService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updateUnidadMedida() {
        try {

            if ($this->validateInputData($this->DTO, 'unidadmedida', 'unidadmedida_validation', 'v_unidadmedida', 'updUnidadMedida') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('unidad_medida_codigo', $this->input->get_post('unidad_medida_codigo'));
                $this->DTO->addParameter('unidad_medida_descripcion', $this->input->get_post('unidad_medida_descripcion'));
                $this->DTO->addParameter('unidad_medida_regex_e', $this->input->get_post('unidad_medida_regex_e'));
                $this->DTO->addParameter('unidad_medida_regex_m', $this->input->get_post('unidad_medida_regex_m'));
                $this->DTO->addParameter('unidad_medida_tipo', $this->input->get_post('unidad_medida_tipo'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo'));

                // Ir al Bussiness Object
                $unidadMedidaService = new UnidadMedidaBussinessService();
                $unidadMedidaService->executeService('update', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function deleteUnidadMedida() {
        try {
            if ($this->validateInputData($this->DTO, 'unidadmedida', 'unidadmedida_validation', 'v_unidadmedida', 'delUnidadMedida') === TRUE) {
                $this->DTO->addParameter('unidad_medida_codigo', $this->input->get_post('unidad_medida_codigo'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $unidadMedidaService = new UnidadMedidaBussinessService();
                $unidadMedidaService->executeService('delete', $this->DTO);
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
        $this->parseParameters('unidad_medida_');
        // ya que podria no haberse enviado y estar no definido
        $unidadMedidaId = $this->fixParameter('unidad_medida_codigo', 'null', NULL);

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
            if (isset($unidadMedidaId) && ($operationId == 'read' || !isset($operationId) || $operationId === FALSE)) {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_READ);
                $this->readUnidadMedida();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchUnidadMedida();
            }
        } else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updateUnidadMedida();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->deleteUnidadMedida();
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addUnidadMedida();
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
