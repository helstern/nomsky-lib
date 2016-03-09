<?php namespace Helstern\Nomsky\GrammarAnalysis\ParseSets;

use Helstern\Nomsky\Grammar\Expressions\Concatenation;
use Helstern\Nomsky\Grammar\Expressions\ExpressionSymbol;
use Helstern\Nomsky\Grammar\Production\StandardProduction;
use Helstern\Nomsky\Grammar\StandardGrammar;
use Helstern\Nomsky\Grammar\Symbol\ArraySet;
use Helstern\Nomsky\Grammar\Symbol\EpsilonSymbol;
use Helstern\Nomsky\Grammar\Symbol\StandardSymbol;
use Helstern\Nomsky\GrammarAnalysis\Production\NormalizedProduction;
use Helstern\Nomsky\GrammarAnalysis\Production\Normalizer;
use Helstern\Nomsky\GrammarAnalysis\Production\SimpleHashKeyFactory;

class TestGrammar
{
    /**
     * @return array|NormalizedProduction[]
     */
    static public function normalizedProductions()
    {
        $productions = self::productions();
        $normalizer = new Normalizer();
        $normalized = $normalizer->normalizeList($productions);
        return $normalized;
    }

    /**
     * S -> a S e
     * S -> B
     * B -> b B e
     * B -> C
     * C -> c C e
     * C -> d
     */
    static public function productions()
    {
        $productions = [];
        $productions[] = new StandardProduction(
            StandardSymbol::nonTerminal('S'),
            new Concatenation(
                ExpressionSymbol::createAdapterForTerminal('a'),
                [
                    ExpressionSymbol::createAdapterForNonTerminal('S'),
                    ExpressionSymbol::createAdapterForTerminal('e'),
                ]
            )
        );
        $productions[] = new StandardProduction(
            StandardSymbol::nonTerminal('S'),
            ExpressionSymbol::createAdapterForNonTerminal('B')
        );
        $productions[] = new StandardProduction(
            StandardSymbol::nonTerminal('B'),
            new Concatenation(
                ExpressionSymbol::createAdapterForTerminal('b'),
                [
                    ExpressionSymbol::createAdapterForNonTerminal('B'),
                    ExpressionSymbol::createAdapterForTerminal('e'),
                ]
            )
        );
        $productions[] = new StandardProduction(
            StandardSymbol::nonTerminal('B'),
            ExpressionSymbol::createAdapterForNonTerminal('C')
        );
        $productions[] = new StandardProduction(
            StandardSymbol::nonTerminal('C'),
            new Concatenation(
                ExpressionSymbol::createAdapterForTerminal('c'),
                [
                    ExpressionSymbol::createAdapterForNonTerminal('C'),
                    ExpressionSymbol::createAdapterForTerminal('e'),
                ]
            )
        );
        $productions[] = new StandardProduction(
            StandardSymbol::nonTerminal('C'),
            ExpressionSymbol::createAdapterForTerminal('d')
        );

        return $productions;
    }


    static public function grammar()
    {
        $productions = self::productions();
        $grammar = new StandardGrammar('test', $productions);
        return $grammar;
    }

    /**
     * @return \Helstern\Nomsky\Grammar\Symbol\ArraySet
     */
    public static function epsilonSet()
    {
        return new ArraySet();
    }

    /**
     * @return ParseSets
     */
    public static function firstSets()
    {
        $sets = new ParseSets(
            [
                StandardSymbol::nonTerminal('S'),
                StandardSymbol::nonTerminal('B'),
                StandardSymbol::nonTerminal('C'),
            ]
        );
        $sets->addTerminal(StandardSymbol::nonTerminal('S'), StandardSymbol::terminal('a'));
        $sets->addTerminal(StandardSymbol::nonTerminal('S'), StandardSymbol::terminal('b'));
        $sets->addTerminal(StandardSymbol::nonTerminal('S'), StandardSymbol::terminal('c'));
        $sets->addTerminal(StandardSymbol::nonTerminal('S'), StandardSymbol::terminal('d'));

        $sets->addTerminal(StandardSymbol::nonTerminal('B'), StandardSymbol::terminal('b'));
        $sets->addTerminal(StandardSymbol::nonTerminal('B'), StandardSymbol::terminal('c'));
        $sets->addTerminal(StandardSymbol::nonTerminal('B'), StandardSymbol::terminal('d'));

        $sets->addTerminal(StandardSymbol::nonTerminal('C'), StandardSymbol::terminal('c'));
        $sets->addTerminal(StandardSymbol::nonTerminal('C'), StandardSymbol::terminal('d'));

        return $sets;
    }

    /**
     * @return ParseSets
     */
    public static function followSets()
    {
        $sets = new ParseSets(
            [
                StandardSymbol::nonTerminal('S'),
                StandardSymbol::nonTerminal('B'),
                StandardSymbol::nonTerminal('C'),
            ]
        );

        $sets->addTerminal(StandardSymbol::nonTerminal('S'), StandardSymbol::terminal('e'));
        $sets->addTerminal(StandardSymbol::nonTerminal('S'), new EpsilonSymbol());

        $sets->addTerminal(StandardSymbol::nonTerminal('B'), StandardSymbol::terminal('e'));
        $sets->addTerminal(StandardSymbol::nonTerminal('B'), new EpsilonSymbol());

        $sets->addTerminal(StandardSymbol::nonTerminal('C'), StandardSymbol::terminal('e'));
        $sets->addTerminal(StandardSymbol::nonTerminal('C'), new EpsilonSymbol());

        return $sets;
    }

    /**
     * @return LookAheadSets
     */
    public static function predictSets()
    {
        $productions = self::normalizedProductions();
        $sets = new LookAheadSets(new SimpleHashKeyFactory());

        $set = new ArraySet();
        $set->add(StandardSymbol::terminal('a'));
        $sets->add($productions[0], $set);

        $set = new ArraySet();
        $set->add(StandardSymbol::terminal('b'));
        $set->add(StandardSymbol::terminal('c'));
        $set->add(StandardSymbol::terminal('d'));
        $sets->add($productions[1], $set);

        $set = new ArraySet();
        $set->add(StandardSymbol::terminal('b'));
        $sets->add($productions[2], $set);

        $set = new ArraySet();
        $set->add(StandardSymbol::terminal('c'));
        $set->add(StandardSymbol::terminal('d'));
        $sets->add($productions[3], $set);

        $set = new ArraySet();
        $set->add(StandardSymbol::terminal('c'));
        $sets->add($productions[4], $set);

        $set = new ArraySet();
        $set->add(StandardSymbol::terminal('d'));
        $sets->add($productions[5], $set);

        return $sets;
    }

}
