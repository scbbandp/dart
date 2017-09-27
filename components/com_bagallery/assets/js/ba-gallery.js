/**
* @package   BaGallery
* @author    Balbooa https://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
*/

var ba_jQuery = jQuery;

function initGalleries()
{
    document.removeEventListener("DOMContentLoaded", initGalleries);
    window.removeEventListener("load", initGalleries);
    ba_jQuery('.ba-gallery').each(function(){
        initGallery(this);
    });
}

function initGallery(bagallery)
{
    if (!bagallery) {
        initGalleries();
        return false;
    }
    var disqus_shortname = jQuery('.disqus-subdomen').val(),
        globalImage = {
            width : '',
            height : ''
        },
        imgC,
        aimgC,
        originalLocation = '',
        viewportmeta = document.querySelector('meta[name="viewport"]'),
        catNames = new Array(),
        galleryId = bagallery.dataset.gallery,
        vk_api = ba_jQuery('#vk-api-id-'+galleryId).val(),
        goodWidth = (ba_jQuery(window).height() - 100) * 1.6,
        goodHeight = ba_jQuery(window).height() - 100,
        scroll = jQuery(window).scrollTop(),
        gallery = ba_jQuery(bagallery),
        galleryModal = gallery.find('.gallery-modal'),
        slideFlag = true,
        vkFlag = false,
        pageRefresh = gallery.find('.page-refresh').val(),
        gFlag = false,
        juri = jQuery('.ba-juri').val(),
        albumMode = gallery.find('.album-mode').val(),
        album = gallery.find('.ba-album'),
        albumOptions = gallery.find('.albums-options').val(),
        defaultFilter = gallery.find('.default-filter-style').val(),
        activeFilter = gallery.find('.active-filter-style').val(),
        galleryOptions = JSON.parse(gallery.find('.gallery-options').val()),
        $container = gallery.find('.ba-gallery-grid'),
        category = gallery.find('.ba-filter-active').attr('data-filter'),
        defaultCat = category,
        winSize = ba_jQuery(window).width(),
        albumWidth = 0,
        widthContent = 0,
        pagination = gallery.find('.ba-pagination-options').val(),
        copyright = gallery.find('.copyright-options').val(),
        lazyloadOptions = {};
        paginationConst = gallery.find('.ba-pagination-constant').val();
    if (albumMode) {
        albumOptions = JSON.parse(albumOptions);
        category = '.root';
        album.find('.ba-album-items').each(function(){
            catNames.push(jQuery(this).attr('data-filter'));
        });
    } else {
        albumOptions = {}
    }
    if (paginationConst) {
        paginationConst = paginationConst.split('-_-');
    }
    if (disqus_shortname) {
        var disqus_url = window.location.href;
    }
    var style = gallery.find('.lightbox-options').val();
    style = JSON.parse(style);            
    var layout = gallery.find('.gallery-layout').val(),
        currentPage = '.page-1',
        paginationPages = 0,
        image = '',
        imageIndex = '',
        elements = getData(),
        titleSize = gallery.find('.modal-title').length,
        categoryDescription = gallery.find('.categories').val();
    if (categoryDescription) {
        categoryDescription = JSON.parse(categoryDescription);
    }
    createVK('');

    var thumbnails = new Array(),
        thumbnailc = 0,
        notification = document.createElement('div');
    notification.id = 'ba-notification'
    gallery.find('.ba-image img').each(function(){
        var src = jQuery(this).attr('src');
        if (!src) {
            src = jQuery(this).attr('data-original');
			jQuery(this).attr('src', jQuery(this).attr('data-original'))
        }
        if (src.indexOf('option=com_bagallery') !== -1) {
            thumbnails.push(src);
        }
    });

    if (thumbnails.length > 0) {
            if (document.body.classList.contains('com_gridbox') && document.body.classList.contains('gridbox')) {
                window.parent.$g('.modal.hide').css({
                    display: 'none',
                    visibility: 'hidden',
                    opacity: 0
                })
            }
            notification.className = 'gallery-notification notification-in';
            var str = '<p>'+gallery.find('.creating-thumbnails').val()+'</p><img src="'+juri;
            str += 'components/com_bagallery/assets/images/reload.svg"></img>';
            notification.innerHTML = str;
            jQuery('body').append(notification);
        }
        
        thumbnails.forEach(function(el, ind){
            ba_jQuery.ajax({
                url : el,
                success: function(msg){
                    thumbnailc++;
                    if (thumbnailc == thumbnails.length) {
                        if (document.body.classList.contains('com_gridbox') && document.body.classList.contains('gridbox')) {
                            setTimeout(function(){
                                notification.className = 'gallery-notification animation-out';
                                setTimeout(function(){
                                    jQuery(notification).remove();
                                    window.parent.document.querySelector('.gridbox-save').click();
                                    window.parent.location.href = window.parent.location.href;
                                }, 500);
                            }, 1000);
                        } else {
                            setTimeout(function(){
                                notification.className = 'gallery-notification animation-out';
                                setTimeout(function(){
                                    jQuery(notification).remove();
                                    window.location.href = window.location.href;
                                }, 500);
                            }, 1000);
                        }
                    }
                }
            });
        });

    copyright = JSON.parse(copyright);
    if (copyright.disable_right_clk == '1') {
        gallery.off('contextmenu.gallery').on('contextmenu.gallery', function(e){
            e.preventDefault();
            e.stopPropagation();
        });
        galleryModal.parent().off('contextmenu.gallery').on('contextmenu.gallery', function(e){
            e.preventDefault();
            e.stopPropagation();
        });
    }
    if (copyright.disable_shortcuts == '1') {
        jQuery(window).on('keydown', function(e){
            if ((e.ctrlKey || e.metaKey) && (e.keyCode == 88 || e.keyCode == 65
                || e.keyCode == 67 || e.keyCode == 86 || e.keyCode == 83)) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    }
    if (copyright.disable_dev_console == '1') {
        function checkDevConsole(e)
        {
            if ((e.keyCode == 123 && e.originalEvent && e.originalEvent.code == 'F12') ||
                (e.keyCode == 73 && e.ctrlKey && e.shiftKey) ||
                (e.keyCode == 67 && e.ctrlKey && e.shiftKey) ||
                (e.keyCode == 75 && e.ctrlKey && e.shiftKey) ||
                (e.keyCode == 83 && e.ctrlKey && e.shiftKey) ||
                (e.keyCode == 81 && e.ctrlKey && e.shiftKey) ||
                (e.keyCode == 116 && e.shiftKey && e.originalEvent.code == 'F5') ||
                (e.keyCode == 118 && e.shiftKey && e.originalEvent.code == 'F7')) {
                return true;
            } else {
                return false;
            }
        }
        jQuery(window).on('keydown', function(e){
            if (checkDevConsole(e)) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
        jQuery(document).off('contextmenu').on('contextmenu', function(e){
            e.preventDefault();
            e.stopPropagation();
        });
    }

    function directionAware(el, event)
    {
        var w = el.width(),
            h = el.height(),
            x = (event.pageX - el.offset().left - (w / 2)) * (w > h ? (h / w) : 1),
            y = (event.pageY - el.offset().top  - (h / 2)) * (h > w ? (w / h) : 1),
            direction = Math.round((((Math.atan2(y, x) * (180 / Math.PI)) + 180) / 90) + 3) % 4;
        switch(direction) {
            case 0:
                return 'top';
                break;
            case 1:
                return 'right';
                break;
            case 2:
                return 'bottom';
                break;
            case 3:
                return 'left';
                break;
        }
    }

    function createVK(vk)
    {
        if (vk_api) {
            if (!vkFlag) {
                var vkScript = document.createElement('script');
                vkScript.src = '//vk.com/js/api/openapi.js?125';
                document.getElementsByTagName('head')[0].appendChild(vkScript);
                ba_jQuery(vkScript).on('load', function(){
                    VK.init({
                        apiId: vk_api,
                        onlyWidgets: true
                    });
                    if (vk) {
                        VK.Widgets.Comments("ba-vk-"+galleryId, vk);
                    }
                    vkFlag = true;
                });
            } else {
                VK.Widgets.Comments("ba-vk-"+galleryId, vk);
            }
        }
    }

    gallery.find('.ba-tooltip').each(function(){
        jQuery(this).parent().on('mouseenter', function(){
            var $this = jQuery(this),
                coord = $this.children().first()[0].getBoundingClientRect(),
                top = coord.top,
                data = $this.find('.ba-tooltip').html(),
                center = (coord.right - coord.left) / 2;
                className = $this.find('.ba-tooltip')[0].className;
            center = coord.left + center;
            if ($this.find('.ba-tooltip').hasClass('ba-bottom')) {
                top = coord.bottom;
            }
            jQuery('body').append('<span class="'+className+'">'+data+'</span>');
            jQuery('body > .ba-tooltip').css({
                'top' : top+'px',
                'left' : center+'px'
            });
        }).on('mouseleave', function(){
            var tooltip = jQuery('body').find(' > .ba-tooltip');
            tooltip.addClass('tooltip-hidden');
            setTimeout(function(){
                tooltip.remove();
            }, 500);
        });
    });

    if (galleryOptions.random_sorting == 1) {
        $container.ba_isotope('shuffle');
    }

    if (albumMode) {
        addBackStyle();
    }
    if (pagination) {
        pagination = JSON.parse(pagination);
    }
    if (defaultFilter) {
        defaultFilter = JSON.parse(defaultFilter);
    }
    
    if (activeFilter) {
        activeFilter = JSON.parse(activeFilter);
    }
    
    function getWidthContent()
    {
        imgC = galleryOptions.column_number * 1
        var s = galleryOptions.image_spacing * 1,
            w = $container.width() * 1;
        if (winSize < 1024 && winSize >= 768) {
            imgC = galleryOptions.tablet_numb;
        } else if (winSize <= 767 && winSize >= 480) {
            imgC = galleryOptions.phone_land_numb;
        } else if (winSize < 480) {
            imgC = galleryOptions.phone_port_numb;
        } else {
            imgC = galleryOptions.column_number * 1;
        }
        setAlbumWidth();

        return Math.floor(((w - s *  imgC * 2) / imgC) + s);
    }

    function setAlbumWidth()
    {
        aimgC = albumOptions.album_column_number * 1
        var s = albumOptions.album_image_spacing * 1,
            w = album.width() * 1;
        if (winSize < 1024 && winSize >= 768) {
            aimgC = albumOptions.album_tablet_numb;
        } else if (winSize <= 767 && winSize >= 480) {
            aimgC = albumOptions.album_phone_land_numb;
        } else if (winSize < 480) {
            aimgC = albumOptions.album_phone_port_numb;
        } else {
            aimgC = albumOptions.album_column_number;
        }
        
        albumWidth = Math.floor(((w - s *  aimgC * 2) / aimgC) + s);
    }
    
    function addBackStyle()
    {
        var backStyle = gallery.find('.back-style').val()
        backStyle = JSON.parse(backStyle);
        gallery.find('.ba-goback a').css({
            'background-color' : backStyle.pagination_bg,
            'border' : '1px solid '+backStyle.pagination_border,
            'border-radius' : backStyle.pagination_radius+'px',
            'color' : backStyle.pagination_font,
        });
        gallery.find('.ba-goback a').hover(function(){
            ba_jQuery(this).css({
                'background-color' : backStyle.pagination_bg_hover,
                'color' : backStyle.pagination_font_hover
            });
        }, function(){
            ba_jQuery(this).css({
                'background-color' : backStyle.pagination_bg,
                'color' : backStyle.pagination_font
            });
        });
    }
    
    function addFilterStyle()
    {
        gallery.find('.ba-filter').css({
            'background-color' : defaultFilter.bg_color,
            'border' : '1px solid '+defaultFilter.border_color,
            'border-radius' : defaultFilter.border_radius+'px',
            'color' : defaultFilter.font_color,
            'font-weight' : defaultFilter.font_weight,
            'font-size' : defaultFilter.font_size+'px'
        });
        gallery.find('.ba-filter-active').css({
            'background-color' : activeFilter.bg_active,
            'border' : '1px solid '+activeFilter.border_color_active,
            'border-radius' : defaultFilter.border_radius+'px',
            'color' : activeFilter.font_color_active,
            'font-weight' : defaultFilter.font_weight,
            'font-size' : defaultFilter.font_size+'px'
        });
        gallery.find('.category-filter').css('text-align', defaultFilter.alignment);
        gallery.find('.ba-filter').hover(function(){
            ba_jQuery(this).css('background-color', defaultFilter.bg_color_hover);
            ba_jQuery(this).css('color', defaultFilter.font_color_hover);
        }, function(){
            ba_jQuery(this).css('background-color', defaultFilter.bg_color);
            ba_jQuery(this).css('color', defaultFilter.font_color);
        });
        gallery.find('.ba-filter-active').hover(function(){
            ba_jQuery(this).css('background-color', activeFilter.bg_hover_active);
            ba_jQuery(this).css('color', activeFilter.font_color_hover_active);
        }, function(){
            ba_jQuery(this).css('background-color', activeFilter.bg_active);
            ba_jQuery(this).css('color', activeFilter.font_color_active);
        });
    }

    function checkHash()
    {
        if (window.location.href.indexOf('#') > 0) {
            window.history.pushState(null, null, window.location.href.replace('#'+window.location.hash, ''))
        }
    }

    function chechAlbumItems(a)
    {
        var title = a.find('h3').text(),
            alias = a.find('a').attr('href'),
            oldCategory = category,
            filter = a.attr('data-filter');
        if (albumOptions.album_enable_lightbox == 1 && a.hasClass('root')) {
            gallery.find('.ba-goback a').hide();
        } else {
            gallery.find('.ba-goback a').css('display', '');
        }
        gallery.find('.ba-goback h2').text(title);
        setCategoryDescription(filter);
        category = filter;
        if (category != '.root' && albumOptions.album_enable_lightbox == 1 && oldCategory == '.root') {
            gallery.next().height(gallery.height());
        }
        if (pagination) {
            currentPage = '.page-1'
            drawPagination();
        }
        gallery.trigger('scroll');
        if (albumOptions.album_enable_lightbox != 1 && galleryOptions.disable_auto_scroll != 1) {
            var position = gallery.offset().top;
            ba_jQuery('html, body').animate({
                scrollTop: position
            }, 'slow');
        }
        if (albumOptions.album_enable_lightbox == 1) {
            if (category == '.root') {
                jQuery('body').removeClass('album-in-lightbox-open');
                gallery.find('.ba-gallery-row-wrapper').css('background-color', '');
            } else {
                gallery.addClass('album-in-lightbox');
                jQuery('body').addClass('album-in-lightbox-open');
                gallery.find('.ba-gallery-row-wrapper').css('background-color', style.lightbox_border);
            }
        }
    }

    gallery.find('.ba-album-items').on('click', function(){
        checkHash();
        var alias = jQuery(this).find('a').attr('href');
        if (pageRefresh == 1) {
            if (alias != window.location.href) {
                refreshPage(alias)
                gallery.find('.ba-pagination').hide();
            }
        } else {
            window.history.pushState(null, null, alias);
            chechAlbumItems(jQuery(this));
            resizeIsotope();
        }
    });

    gallery.find('.ba-goback a').on('click', function(){
        checkHash();
        var catName = album.find('div[data-filter="'+category+'"]')[0].className,
            array = catName.split(' ');
            flag = false;
        for (var i = 0; i < array.length; i++) {
            if (array[i].indexOf('category-') != -1) {
                catName = array[i];
            }
        }
        for (var i = 0; i < catNames.length; i ++) {
            if (catName == catNames[i].replace('.', '')) {
                album.find('div[data-filter="'+catNames[i]+'"]').trigger('click');
                flag = true;
                break;
            }
        }
        if (!flag) {
            category = '.root';
            var alias = album.find('.current-root').val();
            if (pageRefresh == 1) {
                if (alias != window.location.href) {
                    refreshPage(alias)
                    gallery.find('.ba-pagination').hide();
                }
            } else {
                window.history.pushState(null, null, alias);
                if (pagination) {
                    currentPage = '.page-1';
                    addPages();
                    drawPagination();
                }
                resizeIsotope();
            }
        }
    });
    
    function filterAction(a)
    {
        var oldActive = gallery.find('.ba-filter-active'),
            newActive = a,
            filter = a.attr('data-filter'),
            alias = a.attr('href');
        oldActive.removeClass('ba-filter-active');
        oldActive.addClass('ba-filter');
        newActive.removeClass('ba-filter');
        newActive.addClass('ba-filter-active');
        addFilterStyle();
        gallery.find('.ba-select-filter option').each(function(){
            if (ba_jQuery(this).val() == filter) {
                ba_jQuery(this).attr('selected', true);
            } else {
                ba_jQuery(this).removeAttr('selected');
            }
        });
        $container.find('.ba-gallery-items').hide();
        var desc = setCategoryDescription(filter);
        category = filter;
        if (pagination) {
            currentPage = '.page-1'
            addPages();
            drawPagination();
        }
    }

    gallery.find('.category-filter a').on('click', function(event){
        event.preventDefault();
        var $this = jQuery(this),
            alias = $this.attr('href');
        checkHash();
        if (pageRefresh == 1) {
            if (alias != window.location.href) {
                refreshPage(alias)
            }
        } else {
            window.history.pushState(null, null, alias);
            filterAction($this);
            resizeIsotope();
        }
    });
    checkFilter();
    locationImage();

    window.addEventListener("popstate", function(e) {
        checkFilter();
        resizeIsotope();
        locationImage();
    });

    function checkFilter()
    {
        var filterFlag = false,
            search = location.href,
            pos = search.indexOf('ba-page'),
            albumItems = gallery.find('.ba-album-items'),
            filterItems = gallery.find('.category-filter a');
        if (pos != -1) {
            search = search.substr(0, pos - 1);
        } else {
            if (search.indexOf('?') > 0) {
                search = search.split('?');
                search = search[0]+'?'+search[1];
            }
        }
        if (!location.search) {
            if (albumItems.length > 0 ) {
                category = '.root';
            } else if (filterItems.length > 0) {
                filterAction(gallery.find('.category-filter [data-filter="'+defaultCat+'"]'));
            }
        } else {
            if (gallery.find('.active-category-image').length > 0) {
                search = gallery.find('.active-category-image').val();
            }
            if (albumItems.length > 0 ) {
                var a = albumItems.find('a[href="'+search+'"]');
                if (a.length > 0) {
                    chechAlbumItems(a.closest('.ba-album-items'));
                    filterFlag = true;
                }
                if (!filterFlag) {
                    category = '.root';
                }
            } else if (filterItems.length > 0) {
                var a = gallery.find('.category-filter a[href="'+search+'"]');
                if (a.length > 0) {
                    filterAction(a);
                    filterFlag = true;
                }
                if (!filterFlag) {
                    category = defaultCat;
                }
            }                
        }
    }        

    function setCategoryDescription(filter)
    { 
        var description = '';
        if (categoryDescription) {
            var length = categoryDescription.length,
                cat = '';
            filter = filter.substring(10);
            for (var i = 0; i < length; i++) {
                cat = categoryDescription[i].settings.split(';');
                if (cat[4]*1 == filter*1) {
                    if (!cat[7]) {
                        cat[7] = '';
                    }
                    description = cat[7];
                    break;
                }
            }
            description = description.replace(new RegExp("-_-_-_", 'g'), "'").replace(new RegExp("-_-", 'g'), ';');
            description = checkForms(description);
            gallery.find('.categories-description').html(description);
        }
    }

    function checkForms(data)
    {
        if (data.indexOf('baforms ID=') > 0) {
            ba_jQuery.ajax({
                type: "POST",
                dataType: 'text',
                async: false,
                url:"?option=com_bagallery&view=gallery&task=gallery.checkForms&tmpl=component",
                data: {
                    ba_data : data,
                },
                success: function(msg){
                    data = msg;
                }
            });
        }

        return data;
    }
    
    gallery.find('.ba-select-filter').on('change', function(){
        var filter = ba_jQuery(this).val(),
            newActive = gallery.find('.category-filter a[data-filter="'+filter+'"]');
        newActive.trigger('click');
    });
    
    function addCaptionStyle()
    {
        var color = hexToRgb(galleryOptions.caption_bg);
        color.a = galleryOptions.caption_opacity;
        if (!gallery.find('.ba-gallery-grid').hasClass('css-style-11') && !gallery.find('.ba-gallery-grid').hasClass('css-style-13')) {
            gallery.find('.ba-gallery-items .ba-caption').css('background-color',
                        'rgba('+color.r+','+color.g+','+color.b+','+color.a+')');
        }
        if (gallery.find('.ba-gallery-grid').hasClass('css-style-12')) {
            gallery.find('.ba-gallery-items').on('mouseenter', function(event){
                var caption = jQuery(this).find('.ba-caption'),
                    dir = 'from-'+directionAware(jQuery(this), event);
                caption.addClass(dir);
                setTimeout(function(){
                    caption.removeClass(dir);
                }, 300);
            });
            gallery.find('.ba-gallery-items').on('mouseleave', function(event){
                var caption = jQuery(this).find('.ba-caption'),
                    dir = 'to-'+directionAware(jQuery(this), event);
                caption.addClass(dir);
                setTimeout(function(){
                    caption.removeClass(dir);
                }, 300);

            });
        }
        if (!gallery.find('.ba-gallery-grid').hasClass('css-style-11') && !gallery.find('.ba-gallery-grid').hasClass('css-style-13')) {
            gallery.find('.ba-gallery-items h3').css('color', galleryOptions.title_color);
            gallery.find('.ba-gallery-items .short-description').css('color', galleryOptions.description_color);
            gallery.find('.ba-gallery-items .image-category').css('color', galleryOptions.category_color);
        }
        gallery.find('.ba-gallery-items h3').css('font-size', galleryOptions.title_size+'px');
        gallery.find('.ba-gallery-items h3').css('font-weight', galleryOptions.title_weight);
        gallery.find('.ba-gallery-items h3').css('text-align', galleryOptions.title_alignment);
        gallery.find('.ba-gallery-items .short-description').css('font-size', galleryOptions.description_size+'px');
        gallery.find('.ba-gallery-items .short-description').css('font-weight', galleryOptions.description_weight);
        gallery.find('.ba-gallery-items .short-description').css('text-align', galleryOptions.description_alignment);
        gallery.find('.ba-gallery-items .image-category').css('font-size', galleryOptions.category_size+'px');
        gallery.find('.ba-gallery-items .image-category').css('font-weight', galleryOptions.category_weight);
        gallery.find('.ba-gallery-items .image-category').css('text-align', galleryOptions.category_alignment);
        if (!category) {
            category = '.category-0';
        }
        if (album.hasClass('css-style-12')) {
            album.find('.ba-album-items').on('mouseenter', function(event){
                var caption = jQuery(this).find('.ba-caption'),
                    dir = 'from-'+directionAware(jQuery(this), event);
                caption.addClass(dir);
                setTimeout(function(){
                    caption.removeClass(dir);
                }, 300);
            });
            album.find('.ba-album-items').on('mouseleave', function(event){
                var caption = jQuery(this).find('.ba-caption'),
                    dir = 'to-'+directionAware(jQuery(this), event);
                caption.addClass(dir);
                setTimeout(function(){
                    caption.removeClass(dir);
                }, 300);
            });
        }
    }
    
    function hexToRgb(hex)
    {
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    }

    function refreshPage(href)
    {
        var div = document.createElement('div'),
            sBackdrop = jQuery('<div/>', {
                'class' : 'saving-backdrop'
            }),
            img = document.createElement('img');
        img.src = juri+'components/com_bagallery/assets/images/reload.svg';
        document.getElementsByTagName('body')[0].appendChild(sBackdrop[0]);
        document.getElementsByTagName('body')[0].appendChild(img);
        window.history.pushState(null, null, href);
        jQuery(div).load(href+' .ba-gallery[data-gallery="'+gallery.attr('data-gallery')+'"]', function(){
            sBackdrop.className += ' animation-out';
            setTimeout(function(){
                document.getElementsByTagName('body')[0].removeChild(sBackdrop[0]);
                document.getElementsByTagName('body')[0].removeChild(img);
            }, 300);
            galleryModal.parent().remove();
            if (!albumMode && galleryOptions.disable_auto_scroll != 1) {
                var position = $container.offset().top;
                ba_jQuery('html, body').animate({
                    scrollTop: position
                }, 'slow');
            }
            gallery.replaceWith(div.innerHTML);
            initGallery(jQuery('.ba-gallery[data-gallery="'+gallery.attr('data-gallery')+'"]')[0]);
        });
    }
    
    function drawPagination()
    {
        if (pageRefresh == 1) {
            addPaginationStyle();
            gallery.find('.ba-pagination a').off('click').on('click', function(event){
                var $this = jQuery(this);
                if ($this.hasClass('ba-dissabled') || $this.hasClass('ba-current')) {
                    event.preventDefault();
                } else if (!jQuery(this).hasClass('scroll-to-top')) {
                    event.preventDefault();
                    var href = jQuery(this).attr('href');
                    refreshPage(href)
                }
            });
            return false;
        }
        var page = 1,
            n = 0;
        addPages();
        gallery.find('.ba-gallery-items'+category).each(function(){
            if (n == pagination.images_per_page) {
                n = 1;
                page++;
            } else {
                n++;
            }
        });
        paginationPages = page;
        var paginator = gallery.find('.ba-pagination');
        paginator.empty();
        if (page == 1 || gallery.find('.ba-gallery-items'+category).length == 0) {
            resizeIsotope();
            return false;
        }
        if (pagination.pagination_type != 'infinity') {
            if (pagination.pagination_type != 'load') {
                var str = '<a class="ba-btn ba-first-page ba-dissabled"';
                str += ' style="display:none;"';
                str += '><span class="zmdi zmdi-skip-previous"></span></a>';
                str += '<a class="ba-btn ba-prev';
                if (pagination.pagination_type != 'slider') {
                    str += ' ba-dissabled';
                }
                str += '" style="display:none;"><span class="zmdi zmdi-play"></span></a>';
                for (var i = 0; i < page; i++) {
                    str += '<a class="ba-btn';
                    if (i == 0) {
                        str += ' ba-current';
                    }
                    str += '"';
                    str += ' style="display:none"';
                    str += '>';
                    if (pagination.pagination_type != 'dots') {
                        str += (i + 1);
                    }
                    str += '</a>';
                }
                str += '<a class="ba-btn ba-next" style="display:none;"><span class="zmdi zmdi-play"></span></a>';
                str += '<a class="ba-btn ba-last-page"';
                str += ' style="display:none"';
                str += '><span class="zmdi zmdi-skip-next"></span></a>';
            } else {
                var str = '<a class="ba-btn load-more" style="display:none;">'+paginationConst[2]+'</a>';
            }
        } else {
            var str = '',
                scrollTarrget = ba_jQuery(document);
            if (albumMode && albumOptions.album_enable_lightbox == 1) {
                scrollTarrget = gallery;
            }
            currentPage = '.page-1';
            scrollTarrget.on('scroll.infinity', function(event) {
                var paginatorY = gallery.find('.ba-pagination').parent().offset().top - window.innerHeight;
                if (paginatorY < scroll) {
                    var next = currentPage.substr(6)*1+1
                    if (next <= paginationPages) {
                        var current = '';
                        for (var i = 1; i < next; i++) {
                            current += '.page-'+i+', ';
                        }
                        current += '.page-'+next;
                        currentPage = '.page-'+next;
                        $container.ba_isotope({
                            filter: category+current,
                            margin : galleryOptions.image_spacing,
                            count : imgC,
                            mode : layout
                        });
                    } else {
                        scrollTarrget.off('scroll.infinity');
                        str = '<a class="ba-btn scroll-to-top">'+paginationConst[3]+'</a>';
                        gallery.find('.ba-pagination').removeClass('ba-empty').html(str);
                        addPaginationStyle();
                        var position = gallery.offset().top,
                            target = jQuery('html, body');
                        if (gallery.hasClass('album-in-lightbox')) {
                            target = gallery;
                            position = 0;
                        }
                        gallery.find('.ba-pagination a').on('click', function(){
                            target.animate({
                                scrollTop: position
                            }, 'slow');
                        });
                    }
                }
                if (gallery.hasClass('album-in-lightbox')) {
                    scroll = gallery.scrollTop();
                } else {
                    scroll = jQuery(window).scrollTop();
                }
            });
        }
        paginator.html(str);
        if (pagination.pagination_type == 'dots') {
            paginator.find('.ba-first-page, .ba-last-page, .ba-prev, .ba-next').hide();
            paginator.find('a').addClass('ba-dots');
        }
        addPaginationStyle();
        addPaginationFilter();
        gallery.find('.ba-pagination a').on('click', function(event){
            event.preventDefault();
            if (jQuery(this).hasClass('ba-dissabled')) {
                return false;
            }
            var button = ba_jQuery(this);
            paginationAction(button);
            addPaginationStyle();
            checkPaginator();
            gallery.trigger('scroll');
        });
    }

    function checkPaginator()
    {
        var paginator = gallery.find('.ba-pagination');
        if (paginator.find('a').length == 0) {
            paginator.addClass('ba-empty');
        } else {
            paginator.removeClass('ba-empty');
        }
        if (pagination.pagination_type == 'default') {
            var current,
                curInd = 0,
                pagButtons = paginator.find('a').not('.ba-first-page, .ba-last-page, .ba-prev, .ba-next');
            paginator.find('.ba-first-page, .ba-last-page, .ba-prev, .ba-next').css('display', 'inline-block');
            if (pagButtons.length >= 5) {
                pagButtons.each(function(ind, el){
                    if (jQuery(this).hasClass('ba-current')) {
                        current = jQuery(this);
                        curInd = ind;
                        return false;
                    }
                });
                if (curInd <= 2) {
                    pagButtons.each(function(ind, el){
                        if (ind < 5) {
                            jQuery(this).css('display', 'inline-block');
                        } else {
                            jQuery(this).hide();
                        }
                    });
                } else if (curInd + 1 > pagButtons.length - 3) {
                    for (var i = pagButtons.length - 1; i >= 0; i--) {
                        if (i >= pagButtons.length - 5) {
                            jQuery(pagButtons[i]).css('display', 'inline-block');
                        } else {
                            jQuery(pagButtons[i]).hide();
                        }
                    }
                } else {
                    pagButtons.hide();
                    current.css('display', 'inline-block').prev().css('display', 'inline-block')
                        .prev().css('display', 'inline-block');
                    current.next().css('display', 'inline-block').next().css('display', 'inline-block');
                }
            } else {
                pagButtons.css('display', 'inline-block');
            }
        } else if (pagination.pagination_type == 'dots') {
            paginator.find('a').not('.ba-first-page, .ba-last-page, .ba-prev, .ba-next').css('display', 'inline-block');
        } else if (pagination.pagination_type == 'slider') {
            paginator.find('.ba-prev, .ba-next').css('display', 'inline-block');
        } else if (pagination.pagination_type == 'load') {
            paginator.find('a').css('display', 'inline-block');
        }
    }
    
    function setSize()
    {
        if (layout != 'justified') {
            $container.find('.ba-gallery-items').width(widthContent);
            $container.find('.ba-gallery-items').height(widthContent);
        }
        if (layout == 'metro') {
            $container.find('.width2').css('width', widthContent * 2 + (galleryOptions.image_spacing * 1)+'px');
            $container.find('.height2').css('height', widthContent*2+(galleryOptions.image_spacing * 1)+'px');
            $container.find('.height2 img').css('height', widthContent * 2 + (galleryOptions.image_spacing * 1)+'px');
            $container.find('.width2:not(.height2) img').css('height', widthContent+'px');
        } else if (layout == 'masonry') {
            $container.find('.height2').css('height', widthContent * 2 + (galleryOptions.image_spacing * 1)+'px');
            $container.find('.height2 img').css('height', widthContent * 2 + (galleryOptions.image_spacing * 1)+'px');
        } else if (layout == 'square') {
            $container.find('.width2').css('width', widthContent * 2 +(galleryOptions.image_spacing * 1)+'px');
            $container.find('.height2').css('height', widthContent * 2 +(galleryOptions.image_spacing * 1)+'px');
            $container.find('.height2 img').css('height', widthContent * 2+(galleryOptions.image_spacing * 1)+'px');
        } else if (layout == 'random') {
            $container.find('.ba-gallery-items').height('auto');
            $container.find('.ba-gallery-items, .ba-gallery-items img').width(widthContent);
            var ratio = 1;
            $container.find('.ba-gallery-items img').each(function(){
                var $this = jQuery(this),
                    w = $this.attr('data-width'),
                    h = $this.attr('data-height');
                ratio = w / h;
                $this.css('height', widthContent / ratio);
            });
        }
        if (winSize <= 480) {
            $container.find('.width2.height2').width(widthContent).height(widthContent);
            $container.find('.width2.height2 img').height(widthContent);
            $container.find('.width2').not('.height2').width(widthContent).height(widthContent / 2);
            $container.find('.width2').not('.height2').find('img').height(widthContent / 2);
        }
        if (albumOptions.album_layout != 'justified') {
            album.find('.ba-album-items').width(albumWidth);
            album.find('.ba-album-items').height(albumWidth);
        }
        if (albumOptions.album_layout == 'metro') {
            album.find('.width2').css('width', albumWidth * 2 + (galleryOptions.image_spacing * 1)+'px');
            album.find('.height2').css('height', albumWidth*2+(galleryOptions.image_spacing * 1)+'px');
            album.find('.height2 img').css('height', albumWidth * 2 + (galleryOptions.image_spacing * 1)+'px');
            album.find('.width2:not(.height2) img').css('height', albumWidth+'px');
        } else if (albumOptions.album_layout == 'masonry') {
            album.find('.height2').css('height', albumWidth * 2 + (galleryOptions.image_spacing * 1)+'px');
            album.find('.height2 img').css('height', albumWidth * 2 + (galleryOptions.image_spacing * 1)+'px');
        } else if (albumOptions.album_layout == 'square') {
            album.find('.width2').css('width', albumWidth * 2 +(galleryOptions.image_spacing * 1)+'px');
            album.find('.height2').css('height', albumWidth * 2 +(galleryOptions.image_spacing * 1)+'px');
            album.find('.height2 img').css('height', albumWidth * 2+(galleryOptions.image_spacing * 1)+'px');
        } else if (albumOptions.album_layout == 'random') {
            album.find('.ba-album-items').height('auto');
            album.find('.ba-album-items, .ba-album-items img').width(albumWidth);
            var ratio = 1;
            album.find('.ba-album-items img').each(function(){
                var $this = jQuery(this),
                    w = $this.attr('data-width'),
                    h = $this.attr('data-height');
                ratio = w / h;
                $this.css('height', albumWidth / ratio);
            });
        }
        if (winSize <= 480) {
            album.find('.width2.height2').width(albumWidth).height(albumWidth);
            album.find('.width2.height2 img').height(albumWidth);
            album.find('.width2').not('.height2').width(albumWidth).height(albumWidth / 2);
            album.find('.width2').not('.height2').find('img').height(albumWidth / 2);
        }
    }
    
    function resizeIsotope()
    {
        winSize = ba_jQuery(window).width();
        widthContent = getWidthContent();
        setSize();
        if (pageRefresh == 1) {
            currentPage = '';
        }
        if (albumMode) {
            album.ba_isotope({
                filter: category,
                margin : albumOptions.album_image_spacing,
                count : aimgC,
                mode: albumOptions.album_layout
            });
            if (category == '.root') {
                gallery.removeClass('album-in-lightbox').find('.ba-goback').hide();
            }
        }
        if (pagination) {
            if (pagination.pagination_type != 'infinity' && pagination.pagination_type != 'load') {
                $container.ba_isotope({
                    filter: category+currentPage,
                    margin : galleryOptions.image_spacing,
                    count : imgC,
                    mode : layout
                });
            } else {
                var page = currentPage.replace(new RegExp('.page-', 'g'), ''),
                    current = '';
                for (var i = 1; i <= page; i++) {
                    current += category+'.page-'+i;
                    if (i != page) {
                        current += ', ';
                    }
                }
                $container.ba_isotope({
                    filter: current,
                    margin : galleryOptions.image_spacing,
                    count : imgC,
                    mode : layout
                });
            }
        } else {
            $container.ba_isotope({
                filter: category,
                margin : galleryOptions.image_spacing,
                count : imgC,
                mode : layout
            });
        }
        gallery.trigger('scroll');
    }

    $container.on('show_isotope', function(){
        gallery.find('.category-filter').show();
        if (pagination) {
            checkPaginator();
        }
        if (category != '.root') {
            gallery.find('.ba-goback').show();
        }
    });
    
    ba_jQuery('a[data-toggle="tab"], [data-uk-tab]').on('shown shown.bs.tab change.uk.tab', function(){
        resizeIsotope();
    });

    var resizeITime;
    
    ba_jQuery(window).on('resize.isotope', function(){
        clearTimeout(resizeITime);
        resizeITime = setTimeout(function(){
            var newWinsize = ba_jQuery(window).width();
            if (winSize != newWinsize) {
                resizeIsotope();
                if (galleryModal.find('.header-icons').length == 0) {
                    return false;
                }
                if (winSize <= 1024) {
                    var shadow = galleryModal.parent()[0].style.backgroundColor;
                    galleryModal.find('.header-icons')[0].style.boxShadow = 'inset 0px -85px 150px -85px '+shadow;
                } else {
                    galleryModal.find('.header-icons')[0].style.boxShadow = '';
                }
            }
        }, 100);
    });
    
    function paginationAction(button)
    {
        if (pagination.pagination_type != 'load') {
            var next = button.attr('data-filter');
            if (currentPage == next) {
                return false;
            }
            currentPage = next;
            gallery.find('.ba-current').removeClass('ba-current');
            gallery.find('.ba-pagination [data-filter="'+next+'"]').each(function(){
                if (!ba_jQuery(this).hasClass('ba-prev') && !ba_jQuery(this).hasClass('ba-next')
                    && !ba_jQuery(this).hasClass('ba-first-page') && !ba_jQuery(this).hasClass('ba-last-page')) {
                    ba_jQuery(this).addClass('ba-current');
                }
            });
            var prev = next.substr(6)-1;
            if (prev == 0) {
                prev = 1;
                if (pagination.pagination_type == 'slider') {
                    prev = paginationPages;
                } else {
                    gallery.find('.ba-prev').addClass('ba-dissabled');
                    gallery.find('.ba-first-page').addClass('ba-dissabled');
                }
            } else {
                gallery.find('.ba-prev').removeClass('ba-dissabled');
                gallery.find('.ba-first-page').removeClass('ba-dissabled');
            }
            next = next.substr(6);
            next = next*1+1;
            if (next > paginationPages) {
                next = next-1;
                if (pagination.pagination_type == 'slider') {
                    next = 1;
                } else {
                    gallery.find('.ba-next').addClass('ba-dissabled');
                    gallery.find('.ba-last-page').addClass('ba-dissabled');
                }
            } else {
                gallery.find('.ba-next').removeClass('ba-dissabled');
                gallery.find('.ba-last-page').removeClass('ba-dissabled');
            }
            gallery.find('.ba-prev').attr('data-filter', '.page-'+prev);
            gallery.find('.ba-next').attr('data-filter', '.page-'+next);
            if (galleryOptions.disable_auto_scroll != 1) {
                var position = $container.offset().top,
                    target = jQuery('html, body');
                if (gallery.hasClass('album-in-lightbox')) {
                    target = gallery;
                    position = 0;
                }
                target.animate({
                    scrollTop: position
                }, 'slow');
            }
        } else {
            var next = button.attr('data-filter');
            currentPage = next;
            next = next.substr(6);
            if (next < paginationPages) {
                next = next * 1 + 1;
                button.attr('data-filter', '.page-'+next);
            } else {
                button.removeClass('load-more').addClass('scroll-to-top');
                button.text(paginationConst[3]);
                var position = $container.offset().top,
                    target = jQuery('html, body');
                if (gallery.hasClass('album-in-lightbox')) {
                    target = gallery;
                    position = 0;
                }
                button.on('click', function(){
                    target.animate({
                        scrollTop: position
                    }, 'slow');
                });
            }
        }
        resizeIsotope();
    }
    
    function addPaginationStyle()
    {
        gallery.find('.ba-pagination a').css('background-color', pagination.pagination_bg);
        gallery.find('.ba-pagination a').css('border-radius', pagination.pagination_radius+'px');
        gallery.find('.ba-pagination a').css('border', '1px solid '+pagination.pagination_border);
        gallery.find('.ba-pagination a').css('color', pagination.pagination_font);
        gallery.find('.ba-pagination').css('text-align', pagination.pagination_alignment);
        gallery.find('.ba-pagination a').hover(function(){
            ba_jQuery(this).css('background-color', pagination.pagination_bg_hover);
            ba_jQuery(this).css('color', pagination.pagination_font_hover);
        }, function(){
            if (!ba_jQuery(this).hasClass('ba-current')) {
                ba_jQuery(this).css('background-color', pagination.pagination_bg);
                ba_jQuery(this).css('color', pagination.pagination_font);
            } else {
                ba_jQuery(this).css('background-color', pagination.pagination_bg_hover);
                ba_jQuery(this).css('color', pagination.pagination_font_hover);
            }
        });
        gallery.find('.ba-current').css('background-color', pagination.pagination_bg_hover);
        gallery.find('.ba-current').css('color', pagination.pagination_font_hover);
    }
    
    function addPaginationFilter()
    {
        var n = 1;
        if (pagination.pagination_type != 'load' && pagination.pagination_type != 'infinity') {
            gallery.find('.ba-pagination a').not('.ba-first-page, .ba-prev, .ba-next, .ba-last-page').each(function(){
                ba_jQuery(this).attr('data-filter', '.page-'+n);
                n++;
            });
            n--;
            gallery.find('.ba-prev').attr('data-filter', '.page-1');
            gallery.find('.ba-first-page').attr('data-filter', '.page-1');
            gallery.find('.ba-last-page').attr('data-filter', '.page-'+n);
            if (paginationPages != 1) {
                gallery.find('.ba-next').attr('data-filter', '.page-2');
            } else {
                gallery.find('.ba-next').attr('data-filter', '.page-1');
            }
        } else {
            if (paginationPages != 1) {
                gallery.find('.ba-pagination a').attr('data-filter', '.page-2');
            } else {
                gallery.find('.ba-pagination a').attr('data-filter', '.page-1');
            }
        }
    }
    
    function addPages()
    {
        removePages();
        var page = 1,
            items = gallery.find('.ba-gallery-items'+category)
            n = 0;
        if (pageRefresh == 1) {
            items.addClass('page-'+page);
            return false;
        }
        items.each(function(ind, elem){
            if (n < pagination.images_per_page) {
                ba_jQuery(this).addClass('page-'+page);
                n++;
            } else {
                n = 0;
                page++;
                ba_jQuery(this).addClass('page-'+page);
                n++;
            }
        });
    }
    
    function removePages()
    {
        var len = gallery.find('.ba-gallery-items').length,
            n = Math.ceil(len / pagination.images_per_page) + 1;
        for (var i = 1; i <= n; i++) {
            gallery.find('.ba-gallery-items').removeClass('page-'+i);
        }
    }

    if (style.disable_lightbox == 0) {
        gallery.find('.ba-gallery-items').on('click', function(){
            image = ba_jQuery(this).find('.image-id').val();
            image = image.replace(new RegExp("-_-_-_",'g'), "'");
            var item = JSON.parse(image);
            if (item.link == '') {
                elements = getData();
                showOptions();
                galleryModal.ba_modal();
                addModalEvents();
            }
        });
    }
    galleryModal.on('hide', function() {
        if (viewportmeta) {
            viewportmeta.content = viewportContent;
        }
        galleryModal.parent().addClass('hide-animation');
        setTimeout(function(){
            galleryModal.parent().removeClass('hide-animation');
            hideOptions();
            galleryModal.removeClass('ba-description-left').removeClass('ba-description-right');
        }, 500);
    });
    
    function addModalEvents()
    {
        var startCoords = {},
            endCoords = {},
            hDistance, vDistance,
            xabs, yabs,
            hSwipMinDistance = 10,
            vSwipMinDistance = 50;
        imageIndex = elements.indexOf(image);
        galleryModal.parent().find('.modal-nav').show();
        setImage(image);
        galleryModal.parent().find('.modal-nav .ba-left-action').on('click', function(){
            if (slideFlag){
                getPrev();
            }
        });
        galleryModal.on('mousedown', function(event){
            if (ba_jQuery(event.srcElement).hasClass('gallery-modal')) {
                galleryModal.ba_modal('hide');
            }                
        });
        galleryModal.parent().find('.modal-nav .ba-right-action').on('click', function(){
            if (slideFlag) {
                getNext();
            }
        });

        galleryModal.find('.ba-icon-close').on('click touchend', function(event){
            event.preventDefault();
            event.stopPropagation();
            galleryModal.ba_modal('hide');
        });
        
        galleryModal.find('.ba-modal-header .ba-like-wrapper').on('click touchend', function(event){
            event.stopPropagation();
            jQuery(this).addClass('likes-animation');
            setTimeout(function(){
                galleryModal.find('.ba-modal-header .ba-like-wrapper').removeClass('likes-animation');
            }, 300);
            likeImage();
        });

        galleryModal.find('.zmdi.zmdi-share').on('click touchend', function(event){
            event.stopPropagation();
            event.preventDefault();
            var aimDelay = 0;
            galleryModal.find('.ba-share-icons').addClass('visible-sharing').one('click', function(){
                setTimeout(function(){
                    galleryModal.find('.ba-share-icons').addClass('sharing-out');
                    setTimeout(function(){
                        galleryModal.find('.ba-share-icons').removeClass('sharing-out visible-sharing');
                    }, 500);
                }, 100);
            }).find('i').each(function(){
                jQuery(this).css('animation-delay', aimDelay+'s');
                aimDelay += 0.1;
            });
            return false;
        });
        
        ba_jQuery(window).on('keyup', function( event ) {
            event.preventDefault();
            event.stopPropagation();
            if ( event.keyCode === 37 ) {
                if (slideFlag){
                    getPrev();
                }
            } else if (event.keyCode === 39) {
                if (slideFlag) {
                    getNext();
                }
            } else if ( event.keyCode === 27 ) {
                galleryModal.ba_modal('hide');
                galleryModal.find('.ba-share-icons').removeClass('visible-sharing')
            }
        });
        
        ba_jQuery('body').on('touchstart.bagallery', function(event) {
            endCoords = event.originalEvent.targetTouches[0];
            startCoords.pageX = event.originalEvent.targetTouches[0].pageX;
            startCoords.pageY = event.originalEvent.targetTouches[0].pageY;
        });
        
        ba_jQuery('body').on('touchmove.bagallery', function(event) {
            endCoords = event.originalEvent.targetTouches[0];
        });

        ba_jQuery('body').on('touchend.bagallery', function(event) {
            vDistance = endCoords.pageY - startCoords.pageY;
            hDistance = endCoords.pageX - startCoords.pageX;
            xabs = Math.abs(endCoords.pageX - startCoords.pageX);
            yabs = Math.abs(endCoords.pageY - startCoords.pageY);
            if(hDistance >= hSwipMinDistance && xabs >= yabs && zoomClk == 1) {
                getPrev();
            } else if (hDistance <= -hSwipMinDistance && xabs >= yabs && zoomClk == 1) {
                getNext();
            }
        });

        function resizeModal()
        {
            var item = JSON.parse(image);
            if (jQuery(window).width() > 1024 && (item.description || disqus_shortname || vk_api)) {
                galleryModal.addClass('ba-description-'+style.description_position);
            } else {
                galleryModal.removeClass('ba-description-'+style.description_position);
            }
            var vk = {
                redesign : 1,
                limit : 10,
                attach : "*",
                pageUrl : window.location.href
            }
            if (style.auto_resize != 0) {
                setTimeout(function(){
                    jQuery("#ba-vk-"+galleryId).empty();
                    var dWidth = window.innerWidth,
                        dHeight = window.innerHeight;
                    if (!item.video) {
                        var imgWidth = globalImage.width,
                            modalTop,
                            imgHeight = globalImage.height;
                        if (galleryModal.hasClass('ba-description-left') || galleryModal.hasClass('ba-description-right')) {
                            dWidth -= 400;
                        }
                        if (imgWidth < dWidth && imgHeight < dHeight) {
                            
                        } else {
                            var percent = imgWidth / imgHeight;
                            if (imgWidth > imgHeight) {
                                imgWidth = dWidth;
                                imgHeight = imgWidth / percent;
                            } else {
                                imgHeight = dHeight;
                                imgWidth = percent * imgHeight;
                            }
                            if (imgHeight > dHeight) {
                                imgHeight = dHeight;
                                imgWidth = percent * imgHeight;
                            }
                            if (imgWidth > dWidth) {
                                imgWidth = dWidth;
                                imgHeight = imgWidth / percent;
                            }
                            if (imgHeight == dHeight && item.description &&
                                !galleryModal.hasClass('ba-description-left') && !galleryModal.hasClass('ba-description-right')) {
                                dHeight = dHeight * 0.9;
                                imgHeight = dHeight;
                                imgWidth = percent * imgHeight;
                            }
                        }
                        modalTop = (dHeight - imgHeight) / 2;
                        galleryModal.stop().animate({
                            'width' : Math.round(imgWidth),
                            'margin-top' : Math.round(modalTop)
                        }, '500', function(){
                            galleryModal.css({'height' : 'auto'});
                            createVK(vk);
                            slideFlag = true;
                        });
                        goodWidth = imgWidth;
                        goodHeight = imgHeight;
                    } else {
                        if (galleryModal.hasClass('ba-description-left') || galleryModal.hasClass('ba-description-right')) {
                            dWidth -= 400;
                        }
                        var height = dHeight - 200,
                            percent = height / dHeight,
                            width = dWidth * percent;
                        if (jQuery(window).width() <= 1024) {
                            width = dWidth;
                        }
                        galleryModal[0].style.height = '';
                        galleryModal.css({
                            'width' : Math.round(width)+'px'
                        });
                        setTimeout(function(){
                            var top = (height - galleryModal.height()) / 2 + 100;
                            if (top < ba_jQuery(window).height() * 0.1) {
                                top = ba_jQuery(window).height() * 0.1;
                            }
                            galleryModal.css({
                                'margin-top' : top+'px'
                            });
                        }, 1);
                        createVK(vk);
                    }
                }, 500);
            } else {
                if (jQuery(window).width() <= 1024) {
                    galleryModal.addClass('ba-resize');
                    var imgWidth = goodWidth,
                        imgHeight = goodHeight,
                        dWidth = window.innerWidth,
                        dHeight = window.innerHeight;
                    if (imgWidth < dWidth && imgHeight < dHeight) {
                        
                    } else {
                        var percent = imgWidth / imgHeight;
                        if (imgWidth > imgHeight) {
                            imgWidth = dWidth;
                            imgHeight = imgWidth / percent;
                        } else {
                            imgHeight = dHeight;
                            imgWidth = percent * imgHeight;
                        }
                        if (imgHeight > dHeight) {
                            imgHeight = dHeight;
                            imgWidth = percent * imgHeight;
                        }
                        if (imgWidth > dWidth) {
                            imgWidth = dWidth;
                            imgHeight = imgWidth / percent;
                        }
                        if (imgHeight == dHeight && item.description) {
                            dHeight = dHeight * 0.9;
                            imgHeight = dHeight;
                            imgWidth = percent * imgHeight;
                        }
                    }
                    modalTop = (dHeight - imgHeight) / 2;
                    galleryModal.css({
                        'width' : Math.round(imgWidth),
                        'margin-top' : Math.round(modalTop)
                    });
                    createVK(vk);
                } else {
                    galleryModal.removeClass('ba-resize');
                    var width = style.lightbox_width;
                    if (galleryModal.hasClass('ba-description-left') || galleryModal.hasClass('ba-description-right')) {
                        width = width / 100;
                        width = 'calc((100% - 400px)*'+width+')';
                    } else {
                        width += '%';
                    }
                    galleryModal.css({
                        'width' : width,
                        'margin-top' : ''
                    });
                }
            }
        }

        ba_jQuery(window).on('resize.bagallery', function(){
            resizeModal();
        });
    }
    
    function showOptions()
    {
        ba_jQuery('body').addClass('modal-open');
        galleryModal.parent().addClass('ba-scrollable');
        goodWidth = (ba_jQuery(window).height()-100)*1.6;
        goodHeight = ba_jQuery(window).height()-100;
        addModalStyle();
    }
    
    function hideOptions()
    {
        checkHash();
        galleryModal.parent().find('.modal-nav').hide();
        galleryModal.parent().find('.modal-nav .ba-left-action').off('click');
        galleryModal.parent().find('.modal-nav .ba-right-action').off('click');
        ba_jQuery('body').off('touchstart.bagallery');
        ba_jQuery('body').off('touchmove.bagallery');
        ba_jQuery(window).off('orientationchange.bagallery');
        ba_jQuery('body').off('touchend.bagallery');
        galleryModal.off('click');
        ba_jQuery( window ).off('keyup');
        galleryModal.find('.ba-icon-close, .zmdi.zmdi-share').off('click touchend');
        galleryModal.find('.ba-modal-header .ba-like-wrapper').off('click touchend')
        if (style.enable_alias == 1) {
            var location = window.location.href.replace(window.location.search, ''),
            search = window.location.search;
            search = search.substr(1);
            if (isNumber(search)) {
                var loc = window.location.href.replace(window.location.search, '');
            } else {
                var index = search.indexOf('?');
                if (index > 0 ) {
                    search = search.substr(index*1+1);
                    var loc = window.location.href.replace('?'+search, '');
                } else {
                    if (checkTitle(search)) {
                        var loc = window.location.href.replace(window.location.search, '');
                    } else {
                        var loc = window.location.href;
                    }
                }
            }
            if (originalLocation) {
                loc = originalLocation;
            } else {
                if (gallery.find('.active-category-image').length > 0) {
                    loc = gallery.find('.active-category-image').val();
                }
            }
            window.history.pushState(null, null, loc);
        }
        galleryModal.parent().removeClass('ba-scrollable');
        ba_jQuery('body').removeClass('modal-open');
        galleryModal.find('.modal-image').empty();
        if (!fullscreen) {
            galleryModal.find('.display-lightbox-fullscreen').trigger('click');
        }
    }
    
    function getData()
    {
        var items = [];
        if (category) {
            gallery.find(category+' .image-id').each(function(){
                var elem = ba_jQuery(this).val();
                elem = elem.replace(new RegExp("-_-_-_",'g'), "'");;
                var item = JSON.parse(elem);
                if (item.link == '') {
                    items.push(elem);
                }
            });
        } else {
            gallery.find('.image-id').each(function(){
                var elem = ba_jQuery(this).val();
                elem = elem.replace(new RegExp("-_-_-_",'g'), "'");;
                var item = JSON.parse(elem);
                if (item.link == '') {
                    items.push(elem);
                }
            });
        }
        return items;
    }
    
    function getNext()
    {
        imageIndex++;
        if (imageIndex >= elements.length) {
            imageIndex = 0;
        }
        image = elements[imageIndex];
        setImage(image);
    }
    
    function getPrev()
    {
        imageIndex--;
        if (imageIndex < 0) {
            imageIndex = elements.length-1;
        }
        image = elements[imageIndex];
        setImage(image);
    }

    function locationImage()
    {
        var loc = window.location.href.replace(window.location.search, '');
        if (window.location.search) {
            var search = window.location.search;
            search = search.substr(1);
            if (search != decodeURIComponent(search)) {
                search = decodeURIComponent(search);
                window.history.pushState(null, null, loc+'?'+search);
            }
            var id = '';
            if (isNumber(search)) {
                id = window.location.search.replace('?', '');
            } else {
                var index = search.indexOf('?');
                if (index > 0 ) {
                    id = search.substr(index*1+1);
                } else {
                    if (checkTitle(search)) {
                        id = search;
                    }
                }
            }
            var imageFlag = false;
            gallery.find('.ba-gallery-items').each(function(){
                var item = ba_jQuery(this).find('.image-id').val();
                item = item.replace(new RegExp("-_-_-_",'g'), "'");
                item = JSON.parse(item);
                var lUrl = item.lightboxUrl.replace(/ /g, "-").replace(/%/g, "").replace(/\?/g, "");
                if (id && (item.id == id || lUrl.toLowerCase() == decodeURI(id).toLowerCase())) {
                    elements = getData();
                    image = ba_jQuery(this).find('.image-id').val();
                    image = image.replace(new RegExp("-_-_-_",'g'), "'");
                    showOptions();
                    galleryModal.ba_modal();
                    addModalEvents();
                    imageFlag = true;
                    return false;
                }
            });
            if (!imageFlag && galleryModal.hasClass('in')) {
                galleryModal.ba_modal('hide');
            }
        }
    }

    function isNumber(n)
    {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }

    function checkImage(search)
    {
        var flag = false,
            image = gallery.find('.image-id[data-id="ba-image-'+search+'"]');
        if (image.length > 0) {
            flag = true;
        }
        
        return flag;
    }
    
    function checkTitle(search)
    {
        var items = [];
        gallery.find(' .image-id').each(function(){
            var elem = jQuery(this).val();
            elem = elem.replace(new RegExp("-_-_-_",'g'), "'");;
            var item = JSON.parse(elem);
            if (item.link == '') {
                items.push(elem);
            }
        });
        var n = items.length,
            flag = false,
            el = '';
        for (var i = 0; i < n; i++) {
            el = JSON.parse(items[i]);
            if (el.lightboxUrl) {
                var url = el.lightboxUrl.replace(/ /g, "-").replace(/%/g, "").replace(/\?/g, "").toLowerCase();
                if (url == decodeURI(search).toLowerCase()) {
                    flag = true;
                    break;
                }
            }
        }
        
        return flag;
    }

    if (viewportmeta) {
        viewportContent = viewportmeta.content;
    }
    
    function setImage(image)
    {
        if (viewportmeta) {
            viewportmeta.content += ', minimum-scale=1.0, maximum-scale=1.0';
        }
        checkHash();
        galleryModal.find('.ba-zoom-out').addClass('disabled-item');
        galleryModal.find('.ba-zoom-in').removeClass('disabled-item');
        galleryModal.removeClass('hidden-description');
        galleryModal.parent().css('overflow', '');
        var vk = {
            redesign : 1,
            limit : 10,
            attach : "*",
            pageUrl : window.location.href
        }
        var item = JSON.parse(image),
            search = window.location.search;
        if (item.url.indexOf('gallery.addWatermark') !== -1 || item.url.indexOf('gallery.compressionImage') !== -1) {
            jQuery.ajax({
                url : item.url,
                success: function(msg){
                    item.url = msg;
                    image = JSON.stringify(item);
                    gallery.find('.image-id[data-id="ba-image-'+item.id+'"]').val(image);
                    elements[imageIndex] = image;
                    setImage(image);
                }
            });
            return false;
        }
        if (jQuery(window).width() > 1024 && (item.description || disqus_shortname || vk_api)) {
            galleryModal.addClass('ba-description-'+style.description_position);
        } else {
            galleryModal.removeClass('ba-description-'+style.description_position);
        }
        if (style.enable_alias == 1) {
            search = search.substr(1);
            var alias = gallery.find('[data-filter="'+category+'"]')[0],
                pos;
            if (alias) {
                alias = alias.dataset.alias;
            } else {
                alias = '';
            }
            if (isNumber(search) && checkImage(search)) {
                var loc = window.location.href.replace(window.location.search, '');
            } else {
                var index = search.indexOf('?');
                if (index > 0) {
                    search = search.substr(index + 1);
                    var loc = window.location.href.replace('?'+search, '');
                } else {
                    if (checkTitle(search)) {
                        var loc = window.location.href.replace(window.location.search, '');
                    } else {
                        var loc = window.location.href;
                    }
                }
            }
            pos = loc.indexOf(alias);
            if ((loc[pos - 1] == '?' || loc[pos - 1] == '&') && (!loc[pos + alias.length] || loc[pos + alias.length] == '&')) {
                originalLocation = loc;
                loc = loc.substr(0, pos - 1);
            }
            if (item.lightboxUrl) {
                var lUrl = item.lightboxUrl.replace(/ /g, "-").replace(/%/g, "").replace(/\?/g, "");
                if (disqus_shortname) {
                    disqus_url =  loc+'?'+lUrl.toLowerCase();
                }
                window.history.pushState(null, null, loc+'?'+lUrl.toLowerCase());
            } else {
                if (disqus_shortname) {
                    disqus_url = loc+'?'+item.id
                }
                window.history.pushState(null, null, loc+'?'+item.id);
            }
        }
        if (disqus_shortname) {
            jQuery('#disqus_thread').empty()
            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            jQuery(dsq).remove();
            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq)
        }
        jQuery("#ba-vk-"+galleryId).empty();
        galleryModal.find('.ba-download-img').attr('href', item.url);
        if (!item.video) {
            if (style.auto_resize != 0) {
                var str = '<img style="" src="'+juri;
                str += 'components/com_bagallery/assets/images/reload.svg" class="reload">';
                galleryModal.find('.ba-modal-body').addClass('reload-parent');
            } else {
                var str = document.createElement('img');
                str.src = item.url;
                str.onload = function(){
                    goodWidth = this.width;
                    goodHeight = this.height;
                    if (jQuery(window).width() <= 1024) {
                        var imgWidth = goodWidth,
                            imgHeight = goodHeight,
                            dWidth = jQuery(window).width(),
                            dHeight = window.innerHeight;
                        if (imgWidth < dWidth && imgHeight < dHeight) {
                            
                        } else {
                            var percent = imgWidth / imgHeight;
                            if (imgWidth > imgHeight) {
                                imgWidth = dWidth;
                                imgHeight = imgWidth / percent;
                            } else {
                                imgHeight = dHeight;
                                imgWidth = percent * imgHeight;
                            }
                            if (imgHeight > dHeight) {
                                imgHeight = dHeight;
                                imgWidth = percent * imgHeight;
                            }
                            if (imgWidth > dWidth) {
                                imgWidth = dWidth;
                                imgHeight = imgWidth / percent;
                            }
                            if (imgHeight == dHeight && item.description) {
                                dHeight = dHeight * 0.9;
                                imgHeight = dHeight;
                                imgWidth = percent * imgHeight;
                            }
                        }
                        modalTop = (dHeight - imgHeight) / 2;
                        galleryModal.css({
                            'width' : Math.round(imgWidth),
                            'margin-top' : Math.round(modalTop)
                        });
                        createVK(vk);
                    } else {
                        var width = style.lightbox_width;
                        if (galleryModal.hasClass('ba-description-left') || galleryModal.hasClass('ba-description-right')) {
                            width = width / 100;
                            width = 'calc((100% - 400px)*'+width+')';
                        } else {
                            width += '%';
                        }
                        galleryModal.css({
                            'width' : width,
                            'margin-top' : ''
                        });
                    }
                };
            }
            galleryModal.find('.ba-modal-body').removeClass('embed-code');
            galleryModal.find('.modal-image').removeClass('embed');
            galleryModal.find('.ba-download-img, .ba-zoom-out, .ba-zoom-in').show();
        } else {
            galleryModal.find('.modal-image').addClass('embed');
            galleryModal.find('.ba-modal-body').addClass('embed-code');
            galleryModal.find('.ba-download-img, .ba-zoom-out, .ba-zoom-in').addClass('ba-hidden-icons');
            setTimeout(function(){
                galleryModal.find('.ba-download-img, .ba-zoom-out, .ba-zoom-in').removeClass('ba-hidden-icons').hide();
            }, 300);
            var str = item.video.replace('-_-_-_', "'");
            str = checkForms(str);
        }
        galleryModal.find('.modal-image').html(str);
        galleryModal.find('.modal-title').remove();
        if (titleSize > 0) {
            if (item.title) {
                var title = ba_jQuery('<h3/>', {
                    class: 'modal-title',
                    style: 'color:'+style.header_icons_color
                });
                galleryModal.find('.ba-modal-header .ba-modal-title').html(title);
                galleryModal.find('.modal-title').html(item.title);
            }
        }
        goodHeight += gallery.find('.modal-description').height() * 1;
        galleryModal.find('.modal-description').remove();
        galleryModal.find('.ba-likes p').text(item.likes);
        galleryModal.find('.ba-modal-body').css('background-color', style.lightbox_border);
        if (style.auto_resize != 0) {
            var dWidth = window.innerWidth,
                dHeight = window.innerHeight;
            if (!item.video) {
                jQuery('#disqus_thread').hide();
                var newImage = new Image(),
                    imgWidth,
                    imgHeight,
                    modalTop;
                slideFlag = false;
                galleryModal.css('height', goodHeight);
                newImage.onload = function(){
                    imgWidth = this.width;
                    imgHeight = this.height;
                    globalImage.width = this.width;
                    globalImage.height = this.height;
                    if (galleryModal.hasClass('ba-description-left') || galleryModal.hasClass('ba-description-right')) {
                        dWidth -= 400;
                    }
                    if (imgWidth < dWidth && imgHeight < dHeight) {
                        
                    } else {
                        var percent = imgWidth / imgHeight;
                        if (imgWidth > imgHeight) {
                            imgWidth = dWidth;
                            imgHeight = imgWidth / percent;
                        } else {
                            imgHeight = dHeight;
                            imgWidth = percent * imgHeight;
                        }
                        if (imgHeight > dHeight) {
                            imgHeight = dHeight;
                            imgWidth = percent * imgHeight;
                        }
                        if (imgWidth > dWidth) {
                            imgWidth = dWidth;
                            imgHeight = imgWidth / percent;
                        }
                        if (imgHeight == dHeight && item.description &&
                            !galleryModal.hasClass('ba-description-left') && !galleryModal.hasClass('ba-description-right')) {
                            dHeight = dHeight * 0.9;
                            imgHeight = dHeight;
                            imgWidth = percent * imgHeight;
                        }
                    }
                    modalTop = (dHeight - imgHeight) / 2;
                    galleryModal.animate({
                        'width' : Math.round(imgWidth),
                        'height' : Math.round(imgHeight),
                        'margin-top' : Math.round(modalTop)
                    }, '500', function(){
                        galleryModal.find('.modal-image img').attr('src', item.url);
                        galleryModal.find('.modal-image img').removeClass('reload');
                        galleryModal.find('.ba-modal-body').removeClass('reload-parent');
                        galleryModal.css({'height' : 'auto'});
                        if (item.description) {
                            item.description = checkForms(item.description);
                            galleryModal.find('.ba-modal-body .description-wrapper')
                                .prepend('<div class="modal-description"></div>');
                            galleryModal.find('.modal-description').html(item.description);
                        }
                        jQuery('#disqus_thread').show();
                        createVK(vk);
                        slideFlag = true;
                    });
                    goodWidth = imgWidth;
                    goodHeight = imgHeight;
                }
                newImage.src = item.url;
            } else {
                if (galleryModal.hasClass('ba-description-left') || galleryModal.hasClass('ba-description-right')) {
                    dWidth -= 400;
                }
                var height = dHeight - 200,
                    percent = height / dHeight,
                    width = dWidth * percent,
                    top = dHeight * 0.1;
                if (jQuery(window).width() <= 1024) {
                    width = dWidth;
                }
                galleryModal[0].style.height = '';
                galleryModal.css({
                    'width' : Math.round(width)+'px',
                    'margin-top' : top+'px'
                });
                setTimeout(function(){
                    top = (height - galleryModal.height()) / 2 + 100;
                    if (top < ba_jQuery(window).height() * 0.1) {
                        top = ba_jQuery(window).height() * 0.1;
                    }
                    galleryModal.css({
                        'margin-top' : top+'px'
                    });
                }, 1);
                if (item.description) {
                    item.description = checkForms(item.description);
                    galleryModal.find('.ba-modal-body .description-wrapper')
                        .prepend('<div class="modal-description"></div>');
                    galleryModal.find('.modal-description').html(item.description);
                }
                createVK(vk);
            }
            galleryModal.addClass('ba-resize');
        } else {
            if (jQuery(window).width() > 1024) {
                galleryModal.removeClass('ba-resize');
                var width = style.lightbox_width;
                if (galleryModal.hasClass('ba-description-left') || galleryModal.hasClass('ba-description-right')) {
                    width = width / 100;
                    width = 'calc((100% - 400px)*'+width+')';
                } else {
                    width += '%';
                }
                galleryModal.css({
                    'width' : width,
                    'margin-top' : ''
                });
            } else {
                galleryModal.addClass('ba-resize');
            }
            if (item.description) {
                item.description = checkForms(item.description);
                galleryModal.find('.ba-modal-body .description-wrapper')
                    .prepend('<div class="modal-description"></div>');
                galleryModal.find('.modal-description').html(item.description);
            }
            createVK(vk);
        }
        zoomClk = 1;
    }

    galleryModal.find('.ba-zoom-out').on('click', function(){
        if (zoomClk == 1) {
            return false;
        }
        galleryModal.removeClass('hidden-description');
        jQuery(this).addClass('disabled-item');
        galleryModal.find('.ba-zoom-in').removeClass('disabled-item');
        var img = galleryModal.find('.modal-image img');
        img.addClass('ba-zoom-image').css({
            width : zoomW,
            height : zoomH,
            top : zoomT,
            left : zoomL,
            position : 'absolute'
        });
        setTimeout(function(){
            img.css({
                position : '',
                width : '',
                height : '',
                left: '',
                top : '',
                'max-width' : '',
                'max-height' : '',
                'cursor' : ''
            }).off('mousedown.zoom mouseup.zoom touchstart.zoom touchend.zoom').removeClass('ba-zoom-image');
            galleryModal.parent().css('overflow', '');
        }, 150);
        zoomClk = 1;
    });

    var zoomClk = 1,
        zoomW,
        zoomH,
        zoomT,
        zoomL;

    galleryModal.find('.ba-zoom-in').on('click', function(){
        if (slideFlag) {
            if (galleryModal.parent().scrollTop() > 0) {
                galleryModal.parent().animate({
                    scrollTop: 0
                }, 150, function(){
                    galleryModal.find('.ba-zoom-in').trigger('click');
                });
                return false;
            }
            if (zoomClk > 10) {
                jQuery(this).addClass('disabled-item');
                return false;
            }
            galleryModal.addClass('hidden-description');
            galleryModal.find('.ba-zoom-out').removeClass('disabled-item');
            var img = galleryModal.find('.modal-image img'),
                width = img.width() * 1.2,
                height = img.height() * 1.2,
                w = ba_jQuery(window).width(),
                h = ba_jQuery(window).height();
            if (galleryModal.hasClass('ba-description-left') || galleryModal.hasClass('ba-description-right')) {
                w -= 400;
            }
            var left = (w - width) / 2,
                top = (h - img.height() * 1.2) / 2;
            if (galleryModal.hasClass('ba-description-left')) {
                left += 400;
            }
            if (zoomClk == 1) {
                zoomW = img.width();
                zoomH = img.height();
                zoomT = img.position().top;
                zoomL = img.position().left;
                img.css({
                    width : zoomW,
                    height : zoomH,
                    top : zoomT,
                    left : zoomL,
                    position : 'absolute'
                });
            }
            zoomClk++;
            if (img.length == 0) {
                return false;
            }
            setTimeout(function(){
                img.addClass('ba-zoom-image').css({
                    position : 'absolute',
                    width : width,
                    height : height,
                    left: left,
                    top : top,
                    'max-width' : 'none',
                    'max-height' : 'none',
                    'cursor' : 'move'
                });
            }, 100);
            setTimeout(function(){
                img.removeClass('ba-zoom-image');
                galleryModal.parent().css('overflow', 'hidden');
            }, 150);
            galleryModal.off('mousedown.zoom').on('mousedown.zoom', function(){
                return false;
            }).off('mouseup.zoom').on('mouseup.zoom', function(){
                img.off('mousemove.zoom').off('mouseup.zoom');
            });
            img.off('mousedown.zoom touchstart.zoom').on('mousedown.zoom touchstart.zoom', function(e){
                e.stopPropagation();
                var x = e.clientX,
                    y = e.clientY;
                if (e.type == 'touchstart') {
                    x = e.originalEvent.targetTouches[0].pageX;
                    y = e.originalEvent.targetTouches[0].pageY;
                }
                jQuery(this).on('mousemove.zoom touchmove.zoom', function(event){
                    var deltaX = x - event.clientX,
                        deltaY = y - event.clientY,
                        w = document.documentElement.clientWidth,
                        h = document.documentElement.clientHeight;
                    if (e.type == 'touchstart') {
                        deltaX = x - event.originalEvent.targetTouches[0].pageX;
                        deltaY = y - event.originalEvent.targetTouches[0].pageY;
                    }
                    if (galleryModal.hasClass('ba-description-left') || galleryModal.hasClass('ba-description-right')) {
                        w -= 400;
                    }
                    var maxX = (width - w) * -1,
                        maxY = (height - h) * -1,
                        minX = 0,
                        minY = 0;
                    if (galleryModal.hasClass('ba-description-left')) {
                        minX = 400;
                        maxX += 400;
                    }
                    x = event.clientX;
                    y = event.clientY;
                    if (e.type == 'touchstart') {
                        x = event.originalEvent.targetTouches[0].pageX;
                        y = event.originalEvent.targetTouches[0].pageY;
                    }
                    if (width > w) {
                        if (deltaX > 0) {
                            if (left > maxX) {
                                left -= Math.abs(deltaX);
                                left = left < maxX ? maxX : left;
                                jQuery(this).css('left', left);
                            }
                        } else {
                            if (left < minX) {
                                left += Math.abs(deltaX);
                                left = left > minX ? minX : left;
                                jQuery(this).css('left', left);
                            }
                        }
                    }
                    if (height > h) {
                        if (deltaY > 0) {
                            if (top > maxY) {
                                top -= Math.abs(deltaY);
                                top = top < maxY ? maxY : top;
                                jQuery(this).css('top', top);
                            }
                        } else {
                            if (top < minY) {
                                top += Math.abs(deltaY);
                                top = top > minY ? minY : top;
                                jQuery(this).css({
                                    'top' : top
                                });
                            }
                        }
                    }                        
                    return false;
                });
                return false;
            }).off('mouseup.zoom touchend.zoom').on('mouseup.zoom touchend.zoom', function(){
                jQuery(this).off('mousemove.zoom touchmove.zoom');
            });
        }
    });

    galleryModal.find('.ba-download-img').on('click', function(){
        var src = this.href;
        if (src.indexOf('task=gallery.addWatermark') >= 0 || src.url.indexOf('gallery.compressionImage') !== -1) {
            src = src.replace('task=gallery.addWatermark', 'task=gallery.download');
            src = src.replace('task=gallery.compressionImage', 'task=gallery.download');
            this.href = src;
        }
    });

    var fullscreen = true;

    function checkFullscreen()
    {
        if (document.fullscreenElement || document.webkitIsFullScreen
            || document.mozFullScreen || document.msFullscreenElement) {
            galleryModal.find('.display-lightbox-fullscreen').removeClass('zmdi-fullscreen')
                .addClass('zmdi-fullscreen-exit');
            fullscreen = false;
        } else {
            galleryModal.find('.display-lightbox-fullscreen').removeClass('zmdi-fullscreen-exit')
                .addClass('zmdi-fullscreen');
            fullscreen = true;
        }
    }

    document.addEventListener('fullscreenchange', checkFullscreen, false);
    document.addEventListener('webkitfullscreenchange', checkFullscreen, false);
    document.addEventListener('mozfullscreenchange', checkFullscreen, false);
    document.addEventListener('msfullscreenchange', checkFullscreen, false);

    galleryModal.find('.display-lightbox-fullscreen').on('click', function(){
        if (fullscreen) {
            var docElm = document.documentElement;
            if (docElm.requestFullscreen) {
                docElm.requestFullscreen();
            } else if (docElm.mozRequestFullScreen) {
                docElm.mozRequestFullScreen();
            } else if (docElm.webkitRequestFullScreen) {
                docElm.webkitRequestFullScreen();
            } else if (docElm.msRequestFullscreen) {
                docElm.msRequestFullscreen();
            }                
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitCancelFullScreen) {
                document.webkitCancelFullScreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
            this.classList.add('zmdi-fullscreen');
            this.classList.remove('zmdi-fullscreen-exit');
            fullscreen = true;
        }
    });
    
    galleryModal.find('.ba-twitter-share-button').on('click touchend', function(event){
        event.preventDefault();
        event.stopPropagation();
        var url = 'https://twitter.com/intent/tweet?url=',
            title = galleryModal.find('.modal-title').text();
        if (!title) {
            title = ba_jQuery('title').text();
        }
        url += encodeURIComponent(window.location.href);
        url += '&text='+encodeURIComponent(title);
        window.open(url, 'sharer', 'toolbar=0, status=0, width=626, height=436');
    });
    
    galleryModal.find('.ba-facebook-share-button').on('click touchend', function(event){
        event.preventDefault();
        event.stopPropagation();
        var item = JSON.parse(image),
            url = 'http://www.facebook.com/sharer.php?u=';
        url += encodeURIComponent(window.location.href);
        window.open(url, 'sharer', 'toolbar=0, status=0, width=626, height=436');
    });
    
    galleryModal.find('.ba-google-share-button').on('click touchend', function(event){
        event.preventDefault();
        event.stopPropagation();
        var url = 'https://plus.google.com/share?url=';
        url += encodeURIComponent(window.location.href);
        window.open(url, 'sharer', 'toolbar=0, status=0, width=626, height=436');
    });
    
    galleryModal.find('.ba-pinterest-share-button').on('click touchend', function(event){
        event.preventDefault();
        event.stopPropagation();
        var url = 'http://www.pinterest.com/pin/create/button/?url=',
            title = galleryModal.find('.modal-title').text();
        if (!title) {
            title = ba_jQuery('title').text();
        }
        url += encodeURIComponent(window.location.href)+'&media=';
        url += encodeURIComponent(galleryModal.find('.modal-image img').attr('src'))+'&description=';
        url += encodeURIComponent(title);
        window.open(url, 'sharer', 'toolbar=0, status=0, width=626, height=436');
    });

    galleryModal.find('.ba-linkedin-share-button').on('click touchend', function(event){
        event.preventDefault();
        event.stopPropagation();
        var url = 'http://www.linkedin.com/shareArticle?url=',
            title = galleryModal.find('.modal-title').text();
        if (!title) {
            title = ba_jQuery('title').text();
        }
        url += encodeURIComponent(window.location.href)+'&text=';
        url += encodeURIComponent(title);
        window.open(url, 'sharer', 'toolbar=0, status=0, width=626, height=436');
    });

    galleryModal.find('.ba-vk-share-button').on('click touchend', function(event){
        event.preventDefault();
        event.stopPropagation();
        var url = 'http://vk.com/share.php?url=',
            title = galleryModal.find('.modal-title').text();
        if (!title) {
            title = ba_jQuery('title').text();
        }
        url += encodeURIComponent(window.location.href)+'&text=';
        url += encodeURIComponent(title)+'&image=';
        url += encodeURIComponent(galleryModal.find('.modal-image img').attr('src'));
        window.open(url, 'sharer', 'toolbar=0, status=0, width=626, height=436');
    });

    gallery.find('.albums-backdrop, .albums-backdrop-close').on('click', function(){
        gallery.removeClass('album-in-lightbox');
        jQuery('body').removeClass('album-in-lightbox-open');
        gallery.find('.ba-gallery-row-wrapper').css('background-color', '');
        category = '.root';
        var alias = album.find('.current-root').val();
        if (pageRefresh == 1) {
            if (alias != window.location.href) {
                refreshPage(alias);
                gallery.find('.ba-pagination').hide();
            }
        } else {
            window.history.pushState(null, null, alias);
            if (pagination) {
                currentPage = '.page-1';
                addPages();
                drawPagination();
            }
            resizeIsotope();
        }
    });

    var likeFlag = true;
    
    function likeImage()
    {
        var item = JSON.parse(image);
        if (likeFlag) {
            likeFlag = false;
            ba_jQuery.ajax({
                type:"POST",
                dataType:'text',
                url:"?option=com_bagallery&view=gallery&task=gallery.likeIt&tmpl=component&image_id="+item.id,
                data:{
                    image_id : item.id,
                },
                success: function(msg){
                    msg = JSON.parse(msg);
                    galleryModal.find('.ba-modal-header .ba-add-like');
                    item.likes = msg.data;
                    gallery.find('input[data-id="ba-image-'+item.id+'"]').val(JSON.stringify(item));
                    elements[imageIndex] = JSON.stringify(item);
                    galleryModal.find('.ba-likes p').text(msg.data);
                    likeFlag = true;
                }
            });
        }
    }
    
    function addModalStyle()
    {
        var color = hexToRgb(style.lightbox_bg);
        color.a = style.lightbox_bg_transparency;
        galleryModal.parent().css('background-color',
                                                'rgba('+color.r+','+color.g+','+color.b+','+color.a+')');
        if (style.auto_resize != 0) {
            goodWidth = 0;
            goodHeight = 0;
            galleryModal.css({
                'width' : goodWidth+'px',
                'height' : goodHeight+'px',
                'margin-top' : jQuery(window).height() / 2+'px'
            });
        }
    }

    if (defaultFilter) {
        addFilterStyle();
    }
    addCaptionStyle();
    if (pagination) {
        drawPagination();
    }
    setTimeout(function(){
        resizeIsotope();
    }, 100);
    if (galleryModal.find('.header-icons').length > 0) {
        if (winSize <= 1024) {
            var shadow = galleryModal.parent()[0].style.backgroundColor;
            galleryModal.find('.header-icons')[0].style.boxShadow = 'inset 0px -85px 150px -85px '+shadow;
        } else {
            galleryModal.find('.header-icons')[0].style.boxShadow = '';
        }
    }
    if (albumMode) {
        lazyloadOptions.lightbox = albumOptions.album_enable_lightbox
    }
    gallery.find('.ba-gallery-items img').lazyload(lazyloadOptions);
}
//document.addEventListener("DOMContentLoaded", initGalleries);
window.addEventListener("load", initGalleries);