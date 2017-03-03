<?php

namespace Cheppers\Robo\Compass\Task;

use Cheppers\Robo\Compass\Option\CommonOption;

class CompassCompileTask extends BaseTask
{
    use CommonOption;

    /**
     * {@inheritdoc}
     */
    protected $taskName = 'Compass Compile';

    protected $action = 'compile';

    //region Options.
    //region Option - sourceMap.
    /**
     * @var null|bool
     */
    protected $sourceMap = null;

    public function getSourceMap(): ?bool
    {
        return $this->sourceMap;
    }

    /**
     * @return $this
     */
    public function setSourceMap(?bool $value)
    {
        $this->sourceMap = $value;

        return $this;
    }
    //endregion

    //region Option - time.
    /**
     * @var bool
     */
    protected $time = false;

    public function getTime(): bool
    {
        return $this->time;
    }

    /**
     * @return $this
     */
    public function setTime(bool $value)
    {
        $this->time = $value;

        return $this;
    }
    //endregion
    //endregion

    /**
     * {@inheritdoc}
     */
    protected function getOptions(): array
    {
        return [
            'sourcemap' => [
                'type' => 'tri-state',
                'value' => $this->getSourceMap(),
            ],
            'time' => [
                'type' => 'flag',
                'value' => $this->getTime(),
            ],
        ] + $this->getOptionsCommon() + parent::getOptions();
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        parent::setOptions($options);

        $this->setOptionsCommon($options);
        foreach ($options as $name => $value) {
            switch ($name) {
                // @codingStandardsIgnoreStart
                case 'sourceMap':
                    $this->setSourceMap($value);
                    break;

                case 'time':
                    $this->setTime($value);
                    break;
                // @codingStandardsIgnoreEnd
            }
        }

        return $this;
    }
}
