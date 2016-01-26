<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para las operaciones CRUD de los paises.
 *
 * @author $Author: aranape $
 * @since 17-May-2012
 * @version $Id: competenciaTipoController.php 70 2014-03-09 10:20:51Z aranape $
 *
 * $Date: 2014-03-09 05:20:51 -0500 (dom, 09 mar 2014) $
 * $Rev: 70 $
 */
class competenciaTipoController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    private function readCompetenciaTipo() {

        try {
            if ($this->validateInputData($this->DTO, 'competencia_tipo', 'competencia_tipo_validation', 'v_competencia_tipo', 'getCompetenciaTipo') === TRUE) {
                $this->DTO->addParameter('id', $this->input->get_post('competencia_tipo_codigo'));
                $this->DTO->addParameter('competencia_tipo_codigo', $this->input->get_post('competencia_tipo_codigo'));
                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));

                // Ir al Bussiness Object
                $competenciaTipoService = new CompetenciaTipoBussinessService();
                $competenciaTipoService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function fetchCompetenciaTipo() {
        try {
            // Ir al Bussiness Object
            $competenciaTipoService = new CompetenciaTipoBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);

            $competenciaTipoService->executeService('list', $this->DTO);
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addCompetenciaTipo() {
        try {

            if ($this->validateInputData($this->DTO, 'competencia_tipo', 'competencia_tipo_validation', 'v_competencia_tipo', 'addCompetenciaTipo') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('competencia_tipo_codigo', $this->input->get_post('competencia_tipo_codigo'));
                $this->DTO->addParameter('competencia_tipo_descripcion', $this->input->get_post('competencia_tipo_descripcion'));
                $this->DTO->addParameter('activo', true); // Siempre se agrega vivo
                // Ir al Bussiness Object
                $competenciaTipoService = new CompetenciaTipoBussinessService();
                $competenciaTipoService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updateCompetenciaTipo() {
        try {

            if ($this->validateInputData($this->DTO, 'competencia_tipo', 'competencia_tipo_validation', 'v_competencia_tipo', 'updCompetenciaTipo') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('competencia_tipo_codigo', $this->input->get_post('competencia_tipo_codigo'));
                $this->DTO->addParameter('competencia_tipo_descripcion', $this->input->get_post('competencia_tipo_descripcion'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo'));

                // Ir al Bussiness Object
                $competenciaTipoService = new CompetenciaTipoBussinessService();
                $competenciaTipoService->executeService('update', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function deleteCompetenciaTipo() {
        try {
            if ($this->validateInputData($this->DTO, 'competencia_tipo', 'competencia_tipo_validation', 'v_competencia_tipo', 'delCompetenciaTipo') === TRUE) {
                $this->DTO->addParameter('competencia_tipo_codigo', $this->input->get_post('competencia_tipo_codigo'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $competenciaTipoService = new CompetenciaTipoBussinessService();
                $competenciaTipoService->executeService('delete', $this->DTO);
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
        $this->parseParameters('competencia_tipo_');
        // ya que podria no haberse enviado y estar no definido
        $competenciatipoId = $this->fixParameter('competencia_tipo_codigo', 'null', NULL);

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
            if (isset($competenciatipoId) && ($operationId == 'read' || !isset($operationId) || $operationId === FALSE)) {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_READ);
                $this->readCompetenciaTipo();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchCompetenciaTipo();
            }
        } else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updateCompetenciaTipo();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->deleteCompetenciaTipo();
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addCompetenciaTipo();
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
