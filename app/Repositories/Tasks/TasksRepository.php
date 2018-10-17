<?php

namespace App\Repositories\Tasks;

use App\Items\Task\Task as TaskItem;
use App\Models\Task\Requirement as TaskRequirement;
use App\Models\Task\Supervisor as TaskSupervisor;
use App\Models\Task\Task;
use App\Models\Task\User as TaskUser;
use Carbon\Carbon;
use Collection;
use HZ\Laravel\Organizer\App\Contracts\RepositoryInterface;
use Model;
use RepositoryManager;
use Request;
use Str;

class TasksRepository extends RepositoryManager implements RepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    const MODEL = Task::class;

    /**
     * {@inheritDoc}
     */
    const TABLE = 'tasks';
    
    /**
     * {@inheritDoc}
     */
    const TABLE_ALIAS = 't';

    /**
     * {@inheritDoc}
     */
    const DATA = [
        'title', 'description', 'starts_at', 'ends_at', 'priority', 'priority_level',
    ];

    /**
     * {@inheritDoc}
     */
    protected function records(Collection $records): Collection 
    {
        return $records->map(function ($record) {
            return $record;
        });
    }

    /**
     * {@inheritDoc}
     */
    protected function setData(Model $task, Request $request)
    {
    } 

    /**
     * {@inheritDoc}
     */
    protected function onSave(Model $task, Request $request)
    {
        $taskRequirements = $task->requirements();

        foreach ((array) $request->requirements as $requirement) {
            $taskRequirement = $this->findOrCreate(TaskRequirement::class, $requirement['id'] ?? 0);

            $this->setModelData($taskRequirement, (array) $requirement);

            $taskRequirements->save($taskRequirement);
        }

        $taskUsers = $task->users();

        foreach ((array) $request->users as $taskUser) {
            $taskUserModel = $this->findOrCreate(TaskUserModel::class, $taskUser['id'] ?? 0);

            $this->setModelData($taskUserModel, $taskUser);

            $taskUsers->save($taskUserModel);
        }

        $taskSupervisors = $task->supervisors();

        $taskSupervisors->delete();

        foreach ((array) $request->supervisors as $supervisorId) {
            $taskSupervisor = new TaskSupervisor;

            $taskSupervisor->supervisor_id = $supervisorId;

            $taskSupervisors->save($taskSupervisor);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function get(int $id): \Item
    {
        $task = Task::find($id);
        
        $taskInfo = (object) $task->getAttributes();

        $taskInfo->requirements = $task->requirements()->toArray();
        $taskInfo->users = $task->users()->get()->toArray();
        $taskInfo->supervisors = $task->supervisors()->pluck('supervisor_id');

        return new TaskItem($taskInfo);
    }

    /**
     * {@inheritDoc}
     */
    protected function select()
    {
        # code...
    }

    /**
     * {@inheritDoc}
     */
    protected function filter() 
    {
    }  
}
