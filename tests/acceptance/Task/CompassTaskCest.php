<?php

namespace Cheppers\Robo\Compass\Test\Task;

use Cheppers\Robo\Compass\Test\AcceptanceTester;
use Cheppers\Robo\Compass\Test\Helper\RoboFiles\CompassRoboFile;
use Symfony\Component\Filesystem\Filesystem;

class CompassTaskCest
{
    /**
     * @var string[]
     */
    protected $tmpDirs = [];

    /**
     * @var null|\Symfony\Component\Filesystem\Filesystem
     */
    protected $fs = null;

    public function __construct()
    {
        $this->fs = new Filesystem();
    }

    public function __destruct()
    {
        $this->fs->remove($this->tmpDirs);
    }

    public function runCompileSuccess(AcceptanceTester $I): void
    {
        $tmpDir = $this->createTmpDir();
        $projectName = 'success';
        $projectDir = codecept_data_dir("fixtures/$projectName");
        $this->fs->mirror($projectDir, $tmpDir);

        $expectedStdOutput = implode("\n", [
            'directory stylesheets',
            '    write stylesheets/styles.css',
            '',
        ]);
        $expectedStdError = sprintf(
            " [Compass Compile] cd %s && bundle exec compass compile --boring --sass-dir 'css-src'\n",
            escapeshellarg($tmpDir)
        );

        $id = 'compile:success';
        $I->runRoboTask($id, CompassRoboFile::class, 'compile', $tmpDir);
        $I->assertEquals($expectedStdOutput, $I->getRoboTaskStdOutput($id));
        $I->assertEquals($expectedStdError, $I->getRoboTaskStdError($id));
        $I->assertEquals(0, $I->getRoboTaskExitCode($id));
        $I->assertFileExists("{$tmpDir}/stylesheets/styles.css");
    }

    public function runCompileFail(AcceptanceTester $I): void
    {
        $tmpDir = $this->createTmpDir();
        $projectName = 'fail';
        $projectDir = codecept_data_dir("fixtures/$projectName");
        $this->fs->mirror($projectDir, $tmpDir);

        $expectedStdOutput = implode("\n", [
            'directory stylesheets',
            '    error css-src/styles.scss (Line 4: Invalid CSS after "  display: none;": expected "}", was "")',
            'Compilation failed in 1 files.',
            ''
        ]);
        $expectedStdError = sprintf(
            " [Compass Compile] cd %s && bundle exec compass compile --boring --sass-dir 'css-src'\n",
            escapeshellarg($tmpDir)
        );

        $id = 'compile:fail';
        $I->runRoboTask($id, CompassRoboFile::class, 'compile', $tmpDir);
        $I->assertEquals($expectedStdOutput, $I->getRoboTaskStdOutput($id));
        $I->assertContains($expectedStdError, $I->getRoboTaskStdError($id));
        $I->assertEquals(1, $I->getRoboTaskExitCode($id));
    }

    public function runCleanSuccess(AcceptanceTester $I): void
    {
        $tmpDir = $this->createTmpDir();
        $projectName = 'success';
        $projectDir = codecept_data_dir("fixtures/$projectName");
        $this->fs->mirror($projectDir, $tmpDir);

        $id = 'clean:success:prepare';
        $I->runRoboTask($id, CompassRoboFile::class, 'compile', $tmpDir);
        $I->assertFileExists("{$tmpDir}/stylesheets/styles.css");

        $expectedStdOutput = implode("\n", [
            '   delete stylesheets/styles.css',
            '',
        ]);
        $expectedStdError = sprintf(
            " [Compass Clean] cd %s && bundle exec compass clean --boring --sass-dir 'css-src'\n",
            escapeshellarg($tmpDir)
        );

        $id = 'clean:success:run';
        $I->runRoboTask($id, CompassRoboFile::class, 'clean', $tmpDir);
        $I->assertEquals($expectedStdOutput, $I->getRoboTaskStdOutput($id));
        $I->assertEquals($expectedStdError, $I->getRoboTaskStdError($id));
        $I->assertEquals(0, $I->getRoboTaskExitCode($id));
        $I->assertFileNotExists("{$tmpDir}/stylesheets/styles.css");
    }

    public function runValidateSuccess(AcceptanceTester $I): void
    {
        $tmpDir = $this->createTmpDir();
        $projectName = 'success';
        $projectDir = codecept_data_dir("fixtures/$projectName");
        $this->fs->mirror($projectDir, $tmpDir);

        $expectedStdOutput = implode("\n", [
            'directory stylesheets',
            '    write stylesheets/styles.css',
            '    valid stylesheets/styles.css',
            '',
            '',
            '************************************************************',
            '',
            'Result: Valid',
            '1 file validated.',
            'So INTENSE!',
            '',
            '************************************************************',
            '',
        ]);
        $expectedStdError = sprintf(
            " [Compass Validate] cd %s && bundle exec compass validate --boring --sass-dir 'css-src'\n",
            escapeshellarg($tmpDir)
        );

        $id = 'validate:success';
        $I->runRoboTask($id, CompassRoboFile::class, 'validate', $tmpDir, '--boring');
        $I->assertEquals($expectedStdOutput, $I->getRoboTaskStdOutput($id));
        $I->assertEquals($expectedStdError, $I->getRoboTaskStdError($id));
        $I->assertEquals(0, $I->getRoboTaskExitCode($id));
        $I->assertFileExists("{$tmpDir}/stylesheets/styles.css");
    }

    public function runValidateFailWithColors(AcceptanceTester $I): void
    {
        $this->runValidateFail($I, false);
    }

    public function runValidateFailWithoutColors(AcceptanceTester $I): void
    {
        $this->runValidateFail($I, true);
    }

    protected function runValidateFail(AcceptanceTester $I, bool $boring): void
    {
        $tmpDir = $this->createTmpDir();
        $projectName = 'invalid';
        $projectDir = codecept_data_dir("fixtures/$projectName");
        $this->fs->mirror($projectDir, $tmpDir);

        $pattern = $boring ? "  %s %s\n" : "\e[31m  %s\e[0m %s\n";
        $option = $boring ? ' --boring' : '';
        $expectedStdOutput1 = sprintf($pattern, 'invalid', 'stylesheets/styles.css');
        $expectedStdOutput2 = sprintf($pattern, 'invalid', 'stylesheets/foo/bar.css');
        $expectedStdError = sprintf(
            "cd %s && bundle exec compass validate{$option} --sass-dir 'css-src'",
            escapeshellarg($tmpDir)
        );

        $args = [
            'validate',
            $tmpDir,
        ];

        if ($boring) {
            $args[] = '--boring';
        }

        $id = 'validate:fail:' . (int) $boring;
        $I->runRoboTask($id, CompassRoboFile::class, ...$args);
        $I->assertEquals(1, $I->getRoboTaskExitCode($id));
        $I->assertContains($expectedStdOutput1, $I->getRoboTaskStdOutput($id));
        $I->assertContains($expectedStdOutput2, $I->getRoboTaskStdOutput($id));
        $I->assertContains($expectedStdError, $I->getRoboTaskStdError($id));
        $I->assertFileExists("{$tmpDir}/stylesheets/styles.css");
    }

    protected function createTmpDir(): string
    {
        $dirName = tempnam(sys_get_temp_dir(), 'robo-compass.test.');
        if (unlink($dirName)) {
            mkdir($dirName, 0777 - umask(), true);
            $this->fs->copy('Gemfile', "$dirName/Gemfile");
            $this->fs->copy('Gemfile.lock', "$dirName/Gemfile.lock");
        }

        $this->tmpDirs[] = $dirName;

        return $dirName;
    }
}
