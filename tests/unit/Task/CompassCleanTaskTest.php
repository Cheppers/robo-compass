<?php

namespace Cheppers\Robo\Compass\Test\Task;

use Cheppers\Robo\Compass\Task\CompassCleanTask;
use Codeception\Test\Unit;

class CompassCleanTaskTest extends Unit
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
                    "cd 'my-dir' && my-compass clean",
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
        $task = new CompassCleanTask($options);
        $this->tester->assertEquals($expected, $task->getCommand());
    }
}
