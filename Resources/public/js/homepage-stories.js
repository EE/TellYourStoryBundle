
(function($){
    "use strict";
    var $container = $('#masonry-container');

    $.get(Routing.get_stories, function(elements){
        $container.masonry();
        $.map(elements, function(element){

            var elem = document.createElement('div');

            $(elem).css({
                "background-size" : "100%",
                "background-color": "#000",
                "background-image": "url('http://placehold.it/300x300')"
            }).addClass('story-item').text(element.name);

            $container.append(elem);
            $container.masonry('appended', elem).masonry();
        });

    });


})(jQuery);