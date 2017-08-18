/* Navigation */
(function($) {
    $(document).ready(function() {
        $(window).resize(function() {
            if ($(window).width() >= 765) {
                $(".sidebar #nav").slideDown(350);
            } else {
                $(".sidebar #nav").slideUp(350);
            }
        });
        $("#nav > li > a").on("click", function(e) {
            if ($(this).parent().hasClass("has_sub")) {
                e.preventDefault();
            }
            if (!$(this).hasClass("subdrop")) {
                // hide any open menus and remove all other classes
                $("#nav li ul").slideUp(350);
                $("#nav li a").removeClass("subdrop").removeClass("open");
                // open our new menu and add the open class
                $(this).next("ul").slideDown(350);
                $(this).addClass("subdrop");
            } else if ($(this).hasClass("subdrop")) {
                $(this).removeClass("subdrop").addClass("open"); //add class open for background
                $(this).next("ul").slideUp(350);
            }
        });
        
        $(".sidebar-dropdown a").on("click", function(e) {
            e.preventDefault();
            if (!$(this).hasClass("open")) {
                // hide any open menus and remove all other classes
                $(".sidebar #nav").slideUp(350);
                $(".sidebar-dropdown a").removeClass("open");
                // open our new menu and add the open class
                $(".sidebar #nav").slideDown(350);
                $(this).addClass("open");
            } else if ($(this).hasClass("open")) {
                $(this).removeClass("open");
                $(".sidebar #nav").slideUp(350);
            }
        });

        var href = location.href.replace(/https?:\/\/[^\/]+/, '').replace(/\.html$/, '');
        $("#nav > li a").each(function() {
            if($(this).attr("href") == href) {
                $(this).addClass("current");
                $(this).parents("ul").slideDown(350).prev("a").addClass("subdrop");
                return false;
            }
        });

    });
})(jQuery);