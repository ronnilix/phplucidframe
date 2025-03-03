<?php
/**
 * This file is part of the PHPLucidFrame library.
 * Core utility for the database layer. Basic functioning of the database system.
 *
 * @package     PHPLucidFrame\Core
 * @since       PHPLucidFrame v 1.0.0
 * @copyright   Copyright (c), PHPLucidFrame.
 * @link        http://phplucidframe.com
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE
 */

use LucidFrame\Core\Database;
use LucidFrame\Core\QueryBuilder;

/**
 * @internal
 * @ignore
 *
 * Return the current database namespace
 * if $namespace is not provided, $lc_defaultDbSource will be returned
 * if $lc_defaultDbSource is empty, `default` will be returned
 *
 * @param string $namespace The given namespace
 * @return string The database namespace
 */
function db_namespace($namespace = null)
{
    return _app('db')->getNamespace($namespace);
}

/**
 * @internal
 * @ignore
 *
 * Return the database configuration of the given namespace
 * @param string $namespace Namespace of the configuration to read from
 * @return array The array of database configuration
 */
function db_config($namespace = null)
{
    return _app('db')->getConfig($namespace);
}

/**
 * Return the database engine of the given namespace
 * @param string $namespace Namespace of the configuration to read from
 * @return string Database engine name
 */
function db_engine($namespace = null)
{
    return _app('db')->getDriver($namespace);
}

/**
 * Return the database host name of the given namespace
 * @param string $namespace Namespace of the configuration to read from
 * @return string Database host name
 */
function db_host($namespace = null)
{
    return _app('db')->getHost($namespace);
}

/**
 * Return the database name of the given namespace
 * @param string $namespace Namespace of the configuration to read from
 * @return string Database name
 */
function db_name($namespace = null)
{
    return _app('db')->getName($namespace);
}

/**
 * Return the database username of the given namespace
 * @param string $namespace Namespace of the configuration to read from
 * @return string Database username
 */
function db_user($namespace = null)
{
    return _app('db')->getUser($namespace);
}

/**
 * Return the database table prefix of the given namespace
 * @param string $namespace Namespace of the configuration to read from
 * @return string The table prefix
 */
function db_prefix($namespace = null)
{
    return _app('db')->getPrefix($namespace);
}

/**
 * Return the database collation of the given namespace
 * @param string $namespace Namespace of the configuration to read from
 * @return string Database collation
 */
function db_collation($namespace = null)
{
    return _app('db')->getCollation($namespace);
}

/**
 * @internal
 * @ignore
 * Check and get the database configuration settings
 * @param string $namespace Namespace of the configuration to read from
 * @return array
 */
function db_prerequisite($namespace = null)
{
    $db = _app('db');
    $namespace = $db->getNamespace($namespace);

    if ($db->getHost($namespace) && $db->getUser($namespace) && $db->getName($namespace)) {
        return $db->getConfig($namespace);
    } else {
        _header(400);
        throw new \InvalidArgumentException('Required to configure <code class="inline">db</code> in <code class="inline">/inc/parameters/'._cfg('env').'.php</code>.');
    }
}

/**
 * Switch to the given database from the currently active database
 * @param string $namespace Namespace of the configuration to read from
 * @return void
 */
function db_switch($namespace = null)
{
    _app('db', new Database($namespace));
}

/**
 * @deprecated
 * Sets the default client character set
 *
 * @param  string $charset The charset to be set as default.
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function db_setCharset($charset)
{
    return db_query('SET NAMES "%s"', $charset);
}

/**
 * Closes a previously opened database connection
 * @return void
 */
function db_close()
{
    _app('db')->close();
}

/**
 * Make the generated query returned from the query executing functions
 * such as db_query, db_update, db_delete, etc. without executing the query
 * especially for debugging and testing. Call `db_prq(true)` before and `db_prq(false)` after.
 * `db_queryStr()` is same purpose but after executing the query.
 *
 * @param bool $enable Enable to return the query built; defaults to `true`.
 */
function db_prq($enable = true)
{
    _g('db_printQuery', $enable);
}

/**
 * Perform a query on the database
 *
 * @param string $sql SQL query string
 * @param array $args Array of placeholders and their values
 *     array(
 *       ':placeholder1' => $value1,
 *       ':placeholder2' => $value2
 *     )
 * The prefix colon ":" for placeholder is optional
 *
 * @return mixed PDOStatement|boolean|string Returns PDOStatement on success or FALSE on failure
 */
function db_query($sql, $args = array())
{
    return _app('db')->query($sql, $args);
}

/**
 * Get the last executed SQL string or one of the executed SQL strings by providing the index
 *
 * @param  int The index number of the query returned; if not given, the last query is returned
 * @return string Return the built and executed SQL string
 */
