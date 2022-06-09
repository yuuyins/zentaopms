<?php
/**
 * The control file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: control.php 5020 2013-07-05 02:03:26Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
class my extends control
{
    /**
     * Construct function.
     *
     * @access public
     * @return void
     */
    public function __construct($module = '', $method = '')
    {
        parent::__construct($module, $method);
        $this->loadModel('user');
        $this->loadModel('dept');
    }

    /**
     * Index page, goto todo.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->view->title = $this->lang->my->common;
        $this->display();
    }

    /**
     * Get score list
     *
     * @param int $recTotal
     * @param int $recPerPage
     * @param int $pageID
     *
     * @access public
     * @return mixed
     */
    public function score($recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager  = new pager($recTotal, $recPerPage, $pageID);
        $scores = $this->loadModel('score')->getListByAccount($this->app->user->account, $pager);

        $this->view->title      = $this->lang->score->common;
        $this->view->user       = $this->loadModel('user')->getById($this->app->user->account);
        $this->view->pager      = $pager;
        $this->view->scores     = $scores;
        $this->view->position[] = $this->lang->score->record;

        $this->display();
    }

    /**
     * My calendar.
     *
     * @access public
     * @return void
     */
    public function calendar()
    {
        $this->locate($this->createLink('my', 'todo', 'type=before&userID=&status=undone'));
    }

    /**
     * My work view.
     *
     * @param  string     $mode
     * @param  string     $type
     * @param  string|int $param
     * @param  string     $orderBy
     * @param  int        $recTotal
     * @param  int        $recPerPage
     * @param  int        $pageID
     * @access public
     * @return void
     */
    public function work($mode = 'task', $type = 'assignedTo', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        echo $this->fetch('my', $mode, "type=$type&param=$param&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");

        $this->showWorkCount($recTotal, $recPerPage, $pageID);
    }

    /**
     * Show to-do work count.
     *
     * @param int    $recTotal
     * @param int    $recPerPage
     * @param int    $pageID
     * @access public
     * @return void
     */
    public function showWorkCount($recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('task');
        $this->loadModel('story');
        $this->loadModel('bug');
        $this->loadModel('testcase');
        $this->loadModel('testtask');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Get the number of tasks assigned to me. */
        $tasks     = $this->task->getUserTasks($this->app->user->account, 'assignedTo', 0, $pager);
        $taskCount = $pager->recTotal;

        /* Get the number of stories assigned to me. */
        $assignedToStories    = $this->story->getUserStories($this->app->user->account, 'assignedTo', 'id_desc', $pager, 'story', false);
        $assignedToStoryCount = $pager->recTotal;
        $reviewByStories      = $this->story->getUserStories($this->app->user->account, 'reviewBy', 'id_desc', $pager, 'story', false);
        $reviewByStoryCount   = $pager->recTotal;
        $storyCount           = $assignedToStoryCount + $reviewByStoryCount;

        $requirementCount = 0;
        $isOpenedURAndSR  = $this->config->URAndSR;
        if($isOpenedURAndSR)
        {
            /* Get the number of requirements assigned to me. */
            $assignedRequirements     = $this->story->getUserStories($this->app->user->account, 'assignedTo', 'id_desc', $pager, 'requirement');
            $assignedRequirementCount = $pager->recTotal;
            $reviewByRequirements     = $this->story->getUserStories($this->app->user->account, 'reviewBy', 'id_desc', $pager, 'requirement');
            $reviewByRequirementCount = $pager->recTotal;
            $requirementCount         = $assignedRequirementCount + $reviewByRequirementCount;
        }

        /* Get the number of bugs assigned to me. */
        $bugs     = $this->bug->getUserBugs($this->app->user->account, 'assignedTo', 'id_desc', 0, $pager);
        $bugCount = $pager->recTotal;

        /* Get the number of testcases assigned to me. */
        $cases     = $this->testcase->getByAssignedTo($this->app->user->account, 'id_desc', $pager, 'skip');
        $caseCount = $pager->recTotal;

        /* Get the number of testtasks assigned to me. */
        $testTasks     = $this->testtask->getByUser($this->app->user->account, $pager, 'id_desc', 'wait');
        $testTaskCount = $pager->recTotal;

        $issueCount   = 0;
        $riskCount    = 0;
        $reviewCount  = 0;
        $ncCount      = 0;
        $meetingCount = 0;
        $isMax        = $this->config->edition == 'max' ? 1 : 0;
        if($isMax)
        {
            $this->loadModel('issue');
            $this->loadModel('risk');
            $this->loadModel('review');
            $this->loadModel('meeting');

            /* Get the number of issues assigned to me. */
            $issues     = $this->issue->getUserIssues('assignedTo', $this->app->user->account, 'id_desc', $pager);
            $issueCount = $pager->recTotal;

            /* Get the number of risks assigned to me. */
            $risks     = $this->risk->getUserRisks('assignedTo', $this->app->user->account, 'id_desc', $pager);
            $riskCount = $pager->recTotal;

            /* Get the number of reviews assigned to me. */
            $pendingList = $this->loadModel('approval')->getPendingReviews('review');
            $reviewList  = $this->review->getByList($pendingList, 'id_desc', $pager);
            $reviewCount = $pager->recTotal;

            /* Get the number of nc assigned to me. */
            $ncList  = $this->my->getNcList('assignedToMe', 'id_desc', $pager, 'active');
            $ncCount = $pager->recTotal;

            /* Get the number of meetings assigned to me. */
            $meetings     = $this->meeting->getListByUser('futureMeeting', 'id_desc', 0, $pager);
            $meetingCount = $pager->recTotal;
        }

echo <<<EOF
<script>
var taskCount     = $taskCount;
var storyCount    = $storyCount;
var bugCount      = $bugCount;
var caseCount     = $caseCount;
var testTaskCount = $testTaskCount;

var isOpenedURAndSR = $isOpenedURAndSR;
if(isOpenedURAndSR !== 0) var requirementCount = $requirementCount;

var isMax = $isMax;
if(isMax !== 0)
{
    var issueCount   = $issueCount;
    var riskCount    = $riskCount;
    var reviewCount  = $reviewCount;
    var ncCount      = $ncCount;
    var meetingCount = $meetingCount;
}
</script>
EOF;
    }

    /**
     * My contribute view.
     *
     * @param  string     $mode
     * @param  string     $type
     * @param  string|int $param
     * @param  string     $orderBy
     * @param  int        $recTotal
     * @param  int        $recPerPage
     * @param  int        $pageID
     * @access public
     * @return void
     */
    public function contribute($mode = 'task', $type = 'openedBy', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        if(($mode == 'issue' or $mode == 'risk') and $type == 'openedBy') $type = 'createdBy';

        echo $this->fetch('my', $mode, "type=$type&param=$param&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * My todos.
     *
     * @param  string $type
     * @param  int    $userID
     * @param  string $status
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function todo($type = 'before', $userID = '', $status = 'all', $orderBy = "date_desc,status,begin", $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save session. */
        $uri = $this->app->getURI(true);
        $this->session->set('todoList',     $uri, 'my');
        $this->session->set('bugList',      $uri, 'my');
        $this->session->set('taskList',     $uri, 'my');
        $this->session->set('storyList',    $uri, 'my');
        $this->session->set('testtaskList', $uri, 'my');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = new pager($recTotal, $recPerPage, $pageID);

        if(empty($userID)) $userID = $this->app->user->id;
        $user    = $this->loadModel('user')->getById($userID, 'id');
        $account = $user->account;

        /* The title and position. */
        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->todo;
        $this->view->position[] = $this->lang->my->todo;

        /* Append id for secend sort. */
        $sort = common::appendOrder($orderBy);

        $todos = $this->loadModel('todo')->getList($type, $account, $status, 0, $pager, $sort);
        $tasks = $this->loadModel('task')->getUserSuspendedTasks($account);
        foreach($todos as $key => $todo)
        {
            if($todo->type == 'task' and isset($tasks[$todo->idvalue])) unset($todos[$key]);
        }

        /* Assign. */
        $this->view->todos        = $todos;
        $this->view->date         = (int)$type == 0 ? date(DT_DATE1) : date(DT_DATE1, strtotime($type));
        $this->view->type         = $type;
        $this->view->recTotal     = $recTotal;
        $this->view->recPerPage   = $recPerPage;
        $this->view->pageID       = $pageID;
        $this->view->status       = $status;
        $this->view->user         = $user;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->account      = $this->app->user->account;
        $this->view->orderBy      = $orderBy == 'date_desc,status,begin,id_desc' ? '' : $orderBy;
        $this->view->pager        = $pager;
        $this->view->times        = date::buildTimeList($this->config->todo->times->begin, $this->config->todo->times->end, $this->config->todo->times->delta);
        $this->view->time         = date::now();
        $this->view->importFuture = ($type != 'today');

        $this->display();
    }

    /**
     * My stories.
     *
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function story($type = 'assignedTo', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('story');
        /* Save session. */
        if($this->app->viewType != 'json') $this->session->set('storyList', $this->app->getURI(true), 'my');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort = common::appendOrder($orderBy);

        if($type == 'assignedBy')
        {
            $stories = $this->loadModel('my')->getAssignedByMe($this->app->user->account, '', $pager, $orderBy, '', 'story');
        }
        else
        {
            $stories = $this->loadModel('story')->getUserStories($this->app->user->account, $type, $sort, $pager, 'story', false);
        }

        if(!empty($stories)) $stories = $this->story->mergeReviewer($stories);

        /* Assign. */
        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->story;
        $this->view->position[] = $this->lang->my->story;
        $this->view->stories    = $stories;
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->projects   = $this->loadModel('project')->getPairsByProgram();
        $this->view->type       = $type;
        $this->view->recTotal   = $recTotal;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->mode       = 'story';

        $this->display();
    }

    /**
     * My requirements.
     *
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function requirement($type = 'assignedTo', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save session. */
        $this->loadModel('story');
        if($this->app->viewType != 'json') $this->session->set('storyList', $this->app->getURI(true), 'my');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort = common::appendOrder($orderBy);

        if($type == 'assignedBy')
        {
            $stories = $this->loadModel('my')->getAssignedByMe($this->app->user->account, '', $pager, $orderBy, '', 'requirement');
        }
        else
        {
            $stories = $this->loadModel('story')->getUserStories($this->app->user->account, $type, $sort, $pager, 'requirement');
        }

        if(!empty($stories)) $stories = $this->story->mergeReviewer($stories);

        /* Assign. */
        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->story;
        $this->view->position[] = $this->lang->my->story;
        $this->view->stories    = $stories;
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->projects   = $this->loadModel('project')->getPairsByProgram();
        $this->view->type       = $type;
        $this->view->recTotal   = $recTotal;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->mode       = 'requirement';

        $this->display();
    }

    /**
     * My tasks
     *
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function task($type = 'assignedTo', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('task');
        $this->loadModel('execution');
        $queryID  = ($type == 'bySearch') ? (int)$param : 0;

        if($type != 'bySearch') $this->session->set('myTaskType', $type);

        /* Save session. */
        if($this->app->viewType != 'json') $this->session->set('taskList', $this->app->getURI(true), 'execution');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* append id for secend sort. */
        $sort = common::appendOrder($orderBy);

        /* Get tasks. */
        if($type == 'assignedBy')
        {
            $tasks = $this->my->getAssignedByMe($this->app->user->account, 0, $pager, $sort, 0, 'task');
        }
        elseif($type == 'bySearch')
        {
            $tasks = $this->my->getTasksBySearch($this->app->user->account, 0, $pager, $sort, $queryID);
        }
        else
        {
            $tasks = $this->task->getUserTasks($this->app->user->account, $type, 0, $pager, $sort, $queryID);
        }

        $parents         = array();
        $executionIDList = array();
        foreach($tasks as $task)
        {
            if($this->config->systemMode == 'new') $executionIDList[$task->execution] = $task->execution;
            if($task->parent > 0) $parents[$task->parent] = $task->parent;
        }
        $parents = $this->dao->select('*')->from(TABLE_TASK)->where('id')->in($parents)->fetchAll('id');

        if($this->config->systemMode == 'new')
        {
            $projects = $this->dao->select('t1.id,t1.name,t2.id as execution')->from(TABLE_PROJECT)->alias('t1')
                ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.id=t2.project')
                ->where('t2.id')->in($executionIDList)
                ->andWhere('t1.type')->eq('project')
                ->fetchAll('execution');
        }

        foreach($tasks as $task)
        {
            if($task->parent > 0)
            {
                if(isset($tasks[$task->parent]))
                {
                    $tasks[$task->parent]->children[$task->id] = $task;
                    unset($tasks[$task->id]);
                }
                else
                {
                    $parent = $parents[$task->parent];
                    $task->parentName = $parent->name;
                }
            }
        }

        /* Get the story language configuration. */
        $this->app->loadLang('story');

        $actionURL = $this->createLink('my', $this->app->rawMethod, "mode=task&browseType=bySearch&queryID=myQueryID");
        $this->my->buildTaskSearchForm($queryID, $actionURL);

        /* Assign. */
        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->task;
        $this->view->position[] = $this->lang->my->task;
        $this->view->tabID      = 'task';
        $this->view->tasks      = $tasks;
        $this->view->summary    = $this->loadModel('execution')->summary($tasks);
        $this->view->type       = $type;
        $this->view->kanbanList = $this->execution->getPairs(0, 'kanban');
        $this->view->recTotal   = $recTotal;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->view->orderBy    = $orderBy;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->pager      = $pager;
        $this->view->mode       = 'task';
        $this->view->projects   = isset($projects) ? $projects : array();

        if($this->app->viewType == 'json') $this->view->tasks = array_values($this->view->tasks);
        $this->display();
    }

    /**
     * My bugs.
     *
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function bug($type = 'assignedTo', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save session. load Lang. */
        $this->loadModel('bug');
        $this->app->loadLang('bug');
        $queryID  = ($type == 'bySearch') ? (int)$param : 0;
        if($this->app->viewType != 'json') $this->session->set('bugList', $this->app->getURI(true), 'qa');

        if($type != 'bySearch') $this->session->set('myBugType', $type);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort = common::appendOrder($orderBy);
        if($type == 'assignedBy')
        {
            $bugs = $this->loadModel('my')->getAssignedByMe($this->app->user->account, '', $pager, $orderBy, '', 'bug');
        }
        else
        {
            $bugs = $this->loadModel('bug')->getUserBugs($this->app->user->account, $type, $sort, 0, $pager, '', $queryID);
        }

        $bugs = $this->bug->checkDelayedBugs($bugs);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug', false);


        $actionURL = $this->createLink('my', $this->app->rawMethod, "mode=bug&browseType=bySearch&queryID=myQueryID");
        $this->my->buildBugSearchForm($queryID, $actionURL);

        /* assign. */
        $this->view->title       = $this->lang->my->common . $this->lang->colon . $this->lang->my->bug;
        $this->view->position[]  = $this->lang->my->bug;
        $this->view->bugs        = $bugs;
        $this->view->users       = $this->user->getPairs('noletter');
        $this->view->memberPairs = $this->user->getPairs('noletter|nodeleted|noclosed');
        $this->view->tabID       = 'bug';
        $this->view->type        = $type;
        $this->view->recTotal    = $recTotal;
        $this->view->recPerPage  = $recPerPage;
        $this->view->pageID      = $pageID;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->mode       = 'bug';

        $this->display();
    }

    /**
     * My test task.
     *
     * @param  string $type wait|done
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function testtask($type = 'wait', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Save session. */
        if($this->app->viewType != 'json')
        {
            $uri = $this->app->getURI(true);
            $this->session->set('testtaskList', $uri, 'qa');
            $this->session->set('reportList',   $uri, 'qa');
            $this->session->set('buildList',    $uri, 'execution');
        }

        $this->app->loadLang('testcase');

        /* Append id for secend sort. */
        $sort = common::appendOrder($orderBy);

        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->myTestTask;
        $this->view->position[] = $this->lang->my->myTestTask;
        $this->view->tasks      = $this->loadModel('testtask')->getByUser($this->app->user->account, $pager, $sort, $type);

        $this->view->recTotal   = $recTotal;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->view->orderBy    = $orderBy;
        $this->view->type       = $type;
        $this->view->pager      = $pager;
        $this->view->mode       = 'testtask';

        $this->display();
    }

    /**
     * My test case.
     *
     * @param  string     $type      assigntome|openedbyme
     * @param  string|int $param
     * @param  string     $orderBy
     * @param  int        $recTotal
     * @param  int        $recPerPage
     * @param  int        $pageID
     * @access public
     * @return void
     */
    public function testcase($type = 'assigntome', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('testcase');
        $this->loadModel('testtask');

        /* Save session. */
        $uri = $this->app->getURI(true);
        $this->session->set('caseList', $uri, 'qa');
        $this->session->set('bugList',  $uri . "#app={$this->app->tab}", 'qa');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort = common::appendOrder($orderBy);
        $queryID = ($type == 'bysearch') ? (int)$param : 0;

        $cases = array();
        if($type == 'assigntome') $cases = $this->testcase->getByAssignedTo($this->app->user->account, $sort, $pager, 'skip');
        if($type == 'openedbyme') $cases = $this->testcase->getByOpenedBy($this->app->user->account, $sort, $pager, 'skip');
        if($type == 'bysearch' and $this->app->rawMethod == 'contribute') $cases = $this->my->getTestcasesBySearch($queryID, 'openedbyme', $orderBy, $pager);
        if($type == 'bysearch' and $this->app->rawMethod == 'work')       $cases = $this->my->getTestcasesBySearch($queryID, 'assigntome', $orderBy, $pager);

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', $type == 'assigntome' ? false : true);

        $cases = $this->testcase->appendData($cases, $type == 'assigntome' ? 'run' : 'case');

        /* Build the search form. */
        $currentMethod = $this->app->rawMethod;
        $actionURL     = $this->createLink('my', $currentMethod, "mode=testcase&type=bysearch&param=myQueryID&orderBy={$orderBy}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}");
        $this->config->testcase->search['onMenuBar'] = 'yes';
        $this->my->buildTestcaseSearchForm($queryID, $actionURL, $currentMethod);

        /* Assign. */
        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->myTestCase;
        $this->view->position[] = $this->lang->my->myTestCase;
        $this->view->cases      = $cases;
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->tabID      = 'test';
        $this->view->type       = $type;
        $this->view->summary    = $this->testcase->summary($cases);
        $this->view->recTotal   = $recTotal;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->mode       = 'testcase';

        $this->display();
    }

    public function doc($type = 'openedbyme', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save session, load lang. */
        $uri = $this->app->getURI(true);
        if($this->app->viewType != 'json') $this->session->set('docList', $uri, 'doc');
        $this->loadModel('doc');

        $this->session->set('productList',   $uri, 'product');
        $this->session->set('executionList', $uri, 'execution');
        $this->session->set('projectList',   $uri, 'project');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort = common::appendOrder($orderBy);

        $docs = $this->doc->getDocsByBrowseType($type, 0, 0, $sort, $pager);

        /* Assign. */
        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->doc;
        $this->view->position[] = $this->lang->my->doc;
        $this->view->docs       = $docs;
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->type       = $type;
        $this->view->recTotal   = $recTotal;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;

        $this->display();

    }

    /**
     * My projects.
     *
     * @param  string  $status doing|wait|suspended|closed|openedbyme
     * @param  int     $recTotal
     * @param  int     $recPerPage
     * @param  int     $pageID
     * @param  string  $orderBy
     * @access public
     * @return void
     */
    public function project($status = 'doing', $recTotal = 0, $recPerPage = 15, $pageID = 1, $orderBy = 'id_desc')
    {
        $this->loadModel('program');
        $this->app->loadLang('project');

        $uri = $this->app->getURI(true);
        $this->app->session->set('programList', $uri, 'program');
        $this->app->session->set('projectList', $uri, 'my');

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Get PM id list. */
        $accounts = array();
        $projects = $this->user->getObjects($this->app->user->account, 'project', $status, $orderBy, $pager);
        foreach($projects as $project)
        {
            if(!empty($project->PM) and !in_array($project->PM, $accounts)) $accounts[] = $project->PM;
        }
        $PMList = $this->user->getListByAccounts($accounts, 'account');

        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->project;
        $this->view->position[] = $this->lang->my->project;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->projects   = $projects;
        $this->view->PMList     = $PMList;
        $this->view->pager      = $pager;
        $this->view->status     = $status;
        $this->view->recTotal   = $recTotal;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->view->orderBy    = $orderBy;
        $this->display();
    }

    /**
     * My executions.
     * @param  string  $type undone|done
     * @param  string  $orderBy
     * @param  int     $recTotal
     * @param  int     $recPerPage
     * @param  int     $pageID
     *
     * @access public
     * @return void
     */
    public function execution($type = 'undone', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $this->app->loadLang('project');
        $this->app->loadLang('execution');

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->execution;
        $this->view->position[] = $this->lang->my->execution;
        $this->view->tabID      = 'project';
        $this->view->executions = $this->user->getObjects($this->app->user->account, 'execution', $type, $orderBy, $pager);
        $this->view->type       = $type;
        $this->view->pager      = $pager;
        $this->view->mode       = 'execution';

        $this->display();
    }

    /**
     * My issues.
     *
     * @access public
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @return void
     */
    public function issue($type = 'assignedTo', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->app->session->set('issueList', $this->app->getURI(true), 'project');

        $this->view->title      = $this->lang->my->issue;
        $this->view->position[] = $this->lang->my->issue;
        $this->view->mode       = 'issue';
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->type       = $type;
        $this->view->issues     = $type == 'assignedBy' ? $this->loadModel('my')->getAssignedByMe($this->app->user->account, '', $pager,  $orderBy, '', 'issue') : $this->loadModel('issue')->getUserIssues($type, $this->app->user->account, $orderBy, $pager);

        $this->view->projectList = $this->loadModel('project')->getPairsByProgram();

        $this->display();
    }

    /**
     * My risks.
     *
     * @access public
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @return void
     */
    public function risk($type = 'assignedTo', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Set the pager. */
        $this->loadModel('risk');
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->app->session->set('riskList', $this->app->getURI(true), 'project');

        $this->view->title      = $this->lang->my->risk;
        $this->view->position[] = $this->lang->my->risk;
        $this->view->risks      = $type == 'assignedBy' ? $this->loadModel('my')->getAssignedByMe($this->app->user->account, '', $pager, $orderBy, '', 'risk') : $this->loadModel('risk')->getUserRisks($type, $this->app->user->account, $orderBy, $pager);
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->type       = $type;
        $this->view->mode       = 'risk';

        $this->view->projectList = $this->loadModel('project')->getPairsByProgram();

        $this->display();
    }

    /**
     * My audits.
     *
     * @param  string $browseType
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function audit($browseType = 'needreview', $orderBy = 't1.id_desc', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $this->loadModel('datatable');
        $this->loadModel('baseline');
        $this->session->set('reviewList', $this->app->getURI(true));
        $this->app->loadLang('review');
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $pendingList = $this->loadModel('approval')->getPendingReviews('review');
        if($browseType == 'needreview')
        {
            $reviewList  = $this->loadModel('review')->getByList($pendingList, $orderBy, $pager);
        }
        else
        {
            $reviewList = $this->loadModel('review')->getUserReviews($browseType, $orderBy, $pager);
        }

        $this->view->title       = $this->lang->my->myReview;
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->reviewList  = $reviewList;
        $this->view->pendingList = $pendingList;
        $this->view->products    = $this->my->getProductPairs();
        $this->view->recTotal    = $recTotal;
        $this->view->recPerPage  = $recPerPage;
        $this->view->pageID      = $pageID;
        $this->view->browseType  = $browseType;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->mode        = 'audit';
        $this->display();
    }

    /**
     * My ncs.
     *
     * @param  string $browseType
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function nc($browseType = 'assignedToMe', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('nc');
        $this->session->set('ncList', $this->app->getURI(true));

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager  = pager::init($recTotal, $recPerPage, $pageID);
        $ncList = $browseType == 'assignedBy' ? $this->loadModel('my')->getAssignedByMe($this->app->user->account, '', $pager, $orderBy, '', 'nc') : $this->my->getNcList($browseType, $orderBy, $pager, 'active');

        foreach($ncList as $nc) $ncIdList[] = $nc->id;
        $this->session->set('ncIdList', isset($ncIdList) ? $ncIdList : '');

        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->nc;
        $this->view->position[] = $this->lang->my->nc;
        $this->view->browseType = $browseType;
        $this->view->ncs        = $ncList;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->projects   = $this->loadModel('project')->getPairsByProgram();
        $this->view->pager      = $pager;
        $this->view->orderBy    = $orderBy;
        $this->view->mode       = 'nc';
        $this->display();
    }

    /**
     * My meeting list.
     *
     * @param  string $browseType
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function myMeeting($browseType = 'futureMeeting', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('meeting');

        $uri = $this->app->getURI(true);
        $this->session->set('meetingList', $uri, 'my');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->myMeeting;
        $this->view->browseType = $browseType;
        $this->view->meetings   = $this->meeting->getListByUser($browseType, $orderBy, 0, $pager);
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->depts      = $this->loadModel('dept')->getOptionMenu();
        $this->view->users      = $this->loadModel('user')->getPairs('all,noletter');
        $this->view->mode       = 'myMeeting';

        $this->display();
    }

    /**
     * My team.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function team($orderBy = 'id', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $this->lang->navGroup->my = 'system';

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort = common::appendOrder($orderBy);

        /* Get users by dept. */
        $deptID = $this->app->user->admin ? 0 : $this->app->user->dept;
        $users  = $this->loadModel('company')->getUsers('inside', 'bydept', 0, $deptID, $sort, $pager);
        foreach($users as $user) unset($user->password); // Remove passwd.

        $this->view->title      = $this->lang->my->team;
        $this->view->position[] = $this->lang->my->team;
        $this->view->users      = $users;
        $this->view->deptID     = $deptID;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;

        $this->display();
    }

    /**
     * Edit profile
     *
     * @access public
     * @return void
     */
    public function editProfile()
    {
        if($this->app->user->account == 'guest')
        {
            echo js::alert('guest'), js::locate('back');
            return;
        }
        if(!empty($_POST))
        {
            $_POST['account'] = $this->app->user->account;
            $_POST['groups']  = $this->dao->select('`group`')->from(TABLE_USERGROUP)->where('account')->eq($this->post->account)->fetchPairs('group', 'group');
            $this->user->update($this->app->user->id);
            if(dao::isError()) helper::end(js::error(dao::getError()));
            echo js::locate($this->createLink('my', 'profile'), 'parent');
            return;
        }

        $this->app->loadConfig('user');
        $this->app->loadLang('user');

        $userGroups = $this->loadModel('group')->getByAccount($this->app->user->account);

        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->editProfile;
        $this->view->position[] = $this->lang->my->editProfile;
        $this->view->user       = $this->user->getById($this->app->user->account);
        $this->view->rand       = $this->user->updateSessionRandom();
        $this->view->userGroups = implode(',', array_keys($userGroups));
        $this->view->groups     = $this->dao->select('id, name')->from(TABLE_GROUP)->fetchPairs('id', 'name');

        $this->display();
    }

    /**
     * Change password
     *
     * @access public
     * @return void
     */
    public function changePassword()
    {
        if($this->app->user->account == 'guest') return print(js::alert('guest') . js::locate('back'));
        if(!empty($_POST))
        {
            $this->user->updatePassword($this->app->user->id);
            if(dao::isError()) return print(js::error(dao::getError()));
            if(isonlybody()) return print(js::closeModal('parent.parent', 'this'));
            return print(js::locate($this->createLink('my', 'index'), 'parent.parent'));
        }

        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->changePassword;
        $this->view->position[] = $this->lang->my->changePassword;
        $this->view->user       = $this->user->getById($this->app->user->account);
        $this->view->rand       = $this->user->updateSessionRandom();

        $this->display();
    }

    /**
     * Manage contacts.
     *
     * @param  int    $listID
     * @param  string $mode
     * @access public
     * @return void
     */
    public function manageContacts($listID = 0, $mode = '')
    {
        if($_POST)
        {
            $data = fixer::input('post')->setDefault('users', array())->get();
            if($data->mode == 'new')
            {
                if(empty($data->newList))
                {
                    dao::$errors['newList'] = sprintf($this->lang->error->notempty, $this->lang->user->contacts->listName);

                    $response['result']  = 'fail';
                    $response['message'] = dao::getError();
                    return $this->send($response);
                }
                $listID = $this->user->createContactList($data->newList, $data->users);
                if(dao::isError())
                {
                    return $this->send(array('result' => 'fail', 'message' => dao::getError()));
                }
                $this->user->setGlobalContacts($listID, isset($data->share));
                if(isonlybody()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "parent.parent.ajaxGetContacts('#mailto')"));
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('manageContacts', "listID=$listID")));
            }
            elseif($data->mode == 'edit')
            {
                $response['result']  = 'success';
                $response['message'] = $this->lang->saveSuccess;

                $this->user->updateContactList($data->listID, $data->listName, $data->users);
                $this->user->setGlobalContacts($data->listID, isset($data->share));

                if(dao::isError())
                {
                    $response['result']  = 'fail';
                    $response['message'] = dao::getError();
                    return $this->send($response);
                }

                $response['locate'] = inlink('manageContacts', "listID=$listID");
                return $this->send($response);
            }
        }

        $mode  = empty($mode) ? 'edit' : $mode;
        $lists = $this->user->getContactLists($this->app->user->account);

        $globalContacts = isset($this->config->my->global->globalContacts) ? $this->config->my->global->globalContacts : '';
        $globalContacts = !empty($globalContacts) ? explode(',', $globalContacts) : array();

        $myContacts = $this->user->getListByAccount($this->app->user->account);
        $disabled   = $globalContacts;

        if(!empty($myContacts) && !empty($globalContacts))
        {
            foreach($globalContacts as $id)
            {
                if(in_array($id, array_keys($myContacts))) unset($disabled[array_search($id, $disabled)]);
            }
        }

        $listID = $listID ? $listID : key($lists);
        if(!$listID) $mode = 'new';

        /* Create or manage list according to mode. */
        if($mode == 'new')
        {
            $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->user->contacts->createList;
            $this->view->position[] = $this->lang->user->contacts->createList;
        }
        else
        {
            $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->user->contacts->manage;
            $this->view->position[] = $this->lang->user->contacts->manage;
            $this->view->list       = $this->user->getContactListByID($listID);
        }

        $users = $this->user->getPairs('noletter|noempty|noclosed|noclosed', $mode == 'new' ? '' : $this->view->list->userList, $this->config->maxCount);
        if(isset($this->config->user->moreLink)) $this->config->moreLinks['users[]'] = $this->config->user->moreLink;

        $this->view->mode           = $mode;
        $this->view->lists          = $lists;
        $this->view->listID         = $listID;
        $this->view->users          = $users;
        $this->view->disabled       = $disabled;
        $this->view->globalContacts = $globalContacts;
        $this->display();
    }

    /**
     * Delete a contact list.
     *
     * @param  int    $listID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function deleteContacts($listID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->user->contacts->confirmDelete, inlink('deleteContacts', "listID=$listID&confirm=yes")));
        }
        else
        {
            $this->user->deleteContactList($listID);
            return print(js::locate(inlink('manageContacts'), 'parent'));
        }
    }

    /**
     * Build contact lists.
     *
     * @param  string $dropdownName
     * @access public
     * @return void
     */
    public function buildContactLists($dropdownName = 'mailto')
    {
        $this->view->contactLists = $this->user->getContactLists($this->app->user->account, 'withnote');
        $this->view->dropdownName = $dropdownName;
        $this->display();
    }

    /**
     * View my profile.
     *
     * @access public
     * @return void
     */
    public function profile()
    {
        if($this->app->user->account == 'guest') return print(js::alert('guest') . js::locate('back'));

        $this->app->loadConfig('user');
        $this->app->loadLang('user');
        $user = $this->user->getById($this->app->user->account);

        $this->view->title        = $this->lang->my->common . $this->lang->colon . $this->lang->my->profile;
        $this->view->position[]   = $this->lang->my->profile;
        $this->view->user         = $user;
        $this->view->groups       = $this->loadModel('group')->getByAccount($this->app->user->account);
        $this->view->deptPath     = $this->dept->getParents($user->dept);
        $this->view->personalData = $this->user->getPersonalData();
        $this->display();
    }

    /**
     * User preference setting.
     *
     * @access public
     * @return void
     */
    public function preference($showTip = true)
    {
        $this->loadModel('setting');

        if($_POST)
        {
            foreach($_POST as $key => $value) $this->setting->setItem("{$this->app->user->account}.common.$key", $value);

            $this->setting->setItem("{$this->app->user->account}.common.preferenceSetted", 1);
            if(isOnlybody()) return print(js::closeModal('parent.parent'));

            return print(js::locate($this->createLink('my', 'index'), 'parent'));
        }

        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->preference;
        $this->view->position[] = $this->lang->my->preference;
        $this->view->showTip    = $showTip;

        $this->view->URSRList         = $this->loadModel('custom')->getURSRPairs();
        $this->view->URSR             = $this->setting->getURSR();
        $this->view->programLink      = isset($this->config->programLink)   ? $this->config->programLink   : 'program-browse';
        $this->view->productLink      = isset($this->config->productLink)   ? $this->config->productLink   : 'product-all';
        $this->view->projectLink      = isset($this->config->projectLink)   ? $this->config->projectLink   : 'project-browse';
        $this->view->executionLink    = isset($this->config->executionLink) ? $this->config->executionLink : 'execution-task';
        $this->view->preferenceSetted = isset($this->config->preferenceSetted) ? true : false;

        $this->display();
    }

    /**
     * My dynamic.
     *
     * @param  string $type
     * @param  int    $recTotal
     * @param  string $date
     * @param  string $direction    next|pre
     * @access public
     * @return void
     */
    public function dynamic($type = 'today', $recTotal = 0, $date = '', $direction = 'next', $originTotal = 0)
    {
        /* Save session. */
        $uri = $this->app->getURI(true);
        $this->session->set('productList',        $uri, 'product');
        $this->session->set('storyList',          $uri, 'product');
        $this->session->set('designList',         $uri, 'project');
        $this->session->set('productPlanList',    $uri, 'product');
        $this->session->set('releaseList',        $uri, 'product');
        $this->session->set('programList',        $uri, 'program');
        $this->session->set('projectList',        $uri, 'project');
        $this->session->set('executionList',      $uri, 'execution');
        $this->session->set('taskList',           $uri, 'execution');
        $this->session->set('buildList',          $uri, 'execution');
        $this->session->set('bugList',            $uri, 'qa');
        $this->session->set('caseList',           $uri, 'qa');
        $this->session->set('caselibList',        $uri, 'qa');
        $this->session->set('testsuiteList',      $uri, 'qa');
        $this->session->set('testtaskList',       $uri, 'qa');
        $this->session->set('reportList',         $uri, 'qa');
        $this->session->set('docList',            $uri, 'doc');
        $this->session->set('todoList',           $uri, 'my');
        $this->session->set('riskList',           $uri, 'project');
        $this->session->set('issueList',          $uri, 'project');
        $this->session->set('stakeholderList',    $uri, 'project');
        $this->session->set('meetingroomList',    $uri, 'admin');
        $this->session->set('meetingList',        $uri, 'project');
        $this->session->set('meetingList',        $uri, 'assetlib');
        $this->session->set('storyLibList',       $uri, 'assetlib');
        $this->session->set('issueLibList',       $uri, 'assetlib');
        $this->session->set('riskLibList',        $uri, 'assetlib');
        $this->session->set('opportunityLibList', $uri, 'assetlib');
        $this->session->set('practiceLibList',    $uri, 'assetlib');
        $this->session->set('componentLibList',   $uri, 'assetlib');
        $this->session->set('opportunityList',    $uri, 'project');

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage = 50, $pageID = 1);

        /* Append id for secend sort. */
        $orderBy = $direction == 'next' ? 'date_desc' : 'date_asc';

        /* The header and position. */
        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->dynamic;
        $this->view->position[] = $this->lang->my->dynamic;

        $date    = empty($date) ? '' : date('Y-m-d', $date);
        $actions = $this->loadModel('action')->getDynamic($this->app->user->account, $type, $orderBy, $pager, 'all', 'all', 'all', $date, $direction);
        if(empty($recTotal)) $originTotal = $pager->recTotal;

        /* Assign. */
        $this->view->type        = $type;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->dateGroups  = $this->action->buildDateGroup($actions, $direction, $type);
        $this->view->direction   = $direction;
        $this->view->originTotal = $originTotal;
        $this->display();
    }

    /**
     * Upload avatar.
     *
     * @access public
     * @return void
     */
    public function uploadAvatar()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $result = $this->loadModel('user')->uploadAvatar();
            $this->send($result);
        }
    }

    /**
     * Unbind ranzhi
     *
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function unbind($confirm = 'no')
    {
        $this->loadModel('user');
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->user->confirmUnbind, $this->createLink('my', 'unbind', "confirm=yes")));
        }
        else
        {
            $this->user->unbind($this->app->user->account);
            return print(js::locate($this->createLink('my', 'profile'), 'parent'));
        }
    }

    /**
     * Switch vision by ajax.
     *
     * @param  string $vision
     * @access public
     * @return void
     */
    public function ajaxSwitchVision($vision)
    {
        $_SESSION['vision'] = $vision;
        $this->loadModel('setting')->setItem("{$this->app->user->account}.common.global.vision", $vision);
        $this->config->vision = $vision;

        $_SESSION['user']->rights = $this->loadModel('user')->authorize($this->app->user->account);

        echo js::locate($this->createLink('index', 'index'), 'parent');
    }
}
