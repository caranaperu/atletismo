<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para las operaciones CRUD de las pruebasClasificacion.
 *
 * @author $Author: aranape $
 * @since 17-May-2012
 * @version $Id: pruebasClasificacionController.php 73 2014-03-09 10:23:39Z aranape $
 *
 * $Date: 2014-03-09 05:23:39 -0500 (dom, 09 mar 2014) $
 * $Rev: 73 $
 */
class pruebasClasificacionController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    private function readPruebasClasificacion() {

        try {
            if ($this->validateInputData($this->DTO, 'pruebasclasificacion', 'pruebasclasificacion_validation', 'v_pruebasclasificacion', 'getPruebasClasificacion') === TRUE) {
                $this->DTO->addParameter('id', $this->input->get_post('pruebas_clasificacion_codigo'));
                $this->DTO->addParameter('pruebas_clasificacion_codigo', $this->input->get_post('pruebas_clasificacion_codigo'));
                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));

                // Ir al Bussiness Object
                $pruebasClasificacionService = new PruebasClasificacionBussinessService();
                $pruebasClasificacionService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function fetchPruebasClasificacion() {
        try {
            // Ir al Bussiness Object
            $pruebasClasificacionService = new PruebasClasificacionBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);

            $pruebasClasificacionService->executeService('list', $this->DTO);
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addPruebasClasificacion() {
        try {

            if ($this->validateInputData($this->DTO, 'pruebasclasificacion', 'pruebasclasificacion_validation', 'v_pruebasclasificacion', 'addPruebasClasificacion') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('pruebas_clasificacion_codigo', $this->input->get_post('pruebas_clasificacion_codigo'));
                $this->DTO->addParameter('pruebas_clasificacion_descripcion', $this->input->get_post('pruebas_clasificacion_descripcion'));
                $this->DTO->addParameter('pruebas_tipo_codigo', $this->input->get_post('pruebas_tipo_codigo'));
                $this->DTO->addParameter('unidad_medida_codigo', $this->input->get_post('unidad_medida_codigo'));
                $this->DTO->addParameter('activo', true); // Siempre se agrega vivo
                // Ir al Bussiness Object
                $pruebasClasificacionService = new PruebasClasificacionBussinessService();
                $pruebasClasificacionService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updatePruebasClasificacion() {
        try {

            if ($this->validateInputData($this->DTO, 'pruebasclasificacion', 'pruebasclasificacion_validation', 'v_pruebasclasificacion', 'updPruebasClasificacion') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('pruebas_clasificacion_codigo', $this->input->get_post('pruebas_clasificacion_codigo'));
                $this->DTO->addParameter('pruebas_clasificacion_descripcion', $this->input->get_post('pruebas_clasificacion_descripcion'));
                $this->DTO->addParameter('pruebas_tipo_codigo', $this->input->get_post('pruebas_tipo_codigo'));
                                $this->DTO->addParameter('unidad_medida_codigo', $this->input->get_post('unidad_medida_codigo'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo'));

                // Ir al Bussiness Object
                $pruebasClasificacionService = new PruebasClasificacionBussinessService();
                $pruebasClasificacionService->executeService('update', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function deletePruebasClasificacion() {
        try {
            if ($this->validateInputData($this->DTO, 'pruebasclasificacion', 'pruebasclasificacion_validation', 'v_pruebasclasificacion', 'delPruebasClasificacion') === TRUE) {
                $this->DTO->addParameter('pruebas_clasificacion_codigo', $this->input->get_post('pruebas_clasificacion_codigo'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $pruebasClasificacionService = new PruebasClasificacionBussinessService();
                $pruebasClasificacionService->executeService('delete', $this->DTO);
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
        $this->parseParameters(['pruebas_clasificacion_','pruebas_tipo_','unidad_medida_']);
        // ya que podria no haberse enviado y estar no definido
        $pruebasClasificacionId = $this->fixParameter('pruebas_clasificacion_codigo', 'null', NULL);

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
            if (isset($pruebasClasificacionId) && ($operationId == 'read' || !isset($operationId) || $operationId === FALSE)) {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_READ);
                $this->readPruebasClasificacion();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchPruebasClasificacion();
            }
        } else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updatePruebasClasificacion();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->deletePruebasClasificacion();
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addPruebasClasificacion();
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