function db_queryStr()
{
    return _app('db')->getQueryStr();
}

/**
 * @deprecated
 * Escapes special characters in a string for use in a SQL statement,
 * taking into account the current charset of the connection
 *
 * @param  string $str An escaped string
 * @return string
 */
function db_escapeString($str)
{
    return $str;
}

/**
 * @deprecated
 * @internal
 * @ignore
 *
 * Quote and return the value if it is string; otherwise return as it is
 * This is used for array_map()
 */
function db_escapeStringMulti($val)
{
    $val = db_escapeString($val);

    return is_numeric($val) ? $val : '"'.$val.'"';
}

/**
 * Returns a string description of the last error
 * @return string
 */
function db_error()
{
    return _app('db')->getError();
}

/**
 * Returns the error code for the most recent MySQLi function call
 * @return int
 */
function db_errorNo()
{
    return _app('db')->getErrorCode();
}

/**
 * Gets the number of rows in a result
 * @param  PDOStatement $result
 * @return int Returns the number of rows in the result set.
 */
function db_numRows($result)
{
    return _app('db')->getNumRows($result);
}

/**
 * Fetch a result row as an associative, a numeric array, or both
 * @param  PDOStatement $result
 * @return array An array that corresponds to the fetched row or
 *   NULL if there are no more rows for the result set represented by the result parameter.
 */
function db_fetchArray($result)
{
    return _app('db')->fetchArray($result);
}

/**
 * Fetch a result row as an associative array
 * @param  PDOStatement $result
 * @return array An associative array that corresponds to the fetched row or NULL if there are no more rows.
 */
function db_fetchAssoc($result)
{
    return _app('db')->fetchAssoc($result);
}

/**
 * Returns the current row of a result set as an object
 * @param  PDOStatement $result
 * @return object An object that corresponds to the fetched row or NULL if there are no more rows in resultset.
 */
function db_fetchObject($result)
{
    return _app('db')->fetchObject($result);
}

/**
 * Returns the auto generated id used in the last query
 * @return int The value of the `AUTO_INCREMENT` field that was updated by the previous query;
 *  `0` if there was no previous query on the connection or if the query did not update an `AUTO_INCREMENT` value.
 */
function db_insertId()
{
    return _app('db')->getInsertId();
}

/**
 * Returns the generated slug used in the last query
 * @return string The last inserted slug
 */
function db_insertSlug()
{
    return session_get('lastInsertSlug');
}

/**
 * Initialize a query builder to perform a SELECT query on the database
 *
 * @param string $table The table name
 * @param string $alias The optional table alias
 *
 * @return object QueryBuilder
 */
function db_select($table, $alias = null)
{
    return new QueryBuilder($table, $alias);
}

/**
 * Perform a count query on the database and return the count
 *
 * @param string        $arg1 The SQL query string or table name
 * @param string|array  $arg2 The field name to count on
 *   or the array of placeholders and their values if the first argument is SQL
 *
 *      array(
 *          ':placeholder1' => $value1,
 *          ':placeholder2' => $value2
 *      )
 *
 * @param string|null   $arg3 The field alias if the first argument is table name
 *   or the second argument is field name
 *
 * @return int|QueryBuilder The result count or QueryBuilder
 */
function db_count($arg1, $arg2 = null, $arg3 = null)
{
    return _app('db')->getCount($arg1, $arg2, $arg3);
}

/**
 * Initialize a query builder to perform a MAX query on the database
 *
 * @param string $table The table name
 * @param string $field The field name to find max
 * @param string $alias The optional field alias; defaults to "max"
 *
 * @return object QueryBuilder
 */
function db_max($table, $field, $alias = null)
{
    $qb = new QueryBuilder($table);

    return $qb->max($field, $alias ? $alias : 'max');
}

/**
 * Initialize a query builder to perform a MIN query on the database
 *
 * @param string $table The table name
 * @param string $field The field name to find min
 * @param string $alias The optional field alias; defaults to "min"
 *
 * @return object QueryBuilder
 */
function db_min($table, $field, $alias = null)
{
    $qb = new QueryBuilder($table);

    return $qb->min($field, $alias ? $alias : 'min');
}

/**
 * Initialize a query builder to perform a SUM query on the database
 *
 * @param string $table The table name
 * @param string $field The field name to find sum
 * @param string $alias The optional field alias; defaults to "sum"
 *
 * @return object QueryBuilder
 */
function db_sum($table, $field, $alias = null)
{
    $qb = new QueryBuilder($table);

    return $qb->sum($field, $alias ? $alias : 'sum');
}

/**
 * Initialize a query builder to perform an AVG query on the database
 *
 * @param string $table The table name
 * @param string $field The field name to find average
 * @param string $alias The optional field alias; defaults to "avg"
 *
 * @return object QueryBuilder
 */
