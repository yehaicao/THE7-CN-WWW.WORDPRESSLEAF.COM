<?php
/**
 * HTML Tags classes.
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

class DT_Item {
    public static $count = 0;
    
    function __construct() {
        DT_Item::$count++;
    }
    
    function __destruct() {
        DT_Item::$count--;
    }
    
    public $name = '';
    public $id = '';
    public $style = '';
    public $class = '';
    public $data = '';
}

/* checkbox */
class DT_Mcheckbox extends DT_Item {   
    
    public $wrap = '<label>%1$s%2$s</label>';
    public $desc_wrap = '%2$s';
    public $value = '';
    public $checked = false;
    public $desc = '';
    public $data = '';
    
    function __construct( $options = array() ) {
        parent::__construct();
        $this->init( $options );
    }
    
    function __toString() {
        return $this->create();
    }
    
    public function generate_id( $id ) {
        $this->id = parent::$count . '_' . $id;
		$this->desc_wrap = '<label for="%1$s">%2$s</label>';
		$this->wrap = '%1$s%2$s';
    }
    
    public function init( $options ) {
        $def = array(
            'wrap'          => $this->wrap,
            'class'         => $this->class,
            'id'            => $this->id,
            'style'         => $this->style,
            'name'          => $this->name,
            'value'         => $this->value,
            'checked'       => $this->checked,
            'description'   => $this->desc,
            'desc_wrap'     => $this->desc_wrap,
            'data'     		=> $this->data
        );
        $options = wp_parse_args( $options, $def );
        
        $this->wrap = $options['wrap'];
        $this->class = esc_attr($options['class']);
        $this->id = esc_attr($options['id']);
        $this->style = esc_attr($options['style']);
        $this->name = esc_attr($options['name']);
        $this->value = esc_attr($options['value']);
        $this->checked = $options['checked'];
        $this->desc = $options['description'];
        $this->desc_wrap = $options['desc_wrap'];
        $this->data = $options['data'];
    }
    
    public function create() {
        $html = $desc = '';
        $html .= '<input type="checkbox"';
        $html .= sprintf( ' name="%s"', $this->name );
        
        if( $this->id ) {
            $html .= sprintf( ' id="%s"', $this->id );
        }
        
        if( $this->class ) {
            $html .= sprintf( ' class="%s"', $this->class );
        }
        
        if( $this->value ) {
            $html .= sprintf( ' value="%s"', $this->value );
        }
        
        $html .= checked( $this->checked, true, false );
        
        if( $this->style ) {
            $html .= sprintf( ' style="%s"', $this->style );
        }
        
        if( $this->data ) {
            $html .= sprintf( ' %s', $this->data );
        }

        $html .= '/>';
        
        if( $this->desc ) {
            $desc = sprintf( $this->desc_wrap, $this->id, $this->desc, $html );
        }
        
        // wrap this thing
        $html = sprintf( $this->wrap, $html, $desc );
        
        return $html;
    }

}

/* radiobox */
class DT_Mradio extends DT_Item {    
    
	public $wrap = '<label>%1$s%2$s</label>';
    public $desc_wrap = '%2$s';
	public $value = '';
    public $checked = false;
    public $desc = '';
    
    function __construct( $options = array() ) {
        parent::__construct();
        $this->init( $options );
    }
    
    function __toString() {
        return $this->create();
    }
    
    public function generate_id( $id ) {
        $this->id = parent::$count . '_' . $id;
		$this->desc_wrap = '<label for="%1$s">%2$s</label>';
		$this->wrap = '%1$s%2$s';
    }
    
    public function init( $options ) {
        $def = array(
            'wrap'          => $this->wrap,
            'class'         => $this->class,
            'id'            => $this->id,
            'style'         => $this->style,
            'name'          => $this->name,
            'value'         => $this->value,
            'checked'       => $this->checked,
            'description'   => $this->desc,
            'desc_wrap'     => $this->desc_wrap,
			'data'			=> $this->data
        );
        $options = wp_parse_args( $options, $def );
        
        $this->wrap = $options['wrap'];
        $this->class = esc_attr($options['class']);
        $this->id = esc_attr($options['id']);
        $this->style = esc_attr($options['style']);
        $this->name = esc_attr($options['name']);
        $this->value = esc_attr($options['value']);
        $this->checked = $options['checked'];
        $this->desc = $options['description'];
        $this->desc_wrap = $options['desc_wrap'];
        $this->data = $options['data'];
    }
    
