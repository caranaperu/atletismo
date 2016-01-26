<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador que soporta la obtencion de datos para los resultados a poner
 * en diversos graficos del sistema.
 * Solo efectua fetch como unica operacion.
 *
 * @author $Author: aranape $
 * @since 17-May-2012
 * @version $Id: atletasResultadosGraphDataController.php 212 2014-06-23 22:50:52Z aranape $
 *
 * $Date: 2014-06-23 17:50:52 -0500 (lun, 23 jun 2014) $
 * $Rev: 212 $
 */
class atletasResultadosGraphDataController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Leer la documentacion de la clase.
     */
    private function fetchAtletasResultadosGraphData() {
        try {
            // Ir al Bussiness Object
            $atletasResultadosGraphService = new AtletasResultadosGraphDataBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);

            $atletasResultadosGraphService->executeService('list', $this->DTO);
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

        // Vemos si esta definido el tipo de suboperacion
        $operationId = $this->input->get_post('_operationId');
        if (isset($operationId) && is_string($operationId)) {
            $this->DTO->setSubOperationId($operationId);
        }


        // Se setea el usuario
        $this->DTO->setSessionUser($this->getUser());

        $op = $_REQUEST['op'];
        if (!isset($op) || $op == 'fetch') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
            $this->fetchAtletasResultadosGraphData();
        } else {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage(70000, 'Operacion No Conocida', null);
            $outMessage->addProcessError($processError);
        }

        // Envia los resultados a traves del DTO
        $data['data'] = &$this->responseProcessor->process($this->DTO);
        $this->load->view($this->getView(), $data);
    }

    /**
     *
     * @return string con el nombre base que se usara como response
     * procesor de este controller,
     */
    protected function getUserResponseProcessor() {
        return 'ResponseProcessorAmcharts';
    }

    /**
     *
     * @return string con el nombre base que se usara como filter
     * procesor de este controller,
     */
    public function getFilterProcessor() {
        return 'ConstraintProcessorAmcharts';
        ;
    }

}
