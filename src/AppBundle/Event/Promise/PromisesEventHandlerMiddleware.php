<?php

namespace AppBundle\Event\Promise;

use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;

/**
 * Event handler for an easy access to the return value of an event
 *
 * @package AppBundle\Event\Promise
 */
class PromisesEventHandlerMiddleware implements MessageBusMiddleware
{
    /** @var EventPromise[] */
    private $promises = [];

    /**
     * Create a promise from the specified event handler
     *
     * Only the first instance of the event will be handled
     *
     * @param callable $handler
     * @return EventPromise
     */
    public function delegate(callable $handler)
    {
        $promise = new EventPromise($this, $handler);
        $this->promises[] = $promise;

        return $promise;
    }

    /**
     * Remove the promise from the list of waiting handlers
     *
     * @param EventPromise $promise
     */
    public function remove(EventPromise $promise)
    {
        $index = array_search($promise, $this->promises, true);

        // If the promise is not found, there is nothing to do.  Do not raise an error
        if ($index !== false) {
            unset($this->promises[$index]);
        }
    }

    /**
     * The provided $next callable should be called whenever the next middleware should start handling the message.
     * Its only argument should be a Message object (usually the same as the originally provided message).
     *
     * @param object $message
     * @param callable $next
     * @return void
     */
    public function handle($message, callable $next)
    {
        foreach ($this->promises as $promise) {
            if ($promise->canHandle($message)) {
                $promise->handle($message);
            }
        }

        $next($message);
    }
}
