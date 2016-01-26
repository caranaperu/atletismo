<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para las operaciones CRUD de los paises.
 *
 * @author $Author: aranape $
 * @since 17-May-2012
 * @version $Id: categoriasController.php 57 2014-03-09 10:10:13Z aranape $
 *
 * $Date: 2014-03-09 05:10:13 -0500 (dom, 09 mar 2014) $
 * $Rev: 57 $
 */
class categoriasController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    private function readCategorias() {

        try {
            if ($this->validateInputData($this->DTO, 'categorias', 'categorias_validation', 'v_categorias', 'getCategorias') === TRUE) {
                $this->DTO->addParameter('id', $this->input->get_post('categorias_codigo'));
                $this->DTO->addParameter('categorias_codigo', $this->input->get_post('categorias_codigo'));
                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));

                // Ir al Bussiness Object
                $categoriasService = new CategoriasBussinessService();
                $categoriasService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function fetchCategorias() {
        try {
            // Ir al Bussiness Object
            $categoriasService = new CategoriasBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);

            $categoriasService->executeService('list', $this->DTO);
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addCategorias() {
        try {

            if ($this->validateInputData($this->DTO, 'categorias', 'categorias_validation', 'v_categorias', 'addCategorias') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('categorias_codigo', $this->input->get_post('categorias_codigo'));
                $this->DTO->addParameter('categorias_descripcion', $this->input->get_post('categorias_descripcion'));
                $this->DTO->addParameter('categorias_edad_inicial', $this->input->get_post('categorias_edad_inicial'));
                $this->DTO->addParameter('categorias_edad_final', $this->input->get_post('categorias_edad_final'));
                $this->DTO->addParameter('categorias_valido_desde', $this->input->get_post('categorias_valido_desde'));
                $this->DTO->addParameter('categorias_validacion', $this->input->get_post('categorias_validacion'));
                $this->DTO->addParameter('activo', true); // Siempre se agrega vivo
                // Ir al Bussiness Object
                $categoriasService = new CategoriasBussinessService();
                $categoriasService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updateCategorias() {
        try {

            if ($this->validateInputData($this->DTO, 'categorias', 'categorias_validation', 'v_categorias', 'updCategorias') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('categorias_codigo', $this->input->get_post('categorias_codigo'));
                $this->DTO->addParameter('categorias_descripcion', $this->input->get_post('categorias_descripcion'));
                $this->DTO->addParameter('categorias_edad_inicial', $this->input->get_post('categorias_edad_inicial'));
                $this->DTO->addParameter('categorias_edad_final', $this->input->get_post('categorias_edad_final'));
                $this->DTO->addParameter('categorias_valido_desde', $this->input->get_post('categorias_valido_desde'));
                $this->DTO->addParameter('categorias_validacion', $this->input->get_post('categorias_validacion'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo'));

                // Ir al Bussiness Object
                $categoriasService = new CategoriasBussinessService();
                $categoriasService->executeService('update', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function deleteCategorias() {
        try {
            if ($this->validateInputData($this->DTO, 'categorias', 'categorias_validation', 'v_categorias', 'delCategorias') === TRUE) {
                $this->DTO->addParameter('categorias_codigo', $this->input->get_post('categorias_codigo'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $categoriasService = new CategoriasBussinessService();
                $categoriasService->executeService('delete', $this->DTO);
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
        $this->parseParameters('categorias_');
        // ya que podria no haberse enviado y estar no definido
        $paisesId = $this->fixParameter('categorias_codigo', 'null', NULL);

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
            if (isset($paisesId) && ($operationId == 'read' || !isset($operationId) || $operationId === FALSE)) {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_READ);
                $this->readCategorias();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchCategorias();
            }
        } else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updateCategorias();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->deleteCategorias();
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addCategorias();
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