    public function create() {
        $html = $desc = '';
        $html .= '<input type="radio"';
        $html .= sprintf( ' name="%s"', $this->name );
        
        if( $this->id ) {
            $html .= sprintf( ' id="%s"', $this->id );
        }
        
        if( $this->class ) {
            $html .= sprintf( ' class="%s"', $this->class );
        }
        
        if( $this->value ) {
            $html .= sprintf( ' value="%s"', $this->value );
        }
        
        $html .= checked( $this->checked, true, false );
        
        if( $this->style ) {
            $html .= sprintf( ' style="%s"', $this->style );
        }
		
		if( $this->data ) {
            $html .= sprintf( ' %s', $this->data );
        }
        
        $html .= '/>';
        
        if( $this->desc ) {
            $desc = sprintf( $this->desc_wrap, $this->id, $this->desc );
        }
        
        // wrap this thing
        $html = sprintf( $this->wrap, $html, $desc );
        
        return $html;
    }

}

/* select */
class DT_Mselect extends DT_Item {    
    
	public $wrap = '<label>%1$s%2$s</label>';
    public $desc_wrap = '%2$s';
	public $options = array();
    public $selected = '';
    public $desc = '';
    
    function __construct( $options = array() ) {
        parent::__construct();
        $this->init( $options );
    }
    
    function __toString() {
        return $this->create();
    }
    
    public function generate_id( $id ) {
        $this->id = parent::$count . '_' . $id;
		$this->desc_wrap = '<label for="%1$s">%2$s</label>';
		$this->wrap = '%1$s%2$s';
    }
    
    public function init( $options ) {
        $def = array(
            'wrap'          => $this->wrap,
            'class'         => $this->class,
            'id'            => $this->id,
            'style'         => $this->style,
            'name'          => $this->name,
            'options'       => $this->options,
            'selected'      => $this->selected,
            'description'   => $this->desc,
            'desc_wrap'     => $this->desc_wrap
        );
        $options = wp_parse_args( $options, $def );
        
        $this->wrap = $options['wrap'];
        $this->class = esc_attr($options['class']);
        $this->id = esc_attr($options['id']);
        $this->style = esc_attr($options['style']);
        $this->name = esc_attr($options['name']);
        $this->options = $options['options'];
        $this->selected = $options['selected'];
        $this->desc = $options['description'];
        $this->desc_wrap = $options['desc_wrap'];
    }
    
    public function create() {
        $html = $desc = '';
        $html .= '<select';
        $html .= sprintf( ' name="%s"', $this->name );
        
        if( $this->id ) {
            $html .= sprintf( ' id="%s"', $this->id );
        }
        
        if( $this->class ) {
            $html .= sprintf( ' class="%s"', $this->class );
        }
        
        if( $this->style ) {
            $html .= sprintf( ' style="%s"', $this->style );
        }
        
        $html .= '>';
        
        if( !empty($this->options) ) {
            foreach( $this->options as $val=>$title ) {
                $html .= sprintf( '<option value="%s"%s>%s</option>',
                    esc_attr($val),
                    selected( $this->selected, $val, false ),
                    $title
                );
            }
        }
        
        $html .= '</select>';
        
        if( $this->desc ) {
            $desc = sprintf( $this->desc_wrap, $this->id, $this->desc );
        }
        
        // wrap this thing
        $html = sprintf( $this->wrap, $html, $desc );
        
        return $html;
    }

}

/* button */
class DT_Mbutton extends DT_Item {
    
	public $wrap = '%1$s';
    public $value = '';
    public $title = '';
    
    function __construct( $options = array() ) {
        parent::__construct();
        $this->init( $options );
    }
    
    function __toString() {
        return $this->create();
    }
    
    public function generate_id( $id ) {
        $this->id = parent::$count . '_' . $id;
    }
    
