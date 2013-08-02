
(function($){
    "use strict";
    var $container = $('#masonry-container'),
        columnWidth = 300,
        gutter = 20,
        padding = 0.15*columnWidth,
        route = Routing.generate('get_stories', {}, true);


    if ($container.filter('[data-user]'))
    {
        route = Routing.generate('get_stories_by_user', {
            user_id: $container.attr('data-user')
        })
    }

    $.get(route, function(elements){
        $container.masonry({
            columnWidth: columnWidth,
            gutter:      gutter
        });

        for (var i=0; i < elements.length; i++) {
            var current = elements[i],
                background = new Image();

            if (current.hasOwnProperty('background_uri')) {
                background.src = current.background_uri;

                //hack
                background.story = current;

                $(background).on('load', function() {

                    var imageHeight = Math.max($(this).context.height, 300),
                        $elem = $(document.createElement('a')),
                        $circle = $(document.createElement('div'));

                    $elem
                        .attr('href', Routing.generate('story_show', {id: $(this).context.story.id}, true))
                        .css({
                            "background-image": "url('" + $(this).context.src + "')",
                            'height': imageHeight - 2*padding
                        })
                        .addClass('story-item');

                    $circle
                        .addClass('circle')
                        .html($('<span>').addClass('tagline').text($(this).context.story.tagline))
                        .css({
                            'position': 'relative',
                            'top': (imageHeight - columnWidth)/2
                        })
                        .appendTo($elem);

                    $container.append($elem);
                    $container.masonry('appended', $elem).masonry();
                });
            }

        }

    });


})(jQuery);