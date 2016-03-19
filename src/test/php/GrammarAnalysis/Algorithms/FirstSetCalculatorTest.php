<?php namespace Helstern\Nomsky\GrammarAnalysis\Algorithms;

use Helstern\Nomsky\Grammar\Symbol\ArraySet;
use Helstern\Nomsky\Grammar\Symbol\EpsilonSymbol;
use Helstern\Nomsky\Grammar\Symbol\StandardSymbol;
use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\GrammarAnalysis\ParseSets\ParseSets;
use Helstern\Nomsky\TestCase;

class FirstSetCalculatorTest extends TestCase
{
    public function testComputedSetDoesNotContainEpsilonWhenListHasNotEmptyNonTerminal()
    {
        $nonTerminals = [
            'A_1' => StandardSymbol::nonTerminal('A_1')
            , 'A_2' => StandardSymbol::nonTerminal('A_2')
            , 'A_3' => StandardSymbol::nonTerminal('A_3')
            , 'A_4' => StandardSymbol::nonTerminal('A_4')
        ];

        $terminals = [
            'b_1' => StandardSymbol::terminal('b_1')
            , 'b_2' => StandardSymbol::terminal('b_2')
            , 'b_3' => StandardSymbol::terminal('b_3')
            , 'b_4' => StandardSymbol::terminal('b_4')
        ];

        $parseSets = [
            'A_1' => [EpsilonSymbol::singletonInstance()]
            , 'A_2' => [EpsilonSymbol::singletonInstance()]
            , 'A_3' => [$terminals['b_3']]
            , 'A_4' => [EpsilonSymbol::singletonInstance(), $terminals['b_4']]
        ];

        //A_1 and A_2 generate epsilon, but not A_3 so
        $sentence = 'A_2 A_2 A_1 A_3 A_4';

        //we should not see anything past the first terminal
        $expectedSet = new ArraySet();
        $expectedSet->add($terminals['b_3']);

        $actualSet = $this->buildFirstSet($sentence, $parseSets, $nonTerminals, $terminals);

        $this->assertEquals($expectedSet->count(), $actualSet->count(), 'unexpected number of actual items found');
        /** @var Symbol $symbol */
        foreach ($actualSet as $symbol) {
            $contains = $expectedSet->contains($symbol);
            $this->assertTrue($contains, sprintf('Symbol %s not found', $symbol->toString()));
        }
    }

    public function testComputedSetDoesNotContainEpsilonWhenListHasATerminal()
    {
        $nonTerminals = [
            'A_1' => StandardSymbol::nonTerminal('A_1')
            , 'A_2' => StandardSymbol::nonTerminal('A_2')
            , 'A_3' => StandardSymbol::nonTerminal('A_3')
            , 'A_4' => StandardSymbol::nonTerminal('A_4')
        ];

        $terminals = [
            'b_1' => StandardSymbol::terminal('b_1')
            , 'b_2' => StandardSymbol::terminal('b_2')
            , 'b_4' => StandardSymbol::terminal('b_4')
        ];

        $parseSets = [
            'A_1' => [EpsilonSymbol::singletonInstance(), $terminals['b_1']]
            , 'A_2' => [EpsilonSymbol::singletonInstance(), $terminals['b_2']]
            , 'A_3' => [EpsilonSymbol::singletonInstance()]
            , 'A_4' => [EpsilonSymbol::singletonInstance(), $terminals['b_4']]
        ];

        $sentence = 'A_2 A_2 A_1 b_1 A_3 A_4';

        //we should not see anything past the first terminal
        $expectedSet = new ArraySet();
        $expectedSet->add($terminals['b_1']);
        $expectedSet->add($terminals['b_2']);

        $actualSet = $this->buildFirstSet($sentence, $parseSets, $nonTerminals, $terminals);

        $this->assertEquals($expectedSet->count(), $actualSet->count(), 'unexpected number of actual items found');
        /** @var Symbol $symbol */
        foreach ($actualSet as $symbol) {
            $contains = $expectedSet->contains($symbol);
            $this->assertTrue($contains, sprintf('Symbol %s not found', $symbol->toString()));
        }
    }

