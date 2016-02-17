<?php namespace Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\SequenceGroup;

use Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\NormalizeOperator;
use Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\OperationResult\AlternationResult;
use Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\OperationResult\SequenceResult;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Concatenation;

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

                /** @var Expression $head */
                $head = null;
                /** @var array|Expression[] $tail */
                $tail = null;

                if ($tailItem instanceof Concatenation) {//make sure sequence does not contain another sequence
                    $tail = $tailItem->toArray();
                } else {
                    $tail = array($tailItem);
                }

                if ($headItem instanceof Concatenation) {
                    $tail = array_merge($headItem->toArray(), $tail);
                    $head = array_shift($tail);
                } else {
                    $head = $headItem;
                }

                $normalized[] = new Concatenation($head, array($tail));
            }
        }

        return new AlternationResult($normalized);
    }

    /**
     * (x | y) (a b) => x a b | y a b
     *
     * @param array|Expression[] $leftGroupItems alternation items
     * @param array|Expression[] $rightGroupItems sequence items
     * @return AlternationResult
     */
    public function operateOnAlternationAndSequence(array $leftGroupItems, array $rightGroupItems)
    {
        $normalized = array();
        foreach($leftGroupItems as $headItem) {
            /** @var Expression $head */
            $head = null;
            /** @var array|Expression[] $tail */
            $tail = $rightGroupItems;

            if ($headItem instanceof Concatenation) {//make sure sequence does not contain another sequence
                $tail = array_merge($headItem->toArray(), $rightGroupItems);
                $head = array_shift($tail);
            } else {
                $head = $headItem;
            }

            $normalized[] = new Concatenation($head, $tail);
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
            /** @var array $tail */
            $tail = null;
            if ($tailItem instanceof Concatenation) { //make sure sequence does not contain another sequence
                $tail = array_merge($leftGroupItems, $tailItem->toArray());
            } else {
                $tail = array_merge($leftGroupItems, array($tailItem));
            }

            $normalized[] = new Concatenation($head, $tail);
        }

        return new AlternationResult($normalized);
    }

    /**
     * (x y) (a b) => x y a b
     *
     * @param array $leftGroupItems sequence items
     * @param array $rightGroupItems sequence items
     * @return SequenceResult
     */
    public function operateOnSequenceAndSequence(array $leftGroupItems, array $rightGroupItems)
    {
        $normalized = array_merge($leftGroupItems, $rightGroupItems);
        return new SequenceResult($normalized);
    }
}
