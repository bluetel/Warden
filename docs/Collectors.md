Collectors
==========

Collector classes register events on Warden's event dispatcher, they collect information and report back to Warden once the request is finished. Although Warden provides several useful collector classes, such as the Stopwatch and Doctrine collector's you should make your own for applications where you do not use these libraries.

### Creating a collector

Below is an example of a custom collector class;

    class TestCollector implements Warden\Collector\CollectorInterface
    {
        public function describe()
        {
            // These are the information nodes, which can be used
            // To analyse results and trigger exceptions
            return array(
                'test_time'     => ['type' => 'integer', 'default' => 5],
                'test_memory'   => ['type' => 'integer', 'expression' => 'value >= limit']
            );
        }

        public function register($eventDispatcher)
        {
            // Here we register against the warden start and end events
            $eventDispatcher->addListener(Warden\WardenEvents::WARDEN_START, function(Warden\Events\StartEvent $event) {
                // Start the collection of information here
            });

            $eventDispatcher->addListener(Warden\WardenEvents::WARDEN_END, function(Warden\Events\StopEvent $event) {
                // The Stop Event contains a ParamBag which needs to be updated here
                $event->params->setValue('test_time', 138);
                $event->params->setValue('test_memory', 0);
            });
        }
    }

Collector classes can accept Constructor parameters but they must be registered as dependencies, have a look at the documentation file for Configuration for more information on how to register Warden Dependencies.
