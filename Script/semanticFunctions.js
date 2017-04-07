/****************************  Semantic-UI jQuery support *****************************/

/* 

Semantic UI dependency code works in conjunction with class definitions
to generate animation and user interaction with classes

*/

// Gives us tooltips on buttons and info popups
$('.ui.button').popup();
$('.info.circle.icon').popup({            
    inline: true,
    on: 'click',
    popup: $('.epsg.popup'),
    position: 'top left',
    variation: 'tiny'
//  boundary: '.sidebar-panel-body'
});


// Open an information panel with menu button click    
function closeAllPanels() {
    $('.info-panel').each(function (i) {
        $(this).addClass('hidden');
    })
};


// Create sidebar animation and menu button control
$('.open-panel').click(function () {
    // open sidebar
    $('.ui.sidebar').sidebar({
            transition: 'overlay',
            dimPage: false,
            closable: false,
        })
        .sidebar('show');

    // identify active button action
    if ($(this).hasClass('clicked')) {
        $('.open-panel').removeClass('clicked').blur();
        $(".ui.sidebar").sidebar('hide');
    } else {
        $('.open-panel').removeClass('clicked');
        $(this).addClass('clicked');
    }

    // control visible sidebar information
    closeAllPanels();
    var id = '#' + this.id + '-panel';
    $(id).removeClass('hidden');
});


// Close menu with Close icon
$('.sidebar-panel-icon').click(function () {
    $('.open-panel').removeClass('clicked').blur();
    $(".ui.sidebar").sidebar('hide');
});


// Animate Accordions
$('.ui.accordion').accordion();


// Animate Drawing Dropdown
$('.ui.dropdown').dropdown();      


// Create custom zoom-in control
$('#zoom-in').click(function () {
    var $zoom = ol.animation.zoom({
        resolution: map.getView().getResolution()
    });
    map.beforeRender($zoom);
    map.getView().setResolution(map.getView().getResolution() * 0.5);
});

// Create custom zoom-out control
$('#zoom-out').click(function () {
    var $zoom = ol.animation.zoom({
        resolution: map.getView().getResolution()
    });
    map.beforeRender($zoom);
    map.getView().setResolution(map.getView().getResolution() * 2);
});

// close the warning on the bbox
$('.bbox-form .message .close').on('click', function() {
    $('.bbox-form').removeClass('warning');
});