<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para las operaciones CRUD de los paises.
 *
 * @author $Author: aranape $
 * @since 17-May-2012
 * @version $Id: competenciasController.php 298 2014-06-30 23:59:00Z aranape $
 *
 * $Date: 2014-06-30 18:59:00 -0500 (lun, 30 jun 2014) $
 * $Rev: 298 $
 */
class competenciasController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    private function readCompetencias() {

        try {
            if ($this->validateInputData($this->DTO, 'competencias', 'competencias_validation', 'v_competencias', 'getCompetencias') === TRUE) {
                $this->DTO->addParameter('id', $this->input->get_post('competencias_codigo'));
                $this->DTO->addParameter('competencias_codigo', $this->input->get_post('competencias_codigo'));
                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));

                // Ir al Bussiness Object
                $competenciasService = new CompetenciasBussinessService();
                $competenciasService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function fetchCompetencias() {
        try {
            // Ir al Bussiness Object
            $competenciasService = new CompetenciasBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);

            $competenciasService->executeService('list', $this->DTO);
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addCompetencias() {
        try {

            if ($this->validateInputData($this->DTO, 'competencias', 'competencias_validation', 'v_competencias', 'addCompetencias') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('competencias_codigo', $this->input->get_post('competencias_codigo'));
                $this->DTO->addParameter('competencias_descripcion', $this->input->get_post('competencias_descripcion'));
                $this->DTO->addParameter('competencia_tipo_codigo', $this->input->get_post('competencia_tipo_codigo'));
                $this->DTO->addParameter('paises_codigo', $this->input->get_post('paises_codigo'));
                $this->DTO->addParameter('ciudades_codigo', $this->input->get_post('ciudades_codigo'));
                $this->DTO->addParameter('categorias_codigo', $this->input->get_post('categorias_codigo'));
                $this->DTO->addParameter('competencias_fecha_inicio', $this->input->get_post('competencias_fecha_inicio'));
                $this->DTO->addParameter('competencias_fecha_final', $this->input->get_post('competencias_fecha_final'));
                $this->DTO->addParameter('competencias_es_oficial', $this->input->get_post('competencias_es_oficial'));
                $this->DTO->addParameter('competencias_clasificacion', $this->input->get_post('competencias_clasificacion'));
                $this->DTO->addParameter('activo', true); // Siempre se agrega vivo
                // Ir al Bussiness Object
                $competenciasService = new CompetenciasBussinessService();
                $competenciasService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updateCompetencias() {
        try {

            if ($this->validateInputData($this->DTO, 'competencias', 'competencias_validation', 'v_competencias', 'updCompetencias') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('competencias_codigo', $this->input->get_post('competencias_codigo'));
                $this->DTO->addParameter('competencias_descripcion', $this->input->get_post('competencias_descripcion'));
                $this->DTO->addParameter('competencia_tipo_codigo', $this->input->get_post('competencia_tipo_codigo'));
                $this->DTO->addParameter('paises_codigo', $this->input->get_post('paises_codigo'));
                $this->DTO->addParameter('ciudades_codigo', $this->input->get_post('ciudades_codigo'));
                $this->DTO->addParameter('categorias_codigo', $this->input->get_post('categorias_codigo'));
                $this->DTO->addParameter('competencias_fecha_inicio', $this->input->get_post('competencias_fecha_inicio'));
                $this->DTO->addParameter('competencias_fecha_final', $this->input->get_post('competencias_fecha_final'));
                $this->DTO->addParameter('competencias_es_oficial', $this->input->get_post('competencias_es_oficial'));
                $this->DTO->addParameter('competencias_clasificacion', $this->input->get_post('competencias_clasificacion'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo'));

                // Ir al Bussiness Object
                $competenciasService = new CompetenciasBussinessService();
                $competenciasService->executeService('update', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function deleteCompetencias() {
        try {
            if ($this->validateInputData($this->DTO, 'competencias', 'competencias_validation', 'v_competencias', 'delCompetencias') === TRUE) {
                $this->DTO->addParameter('competencias_codigo', $this->input->get_post('competencias_codigo'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $competenciasService = new CompetenciasBussinessService();
                $competenciasService->executeService('delete', $this->DTO);
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
        $this->parseParameters(['competencias_','paises_','ciudades_','competencia_tipo_','categorias_']);
        // ya que podria no haberse enviado y estar no definido
        $competenciatipoId = $this->fixParameter('competencias_codigo', 'null', NULL);

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
                $this->readCompetencias();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchCompetencias();
            }
        } else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updateCompetencias();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->deleteCompetencias();
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addCompetencias();
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
