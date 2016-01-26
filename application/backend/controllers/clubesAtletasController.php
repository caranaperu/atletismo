<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para la relacion club-atleta.
 *
 *
 * @author $Author: aranape $
 * @since 17-May-2012
 * @version $Id: clubesAtletasController.php 54 2014-03-02 05:45:36Z aranape $
 *
 * $Date: 2014-03-02 00:45:36 -0500 (dom, 02 mar 2014) $
 * $Rev: 54 $
 */
class clubesAtletasController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    private function readClubesAtletas() {

        try {
            if ($this->validateInputData($this->DTO, 'clubesatletas', 'clubesatletas_validation', 'v_clubesatletas', 'getClubesAtletas') === TRUE) {
                $this->DTO->addParameter('id', $this->input->get_post('clubesatletas_id'));
                $this->DTO->addParameter('clubesatletas_id', $this->input->get_post('clubesatletas_id'));
                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));

                // Ir al Bussiness Object
                $clubesAtletasService = new ClubesAtletasBussinessService();
                $clubesAtletasService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function deleteClubesAtletas() {
        try {
            if ($this->validateInputData($this->DTO, 'clubesatletas', 'clubesatletas_validation', 'v_clubesatletas', 'delClubesAtletas') === TRUE) {
                $this->DTO->addParameter('clubesatletas_id', $this->input->get_post('clubesatletas_id'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $clubesAtletasService = new ClubesAtletasBussinessService();
                $clubesAtletasService->executeService('delete', $this->DTO);
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
    private function fetchClubesAtletas() {
        try {
            // Ir al Bussiness Object
            $clubesAtletasService = new ClubesAtletasBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);

            $clubesAtletasService->executeService('list', $this->DTO);
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addClubesAtletas() {
        try {
            if ($this->validateInputData($this->DTO, 'clubesatletas', 'clubesatletas_validation', 'v_clubesatletas', 'addClubesAtletas') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('clubes_codigo', $this->input->get_post('clubes_codigo'));
                $this->DTO->addParameter('atletas_codigo', $this->input->get_post('atletas_codigo'));
                $this->DTO->addParameter('clubesatletas_desde', $this->input->get_post('clubesatletas_desde'));
                $this->DTO->addParameter('clubesatletas_hasta', $this->input->get_post('clubesatletas_hasta'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo')); // Siempre se agrega
                // Ir al Bussiness Object
                $clubesAtletasService = new ClubesAtletasBussinessService();
                $clubesAtletasService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updClubesAtletas() {
        try {
            if ($this->validateInputData($this->DTO, 'clubesatletas', 'clubesatletas_validation', 'v_clubesatletas', 'updClubesAtletas') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('clubesatletas_id', $this->input->get_post('clubesatletas_id'));
                $this->DTO->addParameter('clubes_codigo', $this->input->get_post('clubes_codigo'));
                $this->DTO->addParameter('atletas_codigo', $this->input->get_post('atletas_codigo'));
                $this->DTO->addParameter('clubesatletas_desde', $this->input->get_post('clubesatletas_desde'));
                $this->DTO->addParameter('clubesatletas_hasta', $this->input->get_post('clubesatletas_hasta'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo')); // Siempre se agrega
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                // Ir al Bussiness Object
                $clubesAtletasService = new ClubesAtletasBussinessService();
                $clubesAtletasService->executeService('update', $this->DTO);
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
        $this->parseParameters(['clubesatletas_', 'clubes_', 'atletas_']);

        // ya que podria no haberse enviado y estar no definido
        $clubesatletasId = $this->fixParameter('clubesatletas_id', 'null', NULL);

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
            if (isset($clubesatletasId) && ($operationId == 'read' || !isset($operationId) || $operationId === FALSE)) {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_READ);
                $this->readClubesAtletas();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchClubesAtletas();
            }
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addClubesAtletas();
        } else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updClubesAtletas();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->deleteClubesAtletas();
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
