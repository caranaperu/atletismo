<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para las operaciones CRUD de las pruebas atleticas.
 *
 * @author $Author: aranape $
 * @since 17-May-2012
 * @version $Id: pruebasController.php 88 2014-03-25 15:14:07Z aranape $
 *
 * $Date: 2014-03-25 10:14:07 -0500 (mar, 25 mar 2014) $
 * $Rev: 88 $
 */
class pruebasController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    private function readPruebas() {

        try {
            if ($this->validateInputData($this->DTO, 'pruebas', 'pruebas_validation', 'v_pruebas', 'getPruebas') === TRUE) {
                $this->DTO->addParameter('id', $this->input->get_post('pruebas_codigo'));
                $this->DTO->addParameter('pruebas_codigo', $this->input->get_post('pruebas_codigo'));
                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));

                // Ir al Bussiness Object
                $pruebasService = new PruebasBussinessService();
                $pruebasService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function fetchPruebas() {
        try {
            // Ir al Bussiness Object
            $pruebasService = new PruebasBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);

            $pruebasService->executeService('list', $this->DTO);
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addPruebas() {
        try {

            if ($this->validateInputData($this->DTO, 'pruebas', 'pruebas_validation', 'v_pruebas', 'addPruebas') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('pruebas_codigo', $this->input->get_post('pruebas_codigo'));
                $this->DTO->addParameter('pruebas_descripcion', $this->input->get_post('pruebas_descripcion'));
                $this->DTO->addParameter('pruebas_generica_codigo', $this->input->get_post('pruebas_generica_codigo'));
                $this->DTO->addParameter('pruebas_clasificacion_codigo', $this->input->get_post('pruebas_clasificacion_codigo'));
                $this->DTO->addParameter('categorias_codigo', $this->input->get_post('categorias_codigo'));
                $this->DTO->addParameter('pruebas_sexo', $this->input->get_post('pruebas_sexo'));
                $this->DTO->addParameter('pruebas_record_hasta', $this->input->get_post('pruebas_record_hasta'));
                $this->DTO->addParameter('pruebas_anotaciones', $this->input->get_post('pruebas_anotaciones'));
                $this->DTO->addParameter('pruebas_multiple', $this->input->get_post('pruebas_multiple'));

                $this->DTO->addParameter('activo', true); // Siempre se agrega vivo
                // Ir al Bussiness Object
                $pruebasService = new PruebasBussinessService();
                $pruebasService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updatePruebas() {
        try {

            if ($this->validateInputData($this->DTO, 'pruebas', 'pruebas_validation', 'v_pruebas', 'updPruebas') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('pruebas_codigo', $this->input->get_post('pruebas_codigo'));
                $this->DTO->addParameter('pruebas_descripcion', $this->input->get_post('pruebas_descripcion'));
                $this->DTO->addParameter('pruebas_generica_codigo', $this->input->get_post('pruebas_generica_codigo'));
                $this->DTO->addParameter('pruebas_clasificacion_codigo', $this->input->get_post('pruebas_clasificacion_codigo'));
                $this->DTO->addParameter('categorias_codigo', $this->input->get_post('categorias_codigo'));
                $this->DTO->addParameter('pruebas_sexo', $this->input->get_post('pruebas_sexo'));
                $this->DTO->addParameter('pruebas_record_hasta', $this->input->get_post('pruebas_record_hasta'));
                $this->DTO->addParameter('pruebas_anotaciones', $this->input->get_post('pruebas_anotaciones'));
                $this->DTO->addParameter('pruebas_multiple', $this->input->get_post('pruebas_multiple'));

                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo'));

                // Ir al Bussiness Object
                $pruebasService = new PruebasBussinessService();
                $pruebasService->executeService('update', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function deletePruebas() {
        try {
            if ($this->validateInputData($this->DTO, 'pruebas', 'pruebas_validation', 'v_pruebas', 'delPruebas') === TRUE) {
                $this->DTO->addParameter('pruebas_codigo', $this->input->get_post('pruebas_codigo'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $pruebasService = new PruebasBussinessService();
                $pruebasService->executeService('delete', $this->DTO);
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
        $this->parseParameters(['pruebas_', 'pruebas_clasificacion_', 'categorias_']);
        // ya que podria no haberse enviado y estar no definido
        $pruebasId = $this->fixParameter('pruebas_codigo', 'null', NULL);

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
            if (isset($pruebasId) && ($operationId == 'read' || !isset($operationId) || $operationId === FALSE)) {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_READ);
                $this->readPruebas();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchPruebas();
            }
        } else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updatePruebas();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->deletePruebas();
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addPruebas();
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
