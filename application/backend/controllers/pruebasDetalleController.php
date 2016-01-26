<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para la relacion de las pruebas que conforman una
 * principal , tal como el caso de las pruebas del heptatlon.
 *
 *
 * @author $Author: aranape $
 * @since 17-May-2012
 * @version $Id: pruebasDetalleController.php 74 2014-03-09 10:24:37Z aranape $
 *
 * $Date: 2014-03-09 05:24:37 -0500 (dom, 09 mar 2014) $
 * $Rev: 74 $
 */
class pruebasDetalleController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    private function readPruebasDetalle() {

        try {
            if ($this->validateInputData($this->DTO, 'pruebasdetalle', 'pruebasdetalle_validation', 'v_pruebasdetalle', 'getPruebasDetalle') === TRUE) {
                $this->DTO->addParameter('id', $this->input->get_post('pruebasdetalle_id'));
                $this->DTO->addParameter('pruebasdetalle_id', $this->input->get_post('pruebasdetalle_id'));
                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));

                // Ir al Bussiness Object
                $pruebasDetalleService = new PruebasDetalleBussinessService();
                $pruebasDetalleService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function deletePruebasDetalle() {
        try {
            if ($this->validateInputData($this->DTO, 'pruebasdetalle', 'pruebasdetalle_validation', 'v_pruebasdetalle', 'delPruebasDetalle') === TRUE) {
                $this->DTO->addParameter('pruebas_detalle_id', $this->input->get_post('pruebas_detalle_id'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $pruebasDetalleService = new PruebasDetalleBussinessService();
                $pruebasDetalleService->executeService('delete', $this->DTO);
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
    private function fetchPruebasDetalle() {
        try {
            // Ir al Bussiness Object
            $pruebasDetalleService = new PruebasDetalleBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);

            $pruebasDetalleService->executeService('list', $this->DTO);
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addPruebasDetalle() {
        try {
            if ($this->validateInputData($this->DTO, 'pruebasdetalle', 'pruebasdetalle_validation', 'v_pruebasdetalle', 'addPruebasDetalle') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('pruebas_codigo', $this->input->get_post('pruebas_codigo'));
                $this->DTO->addParameter('pruebas_detalle_prueba_codigo', $this->input->get_post('pruebas_detalle_prueba_codigo'));
                $this->DTO->addParameter('pruebas_detalle_orden', $this->input->get_post('pruebas_detalle_orden'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo')); // Siempre se agrega
                // Ir al Bussiness Object
                $pruebasDetalleService = new PruebasDetalleBussinessService();
                $pruebasDetalleService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updPruebasDetalle() {
        try {
            if ($this->validateInputData($this->DTO, 'pruebasdetalle', 'pruebasdetalle_validation', 'v_pruebasdetalle', 'updPruebasDetalle') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('pruebas_detalle_id', $this->input->get_post('pruebas_detalle_id'));
                $this->DTO->addParameter('pruebas_codigo', $this->input->get_post('pruebas_codigo'));
                $this->DTO->addParameter('pruebas_detalle_prueba_codigo', $this->input->get_post('pruebas_detalle_prueba_codigo'));
                $this->DTO->addParameter('pruebas_detalle_orden', $this->input->get_post('pruebas_detalle_orden'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo')); // Siempre se agrega
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                // Ir al Bussiness Object
                $pruebasDetalleService = new PruebasDetalleBussinessService();
                $pruebasDetalleService->executeService('update', $this->DTO);
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
        $this->parseParameters(['pruebas_detalle_', 'pruebas_']);

        // ya que podria no haberse enviado y estar no definido
        $ligasclubesId = $this->fixParameter('pruebas_detalle_id', 'null', NULL);

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
                $this->readPruebasDetalle();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchPruebasDetalle();
            }
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addPruebasDetalle();
        } else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updPruebasDetalle();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->deletePruebasDetalle();
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
