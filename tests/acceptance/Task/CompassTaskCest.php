<?php

namespace Cheppers\Robo\Compass\Test\Task;

use Cheppers\Robo\Compass\Test\AcceptanceTester;
use Cheppers\Robo\Compass\Test\Helper\RoboFiles\CompassRoboFile;
use Symfony\Component\Filesystem\Filesystem;

class CompassTaskCest
{
    /**
     * @var string
     */
    protected $tmpDir = '';

    /**
     * @var null|\Symfony\Component\Filesystem\Filesystem
     */
    protected $fs = null;

    public function __construct()
    {
        $this->fs = new Filesystem();
    }

    // @codingStandardsIgnoreStart
    public function _before()
    {
        $this->initTmpDir();
    }

    public function _after()
    {
        $this->deleteTmpDir();
    }
    // @codingStandardsIgnoreEnd

    public function runCompileSuccess(AcceptanceTester $I)
    {
        $projectName = 'success';
        $projectDir = codecept_data_dir("fixtures/$projectName");
        $this->fs->mirror($projectDir, $this->tmpDir);

        $expectedStdOutput = implode("\n", [
            'directory stylesheets',
            '    write stylesheets/styles.css',
            '',
        ]);
        $expectedStdError = sprintf(
            " [Compass Compile] cd %s && bundle exec compass compile --boring --sass-dir 'css-src'\n",
            escapeshellarg($this->tmpDir)
        );

        $I->runRoboTask(CompassRoboFile::class, 'compile', $this->tmpDir);
        $I->assertEquals($expectedStdOutput, $I->getRoboTaskStdOutput());
        $I->assertEquals($expectedStdError, $I->getRoboTaskStdError());
        $I->assertEquals(0, $I->getRoboTaskExitCode());
        $I->assertFileExists("{$this->tmpDir}/stylesheets/styles.css");
    }

    public function runCompileFail(AcceptanceTester $I)
    {
        $projectName = 'fail';
        $projectDir = codecept_data_dir("fixtures/$projectName");
        $this->fs->mirror($projectDir, $this->tmpDir);

        $expectedStdOutput = implode("\n", [
            'directory stylesheets',
            '    error css-src/styles.scss (Line 4: Invalid CSS after "  display: none;": expected "}", was "")',
            'Compilation failed in 1 files.',
            ''
        ]);
        $expectedStdError = '';

        $I->runRoboTask(CompassRoboFile::class, 'compile', $this->tmpDir);
        $I->assertEquals($expectedStdOutput, $I->getRoboTaskStdOutput());
        $I->assertEquals($expectedStdError, $I->getRoboTaskStdError());
        $I->assertEquals(1, $I->getRoboTaskExitCode());
    }

    public function runCleanSuccess(AcceptanceTester $I)
    {
        $projectName = 'success';
        $projectDir = codecept_data_dir("fixtures/$projectName");
        $this->fs->mirror($projectDir, $this->tmpDir);

        $I->runRoboTask(CompassRoboFile::class, 'compile', $this->tmpDir);
        $I->assertFileExists("{$this->tmpDir}/stylesheets/styles.css");

        $expectedStdOutput = implode("\n", [
            '   delete stylesheets/styles.css',
            '',
        ]);
        $expectedStdError = '';

        $I->runRoboTask(CompassRoboFile::class, 'clean', $this->tmpDir);
        $I->assertEquals($expectedStdOutput, $I->getRoboTaskStdOutput());
        $I->assertEquals($expectedStdError, $I->getRoboTaskStdError());
        $I->assertEquals(0, $I->getRoboTaskExitCode());
        $I->assertFileNotExists("{$this->tmpDir}/stylesheets/styles.css");
    }

    protected function initTmpDir()
    {
        $dirName = tempnam(sys_get_temp_dir(), 'robo-compass.test');
        if (unlink($dirName)) {
            mkdir($dirName, 0777 - umask(), true);
            $this->fs->copy('Gemfile', "$dirName/Gemfile");
            $this->fs->copy('Gemfile.lock', "$dirName/Gemfile.lock");
        }

        $this->tmpDir = $dirName;
    }

    protected function deleteTmpDir()
    {
        if ($this->tmpDir && file_exists($this->tmpDir)) {
            $this->fs->remove($this->tmpDir);
        }
    }
}
