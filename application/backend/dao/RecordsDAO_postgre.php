<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Este DAO es especifico el mantenimiento de los records de diversa indole, sean
 * mundiales,nacionales,etc.
 *
 * @author  $Author: aranape $
 * @since   06-FEB-2013
 * @version $Id: RecordsDAO_postgre.php 307 2014-07-16 02:17:13Z aranape $
 * @history ''
 *
 * $Date: 2014-07-15 21:17:13 -0500 (mar, 15 jul 2014) $
 * $Rev: 307 $
 */
class RecordsDAO_postgre extends \app\common\dao\TSLAppBasicRecordDAO_postgre {

    /**
     * Constructor se puede indicar si las busquedas solo seran en registros activos.
     * @param boolean $activeSearchOnly
     */
    public function __construct($activeSearchOnly = TRUE) {
        parent::__construct(FALSE); // se permite siempre la busqueda incluyendo activos o no.
    }

    /**
     * @see \TSLBasicRecordDAO::getDeleteRecordQuery()
     */
    protected function getDeleteRecordQuery($id, $versionId) {
        return 'select * from ( select sp_records_delete( ' . $id . '::integer,null::character varying,' . $versionId . '::integer)  as updins) as ans where updins is not null';
    }

    /**
     * @see \TSLBasicRecordDAO::getAddRecordQuery()
     */
    protected function getAddRecordQuery(\TSLDataModel &$record, \TSLRequestConstraints &$constraints = NULL) {
        /* @var $record  RecordsModel  */

        $sql = 'select sp_records_save_record(NULL::integer,' .
                '\'' . $record->get_records_tipo_codigo() . '\'::character varying,' .
                $record->get_atletas_resultados_id() . '::integer,' .
                '\'' . $record->get_categorias_codigo() . '\'::character varying,' .
                ($record->get_records_id_origen() ? $record->get_records_id_origen() : 'NULL') . '::integer,' .
                '\'' . $record->get_records_protected() . '\'::boolean,' .
                '\'' . ($record->getActivo() != TRUE ? '0' : '1') . '\'::boolean,' .
                '\'' . $record->getUsuario() . '\'::character varying,NULL::integer,0::BIT);';
        return $sql;
    }

    /**
     * @see \TSLBasicRecordDAO::getFetchQuery()
     */
    protected function getFetchQuery(\TSLDataModel &$record = NULL, \TSLRequestConstraints &$constraints = NULL, $subOperation = NULL) {

        if ($subOperation == 'fetchJoined') {
            $sql = 'select * from (
                    select
                    records_id,
                    records_tipo_codigo,
                    rec.atletas_resultados_id,
                    rec.categorias_codigo,
                    apppruebas_codigo,
                    apppruebas_descripcion,
                    eatl.atletas_codigo,
                    atletas_nombre_completo,
                    atl.atletas_sexo,
                    fn_get_marca_normalizada_tonumber(fn_get_marca_normalizada_totext(atletas_resultados_resultado, um.unidad_medida_codigo, cp.competencias_pruebas_manual, pv.apppruebas_factor_manual), um.unidad_medida_codigo) as numb_resultado,
                    atletas_resultados_resultado,
                    ciudades_altura,
                    coalesce((case when apppruebas_viento_individual = TRUE THEN eatl.atletas_resultados_viento ELSE competencias_pruebas_viento END),0.00) as competencias_pruebas_viento,
                    competencias_pruebas_fecha,
                    co.competencias_descripcion || \' / \' || ciudades_descripcion || \' / \'  || paises_descripcion as lugar,
                    --co.competencias_descripcion,
                    --ciudades_descripcion,
                    --paises_descripcion,
                    rec.activo,
                    rec.xmin as "versionId"
                    from tb_records rec
                inner join tb_atletas_resultados eatl on eatl.atletas_resultados_id = rec.atletas_resultados_id
                inner join tb_competencias_pruebas cp on cp.competencias_pruebas_id = eatl.competencias_pruebas_id
                inner join tb_atletas atl on eatl.atletas_codigo = atl.atletas_codigo
                inner join tb_pruebas pr on pr.pruebas_codigo = cp.pruebas_codigo
                inner join tb_app_pruebas_values pv on pv.apppruebas_codigo = pr.pruebas_generica_codigo
                inner join tb_competencias co on co.competencias_codigo = cp.competencias_codigo
                inner join tb_ciudades ciu on ciu.ciudades_codigo = co.ciudades_codigo
                inner join tb_paises pa on pa.paises_codigo = ciu.paises_codigo
                inner join tb_pruebas_clasificacion cl on cl.pruebas_clasificacion_codigo = pv.pruebas_clasificacion_codigo
                inner join tb_unidad_medida um on um.unidad_medida_codigo = cl.unidad_medida_codigo
                ) rec';
        } else {
            $sql = 'select records_id,records_tipo_codigo,atletas_resultados_id,categorias_codigo,records_id_origen,records_protected,activo,xmin as "versionId" from  tb_records rec';
        }


