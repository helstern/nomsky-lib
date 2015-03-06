<?php namespace Helstern\Nomsky\Parsers\EbnfParser;

use Helstern\Nomsky\Lexers\EbnfLexer\TokenStreamLexerFactory;
use Helstern\Nomsky\Parser\ParseAssertion\TokenAssertions;
use Helstern\Nomsky\Parsers\TestResources;
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
        $expectedAstNode = '\\Helstern\Nomsky\Parsers\EbnfAst\\SyntaxNode';

        $grammarFile = self::getResourceFilePath('ebnf.iso.ebnf');
        $lexer = (new TokenStreamLexerFactory())->fromFile($grammarFile);

        $assertions = new TokenAssertions(new TokenPredicates);
        $parser = new IsoEbnfParser($assertions);

        $actualAstNode = $parser->parse($lexer);
        $this->assertInstanceOf($expectedAstNode, $actualAstNode, 'wrong instance type received');
    }

}
