<?php

//error_reporting(E_ALL&~E_NOTICE);
require_once 'include/config.php';
require_once 'class/mysql.class.php';
require_once 'class/model.class.php';
require_once 'class/auth.class.php';
require_once 'class/simpletemplate.class.php';

$dbConn = new MySQL();

$objUser = new AuthGate($dbConn);
$objModel = new cdskModel($dbConn, $objUser->getLevel());

$strContent = '';
$strMessage = '';
$strAction = isset($_GET['action']) ? $_GET['action'] : '';

$strTemplate = 'home';
if ($objUser->m_arrUser['i_userid']) {
    $arrPlaceholders = array(
        'HOME_ACTION' => '?action=logout',
        'HOME_TEXT' => 'LOGOUT'
    );
} else {
    $arrPlaceholders = array(
        'HOME_ACTION' => '/',
        'HOME_TEXT' => 'HOME'
    );
}

if ($objUser->getStatus() == AUTH_LOGIN) {
    switch ($strAction) {
        case 'logout':
            $objUser->logout();
            break;
        case 'dashboard';
        default:
            if ($_POST['fbform']['fbusrnam']) {
                header('Location: index.php?action=dashboard');
                die();
            }
            $arrPlaceholders['PAGE_TITLE'] = 'Hello';
            if (!$objUser->getStatus()) {
                $strContent = 'Wrong email or password - please try again';
            } else {
                switch ($objUser->getLevel()) {
                    case '0';
                        // dashboard for parent
                        // list of children

                        $arrChildren = $dbConn->sqlHash("
                        SELECT
                            *
                        FROM
                            t_user
                        WHERE
                            i_parentid=" . $objUser->m_arrUser['i_userid'] . "
                            ");

                        //to avoid error if there are no children yet
                        $arrChildren[] = '0';
                        $hashChildren = array();
                        for ($i = 0; $i < count($arrChildren); $i++) {
                            $hashChildren[$arrChildren[$i]['i_userid']] = $arrChildren[$i];
                        }
                        // check if any kids claimed task completion
                        $arrTaskCompleted = $dbConn->sqlHash("
                        SELECT 
                            *
                        FROM
                            t_taskchild
                        WHERE
                            i_childid IN (" . join(',', $arrChildren) . ")
                        AND
                            dt_claimed>'" . $objUser->m_arrUser['dt_lastvisit'] . "'
                            ");
                        $strContent.='<p>Hello ' . $objUser->m_arrUser['vch_firstname'] . '!</p>';
                        $strContent.='<p>Since you last visit <b>' . $objUser->m_arrUser['dt_lastvisit'] . '</b> ';
                        if (count($arrTaskCompleted) == 0) {
                            $strContent.='no children claimed any tasks completed.';
                        } else {
                            foreach ($arrTaskCompleted as $arrTaskData) {
                                $strContent.='<br/>' . $hashChildren[$arrTaskData['i_childid']]['vch_firstname'] . ' has claimed that he has completed task.';
                            }
                        }
                        $strContent.='</p>';
                        $arrListOfTasks = $dbConn->sqlHash("
                            SELECT
                                *
                            FROM
                                t_tasks
                            LEFT JOIN
                                t_taskchild
                                ON(t_tasks.i_taskid=t_taskchild.i_taskid)
                            WHERE
                                i_userid=" . $objUser->m_arrUser['i_userid'] . "
                            ORDER BY
                                dt_deadline DESC,
                                dt_created DESC,
                                i_taskid
                            LIMIT 0,10
                            ");
                        if (count($arrListOfTasks) == 0) {
                            $strContent.='<p>There are no tasks yet.</p>';
                        }
                        foreach ($arrListOfTasks as $arrRecord) {
                            
                        }
                        break;
                    default;
                        // dashboard for child
                        break;
                }
            }
            $arrPlaceholders['CONTENT'] = $strContent;
            $strTemplate = 'main';
            break;
    }
} else {
    switch ($strAction) {
        case 'login':
            $arrPlaceholders['PAGE_TITLE'] = 'Login';
            $fForm = fopen('html/login.html', 'r');
            $strForm = fread($fForm, filesize('html/login.html'));
            $arrPlaceholders['CONTENT'] = $strForm;
            $strTemplate = 'main';
            break;
        case 'register':
            $arrPlaceholders['PAGE_TITLE'] = 'Register';
            $fForm = fopen('html/register.html', 'r');
            $strForm = fread($fForm, filesize('html/register.html'));
            $arrPlaceholders['CONTENT'] = $strForm;
            $strTemplate = 'main';
            break;
        case 'doregister':
            // check if user doesn't exist already
            $arrCheckUser = $dbConn->sqlHash("SELECT * FROM t_users WHERE vch_email='" . mysql_escape_string($_POST['email']) . "'");
            if (count($arrCheckUser) == 0) {
                $strQuery = "
                    INSERT INTO
                        t_users
                    SET
                        vch_email='" . mysql_escape_string($_POST['email']) . "',
                        vch_password='" . md5($_POST['password']) . "',
                        vch_firstname='" . mysql_escape_string($_POST['fname']) . "',
                        vch_surname='" . mysql_escape_string($_POST['sname']) . "',
                        i_parentid=0,
                        dt_registered=NOW()
                    ";
                // echo $strQuery;
                $dbConn->sqlEmpty($strQuery);
                $tplThanks = new SimpleTemplate('html/registered.html');
                $tplThanks->param('FNAME', $_POST['fname']);
            } else {
                $tplThanks = new SimpleTemplate('html/userexists.html');
                $tplThanks->param('FNAME', $_POST['fname']);
            }
            $arrPlaceholders['PAGE_TITLE'] = 'Register';
            $arrPlaceholders['CONTENT'] = $tplThanks->render()->get();
            $strTemplate = 'main';
            break;
        default:
            break;
    }
}
$tplMain = new SimpleTemplate('html/' . $strTemplate . '.html');
$tplMain->params($arrPlaceholders);
echo $tplMain->render()->get();
