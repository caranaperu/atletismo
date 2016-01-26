<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para las operaciones CRUD de los paises.
 *
 * @author $Author: aranape $
 * @since 17-May-2012
 * @version $Id: paisesController.php 279 2014-06-30 02:14:51Z aranape $
 *
 * $Date: 2014-06-29 21:14:51 -0500 (dom, 29 jun 2014) $
 * $Rev: 279 $
 */
class paisesController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    private function readPaises() {

        try {
            if ($this->validateInputData($this->DTO, 'paises', 'paises_validation', 'v_paises', 'getPaises') === TRUE) {
                $this->DTO->addParameter('id', $this->input->get_post('paises_codigo'));
                $this->DTO->addParameter('paises_codigo', $this->input->get_post('paises_codigo'));
                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));

                // Ir al Bussiness Object
                $paisesService = new PaisesBussinessService();
                $paisesService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function fetchPaises() {
        try {
            // Ir al Bussiness Object
            $paisesService = new PaisesBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);

            $paisesService->executeService('list', $this->DTO);
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addPaises() {
        try {

            if ($this->validateInputData($this->DTO, 'paises', 'paises_validation', 'v_paises', 'addPaises') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('paises_codigo', $this->input->get_post('paises_codigo'));
                $this->DTO->addParameter('paises_descripcion', $this->input->get_post('paises_descripcion'));
                $this->DTO->addParameter('paises_entidad', $this->input->get_post('paises_entidad'));
                $this->DTO->addParameter('regiones_codigo', $this->input->get_post('regiones_codigo'));
                $this->DTO->addParameter('activo', true); // Siempre se agrega vivo
                // Ir al Bussiness Object
                $paisesService = new PaisesBussinessService();
                $paisesService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updatePaises() {
        try {

            if ($this->validateInputData($this->DTO, 'paises', 'paises_validation', 'v_paises', 'updPaises') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('paises_codigo', $this->input->get_post('paises_codigo'));
                $this->DTO->addParameter('paises_descripcion', $this->input->get_post('paises_descripcion'));
                $this->DTO->addParameter('paises_entidad', $this->input->get_post('paises_entidad'));
                $this->DTO->addParameter('regiones_codigo', $this->input->get_post('regiones_codigo'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo'));

                // Ir al Bussiness Object
                $paisesService = new PaisesBussinessService();
                $paisesService->executeService('update', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function deletePaises() {
        try {
            if ($this->validateInputData($this->DTO, 'paises', 'paises_validation', 'v_paises', 'delPaises') === TRUE) {
                $this->DTO->addParameter('paises_codigo', $this->input->get_post('paises_codigo'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $paisesService = new PaisesBussinessService();
                $paisesService->executeService('delete', $this->DTO);
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
        $this->parseParameters(['paises_','regiones_']);
        // ya que podria no haberse enviado y estar no definido
        $paisesId = $this->fixParameter('paises_codigo', 'null', NULL);

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
            if (isset($paisesId) && ($operationId == 'read' || !isset($operationId) || $operationId === FALSE)) {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_READ);
                $this->readPaises();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchPaises();
            }
        } else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updatePaises();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->deletePaises();
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addPaises();
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
