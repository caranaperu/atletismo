<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para las operaciones CRUD de los requisitos para los
 * procedimientos de la entidad.
 *
 *
 * @author  $Author: aranape $
 * @since   06-FEB-2013
 * @version $Id: perfilDetalleController.php 395 2014-01-11 09:22:31Z aranape $
 * @history ''
 *
 * $Date: 2014-01-11 04:22:31 -0500 (sáb, 11 ene 2014) $
 * $Rev: 395 $
 */
class perfilDetalleController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    private function readPerfilDetalle() {

        try {
            if ($this->validateInputData($this->DTO, 'perfildetalle', 'perfildetalle_validation', 'v_perfildetalle', 'getPerfilDetalle') === TRUE) {
                $this->DTO->addParameter('id', $this->input->get_post('perfdet_id'));
                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));

                // Ir al Bussiness Object
                $PerfilDetalleService = new PerfilDetalleBussinessService();
                $PerfilDetalleService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function fetchPerfilDetalle() {
        try {
            // Ir al Bussiness Object
            $PerfilDetalleService = new PerfilDetalleBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);
            $PerfilDetalleService->executeService('list', $this->DTO);
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addPerfilDetalle() {
        try {

            if ($this->validateInputData($this->DTO, 'perfildetalle', 'perfildetalle_validation', 'v_perfildetalle', 'addPerfilDetalle') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('perfil_id', $this->input->get_post('perfil_id'));
                $this->DTO->addParameter('perfdet_accessdef', $this->input->get_post('perfdet_accessdef'));
                $this->DTO->addParameter('perfdet_accleer', $this->input->get_post('perfdet_accleer'));
                $this->DTO->addParameter('perfdet_accagregar', $this->input->get_post('perfdet_accagregar'));
                $this->DTO->addParameter('perfdet_accactualizar', $this->input->get_post('perfdet_accactualizar'));
                $this->DTO->addParameter('perfdet_acceliminar', $this->input->get_post('perfdet_acceliminar'));
                $this->DTO->addParameter('perfdet_accimprimir', $this->input->get_post('perfdet_accimprimir'));
                $this->DTO->addParameter('menu_id', $this->input->get_post('menu_id'));

                $this->DTO->addParameter('activo', true); // Siempre se agrega
                // Ir al Bussiness Object
                $PerfilDetalleService = new RequisitosBussinessService();
                $PerfilDetalleService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updPerfilDetalle() {
        try {


            if ($this->validateInputData($this->DTO, 'perfildetalle', 'perfildetalle_validation', 'v_perfildetalle', 'updPerfilDetalle') === TRUE) {

                // Seteamos parametros en el DTO
                $this->DTO->addParameter('perfdet_id', $this->input->get_post('perfdet_id'));
                $this->DTO->addParameter('perfil_id', $this->input->get_post('perfil_id'));
                $this->DTO->addParameter('perfdet_accessdef', $this->input->get_post('perfdet_accessdef'));
                $this->DTO->addParameter('perfdet_accleer', $this->input->get_post('perfdet_accleer'));
                $this->DTO->addParameter('perfdet_accagregar', $this->input->get_post('perfdet_accagregar'));
                $this->DTO->addParameter('perfdet_accactualizar', $this->input->get_post('perfdet_accactualizar'));
                $this->DTO->addParameter('perfdet_acceliminar', $this->input->get_post('perfdet_acceliminar'));
                $this->DTO->addParameter('perfdet_accimprimir', $this->input->get_post('perfdet_accimprimir'));
                $this->DTO->addParameter('menu_id', $this->input->get_post('menu_id'));

                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo'));

                // Ir al Bussiness Object
                $PerfilDetalleService = new PerfilDetalleBussinessService();
                $PerfilDetalleService->executeService('update', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function deletePerfilDetalle() {
        try {
            if ($this->validateInputData($this->DTO, 'perfildetalle', 'perfildetalle_validation', 'v_perfildetalle', 'delPerfilDetalle') === TRUE) {
                $this->DTO->addParameter('perfdet_id', $this->input->get_post('perfdet_id'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $PerfilDetalleService = new PerfilDetalleBussinessService();
                $PerfilDetalleService->executeService('delete', $this->DTO);
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
        $this->parseParameters('perfdet_', 'perfil_', 'menu_');
        // ya que podria no haberse enviado y estar no definido
        $perfdetId = $this->fixParameter('perfdet_id', 'null', NULL);

        // Se setea el usuario
        $this->DTO->setSessionUser($this->getUser());

        // Vemos si esta definido el tipo de suboperacion
        $operationId = $this->input->get_post('_operationId');
        if (isset($operationId) && is_string($operationId)) {
            $this->DTO->setSubOperationId($operationId);
        }

        // Leera los datos del tipo de contribuyentes por default si no se envia
        // una operacion especifica.
        $op = $this->input->get('op');
        if (!isset($op) || $op == 'fetch') {
            if (isset($perfdetId) && ($operationId == 'read' || !isset($operationId) || $operationId === FALSE)) {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_READ);
                $this->readPerfilDetalle();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchPerfilDetalle();
            }
        } else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updPerfilDetalle();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->deletePerfilDetalle();
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addPerfilDetalle();
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
