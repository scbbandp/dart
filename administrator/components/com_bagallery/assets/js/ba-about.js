/**
* @package   BaGallery
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

jQuery(document).ready(function(){

    setTimeout(function(){
        jQuery('.alert.alert-success').addClass('animation-out');
    }, 2000);

    var update = jQuery('#update-data').val();
    update = JSON.parse(update);

    jQuery('#toolbar-cleanup-images').on('click', function(){
        jQuery('#cleanup-images-dialog').modal();
    });

    jQuery('#cleanup-images').on('click', function(){
        var notice = jQuery('#ba-notification'),
            str = update.saving+'<img src="'+update.url;
        str += 'administrator/components/com_bagallery/assets/images/reload.svg"></img>';
        notice.addClass('notification-in');
        notice.find('p').html(str);
        jQuery.ajax({
            type:"POST",
            dataType:'text',
            url:"index.php?option=com_bagallery&task=galleries.cleanup&tmpl=component",
            success: function(msg){
                setTimeout(function(){
                    notice[0].className = 'animation-out';
                    setTimeout(function(){
                        notice.find('p').html(msg);
                        notice[0].className = 'notification-in';
                        setTimeout(function(){
                            notice[0].className = 'animation-out';
                        }, 3000);
                    }, 400);
                }, 2000);
            }
        });
        jQuery('#cleanup-images-dialog').modal('hide');
    });

    jQuery('.ba-custom-select > i, div.ba-custom-select input').on('click', function(event){
        event.stopPropagation()
        var $this = jQuery(this),
            parent = $this.parent();
        jQuery('.visible-select').removeClass('visible-select');
        parent.find('ul').addClass('visible-select');
        parent.find('li').one('click', function(){
            var text = jQuery.trim(jQuery(this).text()),
                val = jQuery(this).attr('data-value');
            parent.find('input[type="text"]').val(text);
            parent.find('input[type="hidden"]').val(val).trigger('change');
        });
        parent.trigger('show');
        setTimeout(function(){
            jQuery('body').one('click', function(){
                jQuery('.visible-select').removeClass('visible-select');
            });
        }, 50);
    });

    jQuery('.ba-tooltip').each(function(){
        jQuery(this).parent().children().first().on('mouseenter', function(){
            var coord = this.getBoundingClientRect(),
                top = coord.top,
                data = jQuery(this).parent().find('.ba-tooltip').html(),
                center = (coord.right - coord.left) / 2;
                className = jQuery(this).parent().find('.ba-tooltip')[0].className;
            center = coord.left + center;
            if (jQuery(this).parent().find('.ba-tooltip').hasClass('ba-bottom')) {
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

    jQuery('div.ba-custom-select').on('show', function(){
        jQuery(this).find('i.zmdi.zmdi-check').parent().addClass('selected');
    })

    jQuery('#toolbar-language button').on('click', function(){
        jQuery('#language-dialog').modal();
    });

    jQuery('#language-dialog').on('show', function(){
        window.addEventListener("message", listenMessage, false);
        uploadMode = 'language'
    });

    jQuery('#language-dialog').on('hide', function(){
        window.removeEventListener("message", listenMessage, false);
    });

    jQuery('#toolbar-about').find('button').on('click', function(){
        jQuery('#about-dialog').modal();
    });
    
    var massage = '';
    
    jQuery('.update-link').on('click', function(event){
        event.preventDefault();
        updateComponent();
    });
    
    jQuery('.leave-feedback').on('click', function(event){
        event.stopPropagation();
        event.preventDefault();
        jQuery('#feedback-dialog').modal();
    });
    
    jQuery('#feedback-dialog').on('show', function(){
        jQuery('#about-dialog').modal('hide');
        jQuery('.feedback-body').show();
        jQuery('.happy-feedback, .not-happy-feedback').hide();
    });
    
    jQuery('.happy-rewiev').on('click', function(event){
        event.stopPropagation();
        event.preventDefault();
        jQuery('.happy-feedback').show();
        jQuery('.feedback-body, .not-happy-feedback').hide();
    });
    
    jQuery('.not-happy-rewiev').on('click', function(event){
        event.stopPropagation();
        event.preventDefault();
        jQuery('.not-happy-feedback').show();
        jQuery('.feedback-body, happy-feedback').hide();
    });

    var iframe,
        uploadMode;

    jQuery('#update-dialog').on('show', function(){
        window.addEventListener("message", listenMessage, false);
        uploadMode = 'update';
    });

    jQuery('#update-dialog').on('hide', function(){
        window.removeEventListener("message", listenMessage, false);
        iframe.remove();
    });

    function updateComponent()
    {
        var src = 'https://www.balbooa.com/demo/index.php?option=com_baupdater&view=bagallery';
        iframe = jQuery('<iframe/>', {
                name : 'update-target',
                id : 'update-target',
                src : src
        });
        iframe.appendTo(jQuery('#form-update'));
        iframe.css('width', '440px')
        iframe.css('height', '290px')
        jQuery('#update-dialog').modal();
    }

    function listenMessage(event)
    {
        if (event.origin == 'https://www.balbooa.com' || event.origin == location.origin) {
            if (uploadMode == 'update') {
                var link = event.data;
                iframe.remove();
                jQuery('#update-dialog').modal('hide');
                var flag = link[0] + link[1] + link[2] + link[3];
                if (flag == 'http') {
                    var notice = jQuery('#ba-notification')
                    jQuery('.ba-update-message').addClass('animation-out');
                    setTimeout(function(){
                        var str = update.const+'<img src="'+update.url;
                        str += 'administrator/components/com_bagallery/assets/images/reload.svg"></img>';
                        notice.addClass('notification-in');
                        notice.find('p').html(str)
                    }, 400);
                    jQuery('#message-dialog p').text('BaGallery is updating');
                    jQuery.ajax({
                        type:"POST",
                        dataType:'text',
                        url:"index.php?option=com_bagallery&task=galleries.updateGallery&tmpl=component",
                        data:{
                            target:link,
                        },
                        success: function(msg){
                            msg = JSON.parse(msg)
                            if (msg.success) {
                                setTimeout(function(){
                                    notice[0].className = 'animation-out';
                                    setTimeout(function(){
                                        notice.find('p').html(update.updated);
                                        notice[0].className = 'notification-in';
                                        jQuery('.update').text(msg.message);
                                        setTimeout(function(){
                                            notice[0].className = 'animation-out';
                                        }, 3000);
                                    }, 400);
                                }, 2000);
                            } else {
                                setTimeout(function(){
                                    notice[0].className = 'animation-out';
                                    setTimeout(function(){
                                        notice.find('p').html(update.error);
                                        notice[0].className = 'notification-in';
                                        jQuery('.update').text(msg.message);
                                        setTimeout(function(){
                                            notice[0].className = 'animation-out';
                                            jQuery('.ba-update-message').removeClass('animation-out');
                                        }, 3000);
                                    }, 400);
                                }, 2000);
                            }
                        }
                    });
                } else {
                    jQuery('#message-dialog').modal();
                    jQuery('#message-dialog p').text(link);
                }
            } else if (uploadMode == 'language') {
                jQuery('#language-dialog').modal('hide');
                jQuery.ajax({
                    type:"POST",
                    dataType:'text',
                    url:"index.php?option=com_bagallery&task=galleries.addLanguage&tmpl=component",
                    data:{
                        ba_url: event.data,
                    },
                    success: function(msg){
                        msg = JSON.parse(msg)
                        jQuery('#language-message-dialog').modal();
                    }
                });
            }
        }
    }
});