function db_avg($table, $field, $alias = null)
{
    $qb = new QueryBuilder($table);

    return $qb->avg($field, $alias ? $alias : 'avg');
}

/**
 * Perform a query on the database and return the first field value only.
 *
 * It adds the `LIMIT 1` clause if the query has no record limit
 * This will be useful for `COUNT()`, `MAX()`, `MIN()` queries
 *
 * @param string $sql The SQL query string
 * @param array $args The array of placeholders and their values
 *
 *      array(
 *          ':placeholder1' => $value1,
 *          ':placeholder2' => $value2
 *      )
 *
 * @return mixed The value of the first field
 */
function db_fetch($sql, $args = array())
{
    return _app('db')->fetchColumn($sql, $args);
}

/**
 * Perform a query on the database and return the first result row as object
 *
 * It adds the `LIMIT 1` clause if the query has no record limit
 * This is useful for one-row fetching. No need explicit `db_query()` call as this invokes it internally.
 *
 * @param string $sql The SQL query string
 * @param array $args The array of placeholders and their values
 *
 *      array(
 *          ':placeholder1' => $value1,
 *          ':placeholder2' => $value2
 *      )
 *
 * @return object The result object
 */
function db_fetchResult($sql, $args = array())
{
    return _app('db')->fetchResult($sql, $args);
}

/**
 * Perform a query on the database and return the array of all results
 *
 * @param string $sql The SQL query string
 * @param array $args The array of placeholders and their values
 * @param int $resultType The optional constant indicating what type of array should be produced.
 *   The possible values for this parameter are the constants
 *   **LC_FETCH_OBJECT**, **LC_FETCH_ASSOC**, or **LC_FETCH_ARRAY**.
 *   Default to **LC_FETCH_OBJECT**.
 *
 * @return array|boolean The result array of objects or associated arrays or index arrays.
 *   If the result not found, return false.
 */
function db_extract($sql, $args = array(), $resultType = LC_FETCH_OBJECT)
{
    return _app('db')->fetchAll($sql, $args, $resultType);
}

/**
 * Get the full table name with prefix
 * @param string $table The table name with or without prefix
 * @return string The table name with prefix
 */
function db_table($table)
{
    return _app('db')->getTable($table);
}

/**
 * Check the table has slug field
 *
 * @param string  $table    The table name without prefix
 * @param boolean $useSlug  True to include the slug field or False to not exclude it
 * @return boolean true or false
 */
function db_tableHasSlug($table, $useSlug = true)
{
    return _app('db')->hasSlug($table, $useSlug);
}

/**
 * Check the table has timestamp fields
 *
 * @param string  $table    The table name without prefix
 * @return boolean true or false
 */
function db_tableHasTimestamps($table)
{
    return _app('db')->hasTimestamps($table);
}

/**
 * Handy db insert/update operation
 * @param string $table The table name without prefix
 * @param array $data The array of data field names and values
 *
 *      array(
 *          'fieldNameToSlug' => $valueToSlug,
 *          'fieldName1' => $fieldValue1,
 *          'fieldName2' => $fieldValue2
 *      )
 *
 * @param int $id The value for ID field to update
 * @param boolean $useSlug TRUE to include the slug field or FALSE to not exclude it
 *   The fourth argument can be provided here if you want to omit this.
 * @param array $condition The condition for the UPDATE query. If you provide this,
 *   the first field of `$data` will not be built for condition
 *
 * ### Example
 *
 *     array(
 *       'fieldName1' => $value1,
 *       'fieldName2' => $value2
 *     )
 *
 * @return bool|int|mixed For insert, returns inserted id on success or FALSE on failure;
 *    For update, returns updated id on success or FALSE on failure
 */
function db_save($table, $data = array(), $id = 0, $useSlug = true, array $condition = array())
{
    if ($id) {
        $data = array_merge(array('id' => $id), $data);

        if (db_update($table, $data, $useSlug, $condition)) {
            return $id;
        }

        return false;
    } else {
        return db_insert($table, $data, $useSlug);
    }
}

