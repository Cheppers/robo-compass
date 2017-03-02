<?php

namespace Cheppers\Robo\Compass\Task;

class CompassCompileTask extends BaseTask
{
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

    //region Option - require.
    /**
     * @var bool[]
     */
    protected $require = [];

    public function getRequire(): array
    {
        return $this->require;
    }

    /**
     * @return $this
     */
    public function setRequire(array $value)
    {
        $this->require = (gettype(reset($value)) === 'boolean') ?
            $value
            : array_fill_keys($value, true);

        return $this;
    }

    /**
     * @return $this
     */
    public function addRequire($value)
    {
        $this->require[$value] = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function removeRequire($value)
    {
        $this->require[$value] = false;

        return $this;
    }
    //endregion

    // region Option - load.
    /**
     * @var string
     */
    protected $load = '';

    public function getLoad(): string
    {
        return $this->load;
    }

    /**
     * @return $this
     */
    public function setLoad(string $value)
    {
        $this->load = $value;

        return $this;
    }
    // endregion

    // region Option - loadAll.
    /**
     * @var string
     */
    protected $loadAll = '';

    public function getLoadAll(): string
    {
        return $this->loadAll;
    }

    /**
     * @return $this
     */
    public function setLoadAll(string $value)
    {
        $this->loadAll = $value;

        return $this;
    }
    // endregion

    // region Option - importPath.
    /**
     * @var string
     */
    protected $importPath = '';

    public function getImportPath(): string
    {
        return $this->importPath;
    }

    /**
     * @return $this
     */
    public function setImportPath(string $value)
    {
        $this->importPath = $value;

        return $this;
    }
    // endregion

    // region Option - quiet.
    /**
     * @var bool
     */
    protected $quiet = false;

    public function getQuiet(): bool
    {
        return $this->quiet;
    }

    /**
     * @return $this
     */
    public function setQuiet(bool $value)
    {
        $this->quiet = $value;

        return $this;
    }
    // endregion

    // region Option - trace.
    /**
     * @var bool
     */
    protected $trace = false;

    public function getTrace(): bool
    {
        return $this->trace;
    }

    /**
     * @return $this
     */
    public function setTrace(bool $value)
    {
        $this->trace = $value;

        return $this;
    }
    // endregion

    // region Option - force.
    /**
     * @var bool
     */
    protected $force = false;

    public function getForce(): bool
    {
        return $this->force;
    }

    /**
     * @return $this
     */
    public function setForce(bool $value)
    {
        $this->force = $value;

        return $this;
    }
    // endregion

    // region Option - boring.
    /**
     * @var bool
     */
    protected $boring = false;

    public function getBoring(): bool
    {
        return $this->boring;
    }

    /**
     * @return $this
     */
    public function setBoring(bool $value)
    {
        $this->boring = $value;

        return $this;
    }
    // endregion

    //region Option - configFile.
    /**
     * @var string
     */
    protected $configFile = '';

    public function getConfigFile(): string
    {
        return $this->configFile;
    }

    /**
     * @return $this
     */
    public function setConfigFile(string $value)
    {
        $this->configFile = $value;

        return $this;
    }
    //endregion

    // region Option - app.
    /**
     * @var string
     */
    protected $app = '';

    public function getApp(): string
    {
        return $this->app;
    }

    /**
     * @return $this
     */
    public function setApp(string $value)
    {
        $this->app = $value;

        return $this;
    }
    // endregion

    // region Option - appDir.
    /**
     * @var string
     */
    protected $appDir = '';

    public function getAppDir(): string
    {
        return $this->appDir;
    }

    /**
     * @return $this
     */
    public function setAppDir(string $value)
    {
        $this->appDir = $value;

        return $this;
    }
    // endregion

    // region Option - sassDir.
    /**
     * @var string
     */
    protected $sassDir = '';

    public function getSassDir(): string
    {
        return $this->sassDir;
    }

    /**
     * @return $this
     */
    public function setSassDir(string $value)
    {
        $this->sassDir = $value;

        return $this;
    }
    // endregion

    // region Option - cssDir.
    /**
     * @var string
     */
    protected $cssDir = '';

    public function getCssDir(): string
    {
        return $this->cssDir;
    }

    /**
     * @return $this
     */
    public function setCssDir(string $value)
    {
        $this->cssDir = $value;

        return $this;
    }
    // endregion

    // region Option - imagesDir.
    /**
     * @var string
     */
    protected $imagesDir = '';

    public function getImagesDir(): string
    {
        return $this->imagesDir;
    }

    /**
     * @return $this
     */
    public function setImagesDir(string $value)
    {
        $this->imagesDir = $value;

        return $this;
    }
    // endregion

    // region Option - javaScriptsDir.
    /**
     * @var string
     */
    protected $javaScriptsDir = '';

    public function getJavaScriptsDir(): string
    {
        return $this->javaScriptsDir;
    }

    /**
     * @return $this
     */
    public function setJavaScriptsDir(string $value)
    {
        $this->javaScriptsDir = $value;

        return $this;
    }
    // endregion

    // region Option - fontsDir.
    /**
     * @var string
     */
    protected $fontsDir = '';

    public function getFontsDir(): string
    {
        return $this->fontsDir;
    }

    /**
     * @return $this
     */
    public function setFontsDir(string $value)
    {
        $this->fontsDir = $value;

        return $this;
    }
    // endregion

    // region Option - environment.
    /**
     * @var string
     */
    protected $environment = '';

    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * @return $this
     */
    public function setEnvironment(string $value)
    {
        $this->environment = $value;

        return $this;
    }
    // endregion

    // region Option - outputStyle.
    /**
     * @var string
     */
    protected $outputStyle = '';

    public function getOutputStyle(): string
    {
        return $this->outputStyle;
    }

    /**
     * @return $this
     */
    public function setOutputStyle(string $value)
    {
        $this->outputStyle = $value;

        return $this;
    }
    // endregion

    // region Option - relativeAssets.
    /**
     * @var bool
     */
    protected $relativeAssets = false;

    public function getRelativeAssets(): bool
    {
        return $this->relativeAssets;
    }

    /**
     * @return $this
     */
    public function setRelativeAssets(bool $value)
    {
        $this->relativeAssets = $value;

        return $this;
    }
    // endregion

    // region Option - noLineComments.
    /**
     * @var bool
     */
    protected $noLineComments = false;

    public function getNoLineComments(): bool
    {
        return $this->noLineComments;
    }

    /**
     * @return $this
     */
    public function setNoLineComments(bool $value)
    {
        $this->noLineComments = $value;

        return $this;
    }
    // endregion

    // region Option - httpPath.
    /**
     * @var string
     */
    protected $httpPath = '';

    public function getHttpPath(): string
    {
        return $this->httpPath;
    }

    /**
     * @return $this
     */
    public function setHttpPath(string $value)
    {
        $this->httpPath = $value;

        return $this;
    }
    // endregion

    // region Option - generatedImagesPath.
    /**
     * @var string
     */
    protected $generatedImagesPath = '';

    public function getGeneratedImagesPath(): string
    {
        return $this->generatedImagesPath;
    }

    /**
     * @return $this
     */
    public function setGeneratedImagesPath(string $value)
    {
        $this->generatedImagesPath = $value;

        return $this;
    }
    // endregion
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
            'require' => [
                'type' => 'value-repeat',
                'value' => $this->getRequire(),
            ],
            'load' => [
                'type' => 'value',
                'value' => $this->getLoad(),
            ],
            'load-all' => [
                'type' => 'value',
                'value' => $this->getLoadAll(),
            ],
            'import-path' => [
                'type' => 'value',
                'value' => $this->getImportPath(),
            ],
            'quiet' => [
                'type' => 'flag',
                'value' => $this->getQuiet(),
            ],
            'trace' => [
                'type' => 'flag',
                'value' => $this->getTrace(),
            ],
            'force' => [
                'type' => 'flag',
                'value' => $this->getForce(),
            ],
            'boring' => [
                'type' => 'flag',
                'value' => $this->getBoring(),
            ],
            'config' => [
                'type' => 'value',
                'value' => $this->getConfigFile(),
            ],
            'app' => [
                'type' => 'value',
                'value' => $this->getApp(),
            ],
            'app-dir' => [
                'type' => 'value',
                'value' => $this->getAppDir(),
            ],
            'sass-dir' => [
                'type' => 'value',
                'value' => $this->getSassDir(),
            ],
            'css-dir' => [
                'type' => 'value',
                'value' => $this->getCssDir(),
            ],
            'images-dir' => [
                'type' => 'value',
                'value' => $this->getImagesDir(),
            ],
            'javascripts-dir' => [
                'type' => 'value',
                'value' => $this->getJavaScriptsDir(),
            ],
            'fonts-dir' => [
                'type' => 'value',
                'value' => $this->getFontsDir(),
            ],
            'environment' => [
                'type' => 'value',
                'value' => $this->getEnvironment(),
            ],
            'output-style' => [
                'type' => 'value',
                'value' => $this->getOutputStyle(),
            ],
            'relative-assets' => [
                'type' => 'flag',
                'value' => $this->getRelativeAssets(),
            ],
            'no-line-comments' => [
                'type' => 'flag',
                'value' => $this->getNoLineComments(),
            ],
            'http-path' => [
                'type' => 'value',
                'value' => $this->getHttpPath(),
            ],
            'generated-images-path' => [
                'type' => 'value',
                'value' => $this->getGeneratedImagesPath(),
            ],
        ] + parent::getOptions();
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        parent::setOptions($options);

