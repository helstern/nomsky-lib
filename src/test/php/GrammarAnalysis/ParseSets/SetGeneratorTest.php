<?php namespace Helstern\Nomsky\GrammarAnalysis\ParseSets;

use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\GrammarAnalysis\Algorithms\EmptySetCalculator;
use Helstern\Nomsky\GrammarAnalysis\Algorithms\FirstSetCalculator;
use Helstern\Nomsky\GrammarAnalysis\Algorithms\FollowSetCalculator;
use Helstern\Nomsky\GrammarAnalysis\Algorithms\PredictSetCalculator;
use Helstern\Nomsky\GrammarAnalysis\Production\NormalizedProduction;
use Helstern\Nomsky\GrammarAnalysis\Production\Normalizer;
use Helstern\Nomsky\GrammarAnalysis\Production\SimpleHashKeyFactory;
use Helstern\Nomsky\TestCase;

class PredictSetGeneratorTest extends TestCase
{
    /**
     * @return \Helstern\Nomsky\GrammarAnalysis\ParseSets\SetsGenerator
     */
    private function createSetGenerator()
    {
        $setGenerator = new SetsGenerator(
            new EmptySetCalculator(),
            new FirstSetCalculator(),
            new FollowSetCalculator(new FirstSetCalculator()) ,
            new PredictSetCalculator(new FirstSetCalculator())
        );
        return $setGenerator;
    }

    public function testGenerateFirstSets()
    {
        $setGenerator = $this->createSetGenerator();

        $testGrammar = TestGrammar::grammar();
        $normalizer = new Normalizer();
        $productions = $normalizer->normalize($testGrammar);

        $expectedSets = TestGrammar::firstSets();
        $actualSets = (new SetsFactory())->createEmptyParseSets($testGrammar);
        $epsilonSet = TestGrammar::epsilonSet();

        $setGenerator->generateFirstSets($productions, $actualSets, $epsilonSet);

        $nonTerminals = $expectedSets->getNonTerminals();
        foreach ($nonTerminals as $nonTerminal) {
            $expectedTerminals = $expectedSets->getTerminalSet($nonTerminal);
            $actualTerminals = $actualSets->getTerminalSet($nonTerminal);

            $expectedArray = [];
            /** @var Symbol $terminal */
            foreach ($expectedTerminals as $terminal) {
                $expectedArray[] = ['type' => $terminal->getType(), 'symbol' => $terminal->toString()];
            }

            $actualArray = [];
            /** @var Symbol $terminal */
            foreach ($actualTerminals as $terminal) {
                $actualArray[] = ['type' => $terminal->getType(), 'symbol' => $terminal->toString()];
            }

            $this->assertEquals($expectedArray, $actualArray);
        }
    }

    public function testGenerateFollowSets()
    {
        $setGenerator = $this->createSetGenerator();

        $testGrammar = TestGrammar::grammar();
        $normalizer = new Normalizer();
        $productions = $normalizer->normalize($testGrammar);

        $firstSets = TestGrammar::firstSets();
        $expectedSets = TestGrammar::followSets();

        $actualSets = (new SetsFactory())->createEmptyParseSets($testGrammar);
        $setGenerator->generateFollowSets($productions, $testGrammar->getStartSymbol(), $actualSets, $firstSets);

        $nonTerminals = $expectedSets->getNonTerminals();
        foreach ($nonTerminals as $nonTerminal) {
            $expectedTerminals = $expectedSets->getTerminalSet($nonTerminal);
            $actualTerminals = $actualSets->getTerminalSet($nonTerminal);

            $expectedArray = [];
            /** @var Symbol $terminal */
            foreach ($expectedTerminals as $terminal) {
                $expectedArray[$terminal->toString()] = $terminal->getType();
            }

            $actualArray = [];
            /** @var Symbol $terminal */
            foreach ($actualTerminals as $terminal) {
                $actualArray[$terminal->toString()] = $terminal->getType();
            }

            ksort($expectedArray);
            ksort($actualArray);

            $this->assertEquals($expectedArray, $actualArray, 'Wrong set for nonterminal ' . $nonTerminal->toString());
        }
    }

    public function testGeneratePredictSets()
    {
        $setGenerator = $this->createSetGenerator();

        $grammar = TestGrammar::grammar();
        $normalizer = new Normalizer();
        $productions = $normalizer->normalize($grammar);

        $expectedSets = TestGrammar::predictSets();
        $firstSets = TestGrammar::firstSets();
        $followSets = TestGrammar::followSets();

        $hashFactory = new SimpleHashKeyFactory();
        $actualSets = new LookAheadSets($hashFactory);
        $setGenerator->generateLookAheadSets($productions, $actualSets, $followSets, $firstSets);

        /** @var LookAheadSetEntry $expectedEntry */
        /** @var NormalizedProduction $production */
        foreach($expectedSets->getEntrySetIterator() as $production => $expected) {
            $actual = $actualSets->getSet($production);

            $actualCountBefore = $actual->count();
            $actual->addAll($expected);
            $actualCountAfter = $actual->count();
            $this->assertEquals($actualCountBefore, $actualCountAfter);

            $expectedCountBefore = $expected->count();
            $expected->addAll($actual);
            $expectedCountAfter = $expected->count();

            $this->assertEquals($expectedCountBefore, $expectedCountAfter);
        }
    }
}
