<?php

namespace Phillip;

/**
 * @author Joshua Estes
 */
class Response
{

    protected $command;
    protected $parameters = array();

    public static function create($command, $parameters = null)
    {
        $r = new self();
        $r->setCommand($command)->setParameters($parameters);
        return $r;
    }

    public function setContainer($container)
    {
        $this->container = $container;
        return $this;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function setCommand($command)
    {
        $this->command = strtoupper($command);
        return $this;
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function setParameters($parameters)
    {
        if (!is_array($parameters)) {
            $parameters = array($parameters);
        }
        $this->parameters = $parameters;
        return $this;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function isValid()
    {
        return (null !== $this->command);
    }

    public function send()
    {
        if (!$this->isValid()) {
            throw \Exception(sprintf('"%s" is invalid and cannot be send to server.',$this->__toString()));
        }

        $this->getContainer()->get('connection')->writeln($this->__toString());
        $this->getContainer()->get('dispatcher')->dispatch('post.response');

        return true;
    }

    public function __toString()
    {
        if ($this->isValid()) {
            return $this->getCommand() . ' '  . implode(' ', $this->getParameters());
        }

        return '';
    }

}
