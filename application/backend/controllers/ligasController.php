<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para las operaciones CRUD de las ligas.
 *
 * @author $Author: aranape $
 * @since 17-May-2012
 * @version $Id: ligasController.php 44 2014-02-18 16:33:05Z aranape $
 *
 * $Date: 2014-02-18 11:33:05 -0500 (mar, 18 feb 2014) $
 * $Rev: 44 $
 */
class ligasController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    private function readLigas() {

        try {
            if ($this->validateInputData($this->DTO, 'ligas', 'ligas_validation', 'v_ligas', 'getLigas') === TRUE) {
                $this->DTO->addParameter('id', $this->input->get_post('ligas_codigo'));
                $this->DTO->addParameter('ligas_codigo', $this->input->get_post('ligas_codigo'));
                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));

                // Ir al Bussiness Object
                $ligasService = new LigasBussinessService();
                $ligasService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function fetchLigas() {
        try {
            // Ir al Bussiness Object
            $ligasService = new LigasBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);

            $ligasService->executeService('list', $this->DTO);
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addLigas() {
        try {

            if ($this->validateInputData($this->DTO, 'ligas', 'ligas_validation', 'v_ligas', 'addLigas') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('ligas_codigo', $this->input->get_post('ligas_codigo'));
                $this->DTO->addParameter('ligas_descripcion', $this->input->get_post('ligas_descripcion'));
                $this->DTO->addParameter('ligas_persona_contacto', $this->input->get_post('ligas_persona_contacto'));
                $this->DTO->addParameter('ligas_direccion', $this->input->get_post('ligas_direccion'));
                $this->DTO->addParameter('ligas_email', $this->input->get_post('ligas_email'));
                $this->DTO->addParameter('ligas_telefono_oficina', $this->input->get_post('ligas_telefono_oficina'));
                $this->DTO->addParameter('ligas_telefono_celular', $this->input->get_post('ligas_telefono_celular'));
                $this->DTO->addParameter('ligas_web_url', $this->input->get_post('ligas_web_url'));
                $this->DTO->addParameter('activo', true); // Siempre se agrega vivo
                // Ir al Bussiness Object
                $ligasService = new LigasBussinessService();
                $ligasService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updateLigas() {
        try {

            if ($this->validateInputData($this->DTO, 'ligas', 'ligas_validation', 'v_ligas', 'updLigas') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('ligas_codigo', $this->input->get_post('ligas_codigo'));
                $this->DTO->addParameter('ligas_descripcion', $this->input->get_post('ligas_descripcion'));
                $this->DTO->addParameter('ligas_persona_contacto', $this->input->get_post('ligas_persona_contacto'));
                $this->DTO->addParameter('ligas_direccion', $this->input->get_post('ligas_direccion'));
                $this->DTO->addParameter('ligas_email', $this->input->get_post('ligas_email'));
                $this->DTO->addParameter('ligas_telefono_oficina', $this->input->get_post('ligas_telefono_oficina'));
                $this->DTO->addParameter('ligas_telefono_celular', $this->input->get_post('ligas_telefono_celular'));
                $this->DTO->addParameter('ligas_web_url', $this->input->get_post('ligas_web_url'));

                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo'));

                // Ir al Bussiness Object
                $ligasService = new LigasBussinessService();
                $ligasService->executeService('update', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function deleteLigas() {
        try {
            if ($this->validateInputData($this->DTO, 'ligas', 'ligas_validation', 'v_ligas', 'delLigas') === TRUE) {
                $this->DTO->addParameter('ligas_codigo', $this->input->get_post('ligas_codigo'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $ligasService = new LigasBussinessService();
                $ligasService->executeService('delete', $this->DTO);
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
        $this->parseParameters('ligas_');
        // ya que podria no haberse enviado y estar no definido
        $ligasId = $this->fixParameter('ligas_codigo', 'null', NULL);

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
            if (isset($ligasId) && ($operationId == 'read' || !isset($operationId) || $operationId === FALSE)) {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_READ);
                $this->readLigas();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchLigas();
            }
        } else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updateLigas();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->deleteLigas();
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addLigas();
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
