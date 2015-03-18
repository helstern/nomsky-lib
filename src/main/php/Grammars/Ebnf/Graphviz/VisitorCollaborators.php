<?php namespace Helstern\Nomsky\Grammars\Ebnf\Graphviz;

use Helstern\Nomsky\Graphviz\DotFile;
use Helstern\Nomsky\Graphviz\DotWriter;

class VisitorCollaborators
{
    /** @var DotFile */
    protected $dotFile;

    /** @var \SplStack*/
    protected $parents;

    /** @var NodeCounter */
    protected $nodeCounter;

    public function __construct(DotFile $dotFile, \SplStack $parents, NodeCounter $nodeCounter)
    {
        $this->dotFile = $dotFile;
        $this->parents = $parents;
        $this->nodeCounter = $nodeCounter;
    }

    /**
     * @return DotWriter
     */
    public function dotWriter()
    {
        $dotWriter = new DotWriter($this->dotFile);
        return $dotWriter;
    }

    /***
     * @return \SplStack
     */
    public function parentNodeIds()
    {
        return $this->parents;
    }

    /**
     * @return NodeCounter
     */
    public function nodeCounter()
    {
        return $this->nodeCounter;
    }

}
