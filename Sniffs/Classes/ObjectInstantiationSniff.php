<?php
namespace MagentoSniffs\Classes;

use PHP_CodeSniffer_File;

/**
 * Class ObjectInstantiationSniff
 * @package MagentoSniffs\Classes
 * @author Adam Paterson <hello@adampaterson.co.uk>
 */
class ObjectInstantiationSniff implements \PHP_CodeSniffer_Sniff
{
    /**
     * Class prefixes which trigger a sniff failure
     * @var array
     */
    protected $forbiddenClassPrefixes = array(
        'Mage_',
        'Enterprise_'
    );

    /**
     * Check for instantiation using 'new'
     *
     * @return array
     */
    public function register()
    {
        return array(
            T_NEW
        );
    }

    /**
     * @param PHP_CodeSniffer_File $phpcsFile
     * @param int $stackPtr
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $next = $phpcsFile->findNext(T_STRING, $stackPtr + 1);
        $className = $phpcsFile->getTokens()[$next]['content'];
        if (preg_match('/^(' . implode('|', $this->forbiddenClassPrefixes) . ')/i', $className)) {
            $phpcsFile->addWarning(
                'Object instantiation using new is discouraged in Magento. Found %s',
                $stackPtr,
                'DirectInstantiation',
                array($className)
            );
        }
    }
}
