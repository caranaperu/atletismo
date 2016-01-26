<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Este DAO es especifico el mantenimiento de los atletas.
 *
 * @author  $Author: aranape $
 * @since   06-FEB-2013
 * @version $Id: AtletasDAO_postgre.php 305 2014-07-16 02:14:00Z aranape $
 * @history ''
 *
 * $Date: 2014-07-15 21:14:00 -0500 (mar, 15 jul 2014) $
 * $Rev: 305 $
 */
class AtletasDAO_postgre extends \app\common\dao\TSLAppBasicRecordDAO_postgre {

    /**
     * Constructor se puede indicar si las busquedas solo seran en registros activos.
     * @param boolean $activeSearchOnly
     */
    public function __construct($activeSearchOnly = TRUE) {
        parent::__construct($activeSearchOnly);
    }

    /**
     * @see \TSLBasicRecordDAO::getDeleteRecordQuery()
     */
    protected function getDeleteRecordQuery($id, $versionId) {
        return 'delete from tb_atletas where atletas_codigo = \'' . $id . '\'  and xmin =' . $versionId;
    }

    /**
     * @see \TSLBasicRecordDAO::getAddRecordQuery()
     */
    protected function getAddRecordQuery(\TSLDataModel &$record, \TSLRequestConstraints &$constraints = NULL) {
        /* @var $record  AtletasModel  */

        $sql = 'select sp_atletas_save_record(' .
                '\'' . $record->get_atletas_codigo() . '\'::character varying,' .
                '\'' . $record->get_atletas_ap_paterno() . '\'::character varying,' .
                '\'' . $record->get_atletas_ap_materno() . '\'::character varying,' .
                '\'' . $record->get_atletas_nombres() . '\'::character varying,' .
                '\'' . $record->get_atletas_sexo() . '\'::character,' .
                '\'' . $record->get_atletas_nro_documento() . '\'::character varying,' .
                '\'' . $record->get_atletas_nro_pasaporte() . '\'::character varying,' .
                '\'' . $record->get_paises_codigo() . '\'::character varying,' .
                '\'' . $record->get_atletas_fecha_nacimiento() . '\'::date,' .
                '\'' . $record->get_atletas_telefono_casa() . '\'::character varying,' .
                '\'' . $record->get_atletas_telefono_celular() . '\'::character varying,' .
                '\'' . $record->get_atletas_email() . '\'::character varying,' .
                '\'' . $record->get_atletas_direccion() . '\'::character varying,' .
                '\'' . $record->get_atletas_observaciones() . '\'::character varying,' .
                '\'' . $record->get_atletas_talla_ropa_buzo() . '\'::character varying,' .
                '\'' . $record->get_atletas_talla_ropa_poloshort() . '\'::character varying,' .
                ($record->get_atletas_talla_zapatillas() == '' ? 'NULL' : ('\'' . $record->get_atletas_talla_zapatillas()) . '\'') . '::numeric,' .
                '\'' . $record->get_atletas_norma_zapatillas() . '\'::character varying,' .
                '\'' . $record->get_atletas_url_foto() . '\'::character varying,' .
                '\'' . $record->getActivo() . '\'::boolean,' .
                '\'' . $record->getUsuario() . '\'::character varying,' .
                'null::integer, 0::BIT)';
        return $sql;
    }

    /**
     * @see \TSLBasicRecordDAO::getFetchQuery()
     */
    protected function getFetchQuery(\TSLDataModel &$record = NULL, \TSLRequestConstraints &$constraints = NULL, $subOperation = NULL) {
        if ($subOperation == 'fetchForList') {
            // USamos un campo virtual que es atletas_agno el cual es computado , por ende se usa un select
            /// extermo para que el where lo pueda usar.
            $sql = 'select atletas_codigo ,atletas_nombre_completo,atletas_sexo,' .
                    'paises_codigo from  tb_atletas a';
        } else if ($subOperation == 'fetchForListByPrueba') {
            // USamos un campo virtual que es atletas_agno el cual es computado , por ende se usa un select
            /// extermo para que el where lo pueda usar.
            $sql = 'select distinct a.atletas_codigo ,atletas_nombre_completo from tb_atletas a
                inner join tb_atletas_resultados ar on ar.atletas_codigo = a.atletas_codigo
                inner join tb_competencias_pruebas cp on cp.competencias_pruebas_id = ar.competencias_pruebas_id
                inner join tb_pruebas pc on pc.pruebas_codigo = cp.pruebas_codigo';
        } else if ($subOperation == 'fetchForListByPruebaGenerica') {
            $sql= 'select distinct a.atletas_codigo,atletas_nombre_completo from tb_atletas a
                inner join tb_atletas_resultados ar on ar.atletas_codigo = a.atletas_codigo
                inner join tb_competencias_pruebas cp on cp.competencias_pruebas_id = ar.competencias_pruebas_id
                inner join tb_pruebas pc on pc.pruebas_codigo = cp.pruebas_codigo
                inner join tb_app_pruebas_values pv on pv.apppruebas_codigo = pc.pruebas_generica_codigo';
        } else {
            // USamos un campo virtual que es atletas_agno el cual es computado , por ende se usa un select
            /// extermo para que el where lo pueda usar.
            $sql = 'select *  from (select atletas_codigo ,atletas_ap_paterno ,atletas_ap_materno,atletas_nombres,atletas_nombre_completo,atletas_sexo,' .
                    'atletas_nro_documento,atletas_nro_pasaporte,paises_codigo,atletas_fecha_nacimiento,EXTRACT(YEAR FROM atletas_fecha_nacimiento)::CHARACTER VARYING as atletas_agno,atletas_telefono_casa,' .
                    'atletas_telefono_celular,atletas_email,atletas_direccion,atletas_observaciones,atletas_talla_ropa_buzo,atletas_talla_ropa_poloshort,atletas_talla_zapatillas,atletas_norma_zapatillas,' .
                    'atletas_url_foto,activo,xmin as "versionId" from  tb_atletas ) a';
        }
        if ($this->activeSearchOnly == TRUE) {
            // Solo activos
            $sql .= ' where a.activo=TRUE ';
        }

        $where = $constraints->getFilterFieldsAsString();
        if (strlen($where) > 0) {
            $sql .= ' and ' . $where;
        }

        if (isset($constraints)) {
            $orderby = $constraints->getSortFieldsAsString();
            if ($orderby !== NULL) {
                $sql .= ' order by ' . $orderby;
            }
        }

        $sql = str_replace('"atletas_codigo"', 'a.atletas_codigo', $sql);

        // Chequeamos paginacion
        $startRow = $constraints->getStartRow();
        $endRow = $constraints->getEndRow();

        if ($endRow > $startRow) {
            $sql .= ' LIMIT ' . ($endRow - $startRow) . ' OFFSET ' . $startRow;
        }

        $sql = str_replace('like', 'ilike', $sql);
        return $sql;
    }