if (!function_exists('db_insert')) {
    /**
     * Handy MYSQL insert operation
     *
     * @param string $table The table name without prefix
     * @param array $data The array of data field names and values
     *
     *      array(
     *          'fieldNameToSlug' => $valueToSlug,
     *          'fieldName1' => $fieldValue1,
     *          'fieldName2' => $fieldValue2
     *      )
     *
     * @param boolean $useSlug True to include the slug field or False to not exclude it
     * @return mixed Returns inserted id on success or FALSE on failure
     */
    function db_insert($table, $data = array(), $useSlug = true)
    {
        QueryBuilder::clearBindValues();

        if (count($data) == 0) {
            return false;
        }

        $db = _app('db');

        $table = db_table($table);

        # Invoke the hook db_insert_[table_name] if any
        $hook = 'db_insert_' . strtolower($table);
        if (function_exists($hook)) {
            return call_user_func_array($hook, array($table, $data, $useSlug));
        }

        # if slug is already provided in the data array, use it
        if (array_key_exists('slug', $data)) {
            $slug = _slug($data['slug']);
            $data['slug'] = $slug;
            session_set('lastInsertSlug', $slug);
            $useSlug = false;
        }

        $dsm = $db->schemaManager;
        if (is_object($dsm) && $dsm->isLoaded()) {
            foreach ($data as $field => $value) {
                $fieldType = $db->schemaManager->getFieldType($table, $field);
                if (is_array($value) && $fieldType == 'array') {
                    $data[$field] = serialize($value);
                } elseif (is_array($value) && $fieldType == 'json') {
                    $jsonValue = json_encode($value);
                    $data[$field] = $jsonValue ? $jsonValue : null;
                } elseif ($fieldType == 'boolean') {
                    $data[$field] = $value ? 1 : 0;
                }
            }
        }

        $fields = array_keys($data);
        $dataValues = array_values($data);

        if (db_tableHasSlug($table, $useSlug)) {
            $fields[] = 'slug';
        }

        if (db_tableHasTimestamps($table)) {
            if (!array_key_exists('created', $data)) {
                $fields[] = 'created';
            }
            if (!array_key_exists('updated', $data)) {
                $fields[] = 'updated';
            }
        }

        $fields = array_unique($fields);

        $sqlFields = implode(', ', $fields);
        $placeHolders = implode(', ', array_fill(0, count($fields), '?'));
        $values = array();
        $i = 0;

        # escape the data
        foreach ($dataValues as $val) {
            if ($i == 0 && $useSlug) {
                $slug = $val;
            }

            $values[] = is_null($val) ? null : $val;

            $i++;
        }

        if (db_tableHasSlug($table, $useSlug)) {
            $slug = _slug($slug, $table);
            session_set('lastInsertSlug', $slug);
            $values[] = $slug;
        }

        if (db_tableHasTimestamps($table)) {
            if (!array_key_exists('created', $data)) {
                $values[] = date('Y-m-d H:i:s');
            }
            if (!array_key_exists('updated', $data)) {
                $values[] = date('Y-m-d H:i:s');
            }
        }

        $sql = sprintf('INSERT INTO  %s (%s) VALUES (%s)', QueryBuilder::quote($table), $sqlFields, $placeHolders);

        return db_query($sql, $values) ? db_insertId() : false;
    }
}

