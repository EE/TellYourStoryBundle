/**
 * Based on https://gist.github.com/madrobby/3202087
 */

// This is just an example, and you'll likely need to adapt it..
//
//   1) adds a "no-svg" CSS class to <body> if SVG is not supported,
//      so you can special-case CSS background images
//   2) loads all IMG tags that have a "data-svg" attribute set with
//      either .svg or .png added to the url given in that attribute
//
// If you create new IMG tags with data-svg on the page later, you'll need to
// call the window.updateSVGIMG method.

(function(global){
    var svg = !!('createElementNS' in document &&
        document.createElementNS('http://www.w3.org/2000/svg','svg').createSVGRect)

    if (!svg) {document.body.className += ' no-svg';} else {document.body.className += ' svg';}

    (global.updateSVGIMG = function(){
        var i, src, extension = svg ? '.svg' : '.png',
            elements = document.getElementsByTagName('img')
        for (i=0;i<elements.length;i++)
            if (src = elements[i].getAttribute('data-svg')) {
                elements[i].src = src + extension
                elements[i].removeAttribute('data-svg')
            }
    })()

})(this)