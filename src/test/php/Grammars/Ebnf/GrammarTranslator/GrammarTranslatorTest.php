<?php namespace Helstern\Nomsky\Grammars\Ebnf\GrammarTranslator;

use Helstern\Nomsky\Grammars\Ebnf\IsoEbnfLexerFactory;
use Helstern\Nomsky\Grammars\Ebnf\IsoEbnfParser;
use Helstern\Nomsky\Parser\Errors\ParseAssertions;
use Helstern\Nomsky\TestCase;
use Helstern\Nomsky\Tokens\TokenPredicates;

class GrammarTranslatorTest extends TestCase
{
    public function testLogoGrammar()
    {
        $grammarFile = $this->getResourceFilePath('logo-simple.ebnf');
        $lexer = (new IsoEbnfLexerFactory())->fromFile($grammarFile);

        $assertions = new ParseAssertions(new TokenPredicates);
        $parser = new IsoEbnfParser($assertions);

        $syntaxNode = $parser->parse($lexer);
        $translator = new GrammarTranslator();
        $grammar = $translator->translate($syntaxNode);

        $this->assertNotNull($grammar);
    }
}

