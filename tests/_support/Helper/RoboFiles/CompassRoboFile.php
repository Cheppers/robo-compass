<?php

namespace Cheppers\Robo\Compass\Test\Helper\RoboFiles;

use Cheppers\Robo\Compass\CompassTaskLoader;
use Robo\Contract\TaskInterface;
use Robo\Tasks;

class CompassRoboFile extends Tasks
{
    use CompassTaskLoader;

    public function compile($dir): TaskInterface
    {
        return $this
            ->taskCompassCompile(['workingDirectory' =>  $dir])
            ->setOutput($this->output())
            ->setBoring(true)
            ->setSassDir('css-src');
    }
}