    public function init( $options ) {
        $def = array(
            'wrap'          => $this->wrap,
            'class'         => $this->class,
            'id'            => $this->id,
            'style'         => $this->style,
            'name'          => $this->name,
            'value'         => $this->value,
            'title'         => $this->title
        );
        $options = wp_parse_args( $options, $def );
        
        $this->wrap = $options['wrap'];
        $this->class = esc_attr($options['class']);
        $this->id = esc_attr($options['id']);
        $this->style = esc_attr($options['style']);
        $this->name = esc_attr($options['name']);
        $this->value = esc_attr($options['value']);
        $this->title = $options['title'];
    }
    
    public function create() {
        $html = $desc = '';
        $html .= '<button type="button"';
        $html .= sprintf( ' name="%s"', $this->name );
        
        if( $this->id ) {
            $html .= sprintf( ' id="%s"', $this->id );
        }
        
        if( $this->class ) {
            $html .= sprintf( ' class="%s"', $this->class );
        }
        
        if( $this->value ) {
            $html .= sprintf( ' value="%s"', $this->value );
        }
                
        if( $this->style ) {
            $html .= sprintf( ' style="%s"', $this->style );
        }
        
        $html .= '>';
        
        if( $this->title ) {
            $html .= $this->title;
        }
        
        $html .= '</button>';
        // wrap this thing
        $html = sprintf( $this->wrap, $html );
        
        return $html;
    }

}

/* text */
class DT_Mtext extends DT_Item {
    
	public $wrap = '<label>%1$s%2$s</label>';
    public $desc_wrap = '%2$s';
    public $value = '';
    public $desc = '';
	public $size = '';
	public $maxlength = '';
    
    function __construct( $options = array() ) {
        parent::__construct();
        $this->init( $options );
    }
    
    function __toString() {
        return $this->create();
    }
    
    public function generate_id( $id ) {
        $this->id = parent::$count . '_' . $id;
		$this->desc_wrap = '<label for="%1$s">%2$s</label>';
		$this->wrap = '%1$s%2$s';
    }
    
    public function init( $options ) {
        $def = array(
            'wrap'          => $this->wrap,
            'class'         => $this->class,
			'size'			=> $this->size,
            'id'            => $this->id,
            'style'         => $this->style,
            'name'          => $this->name,
            'value'         => $this->value,
            'description'   => $this->desc,
            'desc_wrap'     => $this->desc_wrap,
            'maxlength'     => $this->maxlength,
        );
        $options = wp_parse_args( $options, $def );
        
        $this->wrap = (string) $options['wrap'];
        $this->class = esc_attr( $options['class'] );
        $this->id = esc_attr( $options['id'] );
        $this->style = esc_attr( $options['style'] );
        $this->name = esc_attr( $options['name'] );
        $this->value = esc_attr( $options['value'] );
        $this->desc = esc_attr( $options['description'] );
        $this->desc_wrap = (string) $options['desc_wrap'];
        $this->size = absint( $options['size'] );
        $this->maxlength = absint( $options['maxlength'] );
    }
    
    public function create() {
        $html = $desc = '';
        $html .= '<input type="text"';
        $html .= sprintf( ' name="%s"', $this->name );
        
        if( $this->id ) {
            $html .= sprintf( ' id="%s"', $this->id );
        }
        
        if( $this->class ) {
            $html .= sprintf( ' class="%s"', $this->class );
        }
        
        $html .= sprintf( ' value="%s"', $this->value );
                
        if( $this->style ) {
            $html .= sprintf( ' style="%s"', $this->style );
        }
		
		if( $this->size ) {
            $html .= sprintf( ' size="%s"', $this->size );
        }
		
		if( $this->maxlength ) {
            $html .= sprintf( ' maxlength="%s"', $this->maxlength );
        }
        
        $html .= '/>';
        
        if( $this->desc ) {
            $desc = sprintf( $this->desc_wrap, $this->id, $this->desc );
        }
        
        // wrap this thing
        $html = sprintf( $this->wrap, $html, $desc );
        
        return $html;
    }

}

/* textarea */
class DT_Mtextarea extends DT_Item {
    
