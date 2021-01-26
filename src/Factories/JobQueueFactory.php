<?php

/**
 * Job Queue Factory
 * Job queue producer & consumer creator
 * php version 7.4
 * 
 * @category Factory
 * @package  DEOTransCodeChallenge
 * @author   Rudi <rudi@kanayahijab.com>
 * @license  https://opensource.org/license/MIT MIT License
 * @link     https://github.com/rudilee/deotrans-code-chalenge
 */

declare(strict_types=1);

namespace DEOTransCodeChallenge\Factories;

use Bernard\Consumer;
use Bernard\Driver\PredisDriver;
use Bernard\Middleware\MiddlewareBuilder;
use Bernard\Producer;
use Bernard\QueueFactory\PersistentFactory;
use Bernard\Router\SimpleRouter;
use Bernard\Serializer\SimpleSerializer;
use Predis\Client;

/**
 * Job queue producer & consumer creator.
 * 
 * @category Factory
 * @package  DEOTransCodeChallenge
 * @author   Rudi <rudi@kanayahijab.com>
 * @license  https://opensource.org/license/MIT MIT License
 * @link     https://github.com/rudilee/deotrans-code-chalenge
 */

class JobQueueFactory
{
    /**
     * Create Bernard queue factory
     * 
     * @return PersistentFactory
     */
    public static function createQueueFactory(): PersistentFactory
    {
        $queueHostname = $_ENV['QUEUE_HOSTNAME'];
        $predis = new Client(
            "tcp://$queueHostname",
            ['prefix' => 'bernard:']
        );

        $driver = new PredisDriver($predis);
        return new PersistentFactory($driver, new SimpleSerializer);
    }

    /**
     * Create Bernard queue producer
     * 
     * @return Producer
     */
    public static function createProducer(): Producer
    {
        return new Producer(self::createQueueFactory(), new MiddlewareBuilder());
    }

    /**
     * Create Bernard queue consumer
     * 
     * @return Consumer
     */
    public static function createConsumer($sendEmailHandler): Consumer
    {
        $router = new SimpleRouter;
        $router->add('SendEmail', $sendEmailHandler);

        return new Consumer($router, new MiddlewareBuilder);
    }
}
