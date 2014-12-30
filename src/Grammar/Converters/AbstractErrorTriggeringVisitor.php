<?php namespace Helstern\Nomsky\Grammar\Converters;

use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\OptionalItem;
use Helstern\Nomsky\Grammar\Expressions\OptionalList;
use Helstern\Nomsky\Grammar\Expressions\Sequence;
use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisitor;

abstract class AbstractErrorTriggeringVisitor implements HierarchyVisitor
{
    /**
     * @param string $methodName
     * @return string
     */
    abstract protected function getMethodNotCalledWarningMessage($methodName);

    /**
     * @param Alternation $expression
     * @return boolean
     */
    public function startVisitAlternation(Alternation $expression)
    {
        $warningMessage = $this->getMethodNotCalledWarningMessage(__METHOD__);
        trigger_error($warningMessage, E_USER_WARNING);

        return false;
    }

    /**
     * @param Alternation $expression
     * @return boolean
     */
    public function endVisitAlternation(Alternation $expression)
    {
        $warningMessage = $this->getMethodNotCalledWarningMessage(__METHOD__);
        trigger_error($warningMessage, E_USER_WARNING);

        return false;
    }

    /**
     * @param Sequence $expression
     * @return boolean
     */
    public function startVisitSequence(Sequence $expression)
    {
        $warningMessage = $this->getMethodNotCalledWarningMessage(__METHOD__);
        trigger_error($warningMessage, E_USER_WARNING);

        return false;
    }

    /**
     * @param Sequence $expression
     * @return boolean
     */
    public function endVisitSequence(Sequence $expression)
    {
        $warningMessage = $this->getMethodNotCalledWarningMessage(__METHOD__);
        trigger_error($warningMessage, E_USER_WARNING);

        return false;
    }

    /**
     * @param Group $expression
     * @return boolean
     */
    public function startVisitGroup(Group $expression)
    {
        $warningMessage = $this->getMethodNotCalledWarningMessage(__METHOD__);
        trigger_error($warningMessage, E_USER_WARNING);

        return false;
    }

    /**
     * @param Group $expression
     * @return boolean
     */
    public function endVisitGroup(Group $expression)
    {
        $warningMessage = $this->getMethodNotCalledWarningMessage(__METHOD__);
        trigger_error($warningMessage, E_USER_WARNING);

        return false;
    }

    /**
     * @param OptionalList $expression
     * @return boolean
     */
    public function startVisitOptionalList(OptionalList $expression)
    {
        $warningMessage = $this->getMethodNotCalledWarningMessage(__METHOD__);
        trigger_error($warningMessage, E_USER_WARNING);

        return false;
    }

    /**
     * @param OptionalList $expression
     * @return boolean
     */
    public function endVisitOptionalList(OptionalList $expression)
    {
        $warningMessage = $this->getMethodNotCalledWarningMessage(__METHOD__);
        trigger_error($warningMessage, E_USER_WARNING);

        return false;
    }

    /**
     * @param OptionalItem $expression
     * @return boolean
     */
    public function startVisitOptionalItem(OptionalItem $expression)
    {
        $warningMessage = $this->getMethodNotCalledWarningMessage(__METHOD__);
        trigger_error($warningMessage, E_USER_WARNING);

        return false;
    }

    /**
     * @param OptionalItem $expression
     * @return boolean
     */
    public function endVisitOptionalItem(OptionalItem $expression)
    {
        $warningMessage = $this->getMethodNotCalledWarningMessage(__METHOD__);
        trigger_error($warningMessage, E_USER_WARNING);

        return false;
    }

    /**
     * @param Expression $expression
     * @return boolean
     */
    public function visitExpression(Expression $expression)
    {
        $warningMessage = $this->getMethodNotCalledWarningMessage(__METHOD__);
        trigger_error($warningMessage, E_USER_WARNING);

        return false;
    }
}