	public $wrap = '<label>%1$s%2$s</label>';
    public $desc_wrap = '%2$s';
    public $value = '';
    public $desc = '';
    
    function __construct( $options = array() ) {
        parent::__construct();
        $this->init( $options );
    }
    
    function __toString() {
        return $this->create();
    }
    
    public function generate_id( $id ) {
        $this->id = parent::$count . '_' . $id;
		$this->desc_wrap = '<label for="%1$s">%2$s</label>';
		$this->wrap = '%1$s%2$s';
    }
    
    public function init( $options ) {
        $def = array(
            'wrap'          => $this->wrap,
            'class'         => $this->class,
            'id'            => $this->id,
            'style'         => $this->style,
            'name'          => $this->name,
            'value'         => $this->value,
            'description'   => $this->desc,
            'desc_wrap'     => $this->desc_wrap
        );
        $options = wp_parse_args( $options, $def );
        
        $this->wrap = $options['wrap'];
        $this->class = esc_attr($options['class']);
        $this->id = esc_attr($options['id']);
        $this->style = esc_attr($options['style']);
        $this->name = esc_attr($options['name']);
        $this->value = esc_attr($options['value']);
        $this->desc = esc_attr($options['description']);
        $this->desc_wrap = $options['desc_wrap'];
    }
    
    public function create() {
        $html = $desc = '';
        $html .= '<textarea';
        $html .= sprintf( ' name="%s"', $this->name );
        
        if( $this->id ) {
            $html .= sprintf( ' id="%s"', $this->id );
        }
        
        if( $this->class ) {
            $html .= sprintf( ' class="%s"', $this->class );
        }
                
        if( $this->style ) {
            $html .= sprintf( ' style="%s"', $this->style );
        }
        
        $html .= '>';
        
        if( $this->value ) {
            $html .= esc_html($this->value);
        }
        
        $html .= '</textarea>';
        
        if( $this->desc ) {
            $desc = sprintf( $this->desc_wrap, $this->id, $this->desc );
        }
        
        // wrap this thing
        $html = sprintf( $this->wrap, $html, $desc );
        
        return $html;
    }

}

/* link */
class DT_Mlink extends DT_Item {
    public $wrap = '%1$s';
    public $desc_wrap = '%2$s';
    public $href = '#';
    public $desc = '';
	public $rel = '';
	
    function __construct( $options = array() ) {
        parent::__construct();
        $this->init( $options );
    }
    
    function __toString() {
        return $this->create();
    }
    
    public function generate_id( $id ) {
        $this->id = parent::$count . '_' . $id;
    }
    
    public function init( $options ) {
        $def = array(
            'wrap'          => $this->wrap,
            'class'         => $this->class,
            'id'            => $this->id,
            'style'         => $this->style,
            'href'          => $this->href,
            'description'   => $this->desc,
            'desc_wrap'     => $this->desc_wrap,
			'rel'			=> $this->rel
        );
        $options = wp_parse_args( $options, $def );
        
        $this->wrap = $options['wrap'];
        $this->class = esc_attr($options['class']);
        $this->id = esc_attr($options['id']);
        $this->style = esc_attr($options['style']);
        $this->href = esc_url($options['href']);
        $this->desc = esc_attr($options['description']);
        $this->desc_wrap = $options['desc_wrap'];
    }
    
    public function create() {
        $html = $desc = '';
        $html .= '<a';
        
        $html .= sprintf( ' href="%s"', esc_attr($this->href) );
        
        if( $this->id ) {
            $html .= sprintf( ' id="%s"', esc_attr($this->id) );
        }
        
        if( $this->class ) {
            $html .= sprintf( ' class="%s"', esc_attr($this->class) );
        }
                
        if( $this->style ) {
            $html .= sprintf( ' style="%s"', $this->style );
        }
		
		if( $this->rel ) {
            $html .= sprintf( ' rel="%s"', esc_attr($this->rel) );
        }
        
        $html .= '>';
        
        if( $this->desc ) {
            $html .= sprintf( $this->desc_wrap, $this->id, $this->desc );
        }
        
        $html .= '</a>';
        
        // wrap this thing
        $html = sprintf( $this->wrap, $html );
        
        return $html;
    }

}
