/* =========================================================
 * lib/panels.js v0.5.0
 * =========================================================
 * Copyright 2014 Wpbakery
 *
 * Visual composer panels & modals for frontend editor
 *
 * ========================================================= */
(function($) {
  if(_.isUndefined(window.vc)) window.vc = {};
  $( document ).ajaxSend(function(e, xhr, req) {
    req && req.data && req.data.match(/vc_inline=true/) && $('#vc_logo').addClass('vc_ajax-loading');
  }).ajaxStop(function(){
      $('#vc_logo').removeClass('vc_ajax-loading');
    });
  vc.active_panel = false;
  vc.closeActivePanel= function(model) {
    if(!this.active_panel) return false;
    if(model && vc.active_panel.model && vc.active_panel.model.get('id') === model.get('id')) {
      this.active_panel.hide();
    } else if( !model ) {
      this.active_panel.hide();
    }
  };
  vc.updateSettingsBadge = function() {
    var value = vc.$custom_css.val();
    if(value.trim() !== '') {
      $('#vc_post-css-badge').show();
    } else {
      $('#vc_post-css-badge').hide();
    }
  }
  /**
   * Modal prototype
   * @type {*}
   */
  vc.ModalView = Backbone.View.extend({
    events: {
      'hidden.bs.modal': 'hide',
      'shown.bs.modal': 'shown'
    },
    initialize: function() {
      _.bindAll(this, 'setSize', 'hide');
    },
    setSize: function() {
      var height = $(window).height() - 150;
      this.$content.css('maxHeight', height);
    },
    render: function() {
      $(window).bind('resize.ModalView', this.setSize);
      this.setSize();
      vc.closeActivePanel();
      this.$el.modal('show');
      return this;
    },
    hide: function() {
      $(window).unbind('resize.ModalView');
    },
    shown: function() {

    }
  });
  vc.element_start_index = 0;
  /**
   * Add element block to page or shortcodes container.
   * @type {*}
   */
  vc.AddElementBlockView = vc.ModalView.extend({
    el: $('#vc_add-element-dialog'),
    prepend: false,
    builder: '',
    events: {
      'click .vc_shortcode-link': 'createElement',
      'keyup #vc_elements_name_filter':'filterElements',
      'hidden.bs.modal': 'hide',
      'show.bs.modal': 'buildFiltering',
      'click .wpb-content-layouts-container [data-filter]':'filterElements',
      'shown.bs.modal': 'shown'
    },
    buildFiltering: function() {
      this.do_render = false;
      var item_selector, tag, not_in,
      item_selector = '.wpb-layout-element-button';
      tag = this.model ? this.model.get('shortcode') : 'vc_column';
      not_in = this._getNotIn(tag);
      $('#vc_elements_name_filter').val('');
      // New vision
      var as_parent = tag && !_.isUndefined(vc.getMapped(tag).as_parent) ? vc.getMapped(tag).as_parent : false;
      if (_.isObject(as_parent)) {
        var parent_selector = [];
        if (_.isString(as_parent.only)) {
          parent_selector.push(_.reduce(as_parent.only.replace(/\s/, '').split(','), function (memo, val) {
            return memo + ( _.isEmpty(memo) ? '' : ',') + '[data-element="' + val.trim() + '"]';
          }, ''));
        }
        if (_.isString(as_parent.except)) {
          parent_selector.push(_.reduce(as_parent.except.replace(/\s/, '').split(','), function (memo, val) {
            return memo  + ':not([data-element="' + val.trim() + '"])';
          }, ''));
        }
        item_selector += parent_selector.join(',');
      } else {
        if(not_in) item_selector = not_in;
      }
      // OLD fashion
      if (tag !== false && tag !== false && !_.isUndefined(vc.getMapped(tag).allowed_container_element)) {
        if (vc.getMapped(tag).allowed_container_element === false) {
          item_selector += ':not([data-is-container=true])';
        } else if (_.isString(vc.getMapped(tag).allowed_container_element)) {
          item_selector += ':not([data-is-container=true]), [data-element=' + vc.getMapped(tag).allowed_container_element + ']';
        }
      }
      this.$buttons.removeClass('vc_visible').addClass('vc_inappropriate');
      $(item_selector, this.$content).removeClass('vc_inappropriate').addClass('vc_visible');
      this.hideEmptyFilters();
    },
    hideEmptyFilters: function() {
      this.$el.find('.vc_filter-content-elements .active').removeClass('active');
      this.$el.find('.vc_filter-content-elements > :first').addClass('active');
      this.$el.find('[data-filter]').each(function () {
        if (!$($(this).data('filter') + '.vc_visible:not(.vc_inappropriate)', this.$content).length) {
          $(this).parent().hide();
        } else {
          $(this).parent().show();
        }
      });
    },
    render: function(model, prepend) {
      var $list, item_selector, tag, not_in;
      this.builder = new vc.ShortcodesBuilder();
      this.prepend = _.isBoolean(prepend) ? prepend : false;
      this.place_after_id = _.isString(prepend) ? prepend : false;
      this.model = _.isObject(model) ? model : false;
      this.$content = this.$el.find('.wpb-elements-list');
      this.$buttons = $('.wpb-layout-element-button', this.$content);
      return vc.AddElementBlockView.__super__.render.call(this);
    },
    hide: function() {
      $(window).unbind('resize.vcAddElementModal');
      if(this.do_render) {
        if(this.show_settings) {
          this.showEditForm();
        }
        this.exit();
      }
    },
    showEditForm: function() {
          vc.edit_element_block_view.render(this.builder.last());
    },
    exit: function() {
        this.builder.render();
    },
    createElement: function(e) {
      this.do_render = true;
      e.preventDefault();
      var $control = $(e.currentTarget),
        tag = $control.data('tag'),
        model;
      if(this.model === false && tag !== 'vc_row') {
        this.builder
          .create({shortcode: 'vc_row'})
          .create({shortcode: 'vc_column', parent_id: this.builder.lastID(), params: {width: '1/1'}});
        this.model = this.builder.last();
      } else if(this.model !== false && tag === 'vc_row') {
        tag += '_inner';
      }
      var params = {shortcode: tag, parent_id: (this.model ? this.model.get('id') : false), params: vc.getDefaults(tag)};
      if(this.prepend) {
        params.order = 0;
        var shortcode_first = vc.shortcodes.findWhere({parent_id: this.model.get('id')});
        if(shortcode_first) params.order = shortcode_first.get('order') -1;
        vc.activity = 'prepend';
      } else if(this.place_after_id) {
        params.place_after_id = this.place_after_id;
      }
      this.builder.create(params);
      if(tag === 'vc_row') {
        this.builder.create({shortcode: 'vc_column', parent_id: this.builder.lastID(), params: {width: '1/1'}});
      } else if(tag === 'vc_row_inner') {
        this.builder.create({shortcode: 'vc_column_inner', parent_id: this.builder.lastID(), params: {width: '1/1'}});
      }
      if(_.isString(vc.getMapped(tag).default_content) && vc.getMapped(tag).default_content.length) {
        var new_data = this.builder.parse({}, vc.getMapped(tag).default_content, this.builder.last().toJSON());
        _.each(new_data, function(object){
          object.default_content = true;
          this.builder.create(object);
        }, this);
      }
      this.show_settings = _.isBoolean(vc.getMapped(tag).show_settings_on_create) && vc.getMapped(tag).show_settings_on_create === false ? false : true;
      this.$el.modal('hide');
    },
    getDefaultParams: function(tag) {
      var params = {};
      _.each(vc.getMapped(tag).params, function(param){
        if(!_.isUndefined(param.value)) params[param.param_name] = param.value;
      });
      return params;
    },
    _getNotIn:_.memoize(function (tag) {
      var selector = _.reduce(vc.map, function (memo, shortcode) {
        var separator = _.isEmpty(memo) ? '' : ',';
        if (_.isObject(shortcode.as_child)) {
          if (_.isString(shortcode.as_child.only)) {
            if (!_.contains(shortcode.as_child.only.replace(/\s/, '').split(','), tag)) {
              memo += separator + '[data-element=' + shortcode.base + ']';
            }
          }
          if (_.isString(shortcode.as_child.except)) {
            if (_.contains(shortcode.as_child.except.replace(/\s/, '').split(','), tag)) {
              memo += separator + '.[data-element=' + shortcode.base + ']';
            }
          }
        } else if (shortcode.as_child === false) {
          memo += separator + '[data-element=' + shortcode.base + ']';
        }
        return memo;
      }, '');
      return '.wpb-layout-element-button:not(' + selector + ')';
    }),
    filterElements:function (e) {
      e.stopPropagation();
      e.preventDefault();
      var $control = $(e.currentTarget),
        filter = '.wpb-layout-element-button',
        name_filter = $('#vc_elements_name_filter').val();
      if ($control.is('[data-filter]')) {
        $('.wpb-content-layouts-container .isotope-filter .active', this.$content).removeClass('active');
        $control.parent().addClass('active');
        filter += $control.data('filter');
        $('#vc_elements_name_filter').val('');
      } else if (name_filter.length > 0) {
        filter += ":containsi('" + name_filter + "')";
        $('.wpb-content-layouts-container .isotope-filter .active', this.$content).removeClass('active');
      } else if(name_filter.length == 0) {
        $('.wpb-content-layouts-container .isotope-filter [data-filter="*"]').parent().addClass('active');
      }
      $('.vc_visible', this.$content).removeClass('vc_visible');
      $(filter, this.$content).addClass('vc_visible');
    },
    shown: function() {
      if(!vc.is_mobile) $('#vc_elements_name_filter').focus();
    }
  });
  /**
   * Add element to admin
   * @type {*}
   */
  vc.AddElementBlockViewBackendEditor = vc.AddElementBlockView.extend({
    render: function(model, prepend) {
      var $list, item_selector, tag, not_in;
      this.prepend = _.isBoolean(prepend) ? prepend : false;
      this.place_after_id = _.isString(prepend) ? prepend : false;
      this.model = _.isObject(model) ? model : false;
      this.$content = this.$el.find('.wpb-elements-list');
      this.$buttons = $('.wpb-layout-element-button', this.$content);
      return vc.AddElementBlockView.__super__.render.call(this);
    },
    createElement:function (e) {
      var model, column, row;
      _.isObject(e) && e.preventDefault();
      this.do_render = true;
      var tag = $(e.currentTarget).data('tag');
      if (this.model === false) {
        row = vc.shortcodes.create({shortcode:'vc_row'});
        column = vc.shortcodes.create({shortcode:'vc_column', params:{width:'1/1'}, parent_id:row.id, root_id:row.id });
        if (tag != 'vc_row') {
          model = vc.shortcodes.create({
            shortcode: tag,
            parent_id:column.id,
            params:vc.getDefaults(tag),
            root_id:row.id
          });
        } else {
          model = row;
        }
      } else {
        if (tag == 'vc_row') {
          row =  vc.shortcodes.create({
            shortcode:'vc_row_inner',
            parent_id:this.model.id,
            order:(this.prepend ? this.getFirstPositionIndex() : vc.shortcodes.getNextOrder())
          });
          model = vc.shortcodes.create({shortcode:'vc_column_inner', params:{width:'1/1'}, parent_id:row.id, root_id:row.id });
        } else {
          model = vc.shortcodes.create({
            shortcode: tag,
            parent_id:this.model.id,
            order:(this.prepend ? this.getFirstPositionIndex() : vc.shortcodes.getNextOrder()),
            params:vc.getDefaults(tag),
            root_id:this.model.get('root_id')
          });
        }
      }
      this.show_settings = _.isBoolean(vc.getMapped(tag).show_settings_on_create) && vc.getMapped(tag).show_settings_on_create === false ? false : true;
      this.model = model;
      this.$el.modal('hide');
    },
    showEditForm: function() {
      vc.edit_element_block_view.render(this.model);
    },
    exit: function() {
    },
    getFirstPositionIndex:function () {
      vc.element_start_index -= 1;
      return vc.element_start_index;
    }
  });
  /**
   * Panel prototype
   */
  vc.PanelView = Backbone.View.extend({
    draggable: false,
    events: {
      'click [data-dismiss=panel]': 'hide',
      'mouseover [data-transparent=panel]': 'addOpacity',
      'mouseout [data-transparent=panel]': 'removeOpacity'
    },
    initialize: function() {
      _.bindAll(this, 'setSize');
    },
    addOpacity: function() {
      this.$el.addClass('vc_panel-opacity');
    },
    removeOpacity: function(){
      this.$el.removeClass('vc_panel-opacity');
    },
    message_box_timeout: false,
    init: function(){},
    show: function() {
      vc.closeActivePanel();
      this.init();
      vc.active_panel = this;
      $(window).bind('resize.vcPropertyPanel', this.setSize);
      this.setSize();
      this.$el.show();
      if(!this.draggable) {
        this.$el.draggable({iframeFix: true, handle: '.vc_panel-heading'});
        this.draggable = true;
      }
    },
    hide: function(e) {
      e && e.preventDefault();
      $(window).unbind('resize.vcPropertyPanel');
      vc.active_panel = false;
      this.$el.hide();
    },
    content: function() {
      return this.$el.find('.panel-body');
    },
    setSize: function() {
      //var height = $(window).height() - 200; // @fix ACE editor
      //this.content().css('maxHeight', height);
    },
    showMessage: function(text, type) {
      this.message_box_timeout && this.$el.find('.vc_panel-message').remove() && window.clearTimeout(this.message_box_timeout);
      this.message_box_timeout = false;
      var $message_box = $('<div class="vc_panel-message type-' + type +'"></div>').appendTo(this.$el.find('.vc_panel-body'));
      $message_box.text(text).fadeIn();
      this.message_box_timeout = window.setTimeout(function(){
        $message_box.remove();
      }, 6000);
    },
    minimizeBody: function(e) {
      e && e.preventDefault && e.preventDefault();
      this.$el.find('.panel-body,.panel-footer').slideToggle();
    },
    isVisible: function() {
      return this.$el.is(':visible');
    }
  });
  /**
   * Shortcode settings panel
   * @type {*}
   */
  vc.EditElementPanelView = vc.PanelView.extend({
    el: $('#vc_properties-panel'),
    $content: false,
    dependent_elements:{},
    mapped_params:{},
    draggable: false,
    events: {
      'click [data-save=true]': 'save',
      'click [data-dismiss=panel]': 'hide',
      'mouseover [data-transparent=panel]': 'addOpacity',
      'mouseout [data-transparent=panel]': 'removeOpacity'
    },
    initialize: function() {
      _.bindAll(this, 'setSize');
    },
    setSize: function() {
      var height = $(window).height() - 190;
      this.$content.css('maxHeight', height);
    },
    render: function(model, not_request_template) {
      this.model = model;
      var tag = this.model.get('shortcode'),
        params = this.model.setting('params') || [];
      _.bindAll(this, 'hookDependent');
      this.mapped_params = {};
      this.dependent_elements = {};
      _.each(params, function (param) {
        this.mapped_params[param.param_name] = param;
      }, this);
      this.$content = not_request_template ? this.$el : this.$el.find('.vc_properties-list').removeClass('vc_with-tabs');
      this.$content.html('<span class="vc_spinner"></span>');
      this.show();
      !not_request_template &&
      $.ajax({
        type:'POST',
        url:window.ajaxurl,
        data:{
          action:'wpb_show_edit_form',
          element:this.model.get('shortcode'),
          post_id: $('#post_ID').val(),
          shortcode: vc.builder.toString(this.model)
        },
        context:this
      }).done(function (data) {
          this.$content.html(data);
          this.$content.scrollTop(0);
          this.init();
        });
      this.setTitle();
      return this;
    },
    init: function() {
      this.initDependency();
      $('.wpb-edit-form .textarea_html').each(function(){
          window.init_textarea_html($(this));
      });
    },
    initDependency:function () {
      // setup dependencies
      _.each(this.mapped_params, function (param) {
        if (_.isObject(param) && _.isObject(param.dependency) && _.isString(param.dependency.element)) {
          var $masters = $('[name=' + param.dependency.element + '].wpb_vc_param_value', this.$content),
            $slave = $('[name= ' + param.param_name + '].wpb_vc_param_value', this.$content);
          _.each($masters, function (master) {
            var $master = $(master),
              rules = param.dependency;
            if (!_.isArray(this.dependent_elements[$master.attr('name')])) this.dependent_elements[$master.attr('name')] = [];
            this.dependent_elements[$master.attr('name')].push($slave);
            $master.bind('keyup change', this.hookDependent);
            this.hookDependent({currentTarget:$master}, [$slave]);
            if (_.isString(rules.callback)) {
              window[rules.callback].call(this);
            }
          }, this);
        }
      }, this);
    },
    hookDependent: function (e, dependent_elements) {
      var $master = $(e.currentTarget),
          $master_container = $master.closest('.vc_column'),
          master_value,
          is_empty,
          dependent_elements = _.isArray(dependent_elements) ? dependent_elements : this.dependent_elements[$master.attr('name')],
          master_value = $master.is(':checkbox') ? _.map(this.$content.find('[name=' + $(e.currentTarget).attr('name') + '].wpb_vc_param_value:checked'),
          function (element) {
            return $(element).val();
          })
          : $master.val();
      is_empty = $master.is(':checkbox') ? !this.$content.find('[name=' + $master.attr('name') + '].wpb_vc_param_value:checked').length
        : !master_value.length;
      if($master_container.hasClass('vc_dependent-hidden')) {
        _.each(dependent_elements, function($element) {
          $element.closest('.vc_column').addClass('vc_dependent-hidden');
        });
      } else {
        _.each(dependent_elements, function ($element) {
          var param_name = $element.attr('name'),
            rules = _.isObject(this.mapped_params[param_name]) && _.isObject(this.mapped_params[param_name].dependency) ? this.mapped_params[param_name].dependency : {},
            $param_block = $element.closest('.vc_column');
          if (_.isBoolean(rules.not_empty) && rules.not_empty === true && !is_empty) { // Check is not empty show dependent Element.
            $param_block.removeClass('vc_dependent-hidden');
          } else if (_.isBoolean(rules.is_empty) && rules.is_empty === true && is_empty) {
            $param_block.removeClass('vc_dependent-hidden');
          } else if (_.intersection((_.isArray(rules.value) ? rules.value : [rules.value]), (_.isArray(master_value) ? master_value : [master_value])).length) {
            $param_block.removeClass('vc_dependent-hidden');
          } else {
            $param_block.addClass('vc_dependent-hidden');
          }
          $element.trigger('change');
        }, this);
      }
      return this;
    },
    setActive: function() {
      this.$el.prev().addClass('active');
    },
    window: function() {
      return window;
    },
    getParams: function() {
      var attributes_settings = this.mapped_params;
      this.params = _.extend({}, this.model.get('params'));
      _.each(attributes_settings, function (param) {
        var value = vc.atts.parseFrame.call(this, param);
        if(_.isNull(value) || value === '') {
          delete this.params[param.param_name];
        } else {
          this.params[param.param_name] =  value;
        }
      }, this);
      _.each(vc.edit_form_callbacks, function(callback){
        callback.call(this);
      }, this);
      return this.params;
    },
    content: function() {
      return this.$content;
    },
    save: function(){
      this.model.save({params: this.getParams()});
      this.showMessage(window.sprintf(window.i18nLocale.inline_element_saved, vc.getMapped(this.model.get('shortcode')).name), 'success');
      !vc.frame_window && this.hide();
    },
    show: function() {
      if(this.$el.is(':hidden')) vc.closeActivePanel();
      vc.active_panel = this;
      $(window).bind('resize.vcPropertyPanel', this.setSize);
      this.setSize();
      this.$el.show();
      if(!this.draggable) {
        this.$el.draggable({iframeFix: true, handle: '.vc_panel-heading'});
        this.draggable = true;
      }
    },
    hide: function(e) {
      e && e.preventDefault();
      vc.active_panel = false;
      $(window).unbind('resize.vcPropertyPanel');
      this._killEditor();
      this.$el.hide();
      this.$content.html('');
    },
    setTitle: function() {
      this.$el.find('.vc_panel-title').text(vc.getMapped(this.model.get('shortcode')).name + ' settings');
      return this;
    },
    _killEditor:function () {
      if(!_.isUndefined(window.tinyMCE)) {
        $('textarea.textarea_html', this.$el).each(function () {
          var id = $(this).attr('id');
          if(tinymce.majorVersion === "4") {
            window.tinyMCE.execCommand('mceRemoveEditor', true, id);
          } else {
            window.tinyMCE.execCommand("mceRemoveControl", true, id);
          }
        });
      }
      // TODO: move with new version of params types.
      jQuery( 'body' ).off( 'click.wpcolorpicker' );
    }
  });
  /**
   * Post custom css
   * @type {Number}
   */
  vc.PostSettingsPanelView = vc.PanelView.extend({
    events: {
      'click [data-save=true]': 'save',
      'click [data-dismiss=panel]': 'hide',
      'mouseover [data-transparent=panel]': 'addOpacity',
      'mouseout [data-transparent=panel]': 'removeOpacity'
    },
    saved_css_data: '',
    saved_title: '',
    $title: false,
    editor: false,
    post_settings_editor: false,
    initialize: function() {
      vc.$custom_css = $('#vc_post-custom-css');
      this.saved_css_data = vc.$custom_css.val();
      this.saved_title = vc.title;
      this.editor = new Vc_postSettingsEditor();
    },
    render: function() {
      this.$title = this.$el.find('#vc_page-title-field');
      this.$title.val(vc.title);
      !vc.$title.length && $('#vc_settings-title-container').hide();
      this.setEditor();
      return this;
    },
    setEditor: function() {
        this.editor.setEditor(vc.$custom_css.val());
    },
    setSize: function() {
      this.editor.setSize();
    },
    save: function() {
      if(this.$title) {
        var title = this.$title.val();
        if(title != vc.title) {
          vc.frame.setTitle(title);
        }
      }
      this.setAlertOnDataChange();
      vc.$custom_css.val(this.editor.getValue());
      vc.frame_window && vc.frame_window.vc_iframe.loadCustomCss(vc.$custom_css.val());
      vc.updateSettingsBadge();
      this.showMessage(window.i18nLocale.css_updated, 'success');
    },
    /**
     * Set alert if custom css data differs from saved data.
     */
    setAlertOnDataChange: function() {
      if( this.saved_css_data !== this.editor.getValue() ) {
        vc.setDataChanged();
      } else if( this.$title && this.saved_title !== this.$title.val() ) {
        vc.setDataChanged();
      }
    }
  });
  vc.PostSettingsPanelViewBackendEditor = vc.PostSettingsPanelView.extend({
    render: function() {
      this.setEditor();
      return this;
    },
    /**
     * Set alert if custom css data differs from saved data.
     */
    setAlertOnDataChange: function() {
      if(vc.saved_custom_css !== this.editor.getValue() && window.tinymce) {
	    window.switchEditors.go('content', 'tmce');
        window.setTimeout(function(){window.tinymce.get('content').isNotDirty = false;},1000);
      }
    },
    save: function() {
        vc.PostSettingsPanelViewBackendEditor.__super__.save.call(this);
        this.hide();
    }
  });

  /**
   * Templates editor
   * @type {*}
   */
  vc.TemplatesEditorPanelView = vc.PanelView.extend({
    events: {
      'click [data-dismiss=panel]': 'hide',
      'mouseover [data-transparent=panel]': 'addOpacity',
      'mouseout [data-transparent=panel]': 'removeOpacity',
      'click .wpb_remove_template':'removeTemplate',
      'click [data-template_id]':'loadTemplate',
      'click [data-template_name]':'loadDefaultTemplate',
      'click #vc_template-save':'saveTemplate'
    },
    render: function() {
      this.$name = $('#vc_template-name');
      this.$list = $('#vc_template-list');
      $("#vc_tabs-templates").tabs();
      return this;
    },
    /**
     * Remove template from server database.
     * @param e - Event object
     */
    removeTemplate:function (e) {
      e && e.preventDefault();
      var $button = $(e.currentTarget);
      var template_name = $button.closest('.wpb_template_li').find('a').text();
      var answer = confirm(window.i18nLocale.confirm_deleting_template.replace('{template_name}', template_name));
      if (answer) {
        // this.reloadTemplateList(data);
        /*$.post(window.ajaxurl, {
          action: 'wpb_delete_template',
          template_id: $button.attr('rel'),
          vc_inline: true
        });
        $button.closest('.wpb_template_li').remove();*/
          $button.closest('.wpb_template_li').remove();
          this.$list.html(window.i18nLocale.loading);
          $.ajax({
              type:'POST',
              url:window.ajaxurl,
              data:{
                  action:'wpb_delete_template',
                  template_id:$button.attr('rel'),
                  vc_inline: true
              },
              context: this
          }).done(function(html){
              this.$list.html(html);
          });
      }
    },
    /**
     * Load saved template from server.
     * @param e - Event object
     */
    loadTemplate:function (e) {
      e && e.preventDefault();
      var $button = $(e.currentTarget);
      $.ajax({
        type:'POST',
        url:window.ajaxurl,
        data:{
          action: 'vc_frontend_template',
          template_id:$button.data('template_id'),
          vc_inline: true
        },
        context: this
      }).done(function (html) {
          var template, data;
          _.each($(html), function(element){
            if(element.id === "vc_template-data") {
              try {data = JSON.parse(element.innerHTML) } catch(e) {};
            }
            if(element.id === "vc_template-html") {
              template = element.innerHTML;
            }
          });
          template && data && vc.builder.buildFromTemplate(template, data);
          this.showMessage(window.i18nLocale.template_added, 'success');
          /*
           _.each(vc.filters.templates, function (callback) {
           shortcodes = callback(shortcodes);
           });
           */
          //vc.storage.append(shortcodes);
          //Shortcodes.fetch({reset: true});
        });
    },
    /**
     * Load saved template from server.
     * @param e - Event object
     */
    loadDefaultTemplate:function (e) {
      e && e.preventDefault();
      var $button = $(e.currentTarget);
      $.ajax({
        type:'POST',
        url:window.ajaxurl,
        data:{
          action:'vc_frontend_default_template',
          template_name:$button.data('template_name'),
          vc_inline: true
        },
        context: this
      }).done(function (html) {
          var template, data;
          _.each($(html), function(element){
            if(element.id === "vc_template-data") {
              try {data = JSON.parse(element.innerHTML) } catch(e) {};
            }
            if(element.id === "vc_template-html") {
              template = element.innerHTML;
            }
          });
          template && data && vc.builder.buildFromTemplate(template, data);
          this.showMessage(window.i18nLocale.template_added, 'success');
          /*
           _.each(vc.filters.templates, function (callback) {
           shortcodes = callback(shortcodes);
           });
           */
          //vc.storage.append(shortcodes);
          //Shortcodes.fetch({reset: true});
        });
    },
    /**
     * Save current shortcode design as template with title.
     * @param e - Event object
     */
    saveTemplate:function (e) {
      e.preventDefault();
      var name = this.$name.val(),
        data, shortcodes;
      if (_.isString(name) && name.length) {
        shortcodes = this.getPostContent();
        if(!shortcodes.trim().length) {
          this.showMessage(window.i18nLocale.template_is_empty, 'error');
          return false;
        }
        data = {
          action:'wpb_save_template',
          template:shortcodes,
          template_name:name,
          frontend:true,
          vc_inline: true
        };
        this.$name.val('');
        this.showMessage(window.i18nLocale.template_save, 'success');
        this.reloadTemplateList(data);
      } else {
        this.showMessage(window.i18nLocale.please_enter_templates_name, 'error');
      }
    },
    reloadTemplateList:function (data) {
      this.$list.html(window.i18nLocale.loading).load(window.ajaxurl, data);
    },
    getPostContent: function() {
      return vc.builder.getContent();
    }
  });
  vc.TemplatesEditorPanelViewBackendEditor = vc.TemplatesEditorPanelView.extend({
    /**
     * Load saved template from server.
     * @param e - Event object
     */
    loadTemplate:function (e) {
      e.preventDefault();
      var $button = $(e.currentTarget);
      $.ajax({
        type:'POST',
        url:window.ajaxurl,
        data:{
          action:'vc_backend_template',
          template_id:$button.attr('data-template_id'),
          vc_inline: true
        },
        context: this
      }).done(function (shortcodes) {
          _.each(vc.filters.templates, function (callback) {
            shortcodes = callback(shortcodes);
          });
          vc.storage.append(shortcodes);
          vc.shortcodes.fetch({reset: true});
          this.showMessage(window.i18nLocale.template_added, 'success');
        });
    },
    /**
     * Load default template from server.
     * @param e - Event object
     */
    loadDefaultTemplate:function (e) {
      e.preventDefault();
      var $button = $(e.currentTarget);
      $.ajax({
        type:'POST',
        url:window.ajaxurl,
        data:{
          action:'vc_backend_default_template',
          template_name:$button.attr('data-template_name'),
          vc_inline: true
        },
        context: this
      }).done(function (shortcodes) {
          _.each(vc.filters.templates, function (callback) {
            shortcodes = callback(shortcodes);
          });
          vc.storage.append(shortcodes);
          vc.shortcodes.fetch({reset: true});
          this.showMessage(window.i18nLocale.template_added, 'success');
        });
    },
    getPostContent: function() {
      return vc.storage.getContent();
    }
  });
  vc.RowLayoutEditorPanelView = vc.PanelView.extend({
    events: {
      'click [data-dismiss=panel]': 'hide',
      'mouseover [data-transparent=panel]': 'addOpacity',
      'mouseout [data-transparent=panel]': 'removeOpacity',
      'click .vc_layout-btn': 'setLayout',
      'click #vc_row-layout-update': 'updateFromInput'
    },
    _builder: false,
    render: function(model) {
      this.$input = $('#vc_row-layout');
      if(model) this.model = model;
      this.addCurrentLayout();
      vc.column_trig_changes = true;
      return this;
    },
    builder: function() {
      if(!this._builder) this._builder =  new vc.ShortcodesBuilder();
      return this._builder;
    },
    hide: function(e) {
      e && e.preventDefault();
      vc.active_panel = false;
      this.$el.hide();
      vc.column_trig_changes = false;
    },
    addCurrentLayout: function() {
      vc.shortcodes.sort();
      var string = _.map(vc.shortcodes.where({parent_id: this.model.get('id')}), function(model) {
        var width = model.getParam('width');
        return !width ? '1/1' : width; // memo + (memo!='' ? ' + ' : '') + model.getParam('width') || '1/1';
      }, '', this).join(' + ');
      this.$input.val(string);
    },
    isBuildComplete: function() {
      return this.builder().isBuildComplete();
    },
    setLayout: function(e) {
      e && e.preventDefault();
      if (!this.isBuildComplete()) return false;
      var $control = $(e.currentTarget),
        layout = $control.attr('data-cells'),
        columns = this.model.view.convertRowColumns(layout, this.builder());
      this.$input.val(columns.join(' + '));
    },
    updateFromInput: function(e) {
      e && e.preventDefault();
      if (!this.isBuildComplete()) return false;
      var layout,
        cells = this.$input.val();
      if((layout = this.validateCellsList(cells))!==false) {
        this.model.view.convertRowColumns(layout, this.builder());
      } else {
        window.alert(window.i18nLocale.wrong_cells_layout);
      }
    },
    validateCellsList: function(cells) {
      var return_cells = [],
        split = cells.replace(/\s/g, '').split('+'),
        b, num, denom;
      var sum = _.reduce(_.map(split, function(c){
        if(c.match(/^[vc\_]{0,1}span\d{1,2}$/)) {
          var converted_c = vc_convert_column_span_size(c);
          if(converted_c===false) return 1000;
          b = converted_c.split(/\//);
          return_cells.push(b[0] + '' + b[1]);
          return 12*parseInt(b[0], 10)/parseInt(b[1], 10);
        } else if(c.match(/^[1-9]|1[0-2]\/[1-9]|1[0-2]$/)) {
          b = c.split(/\//);
          num = parseInt(b[0], 10);
          denom = parseInt(b[1], 10);
          if(12%denom!==0 || num > denom) return 1000;
          return_cells.push(num  + '' + b[1]);
          return 12*num/denom;
        }
        return 1000;

      }), function(num, memo) {
        memo = memo + num;
        return memo;
      }, 0);
      if(sum >= 1000) return false;
      return return_cells.join('_');
    }
  });
  vc.RowLayoutEditorPanelViewBackend = vc.RowLayoutEditorPanelView.extend({
    builder: function() {
      if(!this.builder) this.builder =  vc.storage;
      return this.builder;
    },
    isBuildComplete: function() {
      return true;
    },
    setLayout: function(e) {
      e && e.preventDefault();
      var $control = $(e.currentTarget),
        layout = $control.attr('data-cells'),
        columns = this.model.view.convertRowColumns(layout);
      this.$input.val(columns.join(' + '));
    }
  });
})(window.jQuery);
