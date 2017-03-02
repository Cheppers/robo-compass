<?php

namespace Cheppers\Robo\Compass;

use Robo\Collection\CollectionBuilder;

trait CompassTaskLoader
{
    /**
     * @return \Cheppers\Robo\Compass\Task\CompassCompileTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskCompassCompile(array $options = []): CollectionBuilder
    {
        return $this->task(Task\CompassCompileTask::class, $options);
    }
}
