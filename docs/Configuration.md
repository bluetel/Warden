Config
======

To configure Warden you first need to create a Yaml file. The Yaml file will tell warden what collectors we want to use and gives you the option to customize the limit's and expressions used to determine when a limit has been reached.

So our yaml file would look a little like this:

    warden:
        collectors:
            stopwatch:
                class: Warden\Collector\Stopwatch\StopwatchCollector
        settings:
            request_time:
                limit: 5
                expression: 'value > limit'

In this config Warden would load the Stopwatch collector and set the `request_time` node to have a limit of 5 (in this case seconds) and the expression is denoting that when `value` is greater than `limit` we have reached our limit.

Next we need an instance of Warden...

    $warden = new Warden\Warden;
    $warden->setup('/path/to/config/warden.yml');

## Adding Dependencies

You can load dependencies into warden to let collectors share common resources, to do this you need to first register the dependency on the warden instance.

    // In this example we are loading the entity manager
    // From our doctrine configuration
    $warden->addDependency('doctrine_orm', $em);

Once we have the dependency in Warden we can assign this to the collectors, which will take this as constructor parameters, for example;

    warden:
        collectors:
            doctrine:
                class: Warden\Collector\Doctrine\DoctrineQueryCollector
                arguments:
                    - "doctrine_orm"

When warden runs through the config file it will now pass our instance of the EntityManager as the first parameter to the DoctrineQueryCollector class.
