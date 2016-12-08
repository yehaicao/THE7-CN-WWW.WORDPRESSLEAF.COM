/* =========================================================
 * params/all.js v0.0.1
 * =========================================================
 * Copyright 2012 Wpbakery
 *
 * Visual composer javascript functions to enable fields.
 * This script loads with settings form.
 * ========================================================= */

var wpb_change_tab_title, wpb_change_accordion_tab_title, vc_activeMce;

!function($) {
    wpb_change_tab_title = function($element, field) {
        $('.tabs_controls a[href=#tab-' + $(field).val() +']').text($('.wpb-edit-form [name=title].wpb_vc_param_value').val());
    };
    wpb_change_accordion_tab_title = function($element, field) {
         var $section_title = $element.prev();
         $section_title.find('a').text($(field).val());
    };

    window.init_textarea_html = function($element) {

        /*
         Simple version without all this buttons from Wordpress
         tinyMCE.init({
         mode : "textareas",
         theme: 'advanced',
         editor_selector: $element.attr('name') + '_tinymce'
         });
         */
        if($('#wp-link').parent().hasClass('wp-dialog')) $('#wp-link').wpdialog('destroy');
        var qt, textfield_id = $element.attr("id"),
            $form_line = $element.closest('.edit_form_line'),
            $content_holder = $form_line.find('.vc_textarea_html_content'),
            content = $content_holder.val();
        // Init Quicktag
        if(_.isUndefined(tinyMCEPreInit.qtInit[textfield_id])) {
            window.tinyMCEPreInit.qtInit[textfield_id] = _.extend({}, window.tinyMCEPreInit.qtInit[wpActiveEditor], {id: textfield_id})
        }
        // Init tinymce
        if(window.tinyMCEPreInit && window.tinyMCEPreInit.mceInit[wpActiveEditor]) {
	        window.tinyMCEPreInit.mceInit[textfield_id] = _.extend({}, window.tinyMCEPreInit.mceInit[wpActiveEditor], {
		        resize: 'vertical',
		        height: 200,
		        id: textfield_id,
		        setup: function (ed) {
			       if (typeof(ed.on) != 'undefined') {
				        ed.on('init', function (ed) {
					        ed.target.focus();
					        wpActiveEditor = textfield_id;
				        });
			        } else {
				        ed.onInit.add(function (ed) {
					        ed.focus();
					        wpActiveEditor = textfield_id;
				        });
			        }
		        }
	        });
            window.tinyMCEPreInit.mceInit[textfield_id].plugins =  window.tinyMCEPreInit.mceInit[textfield_id].plugins.replace(/,?wpfullscreen/, '');
        }
        $element.val($content_holder.val());
        qt = quicktags( window.tinyMCEPreInit.qtInit[textfield_id] );
        QTags._buttonsInit();
        if(window.tinymce) {
            window.switchEditors && window.switchEditors.go(textfield_id, 'tmce');
            if(tinymce.majorVersion === "4") tinymce.execCommand( 'mceAddEditor', true, textfield_id );
        }
        vc_activeMce = textfield_id;
	    wpActiveEditor = textfield_id;
    };
    function init_textarea_html_old($element) {
        var textfield_id = $element.attr("id"),
            $form_line = $element.closest('.edit_form_line'),
            $content_holder = $form_line.find('.vc_textarea_html_content');
        $('#' + textfield_id +'-html').trigger('click');
        $('.switch-tmce').trigger('click');
        $form_line.find('.wp-switch-editor').removeAttr("onclick");
         $('.switch-tmce').trigger('click');
         $element.closest('.edit_form_line').find('.switch-tmce').click(function () {
         window.tinyMCE.execCommand("mceAddControl", true, textfield_id);
         window.switchEditors.go(textfield_id, 'tmce');
         $element.closest('.edit_form_line').find('.wp-editor-wrap').removeClass('html-active').addClass('tmce-active');
             var val = window.switchEditors.wpautop($(this).closest('.edit_form_line').find("textarea.visual_composer_tinymce").val());
             $("textarea.visual_composer_tinymce").val(val);
             // Add tinymce
             window.tinyMCE.execCommand("mceAddControl", true, textfield_id);
         });
         $element.closest('.edit_form_line').find('.switch-html').click(function () {
             $element.closest('.edit_form_line').find('.wp-editor-wrap').removeClass('tmce-active').addClass('html-active');
             window.tinyMCE.execCommand("mceRemoveControl", true, textfield_id);
         });
         $('#wpb_tinymce_content-html').trigger('click');
         $('#wpb_tinymce_content-tmce').trigger('click'); // Fix hidden toolbar
    }
    // TODO: unsecure. Think about it
    Color.prototype.toString = function() {
      if(this._alpha < 1) {
        return this.toCSS('rgba', this._alpha).replace(/\s+/g, '');
      }
      var hex = parseInt( this._color, 10 ).toString( 16 );
      if ( this.error )
        return '';
      // maybe left pad it
      if ( hex.length < 6 ) {
        for (var i = 6 - hex.length - 1; i >= 0; i--) {
          hex = '0' + hex;
        }
      }
      return '#' + hex;
    };
    $('.vc_color-control').each(function(){
      var $control = $(this),
          value = $control.val().replace(/\s+/g, ''),
          alpha_val = 100,
          $alpha, $alpha_output;
      if(value.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/)) {
        alpha_val = parseFloat(value.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/)[1])*100;
      }
      $control.wpColorPicker({
        clear: function(event, ui) {
          $alpha.val(100);
          $alpha_output.val(100 + '%');
        }
      });
      $('<div class="vc_alpha-container">'
        + '<label>Alpha: <output class="rangevalue">' + alpha_val +'%</output></label>'
        + '<input type="range" min="1" max="100" value="' + alpha_val +'" name="alpha" class="vc_alpha-field">'
        + '</div>').appendTo($control.parents('.wp-picker-container:first').addClass('vc_color-picker').find('.iris-picker'));
      $alpha = $control.parents('.wp-picker-container:first').find('.vc_alpha-field');
      $alpha_output = $control.parents('.wp-picker-container:first').find('.vc_alpha-container output')
      $alpha.bind('change keyup', function(){
        var alpha_val = parseFloat($alpha.val()),
            iris = $control.data('a8cIris'),
            color_picker = $control.data('wpWpColorPicker');
        $alpha_output.val($alpha.val() + '%');
        iris._color._alpha = alpha_val/100.0;
        $control.val(iris._color.toString());
        color_picker.toggler.css( { backgroundColor: $control.val() });
      }).val(alpha_val).trigger('change');
    });
    var InitGalleries = function() {
        var that = this;
        // TODO: Backbone style for view binding
        $('.gallery_widget_attached_images_list', this.$view).unbind('click.removeImage').on('click.removeImage', 'a.icon-remove', function(e){
            e.preventDefault();
            var $block = $(this).closest('.edit_form_line');
            $(this).parent().remove();
            var img_ids = [];
            $block.find('.added img').each(function () {
                img_ids.push($(this).attr("rel"));
            });
            $block.find('.gallery_widget_attached_images_ids').val(img_ids.join(',')).trigger('change');
        });
        $('.gallery_widget_attached_images_list').each(function (index) {
            var $img_ul = $(this);
            $img_ul.sortable({
                forcePlaceholderSize:true,
                placeholder:"widgets-placeholder-gallery",
                cursor:"move",
                items:"li",
                update:function () {
                    var img_ids = [];
                    $(this).find('.added img').each(function () {
                        img_ids.push($(this).attr("rel"));
                    });
                    $img_ul.closest('.edit_form_line').find('.gallery' +
                        '' +
                        '_widget_attached_images_ids').val(img_ids.join(',')).trigger('change');
                }
            });
        });
    };
    new InitGalleries();
    var template_options = {
        evaluate:    /<#([\s\S]+?)#>/g,
        interpolate: /\{\{\{([\s\S]+?)\}\}\}/g,
        escape:      /\{\{([^\}]+?)\}\}(?!\})/g
    };

     /**
      * Loop param for shortcode with magic query posts constructor.
      * ====================================
      */
    vc.loop_partial = function(template_name, key, loop, settings) {
        var data = _.isObject(loop) && !_.isUndefined(loop[key]) ? loop[key] : '';
        return _.template($('#_vcl-' + template_name).html(), {name: key, data: data, settings: settings}, template_options);
    };
    vc.loop_field_not_hidden = function(key, loop) {
        return !(_.isObject(loop[key]) && _.isBoolean(loop[key].hidden) && loop[key].hidden === true);
    };
    vc.is_locked = function(data) {
        return _.isObject(data) && _.isBoolean(data.locked) && data.locked === true;
    };

    var Suggester = function(element, options) {
        this.el = element;
        this.$el = $(this.el);
        this.$el_wrap = '';
        this.$block = '';
        this.suggester = '';
        this.selected_items = [];
        this.options = _.isObject(options) ? options : {};
        _.defaults(this.options, {
            css_class: 'vc_suggester',
            limit: false,
            source: {},
            predefined: [],
            locked: false,
            select_callback: function(label, data) {},
            remove_callback: function(label, data) {},
            update_callback: function(label, data) {},
            check_locked_callback: function(el, data) {return false;}
        });
        this.init();
    };

    Suggester.prototype = {
        constructor: Suggester,
        init: function() {
            _.bindAll(this, 'buildSource', 'itemSelected', 'labelClick', 'setFocus', 'resize');
            var that = this;
            this.$el.wrap('<ul class="' + this.options.css_class +'"><li class="input"/></ul>');

            this.$el_wrap = this.$el.parent();
            this.$block = this.$el_wrap.closest('ul').append($('<li class="clear"/>'));
            this.$el.focus(this.resize).blur(function(){
                $(this).parent().width(170);
                $(this).val('');
                });
            this.$block.click(this.setFocus);
            this.suggester = this.$el.data('suggest'); // Remove form here
            this.$el.autocomplete({
                source: this.buildSource,
                select: this.itemSelected,
                minLength: 2,
                focus: function( event, ui ) {return false;}
            }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
                return $( '<li data-value="' + item.value + '">' )
                    .append( "<a>" + item.name + "</a>" )
                    .appendTo( ul );
            };
            this.$el.autocomplete( "widget" ).addClass('vc_ui-front')
            if(_.isArray(this.options.predefined)) {
                _.each(this.options.predefined, function(item){
                    this.create(item);
                }, this);
            }
        },
        resize: function() {
            var position = this.$el_wrap.position(),
                block_position = this.$block.position();
            this.$el_wrap.width(parseFloat(this.$block.width())  - (parseFloat(position.left) - parseFloat(block_position.left) + 4));
        },
        setFocus: function(e) {
            e.preventDefault();
            var $target = $(e.target);
            if($target.hasClass(this.options.css_class)) {
                this.$el.trigger('focus');
            }
        },
        itemSelected: function(event, ui) {
            this.$el.blur();
            this.create(ui.item);
            this.$el.focus();
            return false;
        },
        create: function(item) {
            var index = (this.selected_items.push(item) - 1),
                remove = this.options.check_locked_callback(this.$el, item) === true ? '' : ' <a class="remove">&times;</a>',
                $label,
                exclude_css = '';
            if(_.isUndefined(this.selected_items[index].action)) this.selected_items[index].action = '+';
            exclude_css = this.selected_items[index].action === '-' ? ' exclude' : ' include';
            $label = $('<li class="vc_suggest-label' + exclude_css +'" data-index="' + index + '" data-value="' + item.value + '"><span class="label">' + item.name + '</span>' + remove + '</li>');
            $label.insertBefore(this.$el_wrap);
            if(!_.isEmpty(remove)) $label.click(this.labelClick);
            this.options.select_callback($label, this.selected_items);
        },
        labelClick: function(e) {
            e.preventDefault();
            var $label = $(e.currentTarget),
                index = parseInt($label.data('index'), 10),
                $target = $(e.target);
            if($target.is('.remove')) {
                delete this.selected_items[index];
                this.options.remove_callback($label, this.selected_items);
                $label.remove();
                return false;
            }
            this.selected_items[index].action = this.selected_items[index].action === '+' ? '-' : '+';
            if(this.selected_items[index].action == '+') {
                $label.removeClass('exclude').addClass('include');
            } else {
                $label.removeClass('include').addClass('exclude');
            }
            this.options.update_callback($label, this.selected_items);
        },
        buildSource: function(request, response) {
            var exclude = _.map(this.selected_items, function(item) {return item.value;}).join(',');
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: window.ajaxurl,
                data: {
                    action: 'wpb_get_loop_suggestion',
                    field: this.suggester,
                    exclude: exclude,
                    query: request.term
                }
            }).done(function(data) {
                    response(data);
                });
        }
    };
    $.fn.suggester = function(option) {
            return this.each(function () {
                var $this = $(this),
                    data = $this.data('suggester'),
                    options = _.isObject(option) ? option : {};
                if (!data) $this.data('suggester', (data = new Suggester(this, option)));
                if (typeof option == 'string') data[option]();
            });
    };

    var VcLoopEditorView = Backbone.View.extend({
        className: 'loop_params_holder',
        events: {
            'click input, select': 'save',
            'change input, select': 'save',
            'change :checkbox[data-input]': 'updateCheckbox'
        },
        query_options: {

        },
        return_array: {},
        controller: '',
        initialize: function() {
            _.bindAll(this, 'save', 'updateSuggestion', 'suggestionLocked');
        },
        render: function(controller) {
            var that = this,
                template = _.template($( '#vcl-loop-frame' ).html(), this.model, _.extend({}, template_options, {variable: 'loop'}));
            this.controller = controller;
            this.$el.html(template);
            this.controller.$el.append(this.$el);
            _.each($('[data-suggest]'), function(object){
                var $field = $(object),
                    current_value = window.decodeURIComponent($('[data-suggest-prefill=' + $field.data('suggest') + ']').val());
                $field.suggester({
                    predefined: $.parseJSON(current_value),
                    select_callback: this.updateSuggestion,
                    update_callback: this.updateSuggestion,
                    remove_callback: this.updateSuggestion,
                    check_locked_callback: this.suggestionLocked
                });
            }, this);
            return this;
        },
        show: function() {
            this.$el.slideDown();
        },
        save: function(e) {
            this.return_array = {};
            _.each(this.model, function(value, key){
                var value = this.getValue(key, value);
                if(_.isString(value) && !_.isEmpty(value)) this.return_array[key] = value;
            }, this);
            this.controller.setInputValue(this.return_array);
        },
        getValue: function(key, default_value) {
            var value = $('[name=' + key + ']', this.$el).val();
            return value;
        },
        hide: function() {
            this.$el.slideUp();
        },
        toggle: function() {
            if(!this.$el.is(':animated')) this.$el.slideToggle();
        },
        updateCheckbox: function(e) {
            var $checkbox = $(e.currentTarget),
                input_name = $checkbox.data('input'),
                $input = $('[data-name=' + input_name + ']', this.$el),
                value = [];
            $('[data-input=' + input_name+']:checked').each(function(){
                value.push($(this).val());
            });
            $input.val(value);
            this.save();
        },
        updateSuggestion: function($elem, data) {
            var value,
                $suggestion_block = $elem.closest('[data-block=suggestion]');
            value = _.reduce(data, function(memo, label){
                if(!_.isEmpty(label)) {
                    return memo + (_.isEmpty(memo) ? '' : ',') + (label.action === '-' ? '-' : '') + label.value;
                }
            }, '');
            $suggestion_block.find('[data-suggest-value]').val(value).trigger('change');
        },
        suggestionLocked: function($elem, data) {
            var value = data.value,
                field = $elem.closest('[data-block=suggestion]').find('[data-suggest-value]').data('suggest-value');

            return this.controller.settings[field]
                   && _.isBoolean(this.controller.settings[field].locked)
                   && this.controller.settings[field].locked == true
                   && _.isString(this.controller.settings[field].value)
                   && _.indexOf(this.controller.settings[field].value.replace('-', '').split(/\,/), '' + value) >= 0;
        }
    });
    var VcLoop = Backbone.View.extend({
        events: {
            'click .vc_loop-build': 'showEditor'
        },
        initialize: function() {
            _.bindAll(this, 'createEditor');
            this.$input = $('.wpb_vc_param_value', this.$el);
            this.$button = this.$el.find('.vc_loop-build');
            this.data = this.$input.val();
            this.settings = $.parseJSON(window.decodeURIComponent(this.$button.data('settings')));
        },
        render: function() {
            return this;
        },
        showEditor: function(e) {
            e.preventDefault();
            if(_.isObject(this.loop_editor_view)) {
                this.loop_editor_view.toggle();
                return false;
            }
            $.ajax({
                type:'POST',
                dataType: 'json',
                url: window.ajaxurl,
                data: {
                    action:'wpb_get_loop_settings',
                    value: this.data,
                    settings: this.settings,
                    post_id: vc.post_id
                }
            }).done(this.createEditor);
        },
        createEditor: function(data) {
            this.loop_editor_view = new VcLoopEditorView({model:!_.isEmpty(data) ? data : {}});
            this.loop_editor_view.render(this).show();
        },
        setInputValue: function(value) {
            this.$input.val(_.map(value, function(value, key){
                return key + ':' + value;
            }).join('|'));
        }
    });
    var VcOptionsField = Backbone.View.extend({
        events: {
            'click .vc_options-edit': 'showEditor',
            'click .vc_close-button': 'showEditor',
            'click input, select': 'save',
            'change input, select': 'save',
            'keyup input': 'save'
        },
        data: {},
        fields: {},
        initialize: function() {
            this.$button = this.$el.find('.vc_options-edit');
            this.$form = this.$el.find('.vc_options-fields');
            this.$input = this.$el.find('.wpb_vc_param_value');
            this.settings = this.$form.data('settings');
            this.parseData();
            this.render();
        },
        render: function() {
            var html = '';
            _.each(this.settings, function(field){
                if(!_.isUndefined(this.data[field.name])) {
                    field.value = this.data[field.name];
                } else if(!_.isUndefined(field.value)) {
                    field.value = field.value.toString().split(',');
                    this.data[field.name] = field.value;
                }
                this.fields[field.name] = field;
                if($( '#vcl-options-field-' + field.type).is('script')) {
                    html  += _.template(
                        $( '#vcl-options-field-' + field.type ).html(),
                        $.extend({name: '', label: '', value: [], options: '', description: ''}, field),
                        _.extend({}, template_options)
                    );
                }
            }, this);
            this.$form.html(html + this.$form.html());
            return this;
        },
        parseData: function() {
            _.each(this.$input.val().split("|"), function(data) {
                if(data.match(/\:/)) {
                    var split = data.split(':'),
                        name = split[0],
                        value = split[1];
                    this.data[name] = _.map(value.split(','), function(v){
                        return window.decodeURIComponent(v);
                    });
                }
            }, this);
        },
        saveData: function() {
            var data_string = _.map(this.data, function(value, key){
                return key + ':' + _.map(value, function(v){ return window.encodeURIComponent(v);}).join(',');
            }).join('|');
            this.$input.val(data_string);
        },
        showEditor: function() {
            this.$form.slideToggle();
        },
        save: function(e) {
            var $field = $(e.currentTarget)
            if($field.is(':checkbox')) {
                var value = [];
                this.$el.find('input[name=' + $field.attr('name') + ']').each(function(){
                    if($(this).is(':checked')) {
                        value.push($(this).val());
                    }
                });
                this.data[$field.attr('name')] = value;
            } else {
                this.data[$field.attr('name')] = [$field.val()];
            }
            this.saveData();
        }
    });

    $(function(){
        $('.wpb_el_type_loop').each(function(){
            new VcLoop({el: $(this)});
        });
        $('.wpb_el_type_options').each(function(){
            new VcOptionsField({el: $(this)});
        });
    });
    /**
     * VC_link power code.
     */
    $('.vc_link-build').click(function(e){
        e.preventDefault();
        var $self = $(this),
            $block = $(this).closest('.vc_link'),
            $input = $block.find('.wpb_vc_param_value'),
            $url_label = $block.find('.url-label'),
            $title_label = $block.find('.title-label'),
            value_object = $input.data('json'),
            $link_submit = $('#wp-link-submit'),
            $vc_link_submit = $('<input type="submit" name="vc_link-submit" id="vc_link-submit" class="button-primary" value="Set Link">'),
            dialog;
        $link_submit.hide();
        $("#vc_link-submit").remove();
        $vc_link_submit.insertBefore($link_submit);
        if($.fn.wpdialog && $('#wp-link').length) {
          dialog = {
              $link:  false,
              open: function() {
                this.$link = $('#wp-link').wpdialog({
                  title: wpLinkL10n.title,
                  width: 480,
                  height: 'auto',
                  modal: true,
                  dialogClass: 'wp-dialog',
                  zIndex: 300000
                });
              },
              close: function() {
                this.$link.wpdialog('close');
              }
          };
        } else {
          dialog = window.wpLink;
        }
      /*
        $dialog = $('#wp-link').wpdialog({
                       title: wpLinkL10n.title,
                       width: 480,
                       height: 'auto',
                       modal: true,
                       dialogClass: 'wp-dialog',
                       zIndex: 300000
                   });
       */
        dialog.open(vc_activeMce);
        window.wpLink.textarea = $self;
        if(_.isString(value_object.url)) $('#url-field').val(value_object.url);
        if(_.isString(value_object.title)) $('#link-title-field').val(value_object.title);
        $('#link-target-checkbox').prop('checked', !_.isEmpty(value_object.target) ? true : false);

        $vc_link_submit.unbind('click.vcLink').bind('click.vcLink', function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            var options = {},
                string = '';
            options.url = $('#url-field').val();
            options.title = $('#link-title-field').val();
            options.target = $('#link-target-checkbox').is(':checked') ? ' _blank' : '';
            string = _.map(options, function(value, key){
                if(_.isString(value) && value.length > 0) {
                    return key + ':' + encodeURIComponent(value);
                }
            }).join('|');
            $input.val(string);
            $input.data('json', options);
            $url_label.html(options.url + options.target );
            $title_label.html(options.title);

            // $dialog.wpdialog('close');
            dialog.close();
            $link_submit.show();
            $vc_link_submit.unbind('click.vcLink');
            $vc_link_submit.remove();
            // remove vc_link hooks for wpLink
            $('#wp-link-cancel').unbind('click.vcLink');
            window.wpLink.textarea = '';
            $('#link-target-checkbox').attr('checked', false);
            return false;
        });
        $('#wp-link-cancel').unbind('click.vcLink').bind('click.vcLink', function(e){
            e.preventDefault();
            dialog.close();
            // remove vc_link hooks for wpLink
            $vc_link_submit.unbind('click.vcLink');
            $vc_link_submit.remove();
            // remove vc_link hooks for wpLink
            $('#wp-link-cancel').unbind('click.vcLink');
            window.wpLink.textarea = '';
        });
    });
    var VcSortedList = function(element, settings) {
        this.el = element;
        this.$el = $(this.el);
        this.$data_field = this.$el.find('.wpb_vc_param_value');
        this.$toolbar = this.$el.find('.vc_sorted-list-toolbar');
        this.$current_control = this.$el.find('.vc_sorted-list-container');
        _.defaults(this.options, {});
        this.init();
    };
    VcSortedList.prototype = {
        constructor: VcSortedList,
        init: function() {
            var that = this;
            _.bindAll(this, 'controlEvent', 'save');
            this.$toolbar.on('change', 'input', this.controlEvent);
            var selected_data = this.$data_field.val().split(',');
            for(var i in selected_data) {
                var control_settings = selected_data[i].split('|'),
                    $control = control_settings.length ? this.$toolbar.find('[data-element=' + decodeURIComponent(control_settings[0]) + ']') : false;
                if($control !== false && $control.is('input')) {
                    $control.prop('checked', true);
                    this.createControl({
                        value: $control.val(),
                        label: $control.parent().text(),
                        sub: $control.data('subcontrol'),
                        sub_value:_.map(control_settings.slice(1), function(item) {return window.decodeURIComponent(item)})
                    });
                }
            }
            this.$current_control.sortable({
                stop: this.save
            }).on('change', 'select', this.save);
        },
        createControl: function(data) {
            var sub_control = '',
                selected_sub_value = _.isUndefined(data.sub_value) ? [] : data.sub_value;
            if(_.isArray(data.sub)) {
                _.each(data.sub, function(sub, index){
                    sub_control += ' <select>';
                    _.each(sub, function(item){
                        sub_control += '<option value="' + item[0] + '"' + (_.isString(selected_sub_value[index]) && selected_sub_value[index]===item[0] ? ' selected="true"' : '') + '>' + item[1] + '</option>';
                    });
                    sub_control += '</select>';
                }, this);

            }
            this.$current_control.append('<li class="vc_control-' + data.value + '" data-name="' + data.value + '">' + data.label + sub_control + '</li>');

        },
        controlEvent: function(e) {
            var $control = $(e.currentTarget);
            if($control.is(':checked')) {
                this.createControl({
                   value: $control.val(),
                   label: $control.parent().text(),
                   sub: $control.data('subcontrol')
                });

            } else {
                this.$current_control.find('.vc_control-' + $control.val()).remove();
            }
            this.save();
        },
        save: function() {
            var value = _.map(this.$current_control.find('[data-name]'), function(element){
                var return_string = encodeURIComponent($(element).data('name'));
                $(element).find('select').each(function(){
                    $sub_control = $(this);
                    if($sub_control.is('select') && $sub_control.val() !== '') {
                        return_string += '|' + encodeURIComponent($sub_control.val());
                    }
                });
                return return_string;
            }).join(',');
            this.$data_field.val(value);
        }
    };
    $.fn.VcSortedList = function(option) {
        return this.each(function () {
            var $this = $(this),
                data = $this.data('vc_sorted_list'),
                options = _.isObject(option) ? option : {};
            if (!data) $this.data('vc_sorted_list', (data = new VcSortedList(this, option)));
            if (typeof option == 'string') data[option]();
        });
    };
    $('.vc_sorted-list').VcSortedList();
    $('.wpb_vc_param_value.dropdown').change(function(){
      var $this = $(this),
          $options = $this.find(':selected'),
          prev_option_class = $this.data('option'),
          option_class = $options.length ? $options.attr('class').replace(/\s/g, '_') : '';
      prev_option_class != undefined && $this.removeClass(prev_option_class);
      option_class != undefined && $this.data('option', option_class) && $this.addClass(option_class);
    });
    if($('#vc_edit-form-tabs').length) {
      $('.wpb-edit-form').addClass('vc_with-tabs');
      $('#vc_edit-form-tabs').tabs();
    }

    /**
     * Google fonts element methods
     */
    var GoogleFonts = Backbone.View.extend({
        preview_el: ".vc_google_fonts_form_field-preview-container > span",
        font_family_dropdown_el: ".vc_google_fonts_form_field-font_family-container > select",
        font_style_dropdown_el: ".vc_google_fonts_form_field-font_style-container > select",
        font_style_dropdown_el_container: ".vc_google_fonts_form_field-font_style-container",
        status_el: ".vc_google_fonts_form_field-status-container > span",
        events: {
                'change .vc_google_fonts_form_field-font_family-container > select': 'fontFamilyDropdownChange',
                'change .vc_google_fonts_form_field-font_style-container > select': 'fontStyleDropdownChange'
        },
        initialize: function(attr) {
            _.bindAll(this,'previewElementInactive', 'previewElementActive','previewElementLoading');
            this.$preview_el = $(this.preview_el,this.$el);
            this.$font_family_dropdown_el = $(this.font_family_dropdown_el,this.$el);
            this.$font_style_dropdown_el = $(this.font_style_dropdown_el,this.$el);
            this.$font_style_dropdown_el_container = $(this.font_style_dropdown_el_container,this.$el);
            this.$status_el = $(this.status_el,this.$el);
            this.fontFamilyDropdownRender();
        },
        render: function() {
            return this;
        },
        previewElementRender: function() {
            this.$preview_el.css( { "font-family":this.font_family, "font-style":this.font_style, "font-weight":this.font_weight } );
            return this;
        },
        previewElementInactive: function() {
            this.$status_el.text(window.i18nLocale.gfonts_loading_google_font_failed||"Loading google font failed.").css('color','#FF0000');
        },
        previewElementActive: function() {
            this.$preview_el.text("Grumpy wizards make toxic brew for the evil Queen and Jack.").css('color','inherit');
            this.fontStyleDropdownRender();
        },
        previewElementLoading: function() {
            this.$preview_el.text(window.i18nLocale.gfonts_loading_google_font||"Loading Font...");
        },
        fontFamilyDropdownRender: function() {
            this.fontFamilyDropdownChange();
            return this;
        },
        fontFamilyDropdownChange: function() {
            var $font_family_selected = this.$font_family_dropdown_el.find(':selected');
            this.font_family_url = $font_family_selected.val();
            this.font_family = $font_family_selected.attr('data[font_family]');
            this.font_types = $font_family_selected.attr('data[font_types]');
            this.$font_style_dropdown_el_container.parent().hide();

            if(this.font_family_url.length>0) {
                WebFont.load({
                    google: {
                        families: [this.font_family_url]
                    },
                    inactive: this.previewElementInactive ,
                    active: this.previewElementActive,
                    loading: this.previewElementLoading
                });
            }
            return this;
        },
        fontStyleDropdownRender: function() {
            var str=this.font_types;
            var str_arr=str.split(',');
            var oel='';
            var default_f_style=this.$font_family_dropdown_el.attr('default[font_style]');
            for( var str_inner in str_arr ) {
                var str_arr_inner=str_arr[str_inner].split(':');
                var sel="";
                if(_.isString(default_f_style) && default_f_style.length>0 && str_arr[str_inner]==default_f_style) {
                    sel='selected="selected"';
                }
                oel=oel+'<option '+sel+' value="'+str_arr[str_inner]+'" data[font_weight]="'+str_arr_inner[1]+'" data[font_style]="'+str_arr_inner[2]+'" class="'+str_arr_inner[2]+'_'+str_arr_inner[1]+'" >'+str_arr_inner[0]+'</option>';

            }
            this.$font_style_dropdown_el.html(oel);
            this.$font_style_dropdown_el_container.parent().show();
            this.fontStyleDropdownChange();
            return this;
        },
        fontStyleDropdownChange: function() {
            var $font_style_selected = this.$font_style_dropdown_el.find(':selected');
            this.font_weight = $font_style_selected.attr('data[font_weight]');
            this.font_style = $font_style_selected.attr('data[font_style]');
            this.previewElementRender();
            return this;
        }
    });

    if($('.wpb_el_type_google_fonts').length) {
        if(typeof WebFont != "undefined") {
            $('.wpb_el_type_google_fonts').each(function(){
               new GoogleFonts({el: this});
            });
        } else {
            $('.wpb_el_type_google_fonts > .edit_form_line').html(window.i18nLocale.gfonts_unable_to_load_google_fonts||"Unable to load Google Fonts");
        }
    }
}(window.jQuery);