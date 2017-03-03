<?php

namespace Cheppers\Robo\Compass\Test\Task;

use Cheppers\AssetJar\AssetJar;
use Cheppers\Robo\Compass\Task\CompassValidateTask;
use Cheppers\Robo\Compass\Test\Helper\Dummy\Output as DummyOutput;
use Cheppers\Robo\Compass\Test\Helper\Dummy\Process as DummyProcess;
use Codeception\Test\Unit;
use Codeception\Util\Stub;
use Robo\Robo;

class CompassValidateTaskTest extends Unit
{
    /**
     * @var \Cheppers\Robo\Compass\Test\UnitTester
     */
    protected $tester;

    public function casesGetCommand(): array
    {
        return [
            'all-in-one' => [
                implode(' ', [
                    "cd 'my-dir' && my-compass validate",
                    "--require 'a'",
                    "--load 'a'",
                    "--load-all 'b'",
                    "--import-path 'c'",
                    '--quiet',
                    '--trace',
                    '--force',
                    '--boring',
                    "--config 'a.rb'",
                    "--app 'd'",
                    "--app-dir 'e'",
                    "--sass-dir 'f'",
                    "--css-dir 'g'",
                    "--images-dir 'h'",
                    "--javascripts-dir 'i'",
                    "--fonts-dir 'j'",
                    "--environment 'l'",
                    "--output-style 'm'",
                    '--relative-assets',
                    '--no-line-comments',
                    "--http-path 'n'",
                    "--generated-images-path 'k'",
                ]),
                [
                    'bundleExecutable' => '',
                    'workingDirectory' => 'my-dir',
                    'compassExecutable' => 'my-compass',
                    'require' => ['a'],
                    'load' => 'a',
                    'loadAll' => 'b',
                    'importPath' => 'c',
                    'quiet' => true,
                    'trace' => true,
                    'force' => true,
                    'boring' => true,
                    'configFile' => 'a.rb',
                    'app' => 'd',
                    'appDir' => 'e',
                    'sassDir' => 'f',
                    'cssDir' => 'g',
                    'imagesDir' => 'h',
                    'javaScriptsDir' => 'i',
                    'fontsDir' => 'j',
                    'environment' => 'l',
                    'outputStyle' => 'm',
                    'relativeAssets' => true,
                    'noLineComments' => true,
                    'httpPath' => 'n',
                    'generatedImagesPath' => 'k',
                ],
            ],
        ];
    }

    /**
     * @dataProvider casesGetCommand
     */
    public function testGetCommand(string $expected, array $options): void
    {
        $task = new CompassValidateTask($options);
        $this->tester->assertEquals($expected, $task->getCommand());
    }

    public function testRun(): void
    {
        $expectedExitCode = 1;
        $expectedInvalidFiles = [
            'a.css',
            'b.css',
        ];

        $container = Robo::createDefaultContainer();
        Robo::setContainer($container);

        $mainStdOutput = new DummyOutput([]);

        $assetJar = new AssetJar();
        $options = [
            'assetJar' => $assetJar,
            'assetJarMapping' => ['invalidFiles' => ['compassValidate', 'files']],
        ];

        /** @var \Cheppers\Robo\Compass\Task\CompassValidateTask $task */
        $task = Stub::construct(
            CompassValidateTask::class,
            [$options, []],
            [
                'processClass' => DummyProcess::class,
            ]
        );

        $processIndex = count(DummyProcess::$instances);

        DummyProcess::$prophecy[$processIndex] = [
            'exitCode' => 0,
            'stdOutput' => implode("\n", [
                '  invalid a.css',
                '  invalid b.css',
                '',
            ]),
            'stdError' => '',
        ];

        $task->setLogger($container->get('logger'));
        $task->setOutput($mainStdOutput);

        $result = $task->run();

        $this->tester->assertEquals(
            $expectedExitCode,
            $result->getExitCode(),
            'Exit code is different than the expected.'
        );

        $invalidFiles = $task->getAssetJarValue('invalidFiles');
        $this->tester->assertEquals(
            $expectedInvalidFiles,
            $invalidFiles,
            'Output equals'
        );
    }
}
