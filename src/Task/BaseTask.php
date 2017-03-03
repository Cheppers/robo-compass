<?php

namespace Cheppers\Robo\Compass\Task;

use Cheppers\AssetJar\AssetJarAware;
use Cheppers\AssetJar\AssetJarAwareInterface;
use Robo\Common\OutputAwareTrait;
use Robo\Contract\CommandInterface;
use Robo\Contract\OutputAwareInterface;
use Robo\Result;
use Robo\Task\BaseTask as RoboBaseTask;
use Robo\TaskInfo;
use Symfony\Component\Process\Process;

abstract class BaseTask extends RoboBaseTask implements CommandInterface, AssetJarAwareInterface, OutputAwareInterface
{
    use AssetJarAware;
    use OutputAwareTrait;

    /**
     * @var string
     */
    protected $taskName = 'Compass';

    /**
     * @var string
     */
    protected $command = '';

    /**
     * @var string
     */
    protected $processClass = Process::class;

    /**
     * @var int
     */
    protected $actionExitCode = 0;

    /**
     * @var string
     */
    protected $actionStdOutput = '';

    /**
     * @var string
     */
    protected $actionStdError = '';

    /**
     * @var array
     */
    protected $assets = [];

    //region Options.
    //region Option - action.
    protected $action = '';

    public function getAction(): string
    {
        return $this->action;
    }
    //endregion

    //region Option - workingDirectory.
    /**
     * @var string
     */
    protected $workingDirectory = '';

    public function getWorkingDirectory(): string
    {
        return $this->workingDirectory;
    }

    /**
     * @return $this
     */
    public function setWorkingDirectory(string $value)
    {
        $this->workingDirectory = $value;

        return $this;
    }
    //endregion

    //region Option - bundleExecutable.
    /**
     * @var string
     */
    protected $bundleExecutable = 'bundle';

    public function getBundleExecutable(): string
    {
        return $this->bundleExecutable;
    }

    /**
     * @return $this
     */
    public function setBundleExecutable(string $value)
    {
        $this->bundleExecutable = $value;

        return $this;
    }
    //endregion

    //region Option - compassExecutable.
    /**
     * @var string
     */
    protected $compassExecutable = 'compass';

    public function getCompassExecutable(): string
    {
        return $this->compassExecutable;
    }

    /**
     * @return $this
     */
    public function setCompassExecutable(string $value)
    {
        $this->compassExecutable = $value;

        return $this;
    }
    //endregion
    //endregion

    /**
     * {@inheritdoc}
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    protected function getOptions(): array
    {
        return [
            'workingDirectory' => [
                'type' => 'other',
                'value' => $this->getWorkingDirectory(),
            ],
            'bundleExecutable' => [
                'type' => 'other',
                'value' => $this->getBundleExecutable(),
            ],
            'compassExecutable' => [
                'type' => 'other',
                'value' => $this->getCompassExecutable(),
            ],
            'action' => [
                'type' => 'other',
                'value' => $this->getAction(),
            ],
        ];
    }

    /**
     * @return $this
     */
    public function setOptions(array $option)
    {
        foreach ($option as $name => $value) {
            switch ($name) {
                case 'assetJar':
                    $this->setAssetJar($value);
                    break;

                case 'assetJarMapping':
                    $this->setAssetJarMapping($value);
                    break;

                case 'workingDirectory':
                    $this->setWorkingDirectory($value);
                    break;

                case 'bundleExecutable':
                    $this->setBundleExecutable($value);
                    break;

                case 'compassExecutable':
                    $this->setCompassExecutable($value);
                    break;
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCommand()
    {
        $cmdPattern = '';
        $cmdArgs = [];

        $options = $this->getOptions();

        if ($options['workingDirectory']['value']) {
            $cmdPattern .= 'cd %s && ';
            $cmdArgs[] = escapeshellarg($options['workingDirectory']['value']);
        }

        if ($options['bundleExecutable']['value']) {
            $cmdPattern .= '%s exec ';
            $cmdArgs[] = escapeshellcmd($options['bundleExecutable']['value']);
        }

        $cmdPattern .= '%s %s';
        $cmdArgs[] = escapeshellcmd($options['compassExecutable']['value']);
        $cmdArgs[] = $options['action']['value'];

        foreach ($options as $optionName => $option) {
            switch ($option['type']) {
                case 'flag':
                    if ($option['value']) {
                        $cmdPattern .= " --{$optionName}";
                    }
                    break;

                case 'tri-state':
                    if ($option['value'] !== null) {
                        $cmdPattern .= $option['value'] ? " --{$optionName}" : " --no-{$optionName}";
                    }
                    break;

                case 'value':
                    if ($option['value']) {
                        $cmdPattern .= " --{$optionName} %s";
                        $cmdArgs[] = escapeshellarg($option['value']);
                    }
                    break;

                case 'value-repeat':
                    $items = array_keys($option['value'], true, true);
                    foreach ($items as $item) {
                        $cmdPattern .= " --{$optionName} %s";
                        $cmdArgs[] = escapeshellarg($item);
                    }
                    break;
            }
        }

        return vsprintf($cmdPattern, $cmdArgs);
    }

    public function getTaskName(): string
    {
        return $this->taskName ?: TaskInfo::formatTaskName($this);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTaskContext($context = null)
    {
        if (!$context) {
            $context = [];
        }

        if (empty($context['name'])) {
            $context['name'] = $this->getTaskName();
        }

        return parent::getTaskContext($context);
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->command = $this->getCommand();

        return $this
            ->runHeader()
            ->runDoIt()
            ->runProcessOutputs()
            ->runReleaseAssets()
            ->runReturn();
    }

    /**
     * @return $this
     */
    protected function runHeader()
    {
        $this->printTaskInfo($this->command);

        return $this;
    }

    /**
     * @return $this
     */
    protected function runDoIt()
    {
        /** @var \Symfony\Component\Process\Process $process */
        $process = new $this->processClass($this->command);

        $this->actionExitCode = $process->run(function ($type, $data) {
            $this->runCallback($type, $data);
        });
        $this->actionStdOutput = $process->getOutput();
        $this->actionStdError = $process->getErrorOutput();

        return $this;
    }

    /**
     * @return $this
     */
    protected function runProcessOutputs()
    {
        return $this;
    }

    /**
     * @return $this
     */
    protected function runReleaseAssets()
    {
        if ($this->hasAssetJar()) {
            $assetJar = $this->getAssetJar();
            foreach ($this->assets as $name => $value) {
                $mapping = $this->getAssetJarMap($name);
                if ($mapping) {
                    $assetJar->setValue($mapping, $value);
                }
            }
        }

        return $this;
    }

    protected function runReturn(): Result
    {
        return new Result(
            $this,
            $this->getTaskResultCode(),
            $this->getTaskResultMessage(),
            $this->assets
        );
    }

    protected function runCallback(string $type, string $data): void
    {
        switch ($type) {
            case Process::OUT:
                $this->output()->write($data);
                break;

            case Process::ERR:
                $this->printTaskError($data);
                break;
        }
    }

    protected function getTaskResultCode(): int
    {
        return $this->actionExitCode;
    }

    protected function getTaskResultMessage(): string
    {
        return $this->actionStdError;
    }
}
