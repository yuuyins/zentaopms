<?php
class actionTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('action');
         $tester->dao->delete()->from(TABLE_ACTION)->where('action')->eq('login')->exec();
    }

    /**
     * Test create a action.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $actionType
     * @param  string $comment
     * @param  string $extra
     * @param  string $actor
     * @param  bool   $autoDelete
     * @access public
     * @return object
     */
    public function createTest($objectType, $objectID, $actionType, $comment = '', $extra = '', $actor = '', $autoDelete = true)
    {
        $_POST['uid'] = '';
        $objectID = $this->objectModel->create($objectType, $objectID, $actionType, $comment, $extra, $actor, $autoDelete);

        unset($_POST);
        if(dao::isError()) return dao::getError();

        return $objectID ? $this->objectModel->getById($objectID) : 0;
    }

    /**
     * Test update read field of action when view a task/bug.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function readTest($objectType, $objectID)
    {
        $this->objectModel->read($objectType, $objectID);

        if(dao::isError()) return dao::getError();

        global $tester;
        $objects = $tester->dao->select('*')->from(TABLE_ACTION)->where('objectID')->eq($objectID)->andWhere('objectType')->eq($objectType)->fetchAll();
        return $objects;
    }

    public function getUnreadActionsTest($actionID = 0)
    {
        $objects = $this->objectModel->getUnreadActions($actionID = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get product, project, execution of the object.
     *
     * @param String $objectType
     * @param Int    $objectID
     * @param String $actionType
     * @param String $extra
     * @access public
     * @return array
     */
    public function getRelatedFieldsTest($objectType, $objectID, $actionType = '', $extra = '')
    {
        $objects = $this->objectModel->getRelatedFields($objectType, $objectID, $actionType = '', $extra = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get actions of an object.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return object
     */
    public function getListTest($objectType, $objectID)
    {
        $objects = $this->objectModel->getList($objectType, $objectID);

        if(dao::isError()) return dao::getError();

        $objects[$objectID]->extra = trim($objects[$objectID]->extra, "\n");
        return $objects[$objectID];
    }

    /**
     * Test process Project Actions change actionStype.
     *
     * @param  array  $actions
     * @access public
     * @return string
     */
    public function processProjectActionsTest($actions)
    {
        global $tester;
        $actions = $tester->dao->select('*')->from(TABLE_ACTION)->where('id')->in($actions)->fetchAll('id');

        $objects = $this->objectModel->processProjectActions($actions);

        if(dao::isError()) return dao::getError();

        $IDs = array_keys($objects);
        return implode(',', $IDs);
    }

    /**
     * Test get action by id.
     *
     * @param  int    $actionID
     * @access public
     * @return object
     */
    public function getByIdTest($actionID)
    {
        $object = $this->objectModel->getById($actionID);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Test get deleted objects.
     *
     * @param  string $type
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getTrashesTest($type, $orderBy, $pager)
    {
        $objects = $this->objectModel->getTrashes($type, $orderBy, $pager);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get histories of an action.
     *
     * @param  int    $actionID
     * @access public
     * @return array
     */
    public function getHistoryTest($actionID)
    {
        $objects = $this->objectModel->getHistory($actionID);

        if(dao::isError()) return dao::getError();

        return $objects[$actionID];
    }

    /**
     * Test log histories for an action.
     *
     * @param  int    $actionID
     * @param  array  $changes
     * @access public
     * @return array
     */
    public function logHistoryTest($actionID, $changes)
    {
        $this->objectModel->logHistory($actionID, $changes);

        if(dao::isError()) return dao::getError();

        global $tester;
        $objects = $tester->dao->select('*')->from(TABLE_HISTORY)->where('action')->eq($actionID)->fetchAll();
        return $objects;
    }

    public function printActionTest($action, $desc = '')
    {
        $objects = $this->objectModel->printAction($action, $desc = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get actions as dynamic.
     *
     * @param  string $account
     * @param  string $period
     * @param  string $productID
     * @param  string $projectID
     * @param  string $executionID
     * @param  string $date
     * @param  string $direction
     * @access public
     * @return int
     */
    public function getDynamicTest($account = 'all', $period = 'all', $productID = 'all', $projectID = 'all', $executionID = 'all', $date = '', $direction = 'next')
    {
        $date = $date == 'today' ? date('Y-m-d', time()) : $date;
        $objects = $this->objectModel->getDynamic($account, $period, 'date_desc', null, $productID, $projectID, $executionID, $date, $direction);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    public function getActionConditionTest()
    {
        $objects = $this->objectModel->getActionCondition();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getDynamicBySearchTest($products, $projects, $executions, $queryID, $orderBy = 'date_desc', $pager = null, $date = '', $direction = 'next')
    {
        $objects = $this->objectModel->getDynamicBySearch($products, $projects, $executions, $queryID, $orderBy = 'date_desc', $pager = null, $date = '', $direction = 'next');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get actions by SQL.
     *
     * @param  string $sql
     * @param  string $orderBy
     * @param  object  $pager
     * @access public
     * @return int
     */
    public function getBySQLTest($sql, $orderBy = 'id_desc', $pager = null)
    {
        $objects = $this->objectModel->getBySQL($sql, $orderBy, $pager = null);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    public function transformActionsTest($actions)
    {
        $objects = $this->objectModel->transformActions($actions);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get related data by actions.
     *
     * @param  array  $actions
     * @param  string $field
     * @access public
     * @return array
     */
    public function getRelatedDataByActionsTest($actions, $field)
    {
        global $tester;
        $actions = $tester->dao->select('*')->from(TABLE_ACTION)->where('id')->in($actions)->fetchAll('id');

        $objects = $this->objectModel->getRelatedDataByActions($actions);

        if(dao::isError()) return dao::getError();

        return $objects[$field];
    }

    /**
     * Test get object label.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $actionType
     * @param  array $requirements
     * @access public
     * @return string
     */
    public function getObjectLabelTest($objectType, $objectID, $actionType, $requirements)
    {
        $object = $this->objectModel->getObjectLabel($objectType, $objectID, $actionType, $requirements);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Test set objectLink.
     *
     * @param  int    $actionID
     * @access public
     * @return object
     */
    public function setObjectLinkTest($actionID)
    {
        global $tester;
        $deptUsers = isset($tester->app->user->dept) ? $tester->loadModel('dept')->getDeptUserPairs($tester->app->user->dept, 'id') : '';

        $action = $this->objectModel->getByID($actionID);
        $action->objectLabel = $this->objectModel->getObjectLabel($action->objectType, $action->objectID, $action->action, array('25' => '25'));

        $object = $this->objectModel->setObjectLink($action, $deptUsers);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Test compute the begin date and end date of a period.
     *
     * @param  string $period
     * @access public
     * @return array
     */
    public function computeBeginAndEndTest($period)
    {
        $date = $this->objectModel->computeBeginAndEnd($period);

        $today      = date('Y-m-d');
        $tomorrow   = date::tomorrow();
        $yesterday  = date::yesterday();
        $twoDaysAgo = date::twoDaysAgo();

        if($period == 'all')         return $date['begin'] == '1970-1-1' and $date['end'] == '2109-1-1';
        if($period == 'today')       return $date['begin'] == $today and $date['end'] == $tomorrow;
        if($period == 'yesterday')   return $date['begin'] == $yesterday and $date['end'] == $today;
        if($period == 'twodaysago')  return $date['begin'] == $twoDaysAgo and $date['end'] == $yesterday;
        if($period == 'latest3days') return $date['begin'] == $twoDaysAgo and $date['end'] == $tomorrow;
        if($period == 'thismonth')   return $date == date::getThisMonth();
        if($period == 'lastmonth')   return $date == date::getLastMonth();
        $func = "get$period";
        extract(date::$func());
        if($period == 'thisweek')    return $date['begin'] == $begin and $date['end'] == $end . ' 23:59:59';
        if($period == 'lastweek')    return $date['begin'] == $begin and $date['end'] == $end . ' 23:59:59';
    }

    public function printChangesTest($objectType, $histories, $canChangeTag = true)
    {
        $objects = $this->objectModel->printChanges($objectType, $histories, $canChangeTag = true);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function undeleteTest($actionID)
    {
        $objects = $this->objectModel->undelete($actionID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test hide an object.
     *
     * @param  int    $actionID
     * @access public
     * @return object
     */
    public function hideOneTest($actionID)
    {
        $this->objectModel->hideOne($actionID);

        if(dao::isError()) return dao::getError();

        $object = $this->objectModel->getByID($actionID);
        return $object;
    }

    /**
     * Test hide all deleted objects.
     *
     * @access public
     * @return array
     */
    public function hideAllTest()
    {
        $this->objectModel->hideAll();

        if(dao::isError()) return dao::getError();

        global $tester;
        $objects = $tester->dao->select('*')->from(TABLE_ACTION)->where('action')->eq('deleted')->fetchAll();
        return $objects;
    }

    public function updateCommentTest($actionID)
    {
        $objects = $this->objectModel->updateComment($actionID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test build date group by actions.
     *
     * @param  string $direction
     * @param  string $type
     * @param  string $orderBy
     * @access public
     * @return int
     */
    public function buildDateGroupTest($direction = 'next', $type = 'today', $orderBy = 'date_desc')
    {
        $actions = $this->objectModel->getDynamic('all', $type);
        $objects = $this->objectModel->buildDateGroup($actions, $direction, $type, $orderBy);

        if(dao::isError()) return dao::getError();

        $count = 0;
        foreach($objects as $object) $count += count($object);
        return $count;
    }

    public function hasPreOrNextTest($date, $direction = 'next')
    {
        $objects = $this->objectModel->hasPreOrNext($date, $direction = 'next');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function saveIndexTest($objectType, $objectID, $actionType)
    {
        $objects = $this->objectModel->saveIndex($objectType, $objectID, $actionType);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function printActionForGitLabTest($action)
    {
        $objects = $this->objectModel->printActionForGitLab($action);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function processActionForAPITest($actions, $users = array(), $objectLang = array())
    {
        $objects = $this->objectModel->processActionForAPI($actions, $users = array(), $objectLang = array());

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function processDynamicForAPITest($dynamics)
    {
        $objects = $this->objectModel->processDynamicForAPI($dynamics);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
