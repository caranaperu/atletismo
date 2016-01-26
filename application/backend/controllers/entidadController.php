<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para las ddatos generales de la entidad usuaria
 * del sistema.
 *
 *
 * @author  $Author: aranape $
 * @since   06-FEB-2013
 * @version $Id: entidadController.php 7 2014-02-11 23:55:54Z aranape $
 * @history ''
 *
 * $Date: 2014-02-11 18:55:54 -0500 (mar, 11 feb 2014) $
 * $Rev: 7 $
 */
class entidadController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }


    private function readEntidad() {

        try {
            if ($this->validateInputData($this->DTO, 'entidad', 'entidad_validation', 'v_entidad', 'getEntidad') === TRUE) {
                $this->DTO->addParameter('entidad_id', str_replace('null', '', $this->input->get_post('entidad_id')));
                $this->DTO->addParameter('verifyExist', str_replace('null', '', $this->input->get_post('verifyExist')));

                // Ir al Bussiness Object
                $entidadService = new EntidadBussinessService();
                $entidadService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function fetchEntidad() {
        try {
            // Ir al Bussiness Object
            $entidadService = new EntidadBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);
            $entidadService->executeService('list', $this->DTO);

        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addEntidad() {
        try {


            if ($this->validateInputData($this->DTO, 'entidad', 'entidad_validation', 'v_entidad', 'addEntidad') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('entidad_razon_social', $this->input->get_post('entidad_razon_social'));
                $this->DTO->addParameter('entidad_ruc', $this->input->get_post('entidad_ruc'));
                $this->DTO->addParameter('entidad_direccion', $this->input->get_post('entidad_direccion'));
                $this->DTO->addParameter('entidad_siglas', $this->input->get_post('entidad_siglas'));
                $this->DTO->addParameter('entidad_eslogan', $this->input->get_post('entidad_eslogan'));
                $this->DTO->addParameter('entidad_titulo_alterno', $this->input->get_post('entidad_titulo_alterno'));
                $this->DTO->addParameter('entidad_telefonos', $this->input->get_post('entidad_telefonos'));
                $this->DTO->addParameter('entidad_fax', $this->input->get_post('entidad_fax'));
                $this->DTO->addParameter('entidad_web_url', $this->input->get_post('entidad_web_url'));
                $this->DTO->addParameter('entidad_correo', $this->input->get_post('entidad_correo'));
                $this->DTO->addParameter('activo', true); // Siempre se agrega
                // Ir al Bussiness Object
                $entidadService = new EntidadBussinessService();
                $entidadService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updEntidad() {
        try {

            if ($this->validateInputData($this->DTO, 'entidad', 'entidad_validation', 'v_entidad', 'updEntidad') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('entidad_id', $this->input->get_post('entidad_id'));
                $this->DTO->addParameter('entidad_razon_social', $this->input->get_post('entidad_razon_social'));
                $this->DTO->addParameter('entidad_ruc', $this->input->get_post('entidad_ruc'));
                $this->DTO->addParameter('entidad_direccion', $this->input->get_post('entidad_direccion'));
                $this->DTO->addParameter('entidad_siglas', $this->input->get_post('entidad_siglas'));
                $this->DTO->addParameter('entidad_eslogan', $this->input->get_post('entidad_eslogan'));
                $this->DTO->addParameter('entidad_titulo_alterno', $this->input->get_post('entidad_titulo_alterno'));
                $this->DTO->addParameter('entidad_telefonos', $this->input->get_post('entidad_telefonos'));
                $this->DTO->addParameter('entidad_fax', $this->input->get_post('entidad_fax'));
                $this->DTO->addParameter('entidad_web_url', $this->input->get_post('entidad_web_url'));
                $this->DTO->addParameter('entidad_correo', $this->input->get_post('entidad_correo'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo'));

                // Ir al Bussiness Object
                $entidadService = new EntidadBussinessService();
                $entidadService->executeService('update', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function deleteEntidad() {
        try {
            if ($this->validateInputData($this->DTO, 'entidad', 'entidad_validation', 'v_entidad', 'delEntidad') === TRUE) {
                $this->DTO->addParameter('entidad_id', $this->input->get_post('entidad_id'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $entidadService = new EntidadBussinessService();
                $entidadService->executeService('delete', $this->DTO);
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
        $this->parseParameters('entidad_');
        // ya que podria no haberse enviado y estar no definido
        $entidadId = $this->fixParameter('entidad_id', 'null', NULL);

        // Se setea el usuario
        $this->DTO->setSessionUser($this->getUser());

        // Leera los datos del tipo de contribuyentes por default si no se envia
        // una operacion especifica.
        $op = $this->input->get('op');
        if (!isset($op) || $op == 'fetch') {
            if (isset($entidadId)) {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_READ);
                $this->readEntidad();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchEntidad();
            }
        } else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updEntidad();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->deleteEntidad();
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addEntidad();
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