    /**
     * @param $sentence
     * @param array|Symbol[][] $parseSets
     * @param array|Symbol[] $nonTerminals
     * @param array|Symbol[] $terminals
     *
     * @return ArraySet
     */
    private function buildFirstSet($sentence, array $parseSets, array $nonTerminals, array $terminals)
    {
        $firstSets = $this->buildFirstSets($nonTerminals, $parseSets);
        $sentenceList = $this->buildSentenceList($sentence, $nonTerminals, $terminals);

        $set = new ArraySet();
        $calculator = new FirstSetCalculator();
        $calculator->processSymbolList($set, $sentenceList, $firstSets);
        return $set;
    }

    /**
     * @param $sentence
     * @param array|Symbol[] $nonTerminals
     * @param array|Symbol[] $terminals
     *
     * @return array
     */
    private function buildSentenceList($sentence, array $nonTerminals, array $terminals)
    {
        $list = [];
        foreach (explode(' ', $sentence) as $symbolItem) {
            if (array_key_exists($symbolItem, $nonTerminals)) {
                $list[] = $nonTerminals[$symbolItem];
            } else {
                $list[] = $terminals[$symbolItem];
            }
        }
        return $list;
    }

    /**
     * @param array|Symbol[] $nonTerminals
     * @param array|Symbol[][] $parseSets
     *
     * @return \Helstern\Nomsky\GrammarAnalysis\ParseSets\ParseSets
     */
    private function buildFirstSets(array $nonTerminals, array $parseSets)
    {
        $firstSets = new ParseSets(array_values($nonTerminals));
        foreach ($parseSets as $nonTerminal => $listOfTerminals) {
            foreach ($listOfTerminals as $terminal) {
                $firstSets->addTerminal($nonTerminals[$nonTerminal], $terminal);
            }
        }
        return $firstSets;
    }

    public function testComputeFromListOfNonTerminalsWhichDeriveEpsilon()
    {
        $nonTerminals = [
            'A_1' => StandardSymbol::nonTerminal('A_1')
            , 'A_2' => StandardSymbol::nonTerminal('A_2')
            , 'A_3' => StandardSymbol::nonTerminal('A_3')
            , 'A_4' => StandardSymbol::nonTerminal('A_4')
        ];

        $terminals = [
            'b_1' => StandardSymbol::terminal('b_1')
            , 'b_2' => StandardSymbol::terminal('b_2')
            , 'b_4' => StandardSymbol::terminal('b_4')
        ];

        $parseSets = [
            'A_1' => [EpsilonSymbol::singletonInstance(), $terminals['b_1']]
            , 'A_2' => [EpsilonSymbol::singletonInstance(), $terminals['b_2']]
            , 'A_3' => [EpsilonSymbol::singletonInstance()]
            , 'A_4' => [EpsilonSymbol::singletonInstance(), $terminals['b_4']]
        ];

        $sentence = 'A_2 A_2 A_1 A_3 A_4';

        $expectedSet = new ArraySet();
        $expectedSet->add($terminals['b_1']);
        $expectedSet->add($terminals['b_2']);
        $expectedSet->add($terminals['b_4']);
        $epsilon = EpsilonSymbol::singletonInstance();
        $expectedSet->add($epsilon);

        $actualSet = $this->buildFirstSet($sentence, $parseSets, $nonTerminals, $terminals);

        $this->assertEquals($expectedSet->count(), $actualSet->count(), 'unexpected number of actual items found');
        /** @var Symbol $symbol */
        foreach ($actualSet as $symbol) {
            $contains = $expectedSet->contains($symbol);
            $this->assertTrue($contains, sprintf('Symbol %s not found', $symbol->toString()));
        }
    }
}
