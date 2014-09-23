<?php

namespace MagentoSniff\Performance;

use PHP_CodeSniffer_Sniff;

/**
 * Class ModelLoadSniff
 * Check for model instantiation in template files.
 *
 * @author Adam Paterson <adam@wearejh.com>
 */
class ModelLoadSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Methods to check against.
     * @var array
     */
    protected $methods = array(
        'getModel'
    );

    /**
     * Returns the token types that this sniff is interested in.
     * @return array
     */
    public function register()
    {
        return array(T_DOUBLE_COLON);
    }

    /**
     * @param PHP_CodeSniffer_File $phpcsFile
     * @param int $stackPtr
     * @return void
     */
    public function process(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if (!in_array($tokens[$stackPtr + 1]['content'], $this->methods)) {
            return;
        }

        $error = 'A model should not be loaded in a template. Found: %s';

        /** @var $model find the model class for reporting */
        $model = $phpcsFile->findNext(T_CONSTANT_ENCAPSED_STRING, $stackPtr);
        $data = array(trim($tokens[$model]['content']));
        $phpcsFile->addError($error, $stackPtr, 'Found', $data);
    }
}