if (!function_exists('db_update')) {
    /**
     * Handy MYSQL update operation
     *
     * @param string $table The table name without prefix
     * @param array $data The array of data field names and values
     *   The first field/value pair will be used as condition when you do not provide the fourth argument
     *
     *     array(
     *       'condition_field'      => $conditionFieldValue,
     *       'field_name_to_slug'   => $valueToSlug,
     *       'field_name_1'         => $value1,
     *       'field_name_2'         => $value2
     *     )
     *
     * @param boolean $useSlug TRUE to include the slug field or FALSE to not exclude it
     *   The fourth argument can be provided here if you want to omit this.
     * @param array $condition The condition for the UPDATE query. If you provide this,
     *   the first field of `$data` will not be built for condition but used to be updated
     *
     * ### Example
     *
     *     array(
     *       'field_name_1'    => $value1,
     *       'field_name_2 >=' => $value2,
     *       'field_name_3'    => NULL
     *     )
     *
     *   array of OR condition syntax,
     *
     *     array(
     *       '$or' => array(
     *         'field_name_1'    => $value1,
     *         'field_name_2 >=' => $value2,
     *         'field_name_3'    => NULL
     *       )
     *     )
     *
     * @return boolean Returns TRUE on success or FALSE on failure
     */
    function db_update($table, $data = array(), $useSlug = true, array $condition = array())
    {
        QueryBuilder::clearBindValues();

        if (count($data) == 0) {
            return false;
        }

        $db = _app('db');

        if (func_num_args() === 3 && (gettype($useSlug) === 'string' || is_array($useSlug))) {
            $condition = $useSlug;
            $useSlug = true;
        }

        $table = db_table($table);

        # Invoke the hook db_update_[table_name] if any
        $hook = 'db_update_' . strtolower($table);
        if (function_exists($hook)) {
            return call_user_func_array($hook, array($table, $data, $useSlug, $condition));
        }

        # if slug is already provided in the data array, use it
        if (array_key_exists('slug', $data)) {
            $slug = _slug($data['slug']);
            $data['slug'] = $slug;
            session_set('lastInsertSlug', $slug);
            $useSlug = false;
        }

        $fields     = array();
        $slug       = '';
        $cond       = '';
        $i          = 0;
        $slugIndex  = 1;

        if ($condition) {
            $slugIndex = 0;
        }

        $dsm = $db->schemaManager;
        foreach ($data as $field => $value) {
            if ($i === 0 && !$condition) {
                # $data[0] is for PK condition, but only if $condition is not provided
                $cond = array($field => $value); # for PK condition
                $i++;
                continue;
            }

            if (is_object($dsm) && $dsm->isLoaded()) {
                $fieldType = $dsm->getFieldType($table, $field);
                if (is_array($value) && $fieldType == 'array') {
                    $value = serialize($value);
                } elseif (is_array($value) && $fieldType == 'json') {
                    $jsonValue = json_encode($value);
                    $value = $jsonValue ? $jsonValue : null;
                } elseif ($fieldType == 'boolean') {
                    $value = $value ? 1 : 0;
                }
            }

            $fields[$field] = is_null($value) ? null : $value;

            if ($i === $slugIndex && $useSlug === true) {
                # $data[1] is slug
                $slug = $value;
            }

            $i++;
        }

        # must have condition
        # this prevents unexpected update happened to all records
        if ($cond || $condition) {
            $clause = '';
            $notCond = array();
            $values = array();

            if ($cond && is_array($cond) && count($cond)) {
                QueryBuilder::clearBindValues();
                list($clause, $values) = db_condition($cond);
                $notCond = array(
                    '$not' => $cond
                );
            } elseif ($condition && is_array($condition) && count($condition)) {
                QueryBuilder::clearBindValues();
                list($clause, $values) = db_condition($condition);
                $notCond = array(
                    '$not' => $condition
                );
            }

            if (empty($clause)) {
                return false;
            }

            if (db_tableHasSlug($table, $useSlug)) {
                $slug = _slug($slug, $table, $notCond);
                session_set('lastInsertSlug', $slug);
                $fields['slug'] = $slug;
            }

            if (db_tableHasTimestamps($table)) {
                $fields['updated'] = date('Y-m-d H:i:s');
            }

            $sql = 'UPDATE ' . QueryBuilder::quote($table) . ' SET ';
            foreach ($fields as $key => $value) {
                $placeholder = ':upd_' . $key;
                $sql .= sprintf('`%s` = %s, ', $key, $placeholder);
                $values[$placeholder] = $value;
            }
            $sql = rtrim($sql, ', ');
            $sql .= ' WHERE ' . $clause;

            return db_query($sql, $values) ? true : false;
        }

        return false;
    }
}

if (!function_exists('db_delete')) {
    /**
     * Handy MYSQL delete operation for single record.
     * It checks FK delete RESTRICT constraint, then SET deleted if it cannot be deleted
     *
     * @param string $table Table name without prefix
     * @param array $condition The array of condition for delete - field names and values, for example
     *
     *   array of AND condition syntax,
     *
     *     array(
     *       'field_name_1'    => $value1,
     *       'field_name_2 >=' => $value2,
     *       'field_name_3'    => NULL
     *     )
     *
     *   array of OR condition syntax,
     *
     *     array(
     *       '$or' => array(
     *         'field_name_1'    => $value1,
     *         'field_name_2 >=' => $value2,
     *         'field_name_3'    => NULL
     *       )
     *     )
     *
     * @param boolean $softDelete Soft delete or not
     * @return boolean Returns TRUE on success or FALSE on failure
     */
    function db_delete($table, array $condition = array(), $softDelete = false)
    {
        QueryBuilder::clearBindValues();

        $table = db_table($table);

        # Invoke the hook db_delete_[table_name] if any
        $hook = 'db_delete_' . strtolower($table);
        if (function_exists($hook)) {
            return call_user_func_array($hook, array($table, $condition));
        }

        $values = array();

        if (is_array($condition)) {
            list($condition, $values) = db_condition($condition);
        }

        if ($condition) {
            $condition = ' WHERE '.$condition;
        }

        if ($softDelete) {
            $sql = 'UPDATE '. QueryBuilder::quote($table) . '
                    SET `deleted` = :deleted ' . $condition . '
                    LIMIT 1';
            $values[':deleted'] = date('Y-m-d H:i:s');
            if (_g('db_printQuery')) {
                return $sql;
            }

            return db_query($sql, $values) ? true : false;
        }

        $sql = 'DELETE FROM ' . QueryBuilder::quote($table) . $condition . ' LIMIT 1';
        if (_g('db_printQuery')) {
            return $sql;
        }

        ob_start(); # to capture error return
        db_query($sql, $values);
        $return = ob_get_clean();
        if ($return) {
            # If there is FK delete RESTRICT constraint, make soft delete
            if (db_errorNo() == 1451) {
                if (db_tableHasTimestamps($table)) {
                    $sql = 'UPDATE '. QueryBuilder::quote($table) . '
                            SET `deleted` = :deleted ' . $condition . '
                            LIMIT 1';
                    $values[':deleted'] = date('Y-m-d H:i:s');

                    return db_query($sql, $values);
                }

                return false;
            } else {
                echo $return;

                return false;
            }
        }

        return db_errorNo() == 0;
    }
}

