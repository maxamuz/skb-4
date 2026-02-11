(function ($) {
  Drupal.behaviors.skb4_theme_helper = {
    attach: function (context, settings) {
      $("#header #navigation .main-menu-wrapper .menu-357").click(function () {
        $(".catalog-menu-wrapper").toggle();
        $(this).toggleClass("open");
        $("#page").toggleClass("open-menu");
      });

      $(".category.with-subcategories .sub-category").mCustomScrollbar({
        axis: "y",
        // setTop: 0
        scrollButtons: { enable: true },
        theme: "light-thick",
        scrollbarPosition: "outside",
      });

      if (window.innerWidth <= "768") {
        $(
          "#block-views-news-block-1 .view-content, #block-views-news-block-2 .view-content",
        ).mCustomScrollbar({
          axis: "x",
          // setTop: 0
          // scrollButtons:{enable:true},
          advanced: { autoExpandHorizontalScroll: true },
          theme: "light-thick",
          scrollbarPosition: "outside",
        });

        $(
          "#block-skb4-helper-skb4-helper-catalog .category.with-subcategories, #block-skb4-helper-skb4-helper-catalog-else .category.with-subcategories, #block-system-main .category.with-subcategories",
        ).each(function () {
          let cat = $(this);
          // $(cat).toggleClass('show');
          $("> a, h3 > a", cat).click(function (e) {
            if ($(cat).hasClass("show")) {
            } else {
              e.preventDefault();
            }
            $(cat).addClass("show");
            // e.preventDefault();
          });
          $(".mobile-close", cat).click(function () {
            $(cat).removeClass("show");
          });
        });
      }

      $(
        ".taxonomy-term.vocabulary-product-category .cats > .item-list",
      ).mCustomScrollbar({
        axis: "y",
        // setTop: 0
        scrollButtons: { enable: true },
        theme: "light-thick",
        scrollbarPosition: "outside",
      });

      $(".taxonomy-term.vocabulary-product-category .cats .toggle-list").click(
        function () {
          let but = $(this);
          if ($(but).hasClass("open")) {
            $(but).removeClass("open");
            $(
              ".taxonomy-term.vocabulary-product-category .cats > .item-list",
            ).slideDown();
            $(".taxonomy-term.vocabulary-product-category .cats").css(
              "height",
              "340px",
            );
            $(
              "#category-main .taxonomy-term.vocabulary-product-category .cats h3",
            ).css("margin-bottom", "20px");
          } else {
            $(but).addClass("open");
            $(".taxonomy-term.vocabulary-product-category .cats").css(
              "height",
              "auto",
            );
            $(
              ".taxonomy-term.vocabulary-product-category .cats > .item-list",
            ).slideUp(400, function () {
              $(
                "#category-main .taxonomy-term.vocabulary-product-category .cats h3",
              ).css("margin-bottom", "0");
            });
          }
        },
      );

      if ($(".view-id-references").length) {
        $(".view-id-references").once(function () {
          var jcarousel = $(".view-id-references");

          jcarousel
            .on("jcarousel:reload jcarousel:create", function () {
              var carousel = $(this),
                width = carousel.innerWidth();

              //carousel.jcarousel('items').css('width', Math.ceil(width) + 'px');
              if (window.innerWidth <= "768") {
                carousel.jcarousel("items").css("width", "120px");
              }
              /*if(window.innerWidth>'1190') {
              carousel.jcarousel('items').css('width', '270px');
            } else if ((window.innerWidth<='1190') && (window.innerWidth>'880')){
              carousel.jcarousel('items').css('width', '340px');
            } else if ((window.innerWidth<='880') && (window.innerWidth>'640')){
              carousel.jcarousel('items').css('width', '300px');
            } else {
              carousel.jcarousel('items').css('width', '274px');
            }*/
            })
            .on("jcarousel:createend", function () {
              // Arguments:
              // 1. The method to call
              // 2. The index of the item (note that indexes are 0-based)
              // 3. A flag telling jCarousel jumping to the index without animation
              $(this).jcarousel("scroll", 1, false);
            })
            .jcarousel({
              wrap: "circular",
              transitions: true,
              center: true,
            });

          $(".view-id-references").before(
            "<div class='jcarousel-pagination-n'></div>",
          );

          $(".jcarousel-pagination-n").append(
            "<div class='section'><a href='#' class='jcarousel-control-prev-n' data-jcarouselcontrol='true'></a><a href='#' class='jcarousel-control-next-n' data-jcarouselcontrol='true'></a></div>",
          );

          $(".jcarousel-control-prev-n").jcarouselControl({
            target: "-=1",
          });

          $(".jcarousel-control-next-n").jcarouselControl({
            target: "+=1",
          });

          $(".jcarousel-pagination-n")
            .on("jcarouselpagination:active", "a", function () {
              $(this).addClass("active");
            })
            .on("jcarouselpagination:inactive", "a", function () {
              $(this).removeClass("active");
            })
            .on("click", function (e) {
              e.preventDefault();
            });

          let visibleSlideOffset = 5;

          /*if(window.innerWidth<'1110') {
            visibleSlideOffset = 2;
          }*/

          for (var i = 0; i < visibleSlideOffset; i++) {
            $(".jcarousel-control-next-n").trigger("click");
          }

          let slideViewTime = 5000;

          let timerId = setInterval(function () {
            $(".jcarousel-control-next-n").trigger("click");
          }, slideViewTime);
        });
      }

      if ($(".views-field-field-news-gallery").length) {
        $(".views-field-field-news-gallery").once(function () {
          var jcarousel = $(".views-field-field-news-gallery");

          jcarousel
            .on("jcarousel:reload jcarousel:create", function () {
              var carousel = $(this),
                width = carousel.innerWidth();

              //carousel.jcarousel('items').css('width', Math.ceil(width) + 'px');
              // if(window.innerWidth>'1190') {
              //   carousel.jcarousel('items').css('width', '380px');
              // } else if ((window.innerWidth<='1190') && (window.innerWidth>'880')){
              //   carousel.jcarousel('items').css('width', '340px');
              // } else if ((window.innerWidth<='880') && (window.innerWidth>'640')){
              //   carousel.jcarousel('items').css('width', '300px');
              // } else {
              //   carousel.jcarousel('items').css('width', '274px');
              // }
            })
            .on("jcarousel:createend", function () {
              // Arguments:
              // 1. The method to call
              // 2. The index of the item (note that indexes are 0-based)
              // 3. A flag telling jCarousel jumping to the index without animation
              $(this).jcarousel("scroll", 1, false);
            })
            .jcarousel({
              wrap: "circular",
              transitions: true,
              center: true,
            });

          $(".views-field-field-news-gallery").before(
            "<div class='jcarousel-pagination-n'></div>",
          );

          $(".jcarousel-pagination-n").append(
            "<div class='section'><a href='#' class='jcarousel-control-prev-n' data-jcarouselcontrol='true'></a><a href='#' class='jcarousel-control-next-n' data-jcarouselcontrol='true'></a></div>",
          );

          $(".jcarousel-control-prev-n").jcarouselControl({
            target: "-=1",
          });

          $(".jcarousel-control-next-n").jcarouselControl({
            target: "+=1",
          });

          $(".jcarousel-pagination-n")
            .on("jcarouselpagination:active", "a", function () {
              $(this).addClass("active");
            })
            .on("jcarouselpagination:inactive", "a", function () {
              $(this).removeClass("active");
            })
            .on("click", function (e) {
              e.preventDefault();
            });

          let visibleSlideOffset = 5;

          /*if(window.innerWidth<'1110') {
            visibleSlideOffset = 2;
          }*/

          for (var i = 0; i < visibleSlideOffset; i++) {
            $(".jcarousel-control-next-n").trigger("click");
          }

          /*let slideViewTime = 5000;

          let timerId = setInterval(function() {
            $('.jcarousel-control-next-n').trigger('click');
          }, slideViewTime);*/
        });
      }

      $(".vocabulary-product-category .cats .mCSB_container").once(function () {
        $("li").each(function () {
          let $li = $(this);
          $(" > .parent-link > .with-child", $li).click(function () {
            let $span = $(this);
            $($span).toggleClass("open");
            //let $li = $($parent_link).parent();
            $(" > .item-list", $li).slideToggle();
          });
          //
        });
      });

      $(".view-news.view-display-id-page .view-content").masonry({
        itemSelector: ".views-row",
        layoutMode: "fitRows",
        percentPosition: true,
        gutter: 0,
      });

      var $page = $("html, body");
      $(
        '.node-brand.node-view-mode-full a[href*="#"], .category-navigation a[href*="#"]',
      ).click(function (event) {
        event.preventDefault();
        let tmp = $.attr(this, "href").split("#");
        if (tmp.length == 2) {
          let blockID = tmp[1]; //console.log(blockID);
          var offet = 150;
          /*if ($("body").hasClass('admin-menu'))
              offset += 20;*/
          $page.animate(
            {
              scrollTop: $("#" + blockID).offset().top - 150,
            },
            800,
          );
        }
        return false;
      });

      if ($("#header").length) {
        let sh = $("#header").stickMe({
          topOffset: 180,
          backScrollOffset: 210,
        });
      }

      $(
        "body.node-type-product #main-wrapper #product-main .node-product .product-content-wrapper .field-name-body table, .node-brand .body-wrapper .field-name-body table",
      ).each(function () {
        $(this).parent("div").addClass("with-table");
      });

      $(
        ".node-brand, #block-skb4-helper-skb4-helper-current-offer",
        context,
      ).once("current-offer-wrapper", function () {
        $("table#current-offers", this).each(function () {
          var table = $(this);
          var ths = $("tr:first-child th", this);
          var titles = new Array();
          for (var i = 0; i < ths.length; i++) {
            // console.log($(ths[i]).text());
            titles.push($(ths[i]).text().trim());
          }
          var trs = $("tr", table);
          for (var j = 1; j < trs.length; j++) {
            var tds = $("td", trs[j]);
            for (var k = 0; k < tds.length; k++) {
              $(tds[k]).attr("data-title", titles[k]);
            }
          }
        });
      });

      $("#block-skb4-helper-skb4-helper-current-offer", context).once(
        "content",
        function () {
          $("table#current-offers", this).each(function () {
            var table = $(this);
            var ths = $("tr:first-child th", this);
            var titles = new Array();
            for (var i = 0; i < ths.length; i++) {
              // console.log($(ths[i]).text());
              titles.push($(ths[i]).text().trim());
            }
            var trs = $("tr", table);
            for (var j = 1; j < trs.length; j++) {
              var tds = $("td", trs[j]);
              for (var k = 0; k < tds.length; k++) {
                $(tds[k]).attr("data-title", titles[k]);
              }
            }
          });
        },
      );

      $(".field-name-field-brend-intro", context).each(function () {
        let arrow = $(".arrow", this);
        let hidden = $(".hidden", this);
        $(arrow).click(function () {
          $(arrow).hide();
          $(hidden).show("slow");
        });
      });
    },
  };

  Drupal.behaviors.skb4_filemanaged = {
    attach: function (context, settings) {
      $(".form-managed-file", context).once(
        "form-managed-file-init",
        function () {
          var wrapper1 = $(this),
            wrapper = $(".file-input-container", wrapper1),
            inp = wrapper.find("input"),
            btn = wrapper.find("button"),
            upload_btn = $(".form-submit", wrapper);
          //lbl = wrapper.find( "mark" );

          btn.focus(function () {
            inp.focus();
          });
          // Crutches for the :focus style:
          inp
            .focus(function () {
              wrapper.addClass("focus");
            })
            .blur(function () {
              wrapper.removeClass("focus");
            });

          var file_api =
            window.File && window.FileReader && window.FileList && window.Blob
              ? true
              : false;

          inp
            .change(function () {
              var file_name;
              if (file_api && inp[0].files[0]) {
                file_name = inp[0].files[0].name;
                // console.log(inp);
                // console.log(inp[0].files);
              } else {
                file_name = inp.val().replace("C:\\", "");
              }

              if (!file_name.length) return;
              btn.text(file_name);
              $(
                "input[id^='edit-submitted-attach-upload-button']",
                wrapper1,
              ).mousedown();
              /*if( lbl.is( ":visible" ) ){
                lbl.text( file_name );
            }else
                btn.text( file_name );*/
            })
            .change();
        },
      );

      $(
        ".form-managed-file input[id^='edit-submitted-attach-upload-button']",
      ).css({ display: "none" });
      /*$('.form-managed-file').delegate( 'input.form-file', 'change', function () {
        console.log('tut2');
        let form_file = $(this) ;
        $('input.form-submit', form_file).next('input[type="submit"]').mousedown ();
      });*/
    },
  };

  // Drupal.behaviors.recaptchaAjax = {
  //   attach: function (context, settings) {
  //     if ('grecaptcha' in window && context !== document) {
  //       $('.g-recaptcha:empty', context).each(function () {
  //         grecaptcha.render(this, $(this).data());
  //       });
  //     }
  //   }
  // };
})(jQuery);

(function (w, d, u) {
  var s = d.createElement("script");
  s.async = true;
  s.src = u + "?" + ((Date.now() / 60000) | 0);
  var h = d.getElementsByTagName("script")[0];
  h.parentNode.insertBefore(s, h);
})(
  window,
  document,
  "https://cdn-ru.bitrix24.ru/b34340734/crm/site_button/loader_2_vow86j.js",
);

// ---- Скрипт для cookie ---- //

// ---- Скрипт для cookie ---- //

document.addEventListener("DOMContentLoaded", function () {
  const consentPanel = document.getElementById("cookie-consent");
  const acceptBtn = document.getElementById("accept-cookies");

  // Проверяем, было ли уже принято решение
  if (
    localStorage.getItem("cookiesAccepted") !== "true" &&
    localStorage.getItem("cookiesAccepted") !== "false"
  ) {
    // Если решения не было, показываем панель
    consentPanel.classList.add("visible");
  }
  // Если решение уже было принято, ничего не делаем - панель останется скрытой благодаря CSS

  // Обработчик кнопки "Принять"
  acceptBtn.addEventListener("click", function () {
    localStorage.setItem("cookiesAccepted", "true");
    consentPanel.classList.remove("visible");
    consentPanel.classList.add("hidden");
  });
});
