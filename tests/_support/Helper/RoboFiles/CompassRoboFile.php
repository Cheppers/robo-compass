<?php

namespace Sweetchuck\Robo\Compass\Test\Helper\RoboFiles;

use Sweetchuck\Robo\Compass\CompassTaskLoader;
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

    public function clean($dir): TaskInterface
    {
        return $this
            ->taskCompassClean(['workingDirectory' =>  $dir])
            ->setOutput($this->output())
            ->setBoring(true)
            ->setSassDir('css-src');
    }

    public function validate(
        string $dir,
        array $options = [
            'boring' => false,
        ]
    ): TaskInterface {
        return $this
            ->taskCompassValidate(['workingDirectory' =>  $dir])
            ->setOutput($this->output())
            ->setBoring($options['boring'])
            ->setSassDir('css-src');
    }
}
