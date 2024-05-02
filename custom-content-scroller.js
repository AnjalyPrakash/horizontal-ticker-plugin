jQuery(document).ready(function($) {
    $('.custom-content-scroller').each(function() {
        var $scroller = $(this);
        var $scrollerContent = $scroller.find('.scroller-content');
        var speed = parseInt($scroller.data('speed'), 10);

        $scrollerContent.wrapAll("<div class='inner-wrap'></div>");
        var $wrap = $scroller.find('.inner-wrap');

        var totalWidth = 0;
        $scrollerContent.each(function() {
            totalWidth += $(this).outerWidth(true) + parseInt($(this).css("margin-right"));
        });

        $wrap.width(totalWidth * 2);

        // Clone the content to ensure it fills the visible area for seamless scrolling
        $scrollerContent.clone().appendTo($wrap);

        // Dynamically update the CSS animation based on the content width
        var animationTime = totalWidth / speed * 1000;
        $wrap.css({
            'animation-duration': `${animationTime}ms`,
            'animation-play-state': 'running'
        });

        // Handle hover to pause and resume animation
        $scroller.hover(
            function() {
                $wrap.css('animation-play-state', 'paused');
            },
            function() {
                $wrap.css('animation-play-state', 'running');
            }
        );
    });
});
