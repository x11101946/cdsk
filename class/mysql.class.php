<?php

/**
 * Abstraction of MySQL database
 *
 * @author    B Wieczorkowski
 */
class MySQL {

// Containing db connection identifier. Usable at disconnecting
    private $dbLink;
// Connection status
    private $status;
// Database name
    private $dbName;
// Last query sent
    public $lastQuery;
// Number of queries
    public $numberOfQueries = 0;

    function __construct() {
        $this->connectTo(_HOST, _USER, _PASS, _DB);
    }

    function __destruct() {
        $this->disconnectFrom($this->dbLink);
    }

// Method sets connection to db
// Sets link, status and dbName values if successfull
    /*
      @param    string  $_host        Host address
      @param    string  $_username  User name
      @param    string  $_password       Access password
      @param    string  $_dbName      Name of db
      @return  boolean                True if connected, false if no
      @see      disconnectFrom(), $status, $dbName, $link
     */
    private function connectTo($_host, $_username, $_password, $_dbName) {
        $result = mysql_connect($_host, $_username, $_password);
        if (@mysql_select_db($_dbName)) {
            $this->status = true;
            mysql_query('SET NAMES utf8');
            $this->dbName = $_dbName;
            $this->dbLink = $result;
            return true;
        } else {
            $this->dbName = '';
            $this->dbLink = false;
            return $this->retError(__LINE__);
        }
    }

// Disconnect from db
    /*
      @access  public
      @param   string  $_link  db handler stored in dbLink
      @return  boolean
      see connectTo()
     */

    public function disconnectFrom($_link) {
        if (!@mysql_close($_link)) {
            return $this->retError(__LINE__);
        } else {
            $this->status = false;
            return true;
        }
    }

// Returns hash with query results
// Format of hash: [row number][field name]. Where row number is identical with original id field in db
    /*
      @access  public
      @param    string  $
      @return
      @see
     */

    public function sqlHashID($_query, $_dbName = false) {
        
    }

// Returns hash with query results
// Format of hash: [row number][field name]
    /*
     * @access  public
     * @param    string  $
     * @return
     * @see
     */

    public function sqlHash($_query) {
        $this->lastQuery = $_query;
        $this->numberOfQueries ++;
        $result = mysql_query($_query);
        if (@mysql_errno != 0)
            return $this->retError(__LINE__);
        if (@mysql_num_rows($result) > 0) {
            while ($fields = @mysql_fetch_assoc($result)) {
                $results[] = $fields;
            }
            $this->sqlClear($result);
            return $results;
        } else
            return array();
    }

// Returns 'row' as assiociation table containing specified values at fields' names
    /*
     * @access  public
     * @param    string  $
     * @return
     * @see
     */

    public function sqlRow($_query) {
        if (strpos(strtoupper($_query), 'LIMIT') === false)
            $_query .= ' LIMIT 0,1';
        $this->lastQuery = $_query;
        $this->numberOfQueries ++;
        $result = mysql_query($_query);
        if (@mysql_errno != 0)
            return $this->retError(__LINE__);
        $fields = @mysql_fetch_assoc($result);
        $this->sqlClear($result);
        return $fields;
    }

// Returns value of asked field
//  Caution: specify only one field in query, all remaining will be ingored!
    /*
     * @access  public
     * @param    string  $_query  Query for db.
     * @return  string
     */

    public function sqlField($_query) {
        if (strpos(toolSet::strToUpper($_query), 'LIMIT') === false)
            $_query .= ' LIMIT 0,1';
        $this->lastQuery = $_query;
        $this->numberOfQueries ++;
        $result = mysql_query($_query);
        if (@mysql_errno != 0)
            return $this->retError(__LINE__);
        $fields = @mysql_fetch_row($result);
        $this->sqlClear($result);
        return $fields[0];
    }

// 'Empty' query (with no results)
    /* INSERT, DELETE, UPDATE - returns true if successfull, fals if failed
     *
     * @access  public
     * @param    string  $_query  Query for db
     * @return  boolean           True if successfull ,false if error
     */

    public function sqlEmpty($_query) {
        $this->lastQuery = $_query;
        $this->numberOfQueries ++;
        @mysql_query($_query);
        if (@mysql_errno != 0) {
            return $this->retError(__LINE__);
        }
        return true;
    }

// Sending query to db
// Sets handle for first answer. To get next answers use method sqlAnswer()
    /*
     * @access  public
     * @param    string  $
     * @return
     * @see
     */

    public function sqlQuery($_query) {
        $this->lastQuery = $_query;
        $this->numberOfQueries ++;
        $result = @mysql_query($_query);
        if (@mysql_errno != 0) {
            return $this->retError(__LINE__);
        }
        return $result;
    }

// Gets and returns next lines of answer for a query.
// Query must be specified with sqlQuery().
    /*
     * @access  public
     * @param    string  $
     * @return
     * @see
     */

    public function sqlAnswer($_link) {
        $this->numberOfQueries ++;
        $result = @mysql_fetch_assoc($_link);
        if (@mysql_errno != 0) {
            return $this->retError(__LINE__);
        }
        return $result;
    }

// Returns hash with numbers of tables from db. Functions for URL solving.
    /* [table_name] = number due to def_table.
      @access  public
      @param    string  $  _dbName  Name of db
      @return  array            [table_name] = number due to def_table.
     */

    public function tableList($_dbName) {
        $result = @mysql_list_tables($_dbName);
        $i = 0;
        $_numery = $this->sqlHash('SELECT * FROM def_table');
        foreach ($_numery as $n) {
            $tbl[$n[name]] = $n[id];
        }
        while ($i < @mysql_num_rows($result)) {
            $name = @mysql_tablename($result, $i);
            $tables[$name] = $tbl[$name];
            $i++;
        }
        return $tables;
    }

// Clear query result
    /* mysql_free_result()
      @access  public
      @param    string  $
      @return
      @see
     */

    public function sqlClear($_link) {
        @mysql_free_result($_link);
        return true;
    }

// Assign values to errorno and error
    /*
      @access  private
      @return  boolean            True
      @see     clearError(), $errorno, $error
     */

    public function retError($line = 0, $level = E_USER_NOTICE) {
//    return new Blad(@mysql_errno(), @mysql_error(), __FILE__, $line, $level);
    }

// Returns index generated by last insert
    /*
      @access  private
      @return  integer
      @see     sqlEmpty()
     */

    public function getLastId() {
        return mysql_insert_id();
    }

}

?>