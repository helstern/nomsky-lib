<?php namespace Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\SequenceGroup;

use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\NormalizeOperator;
use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\OperationResult\AlternationResult;
use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\OperationResult\SequenceResult;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Sequence;

class Operator implements NormalizeOperator
{
    /**
     * (x | y) (a | b) => x a | x b | y a | y b
     *
     * @param array|Expression[] $leftGroupItems
     * @param array|Expression[] $rightGroupItems
     * @return AlternationResult
     */
    public function operateOnAlternationAndAlternation(array $leftGroupItems, array $rightGroupItems)
    {
        $normalized = array();
        foreach($leftGroupItems as $headItem) {
            foreach ($rightGroupItems as $tailItem) {
                $normalized[] = new Sequence($headItem, array($tailItem));
            }
        }

        return new AlternationResult($normalized);
    }

    /**
     * (x | y) (a b) => x a b | y a b
     *
     * @param array|Expression[] $leftGroupItems$leftGroupItems
     * @param array|Expression[] $rightGroupItems
     * @return AlternationResult
     */
    public function operateOnAlternationAndSequence(array $leftGroupItems, array $rightGroupItems)
    {
        $normalized = array();
        foreach($leftGroupItems as $headItem) {
            $normalized[] = new Sequence($headItem, array($rightGroupItems));
        }

        return new AlternationResult($normalized);
    }

    /**
     * (x y) (a | b) => x y a | x y b
     *
     * @param array $leftGroupItems
     * @param array $rightGroupItems
     * @return AlternationResult
     */
    public function operateOnSequenceAndAlternation(array $leftGroupItems, array $rightGroupItems)
    {
        $normalized = array();
        $head       = array_shift($leftGroupItems);
        foreach($rightGroupItems as $tailItem) {
            $normalized[] = new Sequence($head, array_merge($leftGroupItems, array($tailItem)));
        }

        return new AlternationResult($normalized);
    }

    /**
     * (x y) (a b) => x y a b
     *
     * @param array $leftGroupItems
     * @param array $rightGroupItems
     * @return SequenceResult
     */
    public function operateOnSequenceAndSequence(array $leftGroupItems, array $rightGroupItems)
    {
        $normalized = array_merge($leftGroupItems, $rightGroupItems);

        return new SequenceResult($normalized);
    }
}
