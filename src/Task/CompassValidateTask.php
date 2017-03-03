<?php

namespace Cheppers\Robo\Compass\Task;

use Cheppers\Robo\Compass\Option\CommonOption;

class CompassValidateTask extends BaseTask
{
    use CommonOption;

    /**
     * {@inheritdoc}
     */
    protected $taskName = 'Compass Validate';

    /**
     * {@inheritdoc}
     */
    protected $action = 'validate';

    /**
     * {@inheritdoc}
     */
    protected $assets = [
        'invalidFiles' => [],
    ];

    /**
     * {@inheritdoc}
     */
    protected function getOptions(): array
    {
        return $this->getOptionsCommon() + parent::getOptions();
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        parent::setOptions($options);
        $this->setOptionsCommon($options);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function runProcessOutputs()
    {
        $pattern = "/^(\e\[31m){0,1}  invalid(\e\[0m){0,1} (?P<fileName>.+)$/um";
        $matches = [];
        if (preg_match_all($pattern, $this->actionStdOutput, $matches)) {
            $this->assets['invalidFiles'] = $matches['fileName'];
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getTaskResultCode(): int
    {
        if ($this->actionExitCode === 0 && $this->assets['invalidFiles']) {
            return 1;
        }

        return parent::getTaskResultCode();
    }
}
