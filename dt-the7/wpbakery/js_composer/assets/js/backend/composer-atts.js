/* =========================================================
 * composer-atts.js v0.2.1
 * =========================================================
 * Copyright 2013 Wpbakery
 *
 * Visual composer backbone/underscore shortcodes attributes
 * form field and parsing controls
 * ========================================================= */
var vc = {filters:{templates:[]}, addTemplateFilter:function (callback) {
    if (_.isFunction(callback)) this.filters.templates.push(callback);
}};
(function ($) {
    var i18n = window.i18nLocale;
    vc.edit_form_callbacks = [];
    vc.atts = {
        parse:function (param) {
            var value;
            var $field = this.content().find('.wpb_vc_param_value[name=' + param.param_name + ']');
          if (!_.isUndefined(vc.atts[param.type]) && !_.isUndefined(vc.atts[param.type].parse)) {
                value = vc.atts[param.type].parse.call(this, param);
            } else {
                value = $field.length ? $field.val() : null;
            }
            if ($field.data('js-function') !== undefined && typeof(window[$field.data('js-function')]) !== 'undefined') {
                var fn = window[$field.data('js-function')];
                fn(this.$el, this);
            }
            return value;
        },
        parseFrame:function (param) {
          var value;
          var $field = this.content().find('.wpb_vc_param_value[name=' + param.param_name + ']');
          if (!_.isUndefined(vc.atts[param.type]) && !_.isUndefined(vc.atts[param.type].parse)) {
            value = vc.atts[param.type].parse.call(this, param);
          } else {
            value = $field.length ? $field.val() : null;
          }
          if ($field.data('js-function') !== undefined && typeof(window[$field.data('js-function')]) !== 'undefined') {
            var fn = window[$field.data('js-function')];
            fn(this.$el, this);
          }
          return value;
        }
    };

    // Default atts
    _.extend(vc.atts, {
        textarea_html:{
            parse:function (param) {
                var $field = this.content().find('.textarea_html.' + param.param_name + ''),
                    mce_id = $field.attr('id');
                return this.window().tinyMCE && this.window().tinyMCE.activeEditor
                       ? this.window().tinyMCE.activeEditor.save()
                       : $field.val();
            },
            render:function (param, value) {
                return _.isUndefined(value) ? value : vc_wpautop(value);
            }
        },
        textarea_safe: {
          parse:function (param) {
            var $field = this.content().find('.wpb_vc_param_value[name=' + param.param_name + ']'),
                new_value = $field.val();
            return new_value.match(/"/) ? '#E-8_' + base64_encode(rawurlencode(new_value)) : new_value;
          },
          render:function (param, value) {
            return value && value.match(/^#E\-8_/) ? $("<div/>").text(rawurldecode(base64_decode(value.replace(/^#E\-8_/, '')))).html() : value;
          }
        },
        checkbox:{
            parse:function (param) {
                var arr = [],
                    new_value = '';
                $('input[name=' + param.param_name + ']', this.content()).each(function (index) {
                    var self = $(this);
                    if (self.is(':checked')) {
                        arr.push(self.attr("value"));
                    }
                });
                if (arr.length > 0) {
                    new_value = arr.join(',');
                }
                return new_value;
            }
        },
        posttypes:{
            parse:function (param) {
                var posstypes_arr = [],
                    new_value = '';
                $('input[name=' + param.param_name + ']', this.content()).each(function (index) {
                    var self = $(this);
                    if (self.is(':checked')) {
                        posstypes_arr.push(self.attr("value"));
                    }
                });
                if (posstypes_arr.length > 0) {
                    new_value = posstypes_arr.join(',');
                }
                return new_value;
            }
        },
        taxonomies:{
            parse:function (param) {
                var posstypes_arr = [],
                    new_value = '';
                $('input[name=' + param.param_name + ']', this.content()).each(function (index) {
                    var self = $(this);
                    if (self.is(':checked')) {
                        posstypes_arr.push(self.attr("value"));
                    }
                });
                if (posstypes_arr.length > 0) {
                    new_value = posstypes_arr.join(',');
                }
                return new_value;
            }
        },
        exploded_textarea:{
            parse:function (param) {
                var $field = this.content().find('.wpb_vc_param_value[name=' + param.param_name + ']');
                return $field.val().replace(/\n/g, ",");
            }
        },
        textarea_raw_html:{
            parse:function (param) {
                var $field = this.content().find('.wpb_vc_param_value[name=' + param.param_name + ']'),
                    new_value = $field.val();
                return base64_encode(rawurlencode(new_value));
            },
            render:function (param, value) {
                return $("<div/>").text(rawurldecode(base64_decode(value))).html();
            }
        },
        dropdown:{
            render:function (param, value) {
                var all_classes = _.isObject(param.value) ? _.values(param.value).join(' ') : '';
                //  this.$el.find('> .wpb_element_wrapper').removeClass(all_classes).addClass(value); // remove all possible class names and add only selected one
                return value;
            }
        },
        attach_images:{
            parse:function (param) {
                var $field = this.content().find('.wpb_vc_param_value[name=' + param.param_name + ']'),
                    thumbnails_html = '';
                // TODO: Check image search with Wordpress
                $field.parent().find('li.added').each(function () {
                    thumbnails_html += '<li><img src="' + $(this).find('img').attr('src') + '" alt=""></li>';
                });
                $('[data-model-id=' + this.model.id + ']').data('field-' + param.param_name + '-attach-images', thumbnails_html);
                return $field.length ? $field.val() : null;
            },
            render:function (param, value) {
                var $thumbnails = this.$el.find('.attachment-thumbnails[data-name=' + param.param_name + ']'),
                    thumbnails_html = this.$el.data('field-' + param.param_name + '-attach-images');
                if (_.isUndefined(thumbnails_html) && !_.isEmpty(value)) {
                    $.ajax({
                        type:'POST',
                        url:window.ajaxurl,
                        data:{
                            action:'wpb_gallery_html',
                            content:value
                        },
                        dataType:'html',
                        context:this
                    }).done(function (html) {
                            vc.atts.attach_images.updateImages($thumbnails, html);
                        });
                } else if(!_.isUndefined(thumbnails_html)) {
                    this.$el.removeData('field-' + param.param_name + '-attach-images');
                    vc.atts.attach_images.updateImages($thumbnails, thumbnails_html);
                }
                return value;
            },
            updateImages:function ($thumbnails, thumbnails_html) {
                $thumbnails.html(thumbnails_html);
                if (thumbnails_html.length) {
                    $thumbnails.removeClass('image-exists').next().addClass('image-exists');
                } else {
                    $thumbnails.addClass('image-exists').next().removeClass('image-exists');
                }
            }
        },
        href: {
            parse: function(param) {
                var $field = this.content().find('.wpb_vc_param_value[name=' + param.param_name + ']'),
                    val = '';
                if($field.length && $field.val() != 'http://') val = $field.val();
                return val;
            }
        },
        attach_image:{
            parse:function (param) {
                var $field = this.content().find('.wpb_vc_param_value[name=' + param.param_name + ']'),
                    image_src = '';
                if ($field.parent().find('li.added').length) {
                    image_src = $field.parent().find('li.added img').attr('src');
                }
                $('[data-model-id=' + this.model.id + ']').data('field-' + param.param_name + '-attach-image', image_src);
                return $field.length ? $field.val() : null;
            },
            render:function (param, value) {
                var image_src = $('[data-model-id=' + this.model.id + ']').data('field-' + param.param_name + '-attach-image');
                var $thumbnail = this.$el.find('.attachment-thumbnail[data-name=' + param.param_name + ']');
                if (_.isUndefined(image_src) && !_.isEmpty(value)) {
                  $.ajax({
                        type:'POST',
                        url:window.ajaxurl,
                        data:{
                            action:'wpb_single_image_src',
                            content:value
                        },
                        dataType:'html',
                        context:this
                    }).done(function (src) {
                            vc.atts['attach_image'].updateImage($thumbnail, src);
                        });
                } else if(!_.isUndefined(image_src)) {
                    $('[data-model-id=' + this.model.id + ']').removeData('field-' + param.param_name + '-attach-image');
                    vc.atts['attach_image'].updateImage($thumbnail, image_src);
                }

                return value;
            },
            updateImage:function ($thumbnail, image_src) {
                if (_.isEmpty(image_src)) {
                    $thumbnail.attr('src', '').hide();
                    $thumbnail.next().removeClass('image-exists').next().removeClass('image-exists');
                } else {
                    $thumbnail.attr('src', image_src).show();
                    $thumbnail.next().addClass('image-exists').next().addClass('image-exists');
                }
            }
        },
        google_fonts:{
            parse:function(param) {
                var $field = this.content().find('.wpb_vc_param_value[name=' + param.param_name + ']');
                var $block = $field.parent();
                var options = {},
                    string_pieces = [],
                    string = '';
                options.font_family = $block.find('.vc_google_fonts_form_field-font_family-select > option:selected').val();
                options.font_style = $block.find('.vc_google_fonts_form_field-font_style-select > option:selected').val();
                string_pieces = _.map(options, function(value, key){
                    if(_.isString(value) && value.length > 0) {
                        return key + ':' + encodeURIComponent(value);
                    }
                });
                string = $.grep(string_pieces , function(value){
                    return _.isString(value) && value.length > 0;
                }).join('|');
                return string;
            }
        },
        font_container:{
            parse:function(param) {
                var $field = this.content().find('.wpb_vc_param_value[name=' + param.param_name + ']');
                var $block = $field.parent();
                var options = {},
                    string_pieces = [],
                    string = '';
                options.tag = $block.find('.vc_font_container_form_field-tag-select > option:selected').val();
                options.font_size = $block.find('.vc_font_container_form_field-font_size-input').val();
                options.text_align = $block.find('.vc_font_container_form_field-text_align-select > option:selected').val();
                options.font_family = $block.find('.vc_font_container_form_field-font_family-select > option:selected').val();
                options.color = $block.find('.vc_font_container_form_field-color-input').val();
                options.line_height = $block.find('.vc_font_container_form_field-line_height-input').val();
                options.font_style_italic = $block.find('.vc_font_container_form_field-font_style-checkbox.italic').is(':checked') ? "1":"";
                options.font_style_bold = $block.find('.vc_font_container_form_field-font_style-checkbox.bold').is(':checked') ? "1":"";
                string_pieces = _.map(options, function(value, key){
                    if(_.isString(value) && value.length > 0) {
                        return key + ':' + encodeURIComponent(value);
                    }
                });
                string = $.grep(string_pieces , function(value){
                    return _.isString(value) && value.length > 0;
                }).join('|');
                return string;
            }
        }
    });
    vc.getMapped = function(tag) {
      return vc.map[tag] || {};
    }
})(window.jQuery);