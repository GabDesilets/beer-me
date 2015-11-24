<?php

namespace AppBundle\Event\Promise;

class EventPromise
{
    /** @var PromisesEventHandlerMiddleware */
    private $middleware;

    private $realObject;

    /** @var callable */
    private $wrappedHandler;

    private $eventClass;

    public function __construct($middleware, callable $handler)
    {
        $this->middleware = $middleware;
        $this->wrappedHandler = $handler;

        // Get the class name of the first argument.  It will define which event it handles.
        $method = new \ReflectionFunction($handler);
        $this->eventClass = $method->getParameters()[0]->getClass()->getName();
    }

    public function handle($event)
    {
        $wrappedHandler = $this->wrappedHandler;
        $this->realObject = $wrappedHandler($event);
        $this->middleware->remove($this);
    }

    public function canHandle($event)
    {
        return $event instanceof $this->eventClass;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->realObject, $name], $arguments);
    }

    public function __get($name)
    {
        return $this->realObject->$name;
    }
}
