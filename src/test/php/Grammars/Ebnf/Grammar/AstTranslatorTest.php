<?php namespace Helstern\Nomsky\Grammars\Ebnf\Grammar;

use Helstern\Nomsky\Grammar\Conversions;
use Helstern\Nomsky\Grammars\Ebnf\IsoEbnf\LexerFactory as IsoEbnfLexerFactory;
use Helstern\Nomsky\Grammars\Ebnf\IsoEbnf\Parser as IsoEbnfParser;
use Helstern\Nomsky\Parser\Errors\ParseAssertions;
use Helstern\Nomsky\TestCase;
use Helstern\Nomsky\Tokens\TokenPredicates;

class AstTranslatorTest extends TestCase
{
    public function testLogoGrammar()
    {
        $grammarFile = $this->getResourceFilePath('logo-simple.ebnf');
        $lexer = (new IsoEbnfLexerFactory())->fromFile($grammarFile);

        $assertions = new ParseAssertions(new TokenPredicates);
        $parser = new IsoEbnfParser($assertions);

        $syntaxNode = $parser->parse($lexer);
        $translator = new AstTranslator();
        $grammar = $translator->translate($syntaxNode);

        $this->assertNotNull($grammar);
    }

    public function testBnfConversion()
    {
        $grammarFile = $this->getResourceFilePath('logo-simple.ebnf');
        $lexer = (new IsoEbnfLexerFactory())->fromFile($grammarFile);

        $assertions = new ParseAssertions(new TokenPredicates);
        $parser = new IsoEbnfParser($assertions);

        $syntaxNode = $parser->parse($lexer);
        $translator = new AstTranslator();
        $ebnfGrammar = $translator->translate($syntaxNode);

        $conversions = new Conversions();
        $bnfGrammar = $conversions->ebnfToBnf($ebnfGrammar);

        $this->assertNotNull($bnfGrammar);
    }
}

