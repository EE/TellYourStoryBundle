## Bundle is in development state, do not use

Required config

    #app/config/config.yml
    fos_rest:
        param_fetcher_listener: force
        body_listener: true
        format_listener: true
        view:
            view_response_listener: 'force'
        routing_loader:
            default_format: json

    sensio_framework_extra:
        view:    { annotations: false }

AppKernel.php

    new FOS\RestBundle\FOSRestBundle(),
    new JMS\SerializerBundle\JMSSerializerBundle(),
    new Mopa\Bundle\BootstrapBundle\MopaBootstrapBundle(),
    new EE\TYSBundle\EETYSBundle(),
    new TB\TYSBundle\TBTYSBundle(),

Dev & Test

    $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
