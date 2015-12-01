<?php

namespace AppBundle\Event\Promise;

/**
 * Proxy object for the result of an event
 *
 * This class will forward calls to the result of an event handled by the PromisesEventHandlerMiddleware.
 *
 * @package AppBundle\Event\Promise
 */
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

    /**
     * Handle the event
     *
     * @param $event
     */
    public function handle($event)
    {
        $wrappedHandler = $this->wrappedHandler;
        $this->realObject = $wrappedHandler($event);

        // Only handles the first instance of the event.  This instance is removed from the list to prevent further
        // events to overwrite the event response object.
        $this->middleware->remove($this);
    }

    /**
     * Check if this instance can handle the specified event
     *
     * Check the event class to determine if it can be handled
     *
     * @param $event
     * @return bool
     */
    public function canHandle($event)
    {
        return $event instanceof $this->eventClass;
    }

    public function __call($name, $arguments)
    {
        // Forward method calls to the event object.  If it is not set yet, it will not work.
        return call_user_func_array([$this->realObject, $name], $arguments);
    }

    public function __get($name)
    {
        // Forward property access to the event object.  If it is not set yet, it will not work.
        return $this->realObject->$name;
    }
}
