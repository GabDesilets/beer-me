<?php

namespace AppBundle\Event\Promise;

use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;

class PromisesEventHandlerMiddleware implements MessageBusMiddleware
{
    /** @var EventPromise[] */
    private $promises = [];

    public function delegate(callable $handler)
    {
        $promise = new EventPromise($this, $handler);

        $this->promises[] = $promise;

        return $promise;
    }

    public function remove(EventPromise $promise)
    {
        $index = array_search($promise, $this->promises, true);

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
        foreach ($this->promises as $promise)
        {
            if ($promise->canHandle($message)) {
                $promise->handle($message);
            }
        }

        $next($message);
    }
}
