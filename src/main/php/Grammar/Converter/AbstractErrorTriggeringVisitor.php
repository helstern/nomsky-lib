<?php namespace Helstern\Nomsky\Grammar\Converter;

use Helstern\Nomsky\Grammar\Expressions\Choice;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\Optional;
use Helstern\Nomsky\Grammar\Expressions\Repetition;
use Helstern\Nomsky\Grammar\Expressions\Concatenation;
use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisitor;

abstract class AbstractErrorTriggeringVisitor implements HierarchyVisitor
{
    /**
     * @param string $methodName
     * @return string
     */
    abstract protected function getMethodNotCalledWarningMessage($methodName);

    /**
     * @param Choice $expression
     *
    * @return boolean
     */
    public function startVisitChoice(Choice $expression)
    {
        $warningMessage = $this->getMethodNotCalledWarningMessage(__METHOD__);
        trigger_error($warningMessage, E_USER_WARNING);

        return false;
    }

    /**
     * @param Choice $expression
     *
     * @return boolean
     */
    public function endVisitChoice(Choice $expression)
    {
        $warningMessage = $this->getMethodNotCalledWarningMessage(__METHOD__);
        trigger_error($warningMessage, E_USER_WARNING);

        return false;
    }

    /**
     * @param Concatenation $expression
     *
     * @return boolean
     */
    public function startVisitConcatenation(Concatenation $expression)
    {
        $warningMessage = $this->getMethodNotCalledWarningMessage(__METHOD__);
        trigger_error($warningMessage, E_USER_WARNING);

        return false;
    }

    /**
     * @param Concatenation $expression
     *
     * @return boolean
     */
    public function endVisitConcatenation(Concatenation $expression)
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
     * @param Repetition $expression
     *
     * @return boolean
     */
    public function startVisitRepetition(Repetition $expression)
    {
        $warningMessage = $this->getMethodNotCalledWarningMessage(__METHOD__);
        trigger_error($warningMessage, E_USER_WARNING);

        return false;
    }

    /**
     * @param Repetition $expression
     *
     * @return boolean
     */
    public function endVisitRepetition(Repetition $expression)
    {
        $warningMessage = $this->getMethodNotCalledWarningMessage(__METHOD__);
        trigger_error($warningMessage, E_USER_WARNING);

        return false;
    }

    /**
     * @param Optional $expression
     *
     * @return boolean
     */
    public function startVisitOptional(Optional $expression)
    {
        $warningMessage = $this->getMethodNotCalledWarningMessage(__METHOD__);
        trigger_error($warningMessage, E_USER_WARNING);

        return false;
    }

    /**
     * @param Optional $expression
     *
     * @return boolean
     */
    public function endVisitOptional(Optional $expression)
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
