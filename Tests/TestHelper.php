<?php


class TestHelper
{

    protected $rootDir;

    protected $dirName;

    protected $phpcs;

    public function __construct()
    {
        $this->rootDir = dirname(dirname(__FILE__));
        $this->dirName = basename($this->rootDir);
        $this->phpcs = new \PHP_CodeSniffer_CLI();
    }

    /**
     * Run PHPCS on a file.
     *
     * @param string $file to run.
     * @return string The output from phpcs.
     */
    public function runPhpCs($file)
    {
        $options = $this->phpcs->getDefaults();
        $standard = $this->rootDir . '/ruleset.xml';
        if (
            defined('PHP_CodeSniffer::VERSION') &&
            version_compare(PHP_CodeSniffer::VERSION, '1.5.0') != -1
        ) {
            $standard = array($standard);
        }
        $options = array_merge($options, array(
            'encoding' => 'utf-8',
            'files' => array($file),
            'standard' => $standard,
        ));

        // New PHPCS has a strange issue where the method arguments
        // are not stored on the instance causing weird errors.
        $reflection = new ReflectionProperty($this->phpcs, 'values');
        $reflection->setAccessible(true);
        $reflection->setValue($this->phpcs, $options);

        ob_start();
        $this->phpcs->process($options);
        $result = ob_get_contents();
        ob_end_clean();
        return $result;
    }
}
