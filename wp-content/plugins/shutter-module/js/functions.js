var jQuery = $.noConflict(true);

function showSuccessModal(e, t, i, n) {
    $("#successModal .modal-header h3").html(e), $("#successModal .modal-body").html(t), "undefined" != typeof i && $("#successModal .modal-footer btn-close").html(i), $("#successModal").modal(), "undefined" != typeof n && window.setTimeout(function () {
        $("#successModal").modal("hide")
    }, 1e3 * n)
}

function showErrorModal(e, t, i, n) {
    $("#errorModal .modal-header h3").html(e), $("#errorModal .modal-body").html(t), "undefined" != typeof i && $("#errorModal .modal-footer btn-close").html(i), $("#errorModal").modal(), "undefined" != typeof n && window.setTimeout(function () {
        $("#errorModal").modal("hide")
    }, 1e3 * n)
}

function uniqueItems(e) {
    var t = e.filter(function (e, t, i) {
        return t == i.indexOf(e)
    });
    return t
}

function isPositiveInteger(e) {
    return e >>> 0 === parseFloat(e)
}

jQuery(document).ready(function ($) {
    $(".products-list li, .about-list li").mouseover(function () {
        $(this).siblings().css({
            "opacity": .25
        })
    }).mouseout(function () {
        $(this).siblings().css({
            "opacity": 1
        })
    }), $(".tabbable .nav a, .products-filter a").click(function (e) {
        $(".tabbable .nav a, .products-filter a").removeClass("active"), $(this).addClass("active"), e.preventDefault()
    }), $(window).on('load', function () {
        $(".flexslider").flexslider({
            "animation": "slide",
            "slideshowSpeed": 3500,
            "animationSpeed": 500,
            "prevText": "<i class='icon-angle-left'></i>",
            "nextText": "<i class='icon-angle-right'></i>"
        })
    }), $(".view-fancybox").fancybox({
        "openEffect": "elastic",
        "closeEffect": "elastic",
        "next": "left",
        "prev": "right"
    });
    var e = $("#isotope");
    // e.imagesLoaded(function () {
    //     e.isotope({
    //         "itemSelector": ".item"
    //     })
    // }), $("#filters a").click(function () {
    //     var t = $(this).attr("data-filter");
    //     return e.isotope({
    //         "filter": t
    //     }), !1
    // }),
    $(window).scroll(function () {
        0 != $(this).scrollTop() ? $("#toTop").fadeIn() : $("#toTop").fadeOut()
    }), $("#toTop").click(function () {
        $("body,html").animate({
            "scrollTop": 0
        }, 1e3)
    }), $(".change-css").click(function () {
        $("#changeColor").attr({
            "href": this.rel
        })
    }), $(".nav-stacked > li").each(function () {
        $(this).find("ul").length > 0 && $(this).find("a").first().append('<i class="icon-chevron-right">'), $(this).find("a").click(function () {
            return $(this).find("i").hasClass("icon-chevron-right") ? ($(this).find("i").removeClass("icon-chevron-right").addClass("icon-chevron-down"), $(this).parent().find("ul").fadeIn(), !1) : $(this).find("i").length > 0 ? ($(this).find("i").removeClass("icon-chevron-down").addClass("icon-chevron-right"), $(this).parent().find("ul").fadeOut(), !1) : !0
        })
    }), $("#search").bind("focus", function () {
        "" != $(this).val() && $(this).autocomplete("search")
    })
}),
    jQuery(document).ready(function ($) {
        $(".input-quantity").click(function () {
            this.select()
        }), $(".input-quantity").mouseover(function () {
            return !1
        })
    }), Array.prototype.filter || (Array.prototype.filter = function (e, t) {
    var i,
        n = this,
        s = [],
        a = 0,
        r = n.length;
    if ("function" == typeof e)
        for (; r > a;) a in n && (i = n[a], e.call(t, i, a, n) && (s[s.length] = i)), ++a;
    return s
});
