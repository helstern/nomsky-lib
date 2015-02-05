<?php namespace Helstern\Nomsky\Lexer;

class NomskyRegexLexerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function getResourceFilePath($fileName)
    {
        return implode(DIRECTORY_SEPARATOR, [__DIR__, 'resources', $fileName]);
    }

    public function testTokenizeNomskiIsoEbnf()
    {
        $grammarFile = $this->getResourceFilePath('nomsky.iso.ebnf');

        $regexTokenScanner = (new NomskyRegexLexerFactory())->fromFile($grammarFile);

        $x = 1;
    }
}
