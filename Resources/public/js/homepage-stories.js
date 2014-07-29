(function ($) {
    "use strict";
    var $container = $('#masonry-container'),
        columnWidth = 300,
        gutter = 20,
        padding = 0.15 * columnWidth,
        config = $container.data(),
        route = Routing.generate('get_stories', config.collection ? {
            by: 'storyCollections',
            id: config.collection
        } : {}, true);

    if ($container.filter('[data-user]').length) {
        route = Routing.generate('get_stories_by_user', {
            user_id: $container.attr('data-user')
        })
    }

    $.get(route, function (elements) {
        $container.masonry({
            columnWidth: columnWidth,
            gutter: gutter
        });

        for (var i = 0; i < elements.length; i++) {
            var current = elements[i],
                background = new Image();

            if (current.hasOwnProperty('background_uri')) {
                background.element = current;
                background.src = current.background_uri;

                $(background).on('load', function () {

                    var ctx = $(this).context,
                        imageHeight = Math.max(ctx.height, 300),
                        $elem = $(document.createElement('a')),
                        $circle = $(document.createElement('div')),
                        isCollection = ctx.element.hasOwnProperty('organization_name');

                    $elem
                        .attr('href', isCollection ?
                            Routing.generate('story_collection_show_by_slug', {slug: ctx.element.slug}, true) :
                            Routing.generate('story_show_by_slug', {slug: ctx.element.slug}, true))
                        .css({
                            "background-image": "url('" + ctx.src + "')",
                            'height': imageHeight - 2 * padding
                        })
                        .addClass('front-page-item')
                        .addClass(isCollection ? 'collection-item' : 'story-item');


                    $circle
                        .addClass('circle')
                        .html($('<span>').addClass('tagline').text(ctx.element.tagline))
                        .css({
                            'position': 'relative',
                            'top': (imageHeight - columnWidth) / 2
                        })
                        .appendTo($elem);

                    $container.append($elem);

                    $container.masonry('appended', $elem).masonry();
                });
            }
        }
    });
})(jQuery);