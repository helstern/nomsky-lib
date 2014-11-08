<?php namespace Helstern\Nomsky\Grammar\Symbol;

interface Set extends \Countable {

    public function remove(Symbol $symbol);

    public function add(Symbol $symbol);

    public function contains(Symbol $symbol);
}