if (!function_exists('db_delete_multi')) {
    /**
     * Handy MYSQL delete operation for multiple records
     *
     * @param string $table Table name without prefix
     * @param array $condition The array of condition for delete - field names and values, for example
     *
     *   array of AND condition syntax,
     *
     *     array(
     *       'field_name_1'    => $value1,
     *       'field_name_2 >=' => $value2,
     *       'field_name_3'    => NULL
     *     )
     *
     *   array of OR condition syntax,
     *
     *     array(
     *       '$or' => array(
     *         'field_name_1'    => $value1,
     *         'field_name_2 >=' => $value2,
     *         'field_name_3'    => NULL
     *       )
     *     )
     *
     * @param boolean $softDelete Soft delete or not
     * @return boolean Returns TRUE on success or FALSE on failure
     */
    function db_delete_multi($table, $condition = null, $softDelete = false)
    {
        QueryBuilder::clearBindValues();

        $table = db_table($table);

        # Invoke the hook db_delete_[table_name] if any
        $hook = 'db_delete_multi_' . strtolower($table);
        if (function_exists($hook)) {
            return call_user_func_array($hook, array($table, $condition));
        }

        $values = array();
        if (is_array($condition)) {
            list($condition, $values) = db_condition($condition);
        }

        if ($condition) {
            $condition = ' WHERE '. $condition;
        }

        if ($softDelete) {
            $sql = 'UPDATE '. QueryBuilder::quote($table) . '
                    SET `deleted` = :deleted ' . $condition;
            $values[':deleted'] = date('Y-m-d H:i:s');
            if (_g('db_printQuery')) {
                return $sql;
            }

            return db_query($sql, $values);
        }

        $sql = 'DELETE FROM ' . QueryBuilder::quote($table) . $condition;
        if (_g('db_printQuery')) {
            return $sql;
        }

        ob_start(); # to capture error return
        db_query($sql, $values);
        $return = ob_get_clean();

        if ($return && db_errorNo() > 0) {
            # if there is any error
            return false;
        }

        return db_errorNo() == 0;
    }
}

if (!function_exists('db_truncate')) {
    /**
     * Truncate the table
     * @param string $table Table name without prefix
     */
    function db_truncate($table)
    {
        $table = db_table($table);

        db_query('TRUNCATE ' . QueryBuilder::quote($table));
    }
}

/**
 * Set foreign key check
 * @param int $flag 0 or 1
 */
function db_setForeignKeyCheck($flag)
{
    db_query('SET FOREIGN_KEY_CHECKS =' . $flag);
}

/**
 * Enable foreign key check
 */
function db_enableForeignKeyCheck()
{
    db_setForeignKeyCheck(1);
}

/**
 * Disable foreign key check
 */
function db_disableForeignKeyCheck()
{
    db_setForeignKeyCheck(0);
}

/**
 * @internal
 * @ignore
 *
 * Build the SQL WHERE clause from the various condition arrays
 *
 * @param array $cond The condition array, for example
 *
 *    array(
 *       'field_name_1'    => $value1,
 *       'field_name_2 >=' => $value2,
 *       'field_name_3     => NULL
 *    )
 *
 * @param string $type The condition type "AND" or "OR"; Default is "AND"
 *
 * @return array The built condition WHERE AND/OR
 *     [0] string The built condition WHERE AND/OR clause
 *     [1] array The values to bind in the condition
 */
function db_condition($cond = array(), $type = 'AND')
{
    return QueryBuilder::buildCondition($cond, $type);
}

/**
 * Build the SQL WHERE clause AND condition from array of conditions
 *
 * @param array $condition The condition array, for example
 *
 *     array(
 *       'field_name_1'    => $value1,
 *       'field_name_2 >=' => $value2,
 *       'field_name_3     => NULL
 *     )
 *
 * ### Operators allowed in condition array
 *     >, >=, <, <=, !=, between, nbetween, like, like%%, like%~, like~%, nlike, nlike%%, nlike%~, nlike~%
 *
 * @return array The built condition WHERE AND
 *     [0] string The built condition WHERE AND clause
 *     [1] array The values to bind in the condition
 */
function db_and($condition = array())
{
    return db_condition($condition, 'AND');
}

