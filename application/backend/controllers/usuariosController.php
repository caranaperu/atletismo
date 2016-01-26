<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para las operaciones CRUD de las marcas
 *
 * @author $Author: aranape@gmail.com $
 * @since 17-May-2012
 * @version $Id: usuariosController.php 61 2015-08-23 22:59:12Z aranape@gmail.com $
 *
 * $Date: 2015-08-23 17:59:12 -0500 (dom, 23 ago 2015) $
 * $Rev: 61 $
 */
class usuariosController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    private function readModelos() {

        try {
            if ($this->validateInputData($this->DTO, 'usuarios', 'usuarios_validation', 'v_usuarios', 'getUsuarios') === TRUE) {
                $this->DTO->addParameter('id', $this->input->get_post('usuarios_id'));
                $this->DTO->addParameter('usuarios_id', $this->input->get_post('usuarios_id'));
                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));

                // Ir al Bussiness Object
                $usuariosService = new UsuariosBussinessService();
                $usuariosService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function fetchModelos() {
        try {
            // Ir al Bussiness Object
            $usuariosService = new UsuariosBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);

            $usuariosService->executeService('list', $this->DTO);
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addModelos() {
        try {

            if ($this->validateInputData($this->DTO, 'usuarios', 'usuarios_validation', 'v_usuarios', 'addUsuarios') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('usuarios_code', $this->input->get_post('usuarios_code'));
                $this->DTO->addParameter('usuarios_password', $this->input->get_post('usuarios_password'));
                $this->DTO->addParameter('usuarios_nombre_completo', $this->input->get_post('usuarios_nombre_completo'));
                $this->DTO->addParameter('usuarios_admin', $this->input->get_post('usuarios_admin'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo'));
                // Ir al Bussiness Object
                $usuariosService = new UsuariosBussinessService();
                $usuariosService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updateModelos() {
        try {

            if ($this->validateInputData($this->DTO, 'usuarios', 'usuarios_validation', 'v_usuarios', 'updUsuarios') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('usuarios_id', $this->input->get_post('usuarios_id'));
                $this->DTO->addParameter('usuarios_code', $this->input->get_post('usuarios_code'));
                $this->DTO->addParameter('usuarios_password', $this->input->get_post('usuarios_password'));
                $this->DTO->addParameter('usuarios_nombre_completo', $this->input->get_post('usuarios_nombre_completo'));
                $this->DTO->addParameter('usuarios_admin', $this->input->get_post('usuarios_admin'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo'));

                // Ir al Bussiness Object
                $usuariosService = new UsuariosBussinessService();
                $usuariosService->executeService('update', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function deleteModelos() {
        try {
            if ($this->validateInputData($this->DTO, 'usuarios', 'usuarios_validation', 'v_usuarios', 'delUsuarios') === TRUE) {
                $this->DTO->addParameter('usuarios_id', $this->input->get_post('usuarios_id'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $usuariosService = new UsuariosBussinessService();
                $usuariosService->executeService('delete', $this->DTO);
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
        $this->parseParameters('usuarios_id');
        // ya que podria no haberse enviado y estar no definido
        $usuariosId = $this->fixParameter('usuarios_id', 'null', NULL);

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
            if (isset($usuariosId) && ($operationId == 'read' || !isset($operationId) || $operationId === FALSE)) {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_READ);
                $this->readModelos();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchModelos();
            }
        } else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updateModelos();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->deleteModelos();
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addModelos();
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
