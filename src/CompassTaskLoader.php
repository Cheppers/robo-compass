<?php

namespace Sweetchuck\Robo\Compass;

use Robo\Collection\CollectionBuilder;

trait CompassTaskLoader
{
    /**
     * @return \Sweetchuck\Robo\Compass\Task\CompassCompileTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskCompassCompile(array $options = []): CollectionBuilder
    {
        return $this->task(Task\CompassCompileTask::class, $options);
    }

    /**
     * @return \Sweetchuck\Robo\Compass\Task\CompassCleanTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskCompassClean(array $options = []): CollectionBuilder
    {
        return $this->task(Task\CompassCleanTask::class, $options);
    }

    /**
     * @return \Sweetchuck\Robo\Compass\Task\CompassValidateTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskCompassValidate(array $options = []): CollectionBuilder
    {
        return $this->task(Task\CompassValidateTask::class, $options);
    }
}