/**
 * Build the SQL WHERE clause OR condition from array of conditions
 *
 * @param array $condition The condition array, for example
 *
 *     array(
 *       'field_name_1'    => $value1,
 *       'field_name_2 >=' => $value2,
 *       'field_name_3     => NULL
 *     )
 *
 * ### Operators allowed in condition array
 *     >, >=, <, <=, !=, between, nbetween, like, like%%, like%~, like~%, nlike, nlike%%, nlike%~, nlike~%
 *
 * @return array The built condition WHERE OR
 *     [0] string The built condition WHERE OR clause
 *     [1] array The values to bind in the condition
 */
function db_or($condition = array())
{
    return db_condition($condition, 'OR');
}

/**
 * Start a new transaction
 */
function db_transaction()
{
    db_query('SET AUTOCOMMIT=0');
    db_query('START TRANSACTION');
}

/**
 * Commit the current transaction, making its changes permanent.
 */
function db_commit()
{
    db_query('COMMIT');
    db_query('SET AUTOCOMMIT=1');
}

/**
 * Roll back the current transaction, canceling its changes.
 */
function db_rollback()
{
    db_query('ROLLBACK');
    db_query('SET AUTOCOMMIT=1');
}

/**
 * Get raw expression string
 * @param string $exp Expression
 * @param array $values The values to be replaced with specifier in $exp. See vsprintf.
 * @return string
 */
function db_raw($exp, array $values = array())
{
    return QueryBuilder::raw($exp, $values);
}

/**
 * @internal
 * @ignore
 *
 * Build the SQL expression like SUM, MAX, AVG, etc
 *
 * @param string $field The field name
 * @param mixed $value The value for the field
 * @param string $exp The SQL expression
 * @return array The condition array, for example
 *
 *     array(
 *       'value'  => $value,
 *       'exp >=' => $exp,
 *       'field   => $field
 *     )
 *
 */
function db_exp($field, $value, $exp = '')
{
    return _app('db')->exp($field, $value, $exp);
}

/**
 * Get a single entity result where the primary key matches the value passed in as the second parameter for the table name in the first parameter.
 * @param string $table The table name to fetch data from
 * @param int $id The value of the primary key to match
 * @return object|null
 */
function db_find($table, $id)
{
    $qb = db_select($table)->where()->condition('id', $id);

    if (db_tableHasTimestamps($table)) {
        $qb->condition('deleted', null);
    }

    $entity = $qb->getSingleResult();

    if ($entity) {
        $schema = _schema(_cfg('defaultDbSource'), true);
        $entity = (array) $entity;
        if ($schema && isset($schema[$table])) {
            foreach ($entity as $field => $value) {
                switch ($schema[$table][$field]['type']) {
                    case 'int':
                    case 'integer':
                    case 'smallint':
                    case 'mediumint':
                    case 'bigint':
                        $entity[$field] = is_numeric($value) ? (int) $value : $value;
                        break;

                    case 'boolean':
                        $entity[$field] = (bool) $value;
                        break;

                    case 'array':
                        $entity[$field] = $value ? unserialize($value) : array();
                        break;

                    case 'json':
                        $entity[$field] = $value ? json_decode($value, true) : array();
                        break;
                }
            }
        }
    }

    return (object) $entity;
}

/**
 * Get a single entity result where the primary key matches the value passed in as the second parameter for the table name in the first parameter OR throws 404 if any result is not found.
 * @param string $table The table name to fetch data from
 * @param int $id The value of the primary key to match
 * @return mixed
 */
function db_findOrFail($table, $id)
{
    $result = db_find($table, $id);

    if (!$result) {
        _page404();
    }

    return $result;
}

/**
 * Get array of data row objects with pagination result
 * @param string $table The table name to fetch data from
 * @param array $condition The condition array for query
 *
 *    array(
 *      'fieldName1'    => $value1,
 *      'fieldName2 >=' => $value2,
 *      'fieldName3     => NULL
 *    )
 *
 *  OR
 *
 *    array(
 *      'fieldName1'    => $value1,
 *      'fieldName2 >=' => $value2,
 *      'fieldName3     => NULL,
 *      '$or' => array(
 *          'fieldName4'    => array(1, 2, 3)
 *          'fieldName4 <'  => 10
 *      )
 *    )
 *
 * @param array $orderBy The order by clause for query
 *
 *     array(
 *       'field'  => 'asc|desc'
 *     )
 *
 * @param array $pagerOptions Array of key/value pairs to Pager options
 * @return array [QueryBuilder, Pager, total]
 */
