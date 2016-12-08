<div id="vc_template-html">
    <?php echo $editor->parseShortcodesString($editor->getTemplateContent()); ?>
    <div data-type="files">
        <?php
            _print_styles();
            print_head_scripts();
            print_late_styles();
            print_footer_scripts();
        ?>
    </div>
</div>
<div id="vc_template-data"><?php echo esc_html_e(json_encode($editor->post_shortcodes)) ?></div>

