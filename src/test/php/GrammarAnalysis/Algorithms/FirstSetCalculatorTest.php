<?php namespace Helstern\Nomsky\GrammarAnalysis\Algorithms;

use Helstern\Nomsky\Grammar\Symbol\ArraySet;
use Helstern\Nomsky\Grammar\Symbol\EpsilonSymbol;
use Helstern\Nomsky\Grammar\Symbol\StandardSymbol;
use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\GrammarAnalysis\ParseSets\ParseSets;
use Helstern\Nomsky\TestCase;

class FirstSetCalculatorTest extends TestCase
{
    public function testComputedSetDoesNotContainEpsilonWhenListHasATerminal()
    {
        $nonTerminals = [
            'A_1' => StandardSymbol::nonTerminal('A_1')
            ,
            'A_2' => StandardSymbol::nonTerminal('A_2')
            ,
            'A_3' => StandardSymbol::nonTerminal('A_3')
            ,
            'A_4' => StandardSymbol::nonTerminal('A_4')
        ];

        $terminals = [
            'b_1' => StandardSymbol::terminal('b_1')
            ,
            'b_2' => StandardSymbol::terminal('b_2')
            ,
            'b_4' => StandardSymbol::terminal('b_4')
        ];


        $firstSets = new ParseSets(array_values($nonTerminals));
        $firstSets->addEpsilon($nonTerminals['A_1']);
        $firstSets->addTerminal($nonTerminals['A_1'], $terminals['b_1']);

        $firstSets->addEpsilon($nonTerminals['A_2']);
        $firstSets->addTerminal($nonTerminals['A_2'], $terminals['b_2']);

        $firstSets->addEpsilon($nonTerminals['A_3']);

        $firstSets->addEpsilon($nonTerminals['A_4']);
        $firstSets->addTerminal($nonTerminals['A_4'], $terminals['b_4']);

        $sentenceString = 'A_2 A_2 A_1 b_1 A_3 A_4';
        $sentenceList = [];
        foreach (explode(' ', $sentenceString) as $symbolItem) {
            if (array_key_exists($symbolItem, $nonTerminals)) {
                $sentenceList[] = $nonTerminals[$symbolItem];
            } else {
                $sentenceList[] = $terminals[$symbolItem];
            }
        }

        //we should not see anything past the first terminal
        $expectedSet = new ArraySet();
        $expectedSet->add($terminals['b_1']);
        $expectedSet->add($terminals['b_2']);

        $actualSet = new ArraySet();
        $calculator = new FirstSetCalculator();
        $calculator->processSymbolList($actualSet, $sentenceList, $firstSets);

        $this->assertEquals($expectedSet->count(), $actualSet->count(), 'unexpected number of actual items found');
        /** @var Symbol $symbol */
        foreach ($actualSet as $symbol) {
            $contains = $expectedSet->contains($symbol);
            $this->assertTrue($contains, sprintf('Symbol %s not found', $symbol->toString()));
        }
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


        $firstSets = new ParseSets(array_values($nonTerminals));
        $firstSets->addEpsilon($nonTerminals['A_1']);
        $firstSets->addTerminal($nonTerminals['A_1'], $terminals['b_1']);

        $firstSets->addEpsilon($nonTerminals['A_2']);
        $firstSets->addTerminal($nonTerminals['A_2'], $terminals['b_2']);

        $firstSets->addEpsilon($nonTerminals['A_3']);

        $firstSets->addEpsilon($nonTerminals['A_4']);
        $firstSets->addTerminal($nonTerminals['A_4'], $terminals['b_4']);

        $sentenceString = 'A_2 A_2 A_1 A_3 A_4';
        $sentenceList = [];
        foreach (explode(' ', $sentenceString) as $symbolItem) {
            $sentenceList[] = $nonTerminals[$symbolItem];
        }

        $expectedSet = new ArraySet();
        $expectedSet->add($terminals['b_1']);
        $expectedSet->add($terminals['b_2']);
        $expectedSet->add($terminals['b_4']);
        $epsilon = EpsilonSymbol::singletonInstance();
        $expectedSet->add($epsilon);

        $actualSet = new ArraySet();
        $calculator = new FirstSetCalculator();
        $calculator->processSymbolList($actualSet, $sentenceList, $firstSets);

        $this->assertEquals($expectedSet->count(), $actualSet->count(), 'unexpected number of actual items found');
        /** @var Symbol $symbol */
        foreach ($actualSet as $symbol) {
            $contains = $expectedSet->contains($symbol);
            $this->assertTrue($contains, sprintf('Symbol %s not found', $symbol->toString()));
        }
    }
}
