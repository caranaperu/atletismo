<?php


if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Este DAO es especifico para el menu del sistema
 *
 * @author  $Author: aranape $
 * @since   06-JULIO-2016
 * @version $Id: SystemMenuDAO_mssql.php 142 2014-04-07 00:38:59Z aranape $
 * @history ''
 *
 * $Date: 2014-04-06 19:38:59 -0500 (dom, 06 abr 2014) $
 * $Rev: 142 $
 */
class SystemMenuDAO_mssql extends \app\common\dao\TSLAppBasicRecordDAO_postgre {

    /**
     * Constructor se puede indicar si las busquedas solo seran en registros activos.
     * @param boolean $activeSearchOnly
     */
    public function __construct($activeSearchOnly = TRUE) {
        parent::__construct($activeSearchOnly);
    }

    /**
     * El orden ya esta prefijado ignorara cual parametro en ese sentido.
     *
     * @see \TSLBasicRecordDAO::getFetchQuery()
     */
    protected function getFetchQuery(\TSLDataModel &$record = NULL, \TSLRequestConstraints &$constraints = NULL, $subOperation = NULL) {
        // Si la busqueda permite buscar solo activos e inactivos
        $sql = 'select sys_systemcode,menu_id,menu_codigo,menu_descripcion,menu_accesstype,menu_parent_id,menu_orden,activo,xmin as "versionId" from  tb_sys_menu ';

        $where = $constraints->getFilterFieldsAsString();
        // Para sql server driver los campos no pueden ir entre comillas
        $where = str_replace('"', '', $where);

        if ($this->activeSearchOnly == TRUE) {
            // Solo activos
            $sql .= ' where activo=1 ';
            if (strlen($where) > 0) {
                $sql .= ' and '.$where;
            }
        } else {
            if (strlen($where) > 0) {
                $sql .= ' where ' . $where;
            }
        }
        $sql .= ' order by menu_parent_id,menu_orden';

        return $sql;
    }

    /**
     * @see \TSLBasicRecordDAO::getRecordQuery()
     */
    protected function getRecordQuery($id) {
        $sql = 'select sys_systemcode,menu_id,menu_codigo,menu_descripcion,menu_accesstype,menu_parent_id,menu_orden,activo,xmin as "versionId" from  tb_sys_menu ';
        $sql .= 'where menu_id=' . $id;
        return $sql;
    }

    /**
     * @see \TSLBasicRecordDAO::getRecordQueryByCode()
     */
    protected function getRecordQueryByCode($code) {
        $sql = 'select sys_systemcode,menu_id,menu_codigo,menu_descripcion,menu_accesstype,menu_parent_id,menu_orden,activo,xmin as "versionId" from  tb_sys_menu ';
        $sql .= 'where menu_codigo=' . $code;
        return $sql;
    }

    /***********************************************************
     * Por ahora no se usan
     * ********************************************************/
    protected function getAddRecordQuery(\TSLDataModel &$record, \TSLRequestConstraints &$constraints = NULL) {
        return NULL;
    }

    protected function getDeleteRecordQuery($id, $versionId) {
        return NULL;
    }

    protected function getUpdateRecordQuery(\TSLDataModel &$record) {
        return NULL;
    }

}

?>