function db_findWithPager($table, array $condition = array(), array $orderBy = array(), array $pagerOptions = array())
{
    if (db_tableHasTimestamps($table)) {
        $condition['deleted'] = null;
    }

    # Count query for the pager
    $countQuery = db_count($table);
    if (!empty($condition)) {
        $countQuery->where($condition);
    }
    $rowCount = $countQuery->fetch();

    # Prerequisite for the Pager
    $pagerOptions = array_merge(array(
        'itemsPerPage' => _cfg('itemsPerPage'),
        'pageNumLimit' => _cfg('pageNumLimit'),
        'ajax' => true
    ), $pagerOptions);

    $pager = _pager();
    $pager->set('total', $rowCount);
    foreach ($pagerOptions as $name => $value) {
        $pager->set($name, $value);
    }
    $pager->calculate();

    # Simple list query
    $qb = db_select($table);
    if (!empty($condition)) {
        $qb->where($condition);
    }
    $qb->limit($pager->get('offset'), $pager->get('itemsPerPage'));

    foreach ($orderBy as $field => $sort) {
        $qb->orderBy($field, $sort);
    }

    return array($qb, $pager, $rowCount);
}

/**
 * Get data of a table by condition
 * @param string $table The table name to fetch data from
 * @param array $condition The condition array for query
 *
 *    array(
 *      'fieldName1'    => $value1,
 *      'fieldName2 >=' => $value2,
 *      'fieldName3     => NULL
 *    )
 *
 *  OR
 *
 *    array(
 *      'fieldName1'    => $value1,
 *      'fieldName2 >=' => $value2,
 *      'fieldName3     => NULL,
 *      '$or' => array(
 *          'fieldName4'    => array(1, 2, 3)
 *          'fieldName4 <'  => 10
 *      )
 *    )
 *
 * @param array $orderBy The order by clause for query
 *
 *     array(
 *       'field'  => 'asc|desc'
 *     )
 *
 * @param int $limit The number of records to return; No limit by default
 * @return array
 */
function db_findBy($table, array $condition, array $orderBy = array(), $limit = null)
{
    if (db_tableHasTimestamps($table)) {
        $condition['deleted'] = null;
    }

    $qb = db_select($table)->where($condition);

    foreach ($orderBy as $field => $sort) {
        $qb->orderBy($field, $sort);
    }

    if ($limit) {
        $qb->limit($limit);
    }

    return $qb->getResult();
}

/**
 * Get one record of a table by condition
 * @param string $table The table name to fetch data from
 * @param array $condition The condition array for query
 *
 *    array(
 *      'fieldName1'    => $value1,
 *      'fieldName2 >=' => $value2,
 *      'fieldName3     => NULL
 *    )
 *
 *  OR
 *
 *    array(
 *      'fieldName1'    => $value1,
 *      'fieldName2 >=' => $value2,
 *      'fieldName3     => NULL,
 *      '$or' => array(
 *          'fieldName4'    => array(1, 2, 3)
 *          'fieldName4 <'  => 10
 *      )
 *    )
 *
 * @param array $orderBy The order by clause for query
 *
 *     array(
 *       'field'  => 'asc|desc'
 *     )
 *
 * @return object|null
 */
function db_findOneBy($table, array $condition, array $orderBy = array())
{
    $result = db_findBy($table, $condition, $orderBy, 1);
    if (!empty($result)) {
        return $result[0];
    }

    return null;
}

/**
 * Get one record of a table by condition or throw 404 if not found
 * @param string $table The table name to fetch data from
 * @param array $condition The condition array for query
 *
 *    array(
 *      'fieldName1'    => $value1,
 *      'fieldName2 >=' => $value2,
 *      'fieldName3     => NULL
 *    )
 *
 *  OR
 *
 *    array(
 *      'fieldName1'    => $value1,
 *      'fieldName2 >=' => $value2,
 *      'fieldName3     => NULL,
 *      '$or' => array(
 *          'fieldName4'    => array(1, 2, 3)
 *          'fieldName4 <'  => 10
 *      )
 *    )
 *
 * @param array $orderBy The order by clause for query
 *
 *     array(
 *       'field'  => 'asc|desc'
 *     )
 *
 * @return object|null
 */
function db_findOneByOrFail($table, array $condition, array $orderBy = array())
{
    $result = db_findOneBy($table, $condition, $orderBy);
    if (empty($result)) {
        _page404();
    }

    return $result;
}

/**
 * Get all records for a table
 * @param string $table The table name to fetch data from
 * @param array $fields The list of the field names to select
 * @param array $orderBy The order by clause for query
 *
 *     array(
 *       'field'  => 'asc|desc'
 *     )
 *
 * @return array
 */
function db_findAll($table, $fields = array(), $orderBy = array())
{
    $qb = db_select($table);

    if (db_tableHasTimestamps($table)) {
        $qb->where()->condition('deleted', null);
    }

    if (!empty($fields)) {
        $qb->fields($table, $fields);
    }

    if (!empty($orderBy)) {
        foreach ($orderBy as $field => $sort) {
            $qb->orderBy($field, $sort);
        }
    }

    return $qb->getResult();
}
