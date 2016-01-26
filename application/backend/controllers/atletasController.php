<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para las operaciones CRUD de los atletas.
 *
 * @author $Author: aranape $
 * @since 17-May-2012
 * @version $Id: atletasController.php 81 2014-03-25 10:06:48Z aranape $
 *
 * $Date: 2014-03-25 05:06:48 -0500 (mar, 25 mar 2014) $
 * $Rev: 81 $
 */
class atletasController extends app\common\controller\TSLAppDefaultController {

    public function __construct() {
        parent::__construct();
    }

    private function readAtletas() {

        try {
            if ($this->validateInputData($this->DTO, 'atletas', 'atletas_validation', 'v_atletas', 'getAtletas') === TRUE) {
                $this->DTO->addParameter('atletas_codigo', $this->input->get_post('atletas_codigo'));
                $this->DTO->addParameter('id', $this->input->get_post('atletas_codigo'));
                $this->DTO->addParameter('verifyExist', $this->input->get_post('verifyExist'));

                // Ir al Bussiness Object
                $atletasService = new AtletasBussinessService();
                $atletasService->executeService('read', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function fetchAtletas() {
        try {
            // Ir al Bussiness Object
            $atletasService = new AtletasBussinessService();

            $constraints = &$this->DTO->getConstraints();

            // Procesamos los constraints
            $this->getConstraintProcessor()->process($_REQUEST, $constraints);

            $atletasService->executeService('list', $this->DTO);
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function addAtletas() {
        try {

            if ($this->validateInputData($this->DTO, 'atletas', 'atletas_validation', 'v_atletas', 'addAtletas') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('atletas_codigo', $this->input->get_post('atletas_codigo'));
                $this->DTO->addParameter('atletas_ap_paterno', $this->input->get_post('atletas_ap_paterno'));
                $this->DTO->addParameter('atletas_ap_materno', $this->input->get_post('atletas_ap_materno'));
                $this->DTO->addParameter('atletas_nombres', $this->input->get_post('atletas_nombres'));
                $this->DTO->addParameter('paises_codigo', $this->input->get_post('paises_codigo'));
                $this->DTO->addParameter('atletas_nro_documento', $this->input->get_post('atletas_nro_documento'));
                $this->DTO->addParameter('atletas_nro_pasaporte', $this->input->get_post('atletas_nro_pasaporte'));
                $this->DTO->addParameter('atletas_fecha_nacimiento', $this->input->get_post('atletas_fecha_nacimiento'));
                $this->DTO->addParameter('atletas_direccion', $this->input->get_post('atletas_direccion'));
                $this->DTO->addParameter('atletas_telefono_casa', $this->input->get_post('atletas_telefono_casa'));
                $this->DTO->addParameter('atletas_telefono_celular', $this->input->get_post('atletas_telefono_celular'));
                $this->DTO->addParameter('atletas_email', $this->input->get_post('atletas_email'));
                $this->DTO->addParameter('atletas_sexo', $this->input->get_post('atletas_sexo'));
                $this->DTO->addParameter('atletas_observaciones', $this->input->get_post('atletas_observaciones'));
                $this->DTO->addParameter('atletas_talla_ropa_buzo', $this->input->get_post('atletas_talla_ropa_buzo'));
                $this->DTO->addParameter('atletas_talla_ropa_poloshort', $this->input->get_post('atletas_talla_ropa_poloshort'));
                $this->DTO->addParameter('atletas_talla_zapatillas', $this->input->get_post('atletas_talla_zapatillas'));
                $this->DTO->addParameter('atletas_norma_zapatillas', $this->input->get_post('atletas_norma_zapatillas'));
                $this->DTO->addParameter('atletas_url_foto', $this->input->get_post('atletas_url_foto'));

                $this->DTO->addParameter('activo', true); // Siempre se agrega vivo
                // Ir al Bussiness Object
                $atletasService = new AtletasBussinessService();
                $atletasService->executeService('add', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function updateAtletas() {
        try {

            if ($this->validateInputData($this->DTO, 'atletas', 'atletas_validation', 'v_atletas', 'updAtletas') === TRUE) {
                // Seteamos parametros en el DTO
                $this->DTO->addParameter('atletas_codigo', $this->input->get_post('atletas_codigo'));
                $this->DTO->addParameter('atletas_ap_paterno', $this->input->get_post('atletas_ap_paterno'));
                $this->DTO->addParameter('atletas_ap_materno', $this->input->get_post('atletas_ap_materno'));
                $this->DTO->addParameter('atletas_nombres', $this->input->get_post('atletas_nombres'));
                $this->DTO->addParameter('paises_codigo', $this->input->get_post('paises_codigo'));
                $this->DTO->addParameter('atletas_nro_documento', $this->input->get_post('atletas_nro_documento'));
                $this->DTO->addParameter('atletas_nro_pasaporte', $this->input->get_post('atletas_nro_pasaporte'));
                $this->DTO->addParameter('atletas_fecha_nacimiento', $this->input->get_post('atletas_fecha_nacimiento'));
                $this->DTO->addParameter('atletas_direccion', $this->input->get_post('atletas_direccion'));
                $this->DTO->addParameter('atletas_telefono_casa', $this->input->get_post('atletas_telefono_casa'));
                $this->DTO->addParameter('atletas_telefono_celular', $this->input->get_post('atletas_telefono_celular'));
                $this->DTO->addParameter('atletas_email', $this->input->get_post('atletas_email'));
                $this->DTO->addParameter('atletas_sexo', $this->input->get_post('atletas_sexo'));
                $this->DTO->addParameter('atletas_observaciones', $this->input->get_post('atletas_observaciones'));
                $this->DTO->addParameter('atletas_talla_ropa_buzo', $this->input->get_post('atletas_talla_ropa_buzo'));
                $this->DTO->addParameter('atletas_talla_ropa_poloshort', $this->input->get_post('atletas_talla_ropa_poloshort'));
                $this->DTO->addParameter('atletas_talla_zapatillas', $this->input->get_post('atletas_talla_zapatillas'));
                $this->DTO->addParameter('atletas_norma_zapatillas', $this->input->get_post('atletas_norma_zapatillas'));
                $this->DTO->addParameter('atletas_url_foto', $this->input->get_post('atletas_url_foto'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));
                $this->DTO->addParameter('activo', $this->input->get_post('activo'));

                // Ir al Bussiness Object
                $atletasService = new AtletasBussinessService();
                $atletasService->executeService('update', $this->DTO);
            }
        } catch (Exception $ex) {
            $outMessage = &$this->DTO->getOutMessage();
            // TODO: Internacionalizar.
            $processError = new TSLProcessErrorMessage($ex->getCode(), 'Error Interno', $ex);
            $outMessage->addProcessError($processError);
        }
    }

    private function deleteAtletas() {
        try {
            if ($this->validateInputData($this->DTO, 'atletas', 'atletas_validation', 'v_atletas', 'delAtletas') === TRUE) {
                $this->DTO->addParameter('atletas_codigo', $this->input->get_post('atletas_codigo'));
                $this->DTO->addParameter('versionId', $this->input->get_post('versionId'));

                // Ir al Bussiness Object
                $atletasService = new AtletasBussinessService();
                $atletasService->executeService('delete', $this->DTO);
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
        $this->parseParameters(['atletas_','paises_']);
        // ya que podria no haberse enviado y estar no definido
        $atletasId = $this->fixParameter('atletas_codigo', 'null', NULL);

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
            if (isset($atletasId) && ($operationId == 'read' || !isset($operationId) || $operationId === FALSE)) {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_READ);
                $this->readAtletas();
            } else {
                $this->DTO->setOperation(TSLIDataTransferObj::OP_FETCH);
                $this->fetchAtletas();
            }
        } else if ($op == 'upd') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_UPDATE);
            $this->updateAtletas();
        } else if ($op == 'del') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_DELETE);
            $this->deleteAtletas();
        } else if ($op == 'add') {
            $this->DTO->setOperation(TSLIDataTransferObj::OP_ADD);
            $this->addAtletas();
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
