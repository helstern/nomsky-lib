<?php namespace Helstern\Nomsky\Parser\Ast;

interface WalkListItemCollector
{
    /**
     * @param array|WalkAction[] $items
     *
     * @return int
     */
    public function collectList(array $items);

    /**
     * @param WalkAction $item
     *
     * @return boolean
     */
    public function collect(WalkAction $item);
}
