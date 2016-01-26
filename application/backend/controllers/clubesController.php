<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para las operaciones CRUD de las ligas.
 *
 * @author $Author: aranape $
 * @since 17-May-2012
 * @version $Id: clubesController.php 43 2014-02-18 16:32:15Z aranape $
 *
 * $Date: 2014-02-18 11:32:15 -0500 (mar, 18 feb 2014) $
 * $Rev: 43 $
 */
class clubesController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    private function readClubes() {

        try {
            if ($this->validateInputData($this->DTO, 'clubes', 'clubes_validation', 'v_clubes', 'getClubes') === TRUE) {
                $this->DTO->addParameter('id', $this->input->get_post('clubes_codigo'));
                $this->DTO->addParameter('clubes_codigo', $this->input->get_post('clubes_codigo'));
                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));

                // Ir al Bussiness Object
                $clubesService = new ClubesBussinessService();
                $clubesService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function fetchClubes() {
        try {
            // Ir al Bussiness Object
            $clubesService = new ClubesBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);

            $clubesService->executeService('list', $this->DTO);
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addClubes() {
        try {

            if ($this->validateInputData($this->DTO, 'clubes', 'clubes_validation', 'v_clubes', 'addClubes') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('clubes_codigo', $this->input->get_post('clubes_codigo'));
                $this->DTO->addParameter('clubes_descripcion', $this->input->get_post('clubes_descripcion'));
                $this->DTO->addParameter('clubes_persona_contacto', $this->input->get_post('clubes_persona_contacto'));
                $this->DTO->addParameter('clubes_direccion', $this->input->get_post('clubes_direccion'));
                $this->DTO->addParameter('clubes_email', $this->input->get_post('clubes_email'));
                $this->DTO->addParameter('clubes_telefono_oficina', $this->input->get_post('clubes_telefono_oficina'));
                $this->DTO->addParameter('clubes_telefono_celular', $this->input->get_post('clubes_telefono_celular'));
                $this->DTO->addParameter('clubes_web_url', $this->input->get_post('clubes_web_url'));

                $this->DTO->addParameter('activo', true); // Siempre se agrega vivo
                // Ir al Bussiness Object
                $clubesService = new ClubesBussinessService();
                $clubesService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updateClubes() {
        try {

            if ($this->validateInputData($this->DTO, 'clubes', 'clubes_validation', 'v_clubes', 'updClubes') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('clubes_codigo', $this->input->get_post('clubes_codigo'));
                $this->DTO->addParameter('clubes_descripcion', $this->input->get_post('clubes_descripcion'));
                $this->DTO->addParameter('clubes_persona_contacto', $this->input->get_post('clubes_persona_contacto'));
                $this->DTO->addParameter('clubes_direccion', $this->input->get_post('clubes_direccion'));
                $this->DTO->addParameter('clubes_email', $this->input->get_post('clubes_email'));
                $this->DTO->addParameter('clubes_telefono_oficina', $this->input->get_post('clubes_telefono_oficina'));
                $this->DTO->addParameter('clubes_telefono_celular', $this->input->get_post('clubes_telefono_celular'));
                $this->DTO->addParameter('clubes_web_url', $this->input->get_post('clubes_web_url'));

                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo'));

                // Ir al Bussiness Object
                $clubesService = new ClubesBussinessService();
                $clubesService->executeService('update', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function deleteClubes() {
        try {
            if ($this->validateInputData($this->DTO, 'clubes', 'clubes_validation', 'v_clubes', 'delClubes') === TRUE) {
                $this->DTO->addParameter('clubes_codigo', $this->input->get_post('clubes_codigo'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $clubesService = new ClubesBussinessService();
                $clubesService->executeService('delete', $this->DTO);
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
        $this->parseParameters('clubes_');
        // ya que podria no haberse enviado y estar no definido
        $clubesId = $this->fixParameter('clubes_codigo', 'null', NULL);

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
            if (isset($clubesId) && ($operationId == 'read' || !isset($operationId) || $operationId === FALSE)) {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_READ);
                $this->readClubes();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchClubes();
            }
        } else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updateClubes();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->deleteClubes();
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addClubes();
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
