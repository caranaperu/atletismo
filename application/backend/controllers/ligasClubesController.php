<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para la entidad de asignacion de personal, el caso
 * del fetch es especial ya que puede requerirse como representacion
 * del modelo fisico o como para lista incorporando datos basicos
 * de personal.
 *
 * Para poder diferenciar que operacion de negocio se requiere se espera que como
 * parametro de fetch venganun parametro _operationId con cualquiera de los siguientes
 * valores :
 *  fetchAll   - se requiere todos los empleados (personal) esten o no asociados a una gerencia
 *              ,subgerencia o jefatura. se usa el modelo de lista como salida
 *  fetchFree  - se requiere todos aquellos no asociados a ninguna gerencia,subgerencia,jefatura.
 *  fetchMatch - como es de esperarse require todas las asignaciones de una especifica gerencia,
 *               subgerencia,jefatura o combinacion de todos.
 *  fetch      - Este caso es el normal y requiere los datos del modelo fisico y tambien puede filtrarse
 *               una especifica gerencia,subgerencia,jefatura o combinacion de todos.
 *
 *
 * @author $Author: aranape $
 * @since 17-May-2012
 * @version $Id: ligasClubesController.php 54 2014-03-02 05:45:36Z aranape $
 *
 * $Date: 2014-03-02 00:45:36 -0500 (dom, 02 mar 2014) $
 * $Rev: 54 $
 */
class ligasClubesController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    private function readLigasClubes() {

        try {
            if ($this->validateInputData($this->DTO, 'ligasclubes', 'ligasclubes_validation', 'v_ligasclubes', 'getLigasClubes') === TRUE) {
                $this->DTO->addParameter('id', $this->input->get_post('ligasclubes_id'));
                $this->DTO->addParameter('ligasclubes_id', $this->input->get_post('ligasclubes_id'));
                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));

                // Ir al Bussiness Object
                $ligasClubesService = new LigasClubesBussinessService();
                $ligasClubesService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function deleteLigasClubes() {
        try {
            if ($this->validateInputData($this->DTO, 'ligasclubes', 'ligasclubes_validation', 'v_ligasclubes', 'delLigasClubes') === TRUE) {
                $this->DTO->addParameter('ligasclubes_id', $this->input->get_post('ligasclubes_id'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $ligasClubesService = new LigasClubesBussinessService();
                $ligasClubesService->executeService('delete', $this->DTO);
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
    private function fetchLigasClubes() {
        try {
            // Ir al Bussiness Object
            $ligasClubesService = new LigasClubesBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);

            $ligasClubesService->executeService('list', $this->DTO);
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addLigasClubes() {
        try {
            if ($this->validateInputData($this->DTO, 'ligasclubes', 'ligasclubes_validation', 'v_ligasclubes', 'addLigasClubes') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('ligas_codigo', $this->input->get_post('ligas_codigo'));
                $this->DTO->addParameter('clubes_codigo', $this->input->get_post('clubes_codigo'));
                $this->DTO->addParameter('ligasclubes_desde', $this->input->get_post('ligasclubes_desde'));
                $this->DTO->addParameter('ligasclubes_hasta', $this->input->get_post('ligasclubes_hasta'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo')); // Siempre se agrega
                // Ir al Bussiness Object
                $ligasClubesService = new LigasClubesBussinessService();
                $ligasClubesService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updLigasClubes() {
        try {
            if ($this->validateInputData($this->DTO, 'ligasclubes', 'ligasclubes_validation', 'v_ligasclubes', 'updLigasClubes') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('ligasclubes_id', $this->input->get_post('ligasclubes_id'));
                $this->DTO->addParameter('ligas_codigo', $this->input->get_post('ligas_codigo'));
                $this->DTO->addParameter('clubes_codigo', $this->input->get_post('clubes_codigo'));
                $this->DTO->addParameter('ligasclubes_desde', $this->input->get_post('ligasclubes_desde'));
                $this->DTO->addParameter('ligasclubes_hasta', $this->input->get_post('ligasclubes_hasta'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo')); // Siempre se agrega
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                // Ir al Bussiness Object
                $ligasClubesService = new LigasClubesBussinessService();
                $ligasClubesService->executeService('update', $this->DTO);
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
    $this->parseParameters(['ligasclubes_', 'ligas_', 'clubes_']);

        // ya que podria no haberse enviado y estar no definido
        $ligasclubesId = $this->fixParameter('ligasclubes_id', 'null', NULL);

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
            if (isset($ligasclubesId) && ($operationId == 'read' || !isset($operationId) || $operationId === FALSE)) {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_READ);
                $this->readLigasClubes();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchLigasClubes();
            }
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addLigasClubes();
        } else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updLigasClubes();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->deleteLigasClubes();
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
