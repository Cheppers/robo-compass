<?php

namespace Cheppers\Robo\Compass\Test\Task;

use Cheppers\Robo\Compass\Task\CompassCompileTask;
use Codeception\Test\Unit;

class CompassCompileTaskTest extends Unit
{
    /**
     * @var \Cheppers\Robo\Compass\Test\UnitTester
     */
    protected $tester;

    public function casesGetCommand(): array
    {
        return [
            'basic' => [
                'bundle exec compass compile',
                [],
            ],
            'bundleExecutable empty' => [
                'compass compile',
                [
                    'bundleExecutable' => '',
                ],
            ],
            'bundleExecutable value' => [
                'my-bundle exec compass compile',
                [
                    'bundleExecutable' => 'my-bundle',
                ],
            ],
            'workingDirectory' => [
                "cd 'my-dir' && compass compile",
                [
                    'bundleExecutable' => '',
                    'workingDirectory' => 'my-dir'
                ],
            ],
            'compassExecutable' => [
                "my-compass compile",
                [
                    'bundleExecutable' => '',
                    'compassExecutable' => 'my-compass'
                ],
            ],
            'sourceMap true' => [
                'compass compile --sourcemap',
                [
                    'bundleExecutable' => '',
                    'sourceMap' => true,
                ],
            ],
            'sourceMap false' => [
                'compass compile --no-sourcemap',
                [
                    'bundleExecutable' => '',
                    'sourceMap' => false,
                ],
            ],
            'time true' => [
                'compass compile --time',
                [
                    'bundleExecutable' => '',
                    'time' => true,
                ],
            ],
            'require vector' => [
                "compass compile --require 'a' --require 'b'",
                [
                    'bundleExecutable' => '',
                    'require' => ['a', 'b'],
                ],
            ],
            'require boolean-map' => [
                "compass compile --require 'a' --require 'c'",
                [
                    'bundleExecutable' => '',
                    'require' => ['a' => true, 'b' => false, 'c' => true],
                ],
            ],
            'load' => [
                "compass compile --load 'a'",
                [
                    'bundleExecutable' => '',
                    'load' => 'a',
                ],
            ],
            'loadAll' => [
                "compass compile --load-all 'a'",
                [
                    'bundleExecutable' => '',
                    'loadAll' => 'a',
                ],
            ],
            'importPath' => [
                "compass compile --import-path 'a'",
                [
                    'bundleExecutable' => '',
                    'importPath' => 'a',
                ],
            ],
            'quiet' => [
                'compass compile --quiet',
                [
                    'bundleExecutable' => '',
                    'quiet' => true,
                ],
            ],
            'trace' => [
                'compass compile --trace',
                [
                    'bundleExecutable' => '',
                    'trace' => true,
                ],
            ],
            'force' => [
                'compass compile --force',
                [
                    'bundleExecutable' => '',
                    'force' => true,
                ],
            ],
            'boring' => [
                'compass compile --boring',
                [
                    'bundleExecutable' => '',
                    'boring' => true,
                ],
            ],
            'configFile' => [
                "compass compile --config 'a.rb'",
                [
                    'bundleExecutable' => '',
                    'configFile' => 'a.rb',
                ],
            ],
            'app' => [
                "compass compile --app 'a/b'",
                [
                    'bundleExecutable' => '',
                    'app' => 'a/b',
                ],
            ],
            'appDir' => [
                "compass compile --app-dir 'a/b'",
                [
                    'bundleExecutable' => '',
                    'appDir' => 'a/b',
                ],
            ],
            'sassDir' => [
                "compass compile --sass-dir 'a/b'",
                [
                    'bundleExecutable' => '',
                    'sassDir' => 'a/b',
                ],
            ],
            'cssDir' => [
                "compass compile --css-dir 'a/b'",
                [
                    'bundleExecutable' => '',
                    'cssDir' => 'a/b',
                ],
            ],
            'imagesDir' => [
                "compass compile --images-dir 'a/b'",
                [
                    'bundleExecutable' => '',
                    'imagesDir' => 'a/b',
                ],
            ],
            'javaScriptsDir' => [
                "compass compile --javascripts-dir 'a/b'",
                [
                    'bundleExecutable' => '',
                    'javaScriptsDir' => 'a/b',
                ],
            ],
            'fontsDir' => [
                "compass compile --fonts-dir 'a/b'",
                [
                    'bundleExecutable' => '',
                    'fontsDir' => 'a/b',
                ],
            ],
            'generatedImagesPath' => [
                "compass compile --generated-images-path 'a/b'",
                [
                    'bundleExecutable' => '',
                    'generatedImagesPath' => 'a/b',
                ],
            ],
            'environment' => [
                "compass compile --environment 'dev'",
                [
                    'bundleExecutable' => '',
                    'environment' => 'dev',
                ],
            ],
            'outputStyle' => [
                "compass compile --output-style 'dev'",
                [
                    'bundleExecutable' => '',
                    'outputStyle' => 'dev',
                ],
            ],
            'relativeAssets' => [
                'compass compile --relative-assets',
                [
                    'bundleExecutable' => '',
                    'relativeAssets' => true,
                ],
            ],
            'noLineComments' => [
                'compass compile --no-line-comments',
                [
                    'bundleExecutable' => '',
                    'noLineComments' => true,
                ],
            ],
            'httpPath' => [
                "compass compile --http-path 'foo'",
                [
                    'bundleExecutable' => '',
                    'httpPath' => 'foo',
                ],
            ],
        ];
    }

    /**
     * @dataProvider casesGetCommand
     */
    public function testGetCommand(string $expected, array $options): void
    {
        $task = new CompassCompileTask($options);
        $this->tester->assertEquals($expected, $task->getCommand());
    }

    public function testGetCommandAddRemoveRequire(): void
    {

        $task = (new CompassCompileTask())
            ->setBundleExecutable('')
            ->setRequire(['a', 'b', 'c'])
            ->addRequire('d')
            ->removeRequire('b');

        $this->tester->assertEquals(
            "compass compile --require 'a' --require 'c' --require 'd'",
            $task->getCommand()
        );
    }
}
