function dtWidgetSwitcherListShowHide ( element ) {
	if ( element.length != 1 ) return;

	var container = element.parents('.dt-widget-switcher').next('div.hide-if-js');
	if( 'all' == element.val() ) {
		container.hide();
	}else {
		container.show();
	}
}

function dtAddSortable ( element ) {
	if ( element.length != 1 ) return;

	// add sortable widget
	element.sortable({
        cursor: 'move',
        placeholder: 'dt-widget-sortable-placegolder'
    });

    // sortable remove
	element.parents('.dt-widget-sortable-container').on('click', 'a.dt-widget-sortable-remove', function() {
		jQuery(this).parents('li.ui-state-default').detach();
	} );

	// sortable add
	element.parents('.dt-widget-sortable-container').on('click', 'a.dt-widget-sortable-add', function() {
		var $this = jQuery(this),
			$sortable = jQuery(this).siblings('.dt-widget-sortable'),
			fieldsName = jQuery(this).attr('data-fields-name') || '',
			$fields = $this.parents('.dt-widget-sortable-container').find('li'),
			nextIndex = -1,
			fieldType = $this.attr('data-field-type'),
			fieldHTML = '';

		if ( !fieldsName || !fieldType ) return false;

		$fields.each(function(){
			var currentIndex = parseInt( jQuery(this).attr('data-index') );

			if ( currentIndex > nextIndex ) nextIndex = currentIndex;
		} );

		nextIndex++;

		// common data
		var data = {
			nextIndex : nextIndex,
			fieldsName : fieldsName
		};

		switch( fieldType ) {
			case 'contact-info' : fieldHTML = dtGetContactInfoField( jQuery.extend( data, { title : dtWidgtes.title, content : dtWidgtes.content } ) );
				break;
			case 'accordion' : fieldHTML = dtGetContactInfoField( jQuery.extend( data, { title : dtWidgtes.title, content : dtWidgtes.content } ) );
				break;
			case 'progress-bar' : fieldHTML = dtGetProgressBarField( jQuery.extend( data, { title : dtWidgtes.title, percent : dtWidgtes.percent, showPercent: dtWidgtes.showPercent } ) );
		}

		$sortable.append( fieldHTML );

		if ( 'progress-bar' == fieldType ) {
			dtAddColorpicker( $sortable.find('.dt-widget-colorpicker').not('.wp-color-picker') );		
		}

	} );
}

// add contact info fields
function dtGetContactInfoField( data ) {
	var template = wp.media.template('dt-widget-contact-info-field');

	return template(data);
}

// add progress bar fields
function dtGetProgressBarField( data ) {
	var template = wp.media.template('dt-widget-progress-bars-field');

	return template(data);
}

// colorpicker init
function dtAddColorpicker( element ) {
	if ( element.length < 1 ) return;
	
	element.wpColorPicker({ palettes: false });
}

// add hide/show effect to checkbox list
jQuery(document).ready( function() {

    jQuery('.dt-widget-switcher input').live( 'click', function() {
        if( jQuery(this).attr('name').search('__i__') == -1 ) {
			dtWidgetSwitcherListShowHide( jQuery(this) );
        }
    } );
	
	jQuery('.dt-widget-switcher input:checked').each( function() {
		dtWidgetSwitcherListShowHide( jQuery(this) );
	} );

	jQuery('.dt-widget-sortable').each( function() {
		dtAddSortable( jQuery(this) );
	} );

	jQuery('.dt-widget-colorpicker').each( function() {
		dtAddColorpicker( jQuery(this) );
	} );	

});

// do some stuff on widget save
jQuery(document).ajaxSuccess(function(e, xhr, settings) {
//	var search_str = '%5Bfields%5D=';

	if ( settings.data.search( 'action=save-widget' ) != -1 )
	{
        // do some stuff
		var settingsArray = settings.data.split( '&' ),
			sidebar = '',
			widgetId = '';
		for ( var i = settingsArray.length - 1; i >= 0 ; i-- ) {
			if ( sidebar && widgetId ) { break; }
			
			if ( settingsArray[ i ].search( 'sidebar=' ) != -1 ) {
				sidebar = '#' + settingsArray[ i ].split( '=' )[1] + ' ';
			} else if ( settingsArray[ i ].search( 'widget-id=' ) != -1 ) {
				widgetId = 'div.widget[id$="' + settingsArray[ i ].split( '=' )[1] + '"] ';
			}

		}
		
		dtWidgetSwitcherListShowHide( jQuery( sidebar + widgetId + '.dt-widget-switcher input:checked' ) );
		dtAddSortable( jQuery( sidebar + widgetId + '.dt-widget-sortable' ) );
		dtAddColorpicker( jQuery( sidebar + widgetId + '.dt-widget-colorpicker' ) );
	}
} );

