<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para las operaciones CRUD de las ciudades.
 *
 * @author $Author: aranape $
 * @since 17-May-2012
 * @version $Id: ciudadesController.php 211 2014-06-23 22:49:52Z aranape $
 *
 * $Date: 2014-06-23 17:49:52 -0500 (lun, 23 jun 2014) $
 * $Rev: 211 $
 */
class ciudadesController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    private function readCiudades() {

        try {
            if ($this->validateInputData($this->DTO, 'ciudades', 'ciudades_validation', 'v_ciudades', 'getCiudades') === TRUE) {
                $this->DTO->addParameter('id', $this->input->get_post('ciudades_codigo'));
                $this->DTO->addParameter('ciudades_codigo', $this->input->get_post('ciudades_codigo'));
                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));

                // Ir al Bussiness Object
                $ciudadesService = new CiudadesBussinessService();
                $ciudadesService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function fetchCiudades() {
        try {
            // Ir al Bussiness Object
            $ciudadesService = new CiudadesBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);

            $ciudadesService->executeService('list', $this->DTO);
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addCiudades() {
        try {

            if ($this->validateInputData($this->DTO, 'ciudades', 'ciudades_validation', 'v_ciudades', 'addCiudades') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('ciudades_codigo', $this->input->get_post('ciudades_codigo'));
                $this->DTO->addParameter('ciudades_descripcion', $this->input->get_post('ciudades_descripcion'));
                $this->DTO->addParameter('paises_codigo', $this->input->get_post('paises_codigo'));
                $this->DTO->addParameter('ciudades_altura', $this->input->get_post('ciudades_altura'));
                $this->DTO->addParameter('activo', true); // Siempre se agrega vivo
                // Ir al Bussiness Object
                $ciudadesService = new CiudadesBussinessService();
                $ciudadesService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updateCiudades() {
        try {

            if ($this->validateInputData($this->DTO, 'ciudades', 'ciudades_validation', 'v_ciudades', 'updCiudades') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('ciudades_codigo', $this->input->get_post('ciudades_codigo'));
                $this->DTO->addParameter('ciudades_descripcion', $this->input->get_post('ciudades_descripcion'));
                $this->DTO->addParameter('paises_codigo', $this->input->get_post('paises_codigo'));
                $this->DTO->addParameter('ciudades_altura', $this->input->get_post('ciudades_altura'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo'));

                // Ir al Bussiness Object
                $ciudadesService = new CiudadesBussinessService();
                $ciudadesService->executeService('update', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function deleteCiudades() {
        try {
            if ($this->validateInputData($this->DTO, 'ciudades', 'ciudades_validation', 'v_ciudades', 'delCiudades') === TRUE) {
                $this->DTO->addParameter('ciudades_codigo', $this->input->get_post('ciudades_codigo'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $ciudadesService = new CiudadesBussinessService();
                $ciudadesService->executeService('delete', $this->DTO);
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
        $this->parseParameters('ciudades_','paises_');
        // ya que podria no haberse enviado y estar no definido
        $ciudadesId = $this->fixParameter('ciudades_codigo', 'null', NULL);

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
            if (isset($ciudadesId) && ($operationId == 'read' || !isset($operationId) || $operationId === FALSE)) {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_READ);
                $this->readCiudades();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchCiudades();
            }
        } else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updateCiudades();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->deleteCiudades();
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addCiudades();
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
