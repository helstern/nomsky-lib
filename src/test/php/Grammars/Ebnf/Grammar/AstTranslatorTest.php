<?php namespace Helstern\Nomsky\Grammars\Ebnf\Grammar;

use Helstern\Nomsky\Grammar\Conversions;
use Helstern\Nomsky\Grammar\Expressions\Choice;
use Helstern\Nomsky\Grammar\Expressions\Concatenation;
use Helstern\Nomsky\Grammar\Expressions\ExpressionSymbol;
use Helstern\Nomsky\Grammar\Expressions\Optional;
use Helstern\Nomsky\Grammar\Expressions\Repetition;
use Helstern\Nomsky\Grammar\Production\Production;
use Helstern\Nomsky\Grammar\Production\StandardProduction;
use Helstern\Nomsky\Grammar\StandardGrammar;
use Helstern\Nomsky\Grammar\Symbol\GenericSymbol;
use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\Grammars\Ebnf\IsoEbnf\LexerFactory as IsoEbnfLexerFactory;
use Helstern\Nomsky\Grammars\Ebnf\IsoEbnf\Parser as IsoEbnfParser;
use Helstern\Nomsky\Parser\Errors\ParseAssertions;
use Helstern\Nomsky\TestCase;
use Helstern\Nomsky\Tokens\TokenPredicates;

class AstTranslatorTest extends TestCase
{
    /**
     * @return \Helstern\Nomsky\Grammar\StandardGrammar
     */
    public function createExpectedEbnfLogoGrammar()
    {
        $productions = [];
        $productions[] = new  StandardProduction(
            new GenericSymbol(Symbol::TYPE_NON_TERMINAL, 'program'),
            new Concatenation(
                new ExpressionSymbol(Symbol::TYPE_NON_TERMINAL, 'sentence'),
                [
                    new Repetition(new ExpressionSymbol(Symbol::TYPE_NON_TERMINAL, 'sentence'))
                ]
            )
        );
        $productions[] = new  StandardProduction(
            new GenericSymbol(Symbol::TYPE_NON_TERMINAL, 'sentence'),
            new Choice(
                new Concatenation(
                    new ExpressionSymbol(Symbol::TYPE_TERMINAL, 'FORWARD'),
                    [new ExpressionSymbol(Symbol::TYPE_NON_TERMINAL, 'integer')]
                )
                ,[
                    new Concatenation(
                        new ExpressionSymbol(Symbol::TYPE_TERMINAL, 'BACK'),
                        [new ExpressionSymbol(Symbol::TYPE_NON_TERMINAL, 'integer')]
                    )
                    , new Concatenation(
                        new ExpressionSymbol(Symbol::TYPE_TERMINAL, 'LEFT'),
                        [new ExpressionSymbol(Symbol::TYPE_NON_TERMINAL, 'integer')]
                    )
                    , new Concatenation(
                        new ExpressionSymbol(Symbol::TYPE_TERMINAL, 'RIGHT'),
                        [new ExpressionSymbol(Symbol::TYPE_NON_TERMINAL, 'integer')]
                    )
                    , new Concatenation(
                        new ExpressionSymbol(Symbol::TYPE_TERMINAL, 'REPEAT')
                        ,[
                            new ExpressionSymbol(Symbol::TYPE_NON_TERMINAL, 'integer')
                            , new Optional(
                                new Concatenation(
                                    new ExpressionSymbol(Symbol::TYPE_NON_TERMINAL, 'sentence')
                                    , [new Repetition(
                                        new ExpressionSymbol(Symbol::TYPE_NON_TERMINAL, 'sentence')
                                    )]
                                )
                            )
                        ]
                    )
                ]
            )
        );
        $productions[] = new  StandardProduction(
            new GenericSymbol(Symbol::TYPE_NON_TERMINAL, 'integer'),
            new Choice(
                new ExpressionSymbol(Symbol::TYPE_TERMINAL, 'ZERO')
                ,[
                    new Concatenation(
                        new ExpressionSymbol(Symbol::TYPE_TERMINAL, 'ONE_TO_NINE')
                        , [new Repetition(
                            new ExpressionSymbol(Symbol::TYPE_TERMINAL, 'DIGIT')
                        )]
                    )
                ]
            )
        );

        $grammar = new StandardGrammar('simple logo', $productions);
        return $grammar;
    }

    public function testLogoGrammar()
    {
        $grammarFile = $this->getResourceFilePath('logo-simple.ebnf');
        $lexer = (new IsoEbnfLexerFactory())->fromFile($grammarFile);

        $assertions = new ParseAssertions(new TokenPredicates);
        $parser = new IsoEbnfParser($assertions);

        $syntaxNode = $parser->parse($lexer);
        $translator = new AstTranslator();
        $actualGrammar = $translator->translate($syntaxNode);
        $this->assertNotNull($actualGrammar);

        $expectedGrammar = $this->createExpectedEbnfLogoGrammar();
        $this->assertEquals($expectedGrammar->getName(), $actualGrammar->getName());

        $actualProductions = $actualGrammar->getProductions();
        $expectedProductions = $expectedGrammar->getProductions();

        do {
            /** @var Production $actualProduction */
            $actualProduction = array_pop($actualProductions);
            /** @var Production $expectedProduction */
            $expectedProduction = array_pop($expectedProductions);

            $this->assertEquals(
                $expectedProduction->getNonTerminal()->toString()
                , $actualProduction->getNonTerminal()->toString()
            );
            $this->assertEquals(
                $expectedProduction->getExpression()
                , $actualProduction->getExpression()
            );

        } while (!is_null(key($actualProductions)) && !is_null(key($expectedProductions)));
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

