<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para los resultados de los atletas inputados en forma directa o por consolidacion de resultados de una
 * competencia.
 *
 *
 * @author $Author: aranape $
 * @since 17-May-2012
 * @version $Id: atletasResultadosController.php 213 2014-06-23 22:52:08Z aranape $
 *
 * $Date: 2014-06-23 17:52:08 -0500 (lun, 23 jun 2014) $
 * $Rev: 213 $
 */
class atletasResultadosController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    private function readAtletasResultados() {

        try {
            if ($this->validateInputData($this->DTO, 'atletasresultados', 'atletasresultados_validation', 'v_atletasresultados', 'getAtletasResultados') === TRUE) {
                $this->DTO->addParameter('id', $this->input->get_post('atletas_resultados_id'));
                $this->DTO->addParameter('atletas_resultados_id', $this->input->get_post('atletas_resultados_id'));
                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));

                // Ir al Bussiness Object
                $atletasResultadosService = new AtletasResultadosBussinessService();
                $atletasResultadosService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function deleteAtletasResultados() {
        try {
            if ($this->validateInputData($this->DTO, 'atletasresultados', 'atletasresultados_validation', 'v_atletasresultados', 'delAtletasResultados') === TRUE) {
                $this->DTO->addParameter('atletas_resultados_id', $this->input->get_post('atletas_resultados_id'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $atletasResultadosService = new AtletasResultadosBussinessService();
                $atletasResultadosService->executeService('delete', $this->DTO);
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
    private function fetchAtletasResultados() {
        try {
            // Ir al Bussiness Object
            $atletasResultadosService = new AtletasResultadosBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);

            $atletasResultadosService->executeService('list', $this->DTO);
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addAtletasResultados() {
        try {
            if ($this->validateInputData($this->DTO, 'atletasresultados', 'atletasresultados_validation', 'v_atletasresultados', 'addAtletasResultados') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('competencias_pruebas_id', $this->input->get_post('competencias_pruebas_id'));
                $this->DTO->addParameter('atletas_codigo', $this->input->get_post('atletas_codigo'));
                $this->DTO->addParameter('atletas_resultados_resultado', $this->input->get_post('atletas_resultados_resultado'));
                $this->DTO->addParameter('atletas_resultados_puesto', $this->input->get_post('atletas_resultados_puesto'));
                $this->DTO->addParameter('atletas_resultados_puntos', $this->input->get_post('atletas_resultados_puntos'));
                $this->DTO->addParameter('atletas_resultados_viento', $this->input->get_post('atletas_resultados_viento'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo')); // Siempre se agrega
                // Ir al Bussiness Object
                $atletasResultadosService = new AtletasResultadosBussinessService();
                $atletasResultadosService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updAtletasResultados() {
        try {
            if ($this->validateInputData($this->DTO, 'atletasresultados', 'atletasresultados_validation', 'v_atletasresultados', 'updAtletasResultados') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('atletas_resultados_id', $this->input->get_post('atletas_resultados_id'));
                $this->DTO->addParameter('id', $this->input->get_post('atletas_resultados_id'));
                $this->DTO->addParameter('competencias_pruebas_id', $this->input->get_post('competencias_pruebas_id'));
                $this->DTO->addParameter('atletas_codigo', $this->input->get_post('atletas_codigo'));
                $this->DTO->addParameter('atletas_resultados_resultado', $this->input->get_post('atletas_resultados_resultado'));
                $this->DTO->addParameter('atletas_resultados_puesto', $this->input->get_post('atletas_resultados_puesto'));
                $this->DTO->addParameter('atletas_resultados_puntos', $this->input->get_post('atletas_resultados_puntos'));
                $this->DTO->addParameter('atletas_resultados_viento', $this->input->get_post('atletas_resultados_viento'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo')); // Siempre se agrega
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                // Ir al Bussiness Object
                $atletasResultadosService = new AtletasResultadosBussinessService();
                $atletasResultadosService->executeService('update', $this->DTO);
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
        $this->parseParameters(['atletas_resultados_', 'competencias_', 'atletas_','pruebas_']);

        // ya que podria no haberse enviado y estar no definido
        $atletasResultadosId = $this->fixParameter('atletas_resultados_id', 'null', NULL);

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
            if (isset($atletasResultadosId) && ($operationId == 'read' || !isset($operationId) || $operationId === FALSE)) {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_READ);
                $this->readAtletasResultados();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchAtletasResultados();
            }
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addAtletasResultados();
        } else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updAtletasResultados();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->deleteAtletasResultados();
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