    /**
     * @see \TSLBasicRecordDAO::getRecordQuery()
     */
    protected function getRecordQuery($id) {
        // en este caso el codigo es la llave primaria
        return $this->getRecordQueryByCode($id);
    }

    /**
     * @see \TSLBasicRecordDAO::getRecordQueryByCode()
     */
    protected function getRecordQueryByCode($code) {
        return 'select atletas_codigo ,atletas_ap_paterno ,atletas_ap_materno,atletas_nombres,atletas_nombre_completo,atletas_sexo,' .
                'atletas_nro_documento,atletas_nro_pasaporte,paises_codigo,atletas_fecha_nacimiento,EXTRACT(YEAR FROM atletas_fecha_nacimiento)::CHARACTER VARYING as atletas_agno,atletas_telefono_casa,' .
                'atletas_telefono_celular,atletas_email,atletas_direccion,atletas_observaciones,atletas_talla_ropa_buzo,atletas_talla_ropa_poloshort,' .
                'atletas_talla_zapatillas,atletas_norma_zapatillas,atletas_url_foto,activo,' .
                'xmin as "versionId" from tb_atletas where atletas_codigo =  \'' . $code . '\'';
    }

    /**
     * Aqui el id es el codigo
     * @see \TSLBasicRecordDAO::getUpdateRecordQuery()
     */
    protected function getUpdateRecordQuery(\TSLDataModel &$record) {
        /* @var $record  AtletasModel  */
        $sql = 'select * from (select sp_atletas_save_record(' .
                '\'' . $record->get_atletas_codigo() . '\'::character varying,' .
                '\'' . $record->get_atletas_ap_paterno() . '\'::character varying,' .
                '\'' . $record->get_atletas_ap_materno() . '\'::character varying,' .
                '\'' . $record->get_atletas_nombres() . '\'::character varying,' .
                '\'' . $record->get_atletas_sexo() . '\'::character,' .
                '\'' . $record->get_atletas_nro_documento() . '\'::character varying,' .
                '\'' . $record->get_atletas_nro_pasaporte() . '\'::character varying,' .
                '\'' . $record->get_paises_codigo() . '\'::character varying,' .
                '\'' . $record->get_atletas_fecha_nacimiento() . '\'::date,' .
                '\'' . $record->get_atletas_telefono_casa() . '\'::character varying,' .
                '\'' . $record->get_atletas_telefono_celular() . '\'::character varying,' .
                '\'' . $record->get_atletas_email() . '\'::character varying,' .
                '\'' . $record->get_atletas_direccion() . '\'::character varying,' .
                '\'' . $record->get_atletas_observaciones() . '\'::character varying,' .
                '\'' . $record->get_atletas_talla_ropa_buzo() . '\'::character varying,' .
                '\'' . $record->get_atletas_talla_ropa_poloshort() . '\'::character varying,' .
                ($record->get_atletas_talla_zapatillas() == '' ? 'NULL' : ('\'' . $record->get_atletas_talla_zapatillas()) . '\'') . '::numeric,' .
                '\'' . $record->get_atletas_norma_zapatillas() . '\'::character varying,' .
                '\'' . $record->get_atletas_url_foto() . '\'::character varying,' .
                '\'' . $record->getActivo() . '\'::boolean,' .
                '\'' . $record->get_Usuario_mod() . '\'::character varying,' .
                $record->getVersionId() . '::integer, 1::BIT) as insupd) as ans where insupd is not null;';
        return $sql;
    }

}

?>