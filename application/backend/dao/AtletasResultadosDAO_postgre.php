<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Este DAO es especifico para el mantenimiento de los detalles de los resultados de
 * de una prueba. basicamente es para las pruebas que componen una prueba combinada çno debe usarse para las pruebas normales.
 * A diferencia del manejo directo de resultados este maneja dualmente las pruebas (puede cambiar los datos)
 * y los resultados en si ya que DAO es para ser usado cuando se ingresan datos a traves del atleta y no a traves
 * de la competencia misma.
 *
 *
 * @author  $Author: aranape $
 * @since   06-FEB-2013
 * @version $Id: AtletasResultadosDAO_postgre.php 201 2014-06-23 22:39:43Z aranape $
 * @history ''
 *
 * $Date: 2014-06-23 17:39:43 -0500 (lun, 23 jun 2014) $
 * $Rev: 201 $
 */
class AtletasResultadosDAO_postgre extends \app\common\dao\TSLAppBasicRecordDAO_postgre {

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
        /* @var $record  AtletasResultadosModel  */
        $sql = 'select * from (select sp_atletas_resultados_delete_record(' .
                $id . '::integer,' .
                'false::boolean,' .
                'null::character varying,' .
                $versionId . '::integer)  as updins) as ans where updins is not null';
        return $sql;
    }

    /**
     * @see \TSLBasicRecordDAO::getAddRecordQuery()
     */
    protected function getAddRecordQuery(\TSLDataModel &$record, \TSLRequestConstraints &$constraints = NULL) {
        /* @var $record  AtletasResultadosModel  */

        $sql = 'select sp_atletas_resultados_save_record(' .
                'null::integer,' .
                '\'' . $record->get_atletas_codigo() . '\'::character varying,' .
                $record->get_competencias_pruebas_id() . '::integer,' .
                '\'' . $record->get_atletas_resultados_resultado() . '\'::character varying,' .
                ($record->get_atletas_resultados_puntos() == null ? '0' : $record->get_atletas_resultados_puntos()) . '::integer,' .
                ($record->get_atletas_resultados_puesto() == null ? 'null' : $record->get_atletas_resultados_puesto()) . '::integer,' .
                ($record->get_atletas_resultados_viento() == null ? 'null' : $record->get_atletas_resultados_viento()) . '::numeric,' .
                'false::boolean,' .
                '\'' . $record->getActivo() . '\'::boolean,' .
                '\'' . $record->getUsuario() . '\'::character varying,' .
                'null::integer,0::bit)';
        return $sql;
    }

    /**
     * @see \TSLBasicRecordDAO::getFetchQuery()
     */
    protected function getFetchQuery(\TSLDataModel &$record = NULL, \TSLRequestConstraints &$constraints = NULL, $subOperation = NULL) {

        if ($subOperation == 'fetchJoined') {

            $sql = 'select atletas_resultados_id,ar.competencias_pruebas_id,ar.atletas_codigo,
                        atletas_nombre_completo,atletas_resultados_resultado,atletas_resultados_puntos,
                        atletas_resultados_puesto,atletas_resultados_viento,ar.activo,ar.xmin as "versionId"
                        from  tb_atletas_resultados ar
                        inner join tb_atletas at on at.atletas_codigo = ar.atletas_codigo ';
        } else {
            $sql = 'select atletas_resultados_id,atletas_codigo,competencias_pruebas_id,atletas_resultados_resultado,atletas_resultados_puesto,atletas_resultados_puntos'
                    . ',atletas_resultados_viento,atletas_resultados_protected,'
                    . 'activo,xmin as "versionId" from  tb_atletas_resultados';
        }

        if ($this->activeSearchOnly == TRUE) {
            // Solo activos
            $sql .= ' where ar.activo=TRUE ';
        }

        // Que pasa si el campo a buscar existe en ambas partes del join?
        $where = $constraints->getFilterFieldsAsString();
        if (strlen($where) > 0) {
            if ($this->activeSearchOnly == TRUE) {
                $sql .= ' and ' . $where;
            } else {
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

        //  $sql = str_replace('"competencias_pruebas_id"', 'ar.competencias_pruebas_id', $sql);
        // echo $sql;
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
        return 'select atletas_resultados_id,atletas_codigo,competencias_pruebas_id,atletas_resultados_resultado,atletas_resultados_puesto,'
                . 'atletas_resultados_puntos,atletas_resultados_viento,atletas_resultados_protected,'
                . 'activo,xmin as "versionId" from  tb_atletas_resultados  ' .
                'where atletas_resultados_id =  ' . $code;
    }

    /**
     * Aqui el id es el codigo
     * @see \TSLBasicRecordDAO::getUpdateRecordQuery()
     */
    protected function getUpdateRecordQuery(\TSLDataModel &$record) {
        /* @var $record  AtletasResultadosModel  */

        $sql = 'select * from (select sp_atletas_resultados_save_record(' .
                $record->get_atletas_resultados_id() . '::integer,' .
                '\'' . $record->get_atletas_codigo() . '\'::character varying,' .
                $record->get_competencias_pruebas_id() . '::integer,' .
                '\'' . $record->get_atletas_resultados_resultado() . '\'::character varying,' .
                ($record->get_atletas_resultados_puntos() == null ? '0' : $record->get_atletas_resultados_puntos()) . '::integer,' .
                ($record->get_atletas_resultados_puesto() == null ? 'null' : $record->get_atletas_resultados_puesto()) . '::integer,' .
                ($record->get_atletas_resultados_viento() == null ? 'null' : $record->get_atletas_resultados_viento()) . '::numeric,' .
                'false::boolean,' .
                '\'' . $record->getActivo() . '\'::boolean,' .
                '\'' . $record->get_Usuario_mod() . '\'::character varying,' .
                $record->getVersionId() . '::integer,1::BIT)  as insupd) as ans where insupd is not null;';

        return $sql;
    }

    protected function getLastSequenceOrIdentityQuery(\TSLDataModel &$record = NULL) {
        // Por la estructura del sp , aqui si durante un update la ultima operacion
        // es el insert , en caso se modificara cuidad aqui ya qeu habria que cambiar el metodo.
        return 'SELECT currval(pg_get_serial_sequence(\'tb_atletas_resultados\', \'atletas_resultados_id\'));';
    }

}

?>