        if ($this->activeSearchOnly == TRUE) {
            // Solo activos
            $sql .= ' where "rec.activo"=TRUE ';
        }

        // Que pasa si el campo a buscar existe en ambas partes del join?
        $where = $constraints->getFilterFieldsAsString();
        //  echo $where;
        if ($this->activeSearchOnly == TRUE) {
            if (strlen($where) > 0) {
                $sql .= ' and ' . $where;
            }
        } else {
            if (strlen($where) > 0) {
                $sql .= ' where ' . $where;
            }
        }

        if (isset($constraints)) {
            $orderby = $constraints->getSortFieldsAsString();
            if ($orderby !== NULL) {
                $sql .= ' order by ' . $orderby;
            }
        }

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
        return 'select records_id,records_tipo_codigo,atletas_resultados_id,categorias_codigo,records_id_origen,records_protected,activo,' .
                'xmin as "versionId" from tb_records where "records_id" =  ' . $code;
    }

    /**
     * Aqui el id es el codigo
     * @see \TSLBasicRecordDAO::getUpdateRecordQuery()
     */
    protected function getUpdateRecordQuery(\TSLDataModel &$record) {
        /* @var $record  RecordsModel  */
        $sql = 'select * from (select sp_records_save_record(' .
                $record->get_records_id() . '::integer,' .
                '\'' . $record->get_records_tipo_codigo() . '\'::character varying,' .
                $record->get_atletas_resultados_id() . '::integer,' .
                '\'' . $record->get_categorias_codigo() . '\'::character varying,' .
                ($record->get_records_id_origen() ? $record->get_records_id_origen() : 'NULL') . '::integer,' .
                '\'' . $record->get_records_protected() . '\'::boolean,' .
                '\'' . ($record->getActivo() != TRUE ? '0' : '1') . '\'::boolean,' .
                '\'' . $record->get_Usuario_mod() . '\'::varchar,' .
                $record->getVersionId() . '::integer,1::BIT) as insupd) as ans where insupd is not null;';
        return $sql;
    }

    protected function getLastSequenceOrIdentityQuery(\TSLDataModel &$record = NULL) {
    //    return 'SELECT currval(\'tb_records_records_id_seq\')';

        // Dado que pueden grabarse multiples records basados en uno no usamos directamente  el sequence
        // ya que no neceariamente retornara el id correcto para el ingresado ya que devolveria el ultimo agregado que
        // no es necesariamente el principal.
        /* @var $record  RecordsModel */
        $sql = 'SELECT records_id from tb_records where atletas_resultados_id= ' . $record->get_atletas_resultados_id() . ' and  categorias_codigo = \'' . $record->get_categorias_codigo() .
                '\' and records_tipo_codigo  = \'' . $record->get_records_tipo_codigo() . '\' ';
        return $sql;
    }

}

?>