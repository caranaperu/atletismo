<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para los resultados de los atletas inputados en forma directa , es por eso que los registros del modelo
 * so una combinacion de la prueba de una competencia y el resultado , ya que en realidad el backend realiza operaciones
 * sobre ambas tablas , la de pruebas por competencia y la de resultados de un atleta.
 *
 *
 * @author $Author: aranape $
 * @since 17-May-2012
 * @version $Id: competenciasPruebasController.php 210 2014-06-23 22:49:29Z aranape $
 *
 * $Date: 2014-06-23 17:49:29 -0500 (lun, 23 jun 2014) $
 * $Rev: 210 $
 */
class competenciasPruebasController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

//    private function readCompetenciasPruebas() {
//
//        try {
//            if ($this->validateInputData($this->DTO, 'atletaspruebas_resultados', 'atletaspruebas_resultados_validation', 'v_atletaspruebas_resultados', 'getAtletasPruebasResultados') === TRUE) {
//                $this->DTO->addParameter('id', $this->input->get_post('atletas_resultados_id'));
//                $this->DTO->addParameter('atletas_resultados_id', $this->input->get_post('atletas_resultados_id'));
//                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));
//
//                // Ir al Bussiness Object
//                $competenciasPruebasService = new AtletasPruebasResultadosBussinessService();
//                $competenciasPruebasService->executeService('read', $this->DTO);
//            }
//        } catch (Exception $ex) {
//            $outMessage = &$this->DTO->getOutMessage();
//            // TODO: Internacionalizar.
//            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
//            $outMessage->addProcessError($processError);
//        }
//    }
//

    private function deleteCompetenciasPruebas() {
        try {
            if ($this->validateInputData($this->DTO, 'competencias_pruebas', 'competencias_pruebas_validation', 'v_competencias_pruebas', 'delCompetenciasPruebas') === TRUE) {
                $this->DTO->addParameter('competencias_pruebas_id', $this->input->get_post('competencias_pruebas_id'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $competenciasPruebasService = new CompetenciasPruebasBussinessService();
                $competenciasPruebasService->executeService('delete', $this->DTO);
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
    private function fetchCompetenciasPruebas() {
        try {
            // Ir al Bussiness Object
            $competenciasPruebasService = new CompetenciasPruebasBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);

            $competenciasPruebasService->executeService('list', $this->DTO);
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addCompetenciasPruebas() {
        try {
            if ($this->validateInputData($this->DTO, 'competencias_pruebas', 'competencias_pruebas_validation', 'v_competencias_pruebas', 'addCompetenciasPruebas') === TRUE) {

                // Seteamos parametros en el DTO
                $this->DTO->addParameter('competencias_codigo', $this->input->get_post('competencias_codigo'));
                $this->DTO->addParameter('pruebas_codigo', $this->input->get_post('pruebas_codigo'));
                $this->DTO->addParameter('competencias_pruebas_origen_combinada', $this->input->get_post('competencias_pruebas_origen_combinada'));
                $this->DTO->addParameter('competencias_pruebas_fecha', $this->input->get_post('competencias_pruebas_fecha'));
                $this->DTO->addParameter('competencias_pruebas_viento', $this->input->get_post('competencias_pruebas_viento'));
                $this->DTO->addParameter('competencias_pruebas_tipo_serie', $this->input->get_post('competencias_pruebas_tipo_serie'));
                $this->DTO->addParameter('competencias_pruebas_nro_serie', $this->input->get_post('competencias_pruebas_nro_serie'));
                $this->DTO->addParameter('competencias_pruebas_anemometro', $this->input->get_post('competencias_pruebas_anemometro'));
                $this->DTO->addParameter('competencias_pruebas_material_reglamentario', $this->input->get_post('competencias_pruebas_material_reglamentario'));
                $this->DTO->addParameter('competencias_pruebas_anemometro', $this->input->get_post('competencias_pruebas_anemometro'));
                $this->DTO->addParameter('competencias_pruebas_manual', $this->input->get_post('competencias_pruebas_manual'));
                $this->DTO->addParameter('competencias_pruebas_observaciones', $this->input->get_post('competencias_pruebas_observaciones'));
                $this->DTO->addParameter('competencias_pruebas_origen_id', $this->input->get_post('competencias_pruebas_origen_id'));

                $this->DTO->addParameter('activo', $this->input->get_post('activo')); // Siempre se agrega
                // Ir al Bussiness Object
                $competenciasPruebasService = new CompetenciasPruebasBussinessService();
                $competenciasPruebasService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updCompetenciasPruebas() {
        try {
            if ($this->validateInputData($this->DTO, 'competencias_pruebas', 'competencias_pruebas_validation', 'v_competencias_pruebas', 'updCompetenciasPruebas') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('competencias_pruebas_id', $this->input->get_post('competencias_pruebas_id'));
                $this->DTO->addParameter('id', $this->input->get_post('competencias_pruebas_id'));
                $this->DTO->addParameter('competencias_codigo', $this->input->get_post('competencias_codigo'));
                $this->DTO->addParameter('pruebas_codigo', $this->input->get_post('pruebas_codigo'));
                $this->DTO->addParameter('competencias_pruebas_origen_combinada', $this->input->get_post('competencias_pruebas_origen_combinada'));
                $this->DTO->addParameter('competencias_pruebas_fecha', $this->input->get_post('competencias_pruebas_fecha'));
                $this->DTO->addParameter('competencias_pruebas_viento', $this->input->get_post('competencias_pruebas_viento'));
                $this->DTO->addParameter('competencias_pruebas_tipo_serie', $this->input->get_post('competencias_pruebas_tipo_serie'));
                $this->DTO->addParameter('competencias_pruebas_nro_serie', $this->input->get_post('competencias_pruebas_nro_serie'));
                $this->DTO->addParameter('competencias_pruebas_anemometro', $this->input->get_post('competencias_pruebas_anemometro'));
                $this->DTO->addParameter('competencias_pruebas_material_reglamentario', $this->input->get_post('competencias_pruebas_material_reglamentario'));
                $this->DTO->addParameter('competencias_pruebas_anemometro', $this->input->get_post('competencias_pruebas_anemometro'));
                $this->DTO->addParameter('competencias_pruebas_manual', $this->input->get_post('competencias_pruebas_manual'));
                $this->DTO->addParameter('competencias_pruebas_observaciones', $this->input->get_post('competencias_pruebas_observaciones'));
                $this->DTO->addParameter('competencias_pruebas_origen_id', $this->input->get_post('competencias_pruebas_origen_id'));

                $this->DTO->addParameter('activo', $this->input->get_post('activo')); // Siempre se agrega
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                // Ir al Bussiness Object
                $competenciasPruebasService = new CompetenciasPruebasBussinessService();
                $competenciasPruebasService->executeService('update', $this->DTO);
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
        $this->parseParameters(['competencias_pruebas_', 'competencias_', 'pruebas_']);

        // ya que podria no haberse enviado y estar no definido
        $competenciasPruebasId = $this->fixParameter('competencias_pruebas_id', 'null', NULL);

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
            if (isset($$competenciasPruebasId) && ($operationId == 'read' || !isset($operationId) || $operationId === FALSE)) {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_READ);
                $this->readCompetenciasPruebas();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchCompetenciasPruebas();
            }
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addCompetenciasPruebas();
        } else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updCompetenciasPruebas();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->deleteCompetenciasPruebas();
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
