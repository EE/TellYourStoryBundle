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