<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para las operaciones CRUD para definir los valores de validacion para las categorias
 * de atletas , digase mayores , menores , etc , donde se indicara el peso relativo de una
 * con la otra , digamos pesara mas la que su record sea de mayor valor , por ejemplo mayores pesara mas que menores.
 *
 * @author $Author: aranape $
 * @since 17-May-2012
 * @version $Id: appCategoriasController.php 68 2014-03-09 10:19:20Z aranape $
 *
 * $Date: 2014-03-09 05:19:20 -0500 (dom, 09 mar 2014) $
 * $Rev: 68 $
 */
class appCategoriasController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    private function readAppCategoria() {

        try {
            if ($this->validateInputData($this->DTO, 'appcategorias', 'appcategorias_validation', 'v_appcategorias', 'getAppCategoria') === TRUE) {
                $this->DTO->addParameter('id', $this->input->get_post('appcat_codigo'));
                $this->DTO->addParameter('appcat_codigo', $this->input->get_post('appcat_codigo'));
                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));

                // Ir al Bussiness Object
                $appCategoriasService = new AppCategoriasBussinessService();
                $appCategoriasService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function fetchAppCategoria() {
        try {
            // Ir al Bussiness Object
            $appCategoriasService = new AppCategoriasBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);

            $appCategoriasService->executeService('list', $this->DTO);
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
        $this->parseParameters('appcat_');
        // ya que podria no haberse enviado y estar no definido
        $appCategoriasId = $this->fixParameter('appcat_codigo', 'null', NULL);

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
            if (isset($appCategoriasId) && ($operationId == 'read' || !isset($operationId) || $operationId === FALSE)) {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_READ);
                $this->readAppCategoria();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchAppCategoria();
            }
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