        foreach ($options as $name => $value) {
            switch ($name) {
                // @codingStandardsIgnoreStart
                case 'sourceMap':
                    $this->setSourceMap($value);
                    break;

                case 'time':
                    $this->setTime($value);
                    break;

                case 'require':
                    $this->setRequire($value);
                    break;

                case 'load':
                    $this->setLoad($value);
                    break;

                case 'loadAll':
                    $this->setLoadAll($value);
                    break;

                case 'importPath':
                    $this->setImportPath($value);
                    break;

                case 'quiet':
                    $this->setQuiet($value);
                    break;

                case 'trace':
                    $this->setTrace($value);
                    break;

                case 'force':
                    $this->setForce($value);
                    break;

                case 'boring':
                    $this->setBoring($value);
                    break;

                case 'configFile':
                    $this->setConfigFile($value);
                    break;

                case 'app':
                    $this->setApp($value);
                    break;

                case 'appDir':
                    $this->setAppDir($value);
                    break;

                case 'sassDir':
                    $this->setSassDir($value);
                    break;

                case 'cssDir':
                    $this->setCssDir($value);
                    break;

                case 'imagesDir':
                    $this->setImagesDir($value);
                    break;

                case 'javaScriptsDir':
                    $this->setJavaScriptsDir($value);
                    break;

                case 'fontsDir':
                    $this->setfontsDir($value);
                    break;

                case 'environment':
                    $this->setEnvironment($value);
                    break;

                case 'outputStyle':
                    $this->setOutputStyle($value);
                    break;

                case 'relativeAssets':
                    $this->setRelativeAssets($value);
                    break;

                case 'noLineComments':
                    $this->setNoLineComments($value);
                    break;

                case 'httpPath':
                    $this->setHttpPath($value);
                    break;

                case 'generatedImagesPath':
                    $this->setGeneratedImagesPath($value);
                    break;
                // @codingStandardsIgnoreEnd
            }
        }

        return $this;
    }
}
