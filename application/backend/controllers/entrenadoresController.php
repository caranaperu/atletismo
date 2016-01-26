<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para las operaciones CRUD de las ciudades.
 *
 * @author $Author: aranape $
 * @since 17-May-2012
 * @version $Id: entrenadoresController.php 54 2014-03-02 05:45:36Z aranape $
 *
 * $Date: 2014-03-02 00:45:36 -0500 (dom, 02 mar 2014) $
 * $Rev: 54 $
 */
class entrenadoresController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    private function readEntrenadores() {

        try {
            if ($this->validateInputData($this->DTO, 'entrenadores', 'entrenadores_validation', 'v_entrenadores', 'getEntrenadores') === TRUE) {
          //      $this->DTO->addParameter('entrenadores_codigo', $this->input->get_post('entrenadores_codigo'));
                $this->DTO->addParameter('entrenadores_codigo', $this->input->get_post('entrenadores_codigo'));
                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));

                // Ir al Bussiness Object
                $entrenadoresService = new EntrenadoresBussinessService();
                $entrenadoresService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function fetchEntrenadores() {
        try {
            // Ir al Bussiness Object
            $entrenadoresService = new EntrenadoresBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);

            $entrenadoresService->executeService('list', $this->DTO);
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addEntrenadores() {
        try {

            if ($this->validateInputData($this->DTO, 'entrenadores', 'entrenadores_validation', 'v_entrenadores', 'addEntrenadores') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('entrenadores_codigo', $this->input->get_post('entrenadores_codigo'));
                $this->DTO->addParameter('entrenadores_ap_paterno', $this->input->get_post('entrenadores_ap_paterno'));
                $this->DTO->addParameter('entrenadores_ap_materno', $this->input->get_post('entrenadores_ap_materno'));
                $this->DTO->addParameter('entrenadores_nombres', $this->input->get_post('entrenadores_nombres'));
                $this->DTO->addParameter('entrenadores_nivel_codigo', $this->input->get_post('entrenadores_nivel_codigo'));
                $this->DTO->addParameter('activo', true); // Siempre se agrega vivo
                // Ir al Bussiness Object
                $entrenadoresService = new EntrenadoresBussinessService();
                $entrenadoresService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updateEntrenadores() {
        try {

            if ($this->validateInputData($this->DTO, 'entrenadores', 'entrenadores_validation', 'v_entrenadores', 'updEntrenadores') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('entrenadores_codigo', $this->input->get_post('entrenadores_codigo'));
                $this->DTO->addParameter('entrenadores_ap_paterno', $this->input->get_post('entrenadores_ap_paterno'));
                $this->DTO->addParameter('entrenadores_ap_materno', $this->input->get_post('entrenadores_ap_materno'));
                $this->DTO->addParameter('entrenadores_nombres', $this->input->get_post('entrenadores_nombres'));
                $this->DTO->addParameter('entrenadores_nivel_codigo', $this->input->get_post('entrenadores_nivel_codigo'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo'));

                // Ir al Bussiness Object
                $entrenadoresService = new EntrenadoresBussinessService();
                $entrenadoresService->executeService('update', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function deleteEntrenadores() {
        try {
            if ($this->validateInputData($this->DTO, 'entrenadores', 'entrenadores_validation', 'v_entrenadores', 'delEntrenadores') === TRUE) {
                $this->DTO->addParameter('entrenadores_codigo', $this->input->get_post('entrenadores_codigo'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $entrenadoresService = new EntrenadoresBussinessService();
                $entrenadoresService->executeService('delete', $this->DTO);
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
        $this->parseParameters(['entrenadores_','entrenadores_nivel_']);
        // ya que podria no haberse enviado y estar no definido
        $entrenadoresId = $this->fixParameter('entrenadores_codigo', 'null', NULL);

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
            if (isset($entrenadoresId) && ($operationId == 'read' || !isset($operationId) || $operationId === FALSE)) {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_READ);
                $this->readEntrenadores();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchEntrenadores();
            }
        } else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updateEntrenadores();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->deleteEntrenadores();
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addEntrenadores();
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
