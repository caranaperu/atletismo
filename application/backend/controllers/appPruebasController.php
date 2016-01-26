<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para las operaciones CRUD para definir las genericas de las pruebas
 *
 * @author $Author: aranape $
 * @since 17-May-2012
 * @version $Id: appPruebasController.php 281 2014-06-30 02:17:37Z aranape $
 *
 * $Date: 2014-06-29 21:17:37 -0500 (dom, 29 jun 2014) $
 * $Rev: 281 $
 */
class appPruebasController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    private function readAppPruebas() {

        try {
            if ($this->validateInputData($this->DTO, 'apppruebas', 'apppruebas_validation', 'v_apppruebas', 'getAppPruebas') === TRUE) {
                $this->DTO->addParameter('id', $this->input->get_post('apppruebas_codigo'));
                $this->DTO->addParameter('apppruebas_codigo', $this->input->get_post('apppruebas_codigo'));
                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));

                // Ir al Bussiness Object
                $appPruebasService = new AppPruebasBussinessService();
                $appPruebasService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function fetchAppPruebas() {
        try {
            // Ir al Bussiness Object
            $appPruebasService = new AppPruebasBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);

            $appPruebasService->executeService('list', $this->DTO);
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addAppPruebas() {
        try {
            if ($this->validateInputData($this->DTO, 'apppruebas', 'apppruebas_validation', 'v_apppruebas', 'addAppPruebas') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('apppruebas_codigo', $this->input->get_post('apppruebas_codigo'));
                $this->DTO->addParameter('apppruebas_descripcion', $this->input->get_post('apppruebas_descripcion'));
                $this->DTO->addParameter('pruebas_clasificacion_codigo', $this->input->get_post('pruebas_clasificacion_codigo'));
                $this->DTO->addParameter('apppruebas_marca_menor', $this->input->get_post('apppruebas_marca_menor'));
                $this->DTO->addParameter('apppruebas_marca_mayor', $this->input->get_post('apppruebas_marca_mayor'));
                $this->DTO->addParameter('apppruebas_multiple', $this->input->get_post('apppruebas_multiple'));
                $this->DTO->addParameter('apppruebas_verifica_viento', $this->input->get_post('apppruebas_verifica_viento'));
                $this->DTO->addParameter('apppruebas_viento_individual', $this->input->get_post('apppruebas_viento_individual'));
                $this->DTO->addParameter('apppruebas_viento_limite_normal', $this->input->get_post('apppruebas_viento_limite_normal'));
                $this->DTO->addParameter('apppruebas_viento_limite_multiple', $this->input->get_post('apppruebas_viento_limite_multiple'));
                $this->DTO->addParameter('apppruebas_nro_atletas', $this->input->get_post('apppruebas_nro_atletas'));
                $this->DTO->addParameter('apppruebas_factor_manual', $this->input->get_post('apppruebas_factor_manual'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo')); // Siempre se agrega
                // Ir al Bussiness Object
                $appPruebasService = new AppPruebasBussinessService();
                $appPruebasService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updateAppPruebas() {
        try {

            if ($this->validateInputData($this->DTO, 'apppruebas', 'apppruebas_validation', 'v_apppruebas', 'updAppPruebas') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('apppruebas_codigo', $this->input->get_post('apppruebas_codigo'));
                $this->DTO->addParameter('apppruebas_descripcion', $this->input->get_post('apppruebas_descripcion'));
                $this->DTO->addParameter('pruebas_clasificacion_codigo', $this->input->get_post('pruebas_clasificacion_codigo'));
                $this->DTO->addParameter('apppruebas_marca_menor', $this->input->get_post('apppruebas_marca_menor'));
                $this->DTO->addParameter('apppruebas_marca_mayor', $this->input->get_post('apppruebas_marca_mayor'));
                $this->DTO->addParameter('apppruebas_multiple', $this->input->get_post('apppruebas_multiple'));
                $this->DTO->addParameter('apppruebas_verifica_viento', $this->input->get_post('apppruebas_verifica_viento'));
                $this->DTO->addParameter('apppruebas_viento_individual', $this->input->get_post('apppruebas_viento_individual'));
                $this->DTO->addParameter('apppruebas_viento_limite_normal', $this->input->get_post('apppruebas_viento_limite_normal'));
                $this->DTO->addParameter('apppruebas_viento_limite_multiple', $this->input->get_post('apppruebas_viento_limite_multiple'));
                $this->DTO->addParameter('apppruebas_nro_atletas', $this->input->get_post('apppruebas_nro_atletas'));
                $this->DTO->addParameter('apppruebas_factor_manual', $this->input->get_post('apppruebas_factor_manual'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo'));

                // Ir al Bussiness Object
                $appPruebasService = new AppPruebasBussinessService();
                $appPruebasService->executeService('update', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

        private function deleteAppPruebas() {
        try {
            if ($this->validateInputData($this->DTO, 'apppruebas', 'apppruebas_validation', 'v_apppruebas', 'delAppPruebas') === TRUE) {
                $this->DTO->addParameter('apppruebas_codigo', $this->input->get_post('apppruebas_codigo'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $appPruebasService = new AppPruebasBussinessService();
                $appPruebasService->executeService('delete', $this->DTO);
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
        $this->parseParameters(['apppruebas_','pruebas_clasificacion_']);
        // ya que podria no haberse enviado y estar no definido
        $appPruebassId = $this->fixParameter('apppruebas_codigo', 'null', NULL);

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
            if (isset($appPruebassId) && ($operationId == 'read' || !isset($operationId) || $operationId === FALSE)) {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_READ);
                $this->readAppPruebas();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchAppPruebas();
            }
        }  else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updateAppPruebas();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->deleteAppPruebas();
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addAppPruebas();
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
