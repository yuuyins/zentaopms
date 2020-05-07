<?php
class my extends control
{
    /**
     * Get doing projects.
     *
     * @access public
     * @return void
     */
    public function getDoingProjects()
    {
        $user = $this->dao->select('*')->from(TABLE_USER)->where('account')->eq('admin')->fetch();
        $user->admin = true;
        $this->app->user = $user;
        $this->session->set('user', $user);

        $projects = $this->loadModel('project')->getProjectStats('doing');

        $datas = array();
        $users = $this->dao->select('account, realname')->from(TABLE_USER)->fetchPairs();
        foreach($projects as $project)
        {
            $data = new stdclass();
            $data->name        = $project->name;
            $data->end         = date('Y/m/d', strtotime($project->end));
            $data->progress    = $project->hours->progress;
            $data->managerName = zget($users, $project->PO);
            $data->burnData    = $project->burns;
            $data->delayRisk   = false;
            $data->delay       = $project->delay;
            $data->left        = $project->hours->totalLeft;

            if(!$project->delay)
            {
                /* Compute if the project has the risk of delay. */
                $leftHours = 0;
                $leftDays  = helper::diffDate($project->end, date(DT_DATE1));
                $members   = $this->project->getTeamMembers($project->id);
                foreach($members as $member) $leftHours += $leftDays * $member->hours;

                $data->delayRisk = $leftHours <= $data->left;
            }

            $datas[] = $data;
        }

        $this->send(array('status' => 'success', 'projects' => $datas));
    }
}
