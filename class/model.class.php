<?php

class cdskModel {

    private $m_dbConn;
    private $m_arrChildren;
    private $m_iParentId;

    function __construct($p_dbConn, $p_iUserType) {
        $this->m_dbConn = $p_dbConn;
        $this->m_iParentId = $p_iUserType;
        $this->m_arrChildrenIds = array();
        if ($this->m_iParentId > 0) {
            $this->m_arrChildren=$this->m_dbConn->sqlHash("
                        SELECT
                            *
                        FROM
                            t_user
                        WHERE
                            i_parentid=" . $this->m_iParentId . "
                            ");
        }
    }

    public function insertUser($p_arrUser) {
        
    }

}
