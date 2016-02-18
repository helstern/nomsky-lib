<?php namespace Helstern\Nomsky\Grammars\Ebnf\IsoEbnf;

use Helstern\Nomsky\Parser\Errors\ParseAssertions;
use Helstern\Nomsky\Grammars\TestResources;
use Helstern\Nomsky\Tokens\TokenPredicates;

class IsoEbnfParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $fileName
     * @return string
     */
    static public function getResourceFilePath($fileName)
    {
        $resource = new TestResources();
        return $resource->getResourceFilePath($fileName);
    }

    public function testParseEbnfGrammar()
    {
        $expectedAstNode = '\\Helstern\Nomsky\Grammars\Ebnf\Ast\\SyntaxNode';

        $grammarFile = self::getResourceFilePath('ebnf.iso.ebnf');
        $lexer = (new LexerFactory())->fromFile($grammarFile);

        $assertions = new ParseAssertions(new TokenPredicates);
        $parser = new Parser($assertions);

        $actualAstNode = $parser->parse($lexer);
        $this->assertInstanceOf($expectedAstNode, $actualAstNode, 'wrong instance type received');
    }

}
