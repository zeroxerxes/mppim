(function($) {
    'use strict';
    // *************************************
    // Social Share window
    // *************************************
    $(".ultp-post-share-item a").each(function() {
        $(this).click(function() {
            // For Share window opening
            let share_url = $(this).attr("url");
            let width = 800;
            let height = 500;
            let leftPosition, topPosition;
            //Allow for borders.
            leftPosition = (window.screen.width / 2) - ((width / 2) + 10);
            //Allow for title and status bars.
            topPosition = (window.screen.height / 2) - ((height / 2) + 50);
            let windowFeatures = "height=" + height + ",width=" + width + ",resizable=yes,left=" + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY=" + topPosition;
            window.open(share_url,'sharer', windowFeatures);
            // For Share count add
            let id = $(this).parents(".ultp-post-share-item-inner-block").attr("postId");
            let count = $(this).parents(".ultp-post-share-item-inner-block").attr("count");
            $.ajax({
                url: ultp_data_frontend.ajax,
                type: 'POST',
                data: {
                    action: 'ultp_share_count', 
                    shareCount:count, 
                    postId: id,
                    wpnonce: ultp_data_frontend.security
                },
                error: function(xhr) {
                    console.log('Error occured.please try again' + xhr.statusText + xhr.responseText );
                },
            });
            
            return false;
        })
    })
    // remove sticky behavior when footer is visible
    $(window).scroll(function() {
        if ($(window).scrollTop() + window.innerHeight >= $('footer')?.offset()?.top) {
            $('.wp-block-ultimate-post-post_share .ultp-block-wrapper .ultp-disable-sticky-footer').addClass("remove-sticky");
        } else {
            $('.wp-block-ultimate-post-post_share .ultp-block-wrapper .ultp-disable-sticky-footer').removeClass("remove-sticky");
        }
    });

    // *************************************
    // News Ticker
    // *************************************
    $('.ultp-news-ticker').each( function () {
        $(this).UltpSlider({
            type: $(this).data('type'),
            direction: $(this).data('direction'),
            speed: $(this).data('speed'),
            pauseOnHover: $(this).data('hover') == 1 ? true : false,
            controls: {
                prev: $(this).closest('.ultp-newsTicker-wrap').find('.ultp-news-ticker-prev'),
                next: $(this).closest('.ultp-newsTicker-wrap').find('.ultp-news-ticker-next'),
                toggle: $(this).closest('.ultp-newsTicker-wrap').find('.ultp-news-ticker-pause')
            }
        });
    });

    // *************************************
    // Table of Contents
    // *************************************
    $(".ultp-toc-backtotop").click(function(e) {
        e.preventDefault();
        $("html, body").animate({ scrollTop: 0 }, "slow");
    });
    
    $(window).scroll(function() {
        scrollTopButton(); 
    });

    function scrollTopButton() {
        if ($(document).scrollTop() > 1000) {
            $('.ultp-toc-backtotop').addClass('tocshow');
            $('.wp-block-ultimate-post-table-of-content').addClass('ultp-toc-scroll');
        } else {
            $('.ultp-toc-backtotop').removeClass('tocshow');
            $('.wp-block-ultimate-post-table-of-content').removeClass('ultp-toc-scroll');
        }
    }
    scrollTopButton();

    $(".ultp-collapsible-open").click(function(e) {
        $(this).closest('.ultp-collapsible-toggle').removeClass('ultp-toggle-collapsed');
        $(this).parents('.ultp-block-toc').find('.ultp-block-toc-body').show();
    });

    $(".ultp-collapsible-hide").click(function(e) {
        $(this).closest('.ultp-collapsible-toggle').addClass('ultp-toggle-collapsed');
        $(this).parents('.ultp-block-toc').find('.ultp-block-toc-body').hide();
    });
    
    $(".ultp-toc-lists li a").click(function() {
        $([document.documentElement, document.body]).animate({
            scrollTop: $($(this).attr('href')).offset().top - 50
        }, 500);
    });  


    // *************************************
    // Flex Menu
    // *************************************
    $(document).ready(function() {
        if ($('.ultp-flex-menu').length > 0) {
            const menuText = $('ul.ultp-flex-menu').data('name');
            $('ul.ultp-flex-menu').flexMenu({linkText: menuText, linkTextAll: menuText, linkTitle: menuText});
        }
    });
    $(document).on('click', function (e) {
        if ($(e.target).closest(".flexMenu-viewMore").length === 0) {
            $('.flexMenu-viewMore').removeClass('active');
            $('.flexMenu-viewMore').children("ul.flexMenu-popup").css("display","none")
        }
    });
    $(document).on('click', '.ultp-filter-navigation .flexMenu-popup .filter-item', function(e){
        $('.flexMenu-viewMore').removeClass('active');
        $('.flexMenu-viewMore').children("ul.flexMenu-popup").css("display","none")
    });
    
    // *************************************
    // Previous Next
    // *************************************
    
    $(document).off('click', '.ultp-pagination-ajax-action li, .ultp-loadmore-action, .ultp-prev-action, .ultp-next-action', function(e) { })
    $(document).on('click', '.ultp-prev-action, .ultp-next-action', function(e) {
        e.preventDefault();
        let parents = $(this).closest('.ultp-next-prev-wrap'),
            wrap    = parents.closest('.ultp-block-wrapper').find('.ultp-block-items-wrap'),
            paged   = parseInt(parents.data('pagenum')),
            pages   = parseInt(parents.data('pages'));
        
        if(parents.is('.ultp-disable-editor-click')) {
            return
        }
        if ($(this).hasClass('ultp-prev-action')) {
            if ($(this).hasClass('ultp-disable')) {
                return
            }else{
                paged--;
                parents.data('pagenum', paged);
                parents.find('.ultp-prev-action, .ultp-next-action').removeClass('ultp-disable')
                if (paged == 1) {
                    $(this).addClass('ultp-disable');
                }
            }
        }
        if ($(this).hasClass('ultp-next-action')) {
            if ($(this).hasClass('ultp-disable')) {
                return
            }else{
                paged++;
                parents.data('pagenum', paged);
                parents.find('.ultp-prev-action, .ultp-next-action').removeClass('ultp-disable')
                if (paged == pages) {
                    $(this).addClass('ultp-disable');
                }
            }
        }

        let post_ID = (parents.parents('.ultp-shortcode').length != 0 && parents.data('selfpostid') == 'no') ? parents.parents('.ultp-shortcode').data('postid') : parents.data('postid');

        if ($(this).closest('.ultp-builder-content').length > 0) {
            post_ID = $(this).closest('.ultp-builder-content').data('postid')
        }
        let widgetBlockId = '';
        let widgetBlock = $(this).parents('.widget_block:first');
        if(widgetBlock.length > 0) {
            let widget_items = widgetBlock.attr('id').split("-");
            widgetBlockId = widget_items[widget_items.length-1]
        }
        const ultpUniqueIds = sessionStorage.getItem('ultp_uniqueIds');
        const ultpCurrentUniquePosts = JSON.stringify(wrap.find('.ultp-current-unique-posts').data('current-unique-posts'));

		$.ajax({
            url: ultp_data_frontend.ajax,
            type: 'POST',
            data: {
                action: 'ultp_next_prev', 
                paged: paged ,
                blockId: parents.data('blockid'),
                postId: post_ID,
                exclude: parents.data('expost'),
                blockName: parents.data('blockname'),
                builder: parents.data('builder'),
                filterValue: parents.data('filter-value') || '',
                filterType: parents.data('filter-type') || '',
                widgetBlockId: widgetBlockId,
                ultpUniqueIds : ultpUniqueIds || [],
                ultpCurrentUniquePosts: ultpCurrentUniquePosts || [],
                wpnonce: ultp_data_frontend.security
            },
            beforeSend: function() {
                parents.closest('.ultp-block-wrapper').addClass('ultp-loading-active')
            },
            success: function(data) {
                if(data) {
                    wrap.html(data);
                    setSession('ultp_uniqueIds', JSON.stringify(wrap.find('.ultp-current-unique-posts').data('ultp-unique-ids')) );
                }
            },
            complete:function() {
                parents.closest('.ultp-block-wrapper').removeClass('ultp-loading-active');
            },
            error: function(xhr) {
                console.log('Error occured.please try again' + xhr.statusText + xhr.responseText );
                parents.closest('.ultp-block-wrapper').removeClass('ultp-loading-active');
            },
        });
    });

    // *************************************
    // Loadmore Append
    // *************************************
    
    $(document).on('click', '.ultp-loadmore-action', function(e) {
        if($(this).is('.ultp-disable-editor-click')) {
            return
        }
        e.preventDefault();
        let that    = $(this),
            parents = that.closest('.ultp-block-wrapper'),
            paged   = parseInt(that.data('pagenum')),
            pages   = parseInt(that.data('pages'));
        
        if (that.hasClass('ultp-disable')) {
            return
        }else{
            paged++;
            that.data('pagenum', paged);
            if (paged == pages) {
                $(this).addClass('ultp-disable');
            }else{
                $(this).removeClass('ultp-disable');
            }
        }

        let post_ID = (that.parents('.ultp-shortcode').length != 0 && that.data('selfpostid') == 'no' ) ? that.parents('.ultp-shortcode').data('postid') : that.data('postid');

        if (that.closest('.ultp-builder-content').length > 0) {
            post_ID = that.closest('.ultp-builder-content').data('postid')
        }
        let widgetBlockId = '';
        let widgetBlock = $(this).parents('.widget_block:first');
        if(widgetBlock.length > 0) {
            let widget_items = widgetBlock.attr('id').split("-");
            widgetBlockId = widget_items[widget_items.length-1]
        }

        const ultpUniqueIds = sessionStorage.getItem('ultp_uniqueIds');
        const ultpCurrentUniquePosts = JSON.stringify(parents.find('.ultp-current-unique-posts').data('current-unique-posts'));
        $.ajax({
            url: ultp_data_frontend.ajax,
            type: 'POST',
            data: {
                action: 'ultp_next_prev', 
                paged: paged ,
                blockId: that.data('blockid'),
                postId: post_ID,
                blockName: that.data('blockname'),
                builder: that.data('builder'),
                filterValue: that.closest('.ultp-loadmore').data('filter-value') || '',
                filterType: that.closest('.ultp-loadmore').data('filter-type') || '',
                widgetBlockId: widgetBlockId,
                ultpUniqueIds : ultpUniqueIds || [],
                ultpCurrentUniquePosts: ultpCurrentUniquePosts || [],
                wpnonce: ultp_data_frontend.security
            },
            beforeSend: function() {
                parents.addClass('ultp-loading-active');
            },
            success: function(data) {
                if(data) {
                    parents.find('.ultp-current-unique-posts').remove();
                    $(data).insertBefore( parents.find('.ultp-loadmore-insert-before') );
                    setSession('ultp_uniqueIds', JSON.stringify(parents.find('.ultp-current-unique-posts').data('ultp-unique-ids')) );
                }
            },
            complete:function() {
                parents.removeClass('ultp-loading-active');
            },
            error: function(xhr) {
                console.log('Error occured.please try again' + xhr.statusText + xhr.responseText );
                parents.removeClass('ultp-loading-active');
            },
        });
    });


    // *************************************
    // Filter
    // *************************************
    $(document).on('click', '.ultp-filter-wrap li a', function(e) {
        e.preventDefault();

        if ($(this).closest('li').hasClass('filter-item')) {
            let that    = $(this),
                parents = that.closest('.ultp-filter-wrap'),
                wrap = that.closest('.ultp-block-wrapper');

                parents.find('a').removeClass('filter-active');
                that.addClass('filter-active');
            if(parents.is('.ultp-disable-editor-click')) {
                return
            }
            let post_ID = (parents.parents('.ultp-shortcode').length != 0 && parents.data('selfpostid') == 'no') ? parents.parents('.ultp-shortcode').data('postid') : parents.data('postid');

            if (that.closest('.ultp-builder-content').length > 0) {
                post_ID = that.closest('.ultp-builder-content').data('postid')
            }
            let widgetBlockId = '';
            let widgetBlock = $(this).parents('.widget_block:first');
            if(widgetBlock.length > 0) {
                let widget_items = widgetBlock.attr('id').split("-");
                widgetBlockId = widget_items[widget_items.length-1]
            }
            if (parents.data('blockid')) {
                $.ajax({
                    url: ultp_data_frontend.ajax,
                    type: 'POST',
                    data: {
                        action: 'ultp_filter', 
                        taxtype: parents.data('taxtype'),
                        taxonomy: that.data('taxonomy'),
                        blockId: parents.data('blockid'),
                        postId: post_ID,
                        blockName: parents.data('blockname'),
                        widgetBlockId: widgetBlockId,
                        wpnonce: ultp_data_frontend.security
                    },
                    beforeSend: function() {
                        wrap.addClass('ultp-loading-active');
                    },
                    success: function(response) {
                        wrap.find('.ultp-block-items-wrap').html(response?.data?.filteredData?.blocks);
                        if(response?.data?.filteredData?.paginationType == 'loadMore' && response?.data?.filteredData?.paginationShow) {
                            wrap.find('.ultp-block-items-wrap').append('<span class="ultp-loadmore-insert-before"></span>');
                            wrap.find('.ultp-loadmore').replaceWith(response?.data?.filteredData?.pagination);
                        }
                        else if(response?.data?.filteredData?.paginationType == 'navigation') {
                            wrap.find('.ultp-next-prev-wrap').replaceWith(response?.data?.filteredData?.pagination);
                        }
                        else if(response?.data?.filteredData?.paginationType == 'pagination') {
                            wrap.find('.ultp-pagination-wrap').replaceWith(response?.data?.filteredData?.pagination);
                        }
                    },
                    complete:function() {
                        wrap.removeClass('ultp-loading-active');
                    },
                    error: function(xhr) {
                        console.log('Error occured.please try again' + xhr.statusText + xhr.responseText );
                        wrap.removeClass('ultp-loading-active');
                    },
                });
            }
        }
    });


    // *************************************
    // Pagination Number
    // *************************************
    function showHide(parents, pageNum, pages) {
        if (pageNum == pages) {
            parents.find('.ultp-next-page-numbers').hide()
        }  else {
            parents.find('.ultp-next-page-numbers').show()
        }

        if(pageNum > 1) {
            parents.find('.ultp-prev-page-numbers').show();
        } else {
            parents.find('.ultp-prev-page-numbers').hide()
        }

        if(pageNum > 3) {
            parents.find('.ultp-first-dot').show();
        } else {
            parents.find('.ultp-first-dot').hide();
        }

        if (pageNum > 2) {
            parents.find('.ultp-first-pages').show()
        } else {
            parents.find('.ultp-first-pages').hide()
        }

        if (pages > pageNum + 2) {
            parents.find('.ultp-last-dot').show()
        } else{
            parents.find('.ultp-last-dot').hide()
        }
        
        if (pages > pageNum + 1) {
            parents.find('.ultp-last-pages').show()
        } else{
            parents.find('.ultp-last-pages').hide()
        }
    }

    function serial(parents, pageNum, pages) {
        let datas = pageNum <= 2 ? [1,2,3] : ( pages == pageNum ? [pages-2,pages-1, pages] : [pageNum-1,pageNum,pageNum+1] )
        let i = 0
        parents.find('.ultp-center-item').each(function() {
            if (pageNum == datas[i]) {
                $(this).addClass('pagination-active')
            }
            $(this).find('a').blur();
            $(this).attr('data-current', datas[i]).find('a').text(datas[i])
            i++
        });
    }

    // set session value for unique content on page reload
    if ( $('.ultp-current-unique-posts').length > 0 ) {
        $('.ultp-current-unique-posts').each( function () {
            setSession('ultp_uniqueIds', JSON.stringify($(this).data('ultp-unique-ids')) );
        });
    }
    // session value set function
    function setSession(key, value) {
        if(value != undefined) {
            sessionStorage.setItem(key, value );
        }
    }

    $(document).on('click', '.ultp-pagination-ajax-action li', function(e) {
        e.preventDefault();
        let that    = $(this),
            parents = that.closest('.ultp-pagination-ajax-action'),
            wrap = that.closest('.ultp-block-wrapper');
        if(parents.is('.ultp-disable-editor-click')) {
            return
        }
        let pageNum = 1;
        let pages = parents.attr('data-pages');
        
        if (that.attr('data-current')) {
            pageNum = Number(that.attr('data-current'))
            parents.attr('data-paged', pageNum).find('li').removeClass('pagination-active')
            serial(parents, pageNum, pages)
            showHide(parents, pageNum, pages)
        } else {
            if (that.hasClass('ultp-prev-page-numbers')) {
                pageNum = Number(parents.attr('data-paged')) - 1
                parents.attr('data-paged', pageNum).find('li').removeClass('pagination-active')
                //parents.find('li[data-current="'+pageNum+'"]').addClass('pagination-active')
                serial(parents, pageNum, pages)
                showHide(parents, pageNum, pages)
            } else if (that.hasClass('ultp-next-page-numbers')) {
                pageNum = Number(parents.attr('data-paged')) + 1
                parents.attr('data-paged', pageNum).find('li').removeClass('pagination-active')
                //parents.find('li[data-current="'+pageNum+'"]').addClass('pagination-active')
                serial(parents, pageNum, pages)
                showHide(parents, pageNum, pages)
            }
        }

        let post_ID = (parents.parents('.ultp-shortcode').length != 0 && parents.data('selfpostid') == 'no') ? parents.parents('.ultp-shortcode').data('postid') : parents.data('postid');

        if (that.closest('.ultp-builder-content').length > 0) {
            post_ID = that.closest('.ultp-builder-content').data('postid')
        }
        let widgetBlockId = '';
        let widgetBlock = $(this).parents('.widget_block:first');
        if(widgetBlock.length > 0) {
            let widget_items = widgetBlock.attr('id').split("-");
            widgetBlockId = widget_items[widget_items.length-1]
        }

        const ultpUniqueIds = sessionStorage.getItem('ultp_uniqueIds');
        const ultpCurrentUniquePosts = JSON.stringify(wrap.find('.ultp-current-unique-posts').data('current-unique-posts'));

        if (pageNum) {
            $.ajax({
                url: ultp_data_frontend.ajax,
                type: 'POST',
                data: {
                    exclude: parents.data('expost'),
                    action: 'ultp_pagination', 
                    paged: pageNum,
                    blockId: parents.data('blockid'),
                    postId: post_ID,
                    blockName: parents.data('blockname'),
                    builder: parents.data('builder'),
                    filterValue: parents.data('filter-value') || '',
                    filterType: parents.data('filter-type') || '',
                    widgetBlockId: widgetBlockId,
                    ultpUniqueIds : ultpUniqueIds || [],
                    ultpCurrentUniquePosts: ultpCurrentUniquePosts || [],
                    wpnonce: ultp_data_frontend.security
                },
                beforeSend: function() {
                    wrap.addClass('ultp-loading-active');
                },
                success: function(data) {
                    wrap.find('.ultp-block-items-wrap').html(data);
                    setSession('ultp_uniqueIds', JSON.stringify(wrap.find('.ultp-current-unique-posts').data('ultp-unique-ids')) );
                    if ($(window).scrollTop() > wrap.offset().top) {
                        $([document.documentElement, document.body]).animate({
                            scrollTop: wrap.offset().top - 80
                        }, 100);
                    }
                },
                complete:function() {
                    wrap.removeClass('ultp-loading-active');
                },
                error: function(xhr) {
                    console.log('Error occured.please try again' + xhr.statusText + xhr.responseText );
                    wrap.removeClass('ultp-loading-active');
                },
            });
        }
    });
    
    // *************************************
    // SlideShow
    // *************************************
    
    // Slideshow Display For Elementor via Shortcode
    $( window ).on( 'elementor/frontend/init', () => {
        setTimeout( () => {
            if ($('.elementor-editor-active').length > 0) {
                slideshowDisplay();
            }
        }, 2000);
    });

    // Bricks Builder Backend Slider Support
    if ($(window.parent.document).find('.bricks-panel-controls').length > 0) {
        setTimeout( () => {
            slideshowDisplay();
        }, 2500 );
    }
    
    function slideshowDisplay() {
        $('.wp-block-ultimate-post-post-slider-1, .wp-block-ultimate-post-post-slider-2').each(function () {
            const sectionId = '#' + $(this).attr('id');
            let selector = $(sectionId).find('.ultp-block-items-wrap');
            if($(this).parent('.ultp-shortcode')) {
                selector = $(this).find('.ultp-block-items-wrap');
            }
            let settings = {
                arrows: true,
                dots: selector.data('dots') ? true : false,
                infinite: true,
                speed: 500,
                slidesToShow: selector.data('slidelg') || 1,
                slidesToScroll: 1,
                autoplay: selector.data('autoplay') ? true : false,
                autoplaySpeed: selector.data('slidespeed') || 3000,
                cssEase: "linear",
                prevArrow: selector.parent().find('.ultp-slick-prev').html(),
                nextArrow: selector.parent().find('.ultp-slick-next').html(),
            };
            
            let layTemp = selector.data('layout') == "slide2" || selector.data('layout') == "slide3"  || selector.data('layout') == "slide5" || selector.data('layout') == "slide6"  || selector.data('layout') == "slide8" ;

            if(!selector.data('layout')) { // Slider 1
                if (selector.data('slidelg') < 2) {
                    settings.fade = selector.data('fade') ? true : false
                } else {
                    settings.responsive = [
                        {
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: selector.data('slidesm') || 1,
                                slidesToScroll: 1,
                            }
                        },
                        {
                            breakpoint: 600,
                            settings: {
                                slidesToShow: selector.data('slidexs') || 1,
                                slidesToScroll: 1
                            }
                        }
                    ]
                }
            } else { // Slider 2
                if( selector.data('fade') && layTemp) {
                    settings.fade = selector.data('fade') ? true : false;
                } else if ( !(selector.data('fade')) && layTemp) {
                    settings.slidesToShow = selector.data('slidelg') || 1,
                    settings.responsive = [
                        {
                            breakpoint: 991,
                            settings: {
                                slidesToShow: selector.data('slidesm') || 1,
                                slidesToScroll: 1,
                            }
                        },
                        {
                            breakpoint: 767,
                            settings: {
                                slidesToShow: selector.data('slidexs') || 1,
                                slidesToScroll: 1
                            }
                        }
                    ]
                }  else {
                        settings.slidesToShow = selector.data('slidelg') || 1,
                        settings.centerMode =  true;
                        settings.centerPadding = `${selector.data('paddlg')}px` || 100
                        settings.responsive = [
                            {
                                breakpoint: 991,
                                settings: {
                                    slidesToShow: selector.data('slidesm') || 1,
                                    slidesToScroll: 1,
                                    centerPadding: `${selector.data('paddsm')}px` || 50,
                                }
                            },
                            {
                                breakpoint: 767,
                                settings: {
                                    slidesToShow: selector.data('slidexs') || 1,
                                    slidesToScroll: 1,
                                    centerPadding: `${selector.data('paddxs')}px` || 50,
                                }
                            }
                        ]
                }
            }
            selector.not('.slick-initialized').slick(settings);
        });
    }
    slideshowDisplay();

    // *************************************
    // Accessibility for Loadmore Added
    // *************************************
    $('span[role="button"]').on('keydown', function (e) {
        const keyD = e.key !== undefined ? e.key : e.keyCode;
        if ((keyD === 'Enter' || keyD === 13) || (['Spacebar', ' '].indexOf(keyD) >= 0 || keyD === 32)) {
            e.preventDefault();
            this.click();
        }
    });

    // *************************************
    // Post Grid Popup Modal 
    // *************************************
    $(document).on('click', '.ultp-block-item .ultp-video-icon', function () {
        const parent = $(this).parents('.ultp-block-item');
        let videoIframe = parent.find('iframe');
        parent.find('.ultp-video-modal').addClass('modal_active');
        if (videoIframe.length) {
            let isAutoPlay = parent.find('.ultp-video-icon').attr('enableAutoPlay');
            // Update Src For Autoplay
            if (videoIframe.attr('src') && isAutoPlay) {
                videoIframe.attr('src', `${videoIframe.attr('src')}&autoplay=1`);
            }
            // Spinner for Video
            parent.find('iframe').on("load", function() {
                $('.ultp-loader-container').css('display','none');
            });
        } else {
            $('.ultp-video-modal.modal_active').find('video').trigger('play');
        }
    });
    // Close On Click
    $(document).on('click', '.ultp-video-close', function () {
        closeVideoModal();
    });
    // Escape for Close Modal
    $(document).on('keyup', function(e) {
        if (e.key == "Escape") {
            closeVideoModal();
        }
    });
    function closeVideoModal() {
        if ($('.ultp-video-modal.modal_active').length > 0) {
            let videoIframe = $('.ultp-video-modal.modal_active').find('iframe');
            if (videoIframe.length) {
                if (videoIframe.attr('src')) {
                    const stopVideo = videoIframe.attr('src').replace("&autoplay=1", "");
                    videoIframe.attr('src', stopVideo);
                }
            } else {
                $('.ultp-video-modal.modal_active').find('video').trigger('pause');
            }
            $('.ultp-video-modal').removeClass('modal_active');
        }
    }

    // *************************************
    //  Video Scroll
    // *************************************
    let isSticky = true;
    $(window).scroll(function() {
        let windowHeight = $(this).scrollTop();
        $('.wp-block-ultimate-post-post-image').each(function(){
            let contentSelector = $(this).find('.ultp-builder-video video , .ultp-builder-video iframe');
            if($(this).find('.ultp-video-block').hasClass('ultp-sticky-video')){
                // block height and position
                let blockContent = $(this).find('.ultp-image-wrapper');
                let blockPosition = blockContent.offset();
                // Video Html height and position
                let videoContent = contentSelector.height();
                let videoPosition = contentSelector.offset();
                // Exclude Adminbar height
                let windowTotalHeight = windowHeight + ($('#wpadminbar').height() || 0);
                let totalHeight =  videoPosition.top + videoContent;
                // Scrolling Top to bottom
                if((windowTotalHeight > videoPosition.top)){
                    if(windowTotalHeight > totalHeight && isSticky){
                        $(this).find('.ultp-image-wrapper').css('height', blockContent.height())
                        $(this).find('.ultp-sticky-video').addClass('ultp-sticky-active');
                    }
                }
                // Scrolling bottom to top
                if(windowTotalHeight < (blockContent.height() + blockPosition.top)){
                    $(this).find('.ultp-sticky-video').removeClass('ultp-sticky-active');
                    $(this).find('.ultp-image-wrapper').css('height', 'auto')
                }
                // Close Button
                $('.ultp-sticky-close').on('click', function(){
                    $(this).find('.ultp-image-wrapper').css('height', 'auto')
                    $('.ultp-sticky-video').removeClass('ultp-sticky-active');
                    isSticky = false;
                })
            }
        })
    });

    
    // *************************************
    //   Search Block
    // *************************************
    if ($('.wp-block-ultimate-post-advanced-search').length) {
        let postPages = 1;
        
        // Search Clear Text Button
        $(document).on('click', '.ultp-search-clear', function () {
            postPages = 1;
            const blockId =  $(this).data('blockid');
            $(this).parents('.ultp-search-inputwrap').find('.ultp-searchres-input').val('');
            $(this).removeClass('active');
            $(`.ultp-block-${blockId}`).find('.ultp-result-data').html('');
            $(`.ultp-block-${blockId}`).find('.ultp-search-noresult, .ultp-viewall-results, .ultp-result-loader').removeClass('active');
        });
    
        // Popup Window Close
        $(document).on('click', '.ultp-popupclose-icon', function () {
            $(this).parents('.result-data').removeClass('popup-active');
        });
    
        // Search Button Popup Icon
        $(document).on('click', '.ultp-searchpopup-icon', function() {
            const el = $(this).parents('.ultp-search-frontend');
            const blockId = el.data('blockid');
            handleSetPosition(el, $(`.result-data.ultp-block-${blockId}`).length ? false : true )
            $(`.result-data.ultp-block-${blockId}`).toggleClass('popup-active');
        });
        // In search result page clear button active
        if($('.ultp-searchres-input').val().length > 2){
            $('.ultp-searchres-input').closest('.ultp-search-inputwrap').find('.ultp-search-clear').addClass('active');
        } 
        // Input On Change Action
        $(document).on('input', '.ultp-searchres-input', function(e) {
            searchResultAPI($(this), e.target.value);
        });
        
        const searchResultAPI = (that, searchText, blockId = '', isAppend = true) => {
            blockId = blockId ? blockId : that.parents('.ultp-search-inputwrap').find('.ultp-search-clear').data('blockid')
            const el = $(`.wp-block-ultimate-post-advanced-search.ultp-block-${blockId}`).find('.ultp-search-frontend');
            const selector = $(`.result-data.ultp-block-${blockId}`);
            // Set PopUp Positions
            handleSetPosition(el, selector.length ? false : true )

            if (searchText.length > 2) {
                if (el.data('ajax')) {
                    selector.find('.ultp-search-result').addClass('ultp-search-show');
                    selector.find('.ultp-result-loader').addClass('active');
                    selector.addClass('popup-active')
                    wp.apiFetch({
                        path: '/ultp/ultp_search_data',
                        method: 'POST',
                        data: {
                            searchText: searchText,
                            date: parseInt(el.data('date')),
                            image: parseInt(el.data('image')),
                            author: parseInt(el.data('author')),
                            excerpt: parseInt(el.data('excerpt')),
                            category: parseInt(el.data('catenable')),
                            excerptLimit: parseInt(el.data('excerptlimit')),
                            postPerPage: el.data('allresult') ? el.data('postno') : 10,
                            exclude: (typeof el.data('searchposttype') !== 'string') && el.data('searchposttype').length > 0 && el.data('searchposttype'),
                            paged: postPages,
                            wpnonce: ultp_data_frontend.security
                        }
                    })
                    .then(res => {
                        if (res.post_data) {
                            if (isAppend) {
                                selector.find('.ultp-search-result').addClass('ultp-search-show');
                                selector.find('.ultp-result-data').addClass('ultp-result-show');
                                selector.find('.ultp-result-data').html(res.post_data);
                            } else {

                                selector.find('.ultp-search-result').addClass('ultp-search-show');
                                selector.find('.ultp-result-data').addClass('ultp-result-show');

                                selector.find('.ultp-result-data').append(res.post_data).fadeIn(500, function(){
                                    $(this).animate({scrollTop: $(this).prop("scrollHeight")}, 400)
                                });
                            }
                            selector.find('.ultp-search-noresult, .ultp-result-loader').removeClass('active');
                            const itemCount = selector.find('.ultp-result-data .ultp-search-result__item').length;
                            selector.find('.ultp-viewall-results').addClass('active').find('span').text(`(${res.post_count - itemCount})`)
                        } else {
                            selector.find('.ultp-result-data').removeClass('ultp-result-show');
                            selector.find('.ultp-result-data').html('');
                            selector.find('.ultp-search-noresult').addClass('active');
                            selector.find('.ultp-result-loader, .ultp-viewall-results').removeClass('active');
                        }
                        if (el.data('allresult')) {
                            const itemCount = selector.find('.ultp-result-data .ultp-search-result__item').length
                            if (res.post_count && res.post_count > itemCount) {
                                selector.find('.ultp-viewall-results').addClass('active').find('span').text(`(${res.post_count - itemCount})`)
                            } else {
                                selector.find('.ultp-viewall-results').removeClass('active')
                            }
                        }
                    })
                }
            } else {
                selector.find('.ultp-search-result').removeClass('ultp-search-show');
                selector.find('.ultp-result-data').removeClass('ultp-result-show');
                selector.find('.ultp-search-noresult').removeClass('active');
            }
            if (searchText.length < 3) {
                postPages = 1;
                selector.find('.ultp-result-data').html('');
                selector.find('.ultp-viewall-results').removeClass('active');
                selector.find('.ultp-search-noresult').removeClass('active');

                // Clear Button hide
                el.find('.ultp-search-clear').removeClass('active');
                $(`.result-data.ultp-block-${blockId}`).find('.ultp-search-clear').removeClass('active');
            } else {
                // Clear Button Show
                el.find('.ultp-search-clear').addClass('active');
                $(`.result-data.ultp-block-${blockId}`).find('.ultp-search-clear').addClass('active');
            }
        }
    
        // View All Result
        $(document).on('click', '.ultp-viewall-results', function(e) {
            postPages++;
            const blockId = $(this).closest('.result-data').data('blockid')
            searchResultAPI($(this), $(`.ultp-block-${blockId} .ultp-searchres-input`).val(), blockId, false );
        });
    
        // Outside Click Close Popup [Done]
        if ($('.wp-block-ultimate-post-advanced-search').length > 0) {
            $(document).on('click', function(e) {
                if(!($(e.target).closest('.ultp-searchpopup-icon').length) && !$(e.target).closest('.ultp-searchres-input').length){
                    if (!$(e.target).closest('.result-data.popup-active').length) {
                        $('.result-data').removeClass('popup-active');
                    }
                }   
                if (!$(e.target).closest('.ultp-search-frontend').length) {
                    if (!$(e.target).closest('.result-data.popup-active').length) {
                        $('.result-data').removeClass('popup-active');
                    }
                }
            });
        }
    
        // Enter Key In Search Box
        $(document).on('keyup', '.ultp-searchres-input', function(e) {
            const blockId = $(this).closest('.ultp-search-inputwrap').find('.ultp-search-clear').data('blockid');
            const goSearch = $(`.wp-block-ultimate-post-advanced-search.ultp-block-${blockId}`).find('.ultp-search-frontend').data('gosearch'); 
            const newTabData = $(`.wp-block-ultimate-post-advanced-search.ultp-block-${blockId}`).find('.ultp-search-frontend').data('enablenewtab');
            let tabTarget = "_self";
            if(newTabData) {
                tabTarget = "_blank";
            }
            if (goSearch) {
                if (e.key == "Enter" && $(this).val().length > 2) {
                    window.open(`${ultp_data_frontend.home_url}/?s=${$(this).val()}`, tabTarget);
                }
            }
        });
    
        // Search Button Click Event
        $(document).on('click', '.ultp-search-button', function(e) {
            const blockId = $(this).closest('.ultp-searchform-content').find('.ultp-search-clear').data('blockid');
            const goSearch = $(`.wp-block-ultimate-post-advanced-search.ultp-block-${blockId}`).find('.ultp-search-frontend').data('gosearch');
            const newTabData = $(`.wp-block-ultimate-post-advanced-search.ultp-block-${blockId}`).find('.ultp-search-frontend').data('enablenewtab');
            let tabTarget = "_self";
            if(newTabData) {
                tabTarget = "_blank";
            }
            if (goSearch) {
                window.open(`${ultp_data_frontend.home_url}/?s=${$(this).closest('.ultp-searchform-content').find('.ultp-searchres-input').val()}`, tabTarget);
            } else {
                $(`.result-data.ultp-block-${blockId}`).addClass('popup-active');
            }
        });
        
        // Search Input Click Popup Show
        $(document).on('click', '.ultp-searchres-input', function(e) {
            const blockId = $(this).closest('.ultp-searchform-content').find('.ultp-search-clear').data('blockid');
            $('.result-data').removeClass('popup-active');
            $(`.result-data.ultp-block-${blockId}`).addClass('popup-active');
        });
    
        // Resize Window Popup Position Reset
        $(window).on('resize', function() {
            if ($('.ultp-search-result').length > 0) {
                $('.ultp-search-frontend').each( function (el) {
                    handleSetPosition($(el));
                });
            }
        });
    
        // Popup Top/Left Position and Append Result Content
        const handleSetPosition = ( el, newBlock = false) => {
            const blockId =  el.data('blockid');
            const popupType = el.data('popuptype');
            const popupposition = el.data('popupposition');
            
            if (newBlock) {
                const viewAllResult = el.data('allresult');
                const searchResultTemplate = `<div class="ultp-search-result" data-image=${el.data('image') || false} data-author=${el.data('author') || false} data-date=${el.data('date') || false} data-excerpt=${el.data('excerpt') || false} data-excerptlimit=${el.data('excerptlimit')} data-allresult=${viewAllResult || false} data-catenable=${el.data('catenable') || false} data-postno=${el.data('postno') || false} data-gosearch=${el.data('gosearch') || false} data-popupposition=${popupposition || false}>
                    <div class="ultp-result-data"></div>
                    <div class="ultp-search-result__item ultp-search-noresult">${el.data('noresultext')}</div>
                    <div class="ultp-search-result__item ultp-result-loader"></div>
                    ${viewAllResult ? `<div class="ultp-viewall-results ultp-search-result__item">${el.data('viewmoretext')}<span></span></div><div class="ultp-search-result__item ultp-viewmore-loader"></div>` : ''}
                    </div>`;
    
                if (popupType) {
                    const canvas =  $(`.ultp-block-${blockId}`).find('.ultp-search-canvas').detach();
                    $('body').append(`<div class="result-data ultp-block-${blockId} ultp-search-animation-${popupType}" data-blockid=${blockId}><div class="ultp-search-canvas">${canvas.html() + (el.data('ajax') ?  searchResultTemplate : '')}</div></div>`);
                } else {
                    $('body').append(`<div class="result-data ultp-block-${blockId}" data-blockid=${blockId}>${searchResultTemplate}</div>`); 
                }
            }
            
            let posSelector = '';
            if (!popupType) {
                posSelector = el.find('.ultp-searchform-content');
                const elementPosition = posSelector.offset();
                return $(`body > .ultp-block-${blockId}`).css({"width" : `${posSelector.width()}px`, "top" : `${elementPosition?.top +  posSelector.height()}px`, "left":  `${elementPosition?.left}px`});
            } 
            if (popupType == "popup") {
                posSelector = el.find('.ultp-searchpopup-icon');
                const elementPosition = posSelector.offset();
                const contentPosition = popupposition == 'right' ? (elementPosition?.left > $(`body > .ultp-block-${blockId}`).width()) : (($(document).width() - elementPosition?.left) > $(`body > .ultp-block-${blockId}`).width());
                let right = '';
                let left = '';
                if(popupposition == 'right'){
                    right = contentPosition ? ($(document).width() - elementPosition?.left) - posSelector.outerWidth() + 'px' : 'unset'; 
                    left = contentPosition ? 'auto' : (elementPosition?.left + (popupposition == 'right' ? 10 : 0)) + 'px';
                } else {
                    right = contentPosition ?  'unset' : ($(document).width() - elementPosition?.left) - posSelector.outerWidth() + 'px' ; 
                    left = contentPosition ? (elementPosition?.left + (popupposition == 'right' ? 10 : 0)) + 'px' : 'auto';
                }
                return $(`body > .ultp-block-${blockId}`).css({ "top" : `${elementPosition?.top +  posSelector.outerHeight()}px`, 'right' : right, 'left' : left});
            }            
        }
    }
    
})( jQuery );