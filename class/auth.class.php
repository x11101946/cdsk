<?php

define('AUTH_LOGIN', true);
define('AUTH_LOGOUT', false);
define('USER_ADM', 2);
define('USER_CLIENT', 1);
define('USER_NONE', 0);

/*
 * Class: AuthGate - handling user authentication and access
 */

class AuthGate {

    private $m_bStatus = AUTH_LOGOUT;
    private $m_iStatus = 0;
    private $m_arrAccess = array();
    public $m_arrUser = array();

    function __construct($p_dbConn, $p_strLogout = '') {
        if ($p_strLogout) {
            $this->logout();
        }

        if ($_POST['fbform']['fbusrnam'] && $_POST['fbform']['fbusrpwd']) {
            $_SESSION['fbusrnam'] = $_POST['fbform']['fbusrnam'];
            $_SESSION['fbusrpwd'] = md5($_POST['fbform']['fbusrpwd']);
        }

        $arrUser = $p_dbConn->sqlHash("
            SELECT
                *
            FROM
                t_users
            WHERE
                vch_email='" . mysql_escape_string($_SESSION['fbusrnam']) . "'
                AND vch_password='" . mysql_escape_string($_SESSION['fbusrpwd']) . "'
        ");
        if (count($arrUser)) {
            $this->m_bStatus = AUTH_LOGIN;
            $this->m_iStatus = $arrUser[0]['i_parentid'];
            $this->m_arrUser = $arrUser[0];
            unset($this->m_arrUser['vch_password']);
            if ($_POST['fbform']['fbusrnam']) {
                $_SESSION['lastvisit']=$this->m_arrUser['dt_lastvisit'];
                $p_dbConn->sqlEmpty("
                UPDATE
                    t_users
                SET
                    dt_lastvisit=NOW();
                    ");
            }
            $this->m_arrUser['dt_lastvisit']=$_SESSION['lastvisit'];
            if ($_SESSION['REQUEST_URI']) {
                $strRedirect = $_SESSION['REQUEST_URI'];
                unset($_SESSION['REQUEST_URI']);
                header('Location: ' . $strRedirect);
                die();
            }
        } else {
            $this->m_bStatus = AUTH_LOGOUT;
            $this->m_iStatus = 0;
            $this->m_arrAccess = array();
        }
    }

    function logout($p_strURL = '') {
        unset($_SESSION['fbusrnam']);
        unset($_SESSION['fbusrpwd']);
        $this->m_bStatus = AUTH_LOGOUT;
        $this->m_iStatus = 0;
        $this->m_arrAccess = array();
        $strURL = $p_strURL != '' ? $p_strURL : $_SERVER['PHP_SELF'];
        header('Location: ' . $strURL);
        die();
    }

    function getStatus() {
        return $this->m_bStatus;
    }

    function getLevel() {
        return $this->m_iStatus;
    }

    function getAccess() {
        return $this->m_arrAccess;
    }

}
