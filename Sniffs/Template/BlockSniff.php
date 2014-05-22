<?php

class Magento_Sniffs_Template_BlockSniff implements PHP_CodeSniffer_Sniff
{
    public $methods = array(
        'createBlock',
    );

    public function register()
    {
        return array(T_STRING);
    }

    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if (!in_array($tokens[$stackPtr]['content'], $this->methods))
            return;

        $prevToken = $phpcsFile->findPrevious(T_WHITESPACE, $stackPtr - 1, null, true);
        if ($tokens[$prevToken]['code'] !== T_OBJECT_OPERATOR)
            return;

        $phpcsFile->addError('createBlock() should not be used in a template file', $stackPtr);
    }
}