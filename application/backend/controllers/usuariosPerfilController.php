<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para las operaciones CRUD de los usuarios de la entidad.
 *
 *
 * @author  $Author: aranape $
 * @since   06-FEB-2013
 * @version $Id: usuariosPerfilController.php 394 2014-01-11 09:22:07Z aranape $
 * @history ''
 *
 * $Date: 2014-01-11 04:22:07 -0500 (sáb, 11 ene 2014) $
 * $Rev: 394 $
 */
class usuariosPerfilController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    private function readUsuariosPerfil() {

        try {
            if ($this->validateInputData($this->DTO, 'usuario_perfil', 'usuario_perfil_validation', 'v_usuario_perfil', 'getUsuarioPerfil') === TRUE) {
                $this->DTO->addParameter('usuario_perfil_id', $this->input->get_post('usuario_perfil_id'));
                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));

                // Ir al Bussiness Object
                $usuariosPerfilService = new UsuariosPerfilBussinessService();
                $usuariosPerfilService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function fetchUsuariosPerfil() {
        try {
            $operationId = $this->input->get_post('_operationId');
            if (isset($operationId) && is_string($operationId)) {
                $this->DTO->setSubOperationId($operationId);
            }

            // Ir al Bussiness Object
            $usuariosPerfilService = new UsuariosPerfilBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);

            $usuariosPerfilService->executeService('list', $this->DTO);
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addUsuariosPerfil() {
        try {

            if ($this->validateInputData($this->DTO,  'usuario_perfil', 'usuario_perfil_validation', 'v_usuario_perfil', 'addUsuarioPerfil') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('usuarios_id', $this->input->get_post('usuarios_id'));
                $this->DTO->addParameter('perfil_id', $this->input->get_post('perfil_id'));
                $this->DTO->addParameter('activo', true); // Siempre se agrega
                //
                // Ir al Bussiness Object
                $usuariosPerfilService = new UsuariosPerfilBussinessService();
                $usuariosPerfilService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updUsuariosPerfil() {
        try {


            if ($this->validateInputData($this->DTO,  'usuario_perfil', 'usuario_perfil_validation', 'v_usuario_perfil',  'updUsuarioPerfil') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('usuario_perfil_id', $this->input->get_post('usuario_perfil_id'));
                $this->DTO->addParameter('usuarios_id', $this->input->get_post('usuarios_id'));
                $this->DTO->addParameter('perfil_id', $this->input->get_post('perfil_id'));

                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo'));

                // Ir al Bussiness Object
                $usuariosPerfilService = new UsuariosPerfilBussinessService();
                $usuariosPerfilService->executeService('update', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function delUsuariosPerfil() {
        try {
            if ($this->validateInputData($this->DTO, 'usuario_perfil', 'usuario_perfil_validation', 'v_usuario_perfil', 'delUsuarioPerfil') === TRUE) {
                $this->DTO->addParameter('usuario_perfil_id', $this->input->get_post('usuario_perfil_id'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $usuariosPerfilService = new UsuariosPerfilBussinessService();
                $usuariosPerfilService->executeService('delete', $this->DTO);
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
        $this->parseParameters(array('usuarioperfiles','usuarios_', 'perfil_','sys_'));

        // ya que podria no haberse enviado y estar no definido
        $usuarioId = $this->fixParameter('usuario_perfil_id', 'null', NULL);

        // Se setea el usuario
        $this->DTO->setSessionUser($this->getUser());

        // Leera los datos del tipo de contribuyentes por default si no se envia
        // una operacion especifica.
        $op = $_REQUEST['op'];
        if (!isset($op) || $op == 'fetch') {
            if (isset($usuarioId)) {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_READ);
                $this->readUsuariosPerfil();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchUsuariosPerfil();
            }
        } else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updUsuariosPerfil();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->delUsuariosPerfil();
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addUsuariosPerfil();
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
