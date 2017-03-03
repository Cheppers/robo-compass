<?php

namespace Cheppers\Robo\Compass\Task;

use Cheppers\Robo\Compass\Option\CommonOption;

class CompassCleanTask extends BaseTask
{
    use CommonOption;

    /**
     * {@inheritdoc}
     */
    protected $taskName = 'Compass Clean';

    /**
     * {@inheritdoc}
     */
    protected $action = 'clean';

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
}
