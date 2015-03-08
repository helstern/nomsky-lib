<?php namespace Helstern\Nomsky\Grammars\Ebnf;

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

    /**
     * @group now
     */
    public function testParseEbnfGrammar()
    {
        $expectedAstNode = '\\Helstern\Nomsky\Grammars\Ebnf\Ast\\SyntaxNode';

        $grammarFile = self::getResourceFilePath('ebnf.iso.ebnf');
        $lexer = (new IsoEbnfLexerFactory())->fromFile($grammarFile);

        $assertions = new ParseAssertions(new TokenPredicates);
        $parser = new IsoEbnfParser($assertions);

        $actualAstNode = $parser->parse($lexer);
        $this->assertInstanceOf($expectedAstNode, $actualAstNode, 'wrong instance type received');
    }

}
