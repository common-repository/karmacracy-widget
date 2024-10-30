<?php
//KARMACRACY WIDGET EXTENSION (called from karmacracy-insert-link inside wp_karmacracy_wdgt::output_settings()

//UPDATE
if (isset($_POST["widgetupdate"])) {
	check_admin_referer('wp_karmacracy_wdgt_save_widget_settings');
	$options = array();
	$options['widget_width'] = preg_replace("/[^0-9]/","",sanitize_text_field($_REQUEST['size']));
	$cpars=array("color1","color2","color3","color4","color5","color6","color7","color8","color9");
	foreach ($cpars as $cp) {
		$options["widget_".$cp] = preg_replace("/[^0-9a-f]/","",sanitize_text_field(strtolower($_REQUEST[$cp])));
	}
	$options['widget_sc'] = isset($_REQUEST["sc"])?true:false;
	$options['widget_rb'] = isset($_REQUEST["rb"])?true:false;
	$options['widget_np'] = isset($_REQUEST["np"])?true:false;
	$options['widget_location'] = sanitize_text_field($_REQUEST['location']);
    $options['widget_button'] = isset($_REQUEST["button"]) ? $_REQUEST["button"] : 'widget';
    $options['widget_button_display'] = isset($_REQUEST["button_display"]) ? $_REQUEST["button_display"] : 'over';
    $options['widget_medio_id'] = isset($_REQUEST["medio_id"]) ? $_REQUEST["medio_id"] : '';

	if (@$_REQUEST["widget_active"]=="1") {
		$options['widget_active'] = true;

	} else {
		$options['widget_active'] = false;
	}
	update_option($this->get_plugin_info('slug')."-widget", $options);
	?>
<div class='updated'>
	<p>
		<strong><?php _e('Updated widget settings', $this->get_plugin_info('locale')); ?> </strong>
	</p>
</div>
	<?php
}

// GET OPTIONS
$options=get_option($this->get_plugin_info('slug')."-widget");

?>
<h2><?php _e("WP Karmacracy Widget Settings", $this->get_plugin_info('locale')); ?></h2>
<form method="post" action="<?php echo esc_attr($_SERVER["REQUEST_URI"]); ?>">
    <?php wp_nonce_field('wp_karmacracy_wdgt_save_widget_settings') ?>
    <table class="form-table">
        <tbody>
            <tr valign='top'>
                <th scope="row" colspan="2">
                	<input type="checkbox" id="widget_active" name="widget_active" onclick="checkWidget(jQuery(this));" value="1" <?php echo (($options["widget_active"])?("checked='checked'"):"")?>>
                	<label for='widget_active'> <?php _e('Show karmacracy widget in the blog', $this->get_plugin_info('locale')); ?></label>
                </th>
            </tr>
        </tbody>
    </table>

<?php
$WIDGET_CODE_TEMPLATE="<div class=\"kcy_karmacracy_widget_h_ID\"></div><script defer=\"defer\" src=\"#KCYJSURL#\"></script>";
$WVERSION=self::WIDGET_VERSION;
?>
<script type="text/javascript" src="<?php echo plugins_url('js/jquery.colorpicker.js', __FILE__)?>"></script>
<link href="<?php echo plugins_url('css/colorpicker.css', __FILE__)?>" media="screen, projection" rel="stylesheet" type="text/css" />
<link href="<?php echo plugins_url('css/karmacracy-widget.css', __FILE__)?>" media="screen, projection" rel="stylesheet" type="text/css" />
<script type="text/javascript">
//<![CDATA[
function checkWidget(t) {
	if (t.is(":checked")) {
		jQuery('#wchooser').slideDown();
	} else {
		jQuery('#wchooser').slideUp();
	}
}
function recalc() {
    var type = jQuery("input:radio[name=button]:checked").val();
    jQuery('#atw').html('<div class="kcy_karmacracy_widget_h_ID"></div>');
    var kcyJsUrl="http://rodney.karmacracy.com/widget-<?php echo $WVERSION?>/?id=ID";
    if(type!=='button') {
        jQuery('.button-stuff').hide();
        jQuery('.widget-stuff').show();
        kcyJsUrl+="&type="+(jQuery('typeV').attr('checked')?"v":"h");
        kcyJsUrl+="&width="+jQuery('#size').val();
        kcyJsUrl+="&sc="+(jQuery('#sc').attr('checked')?"1":"0");
        kcyJsUrl+="&rb="+(jQuery('#rb').attr('checked')?"1":"0");
        kcyJsUrl+="&np="+(jQuery('#np').attr('checked')?"1":"0");
        kcyJsUrl+="&c1="+jQuery('#color1').val();
        kcyJsUrl+="&c2="+jQuery('#color2').val();
        kcyJsUrl+="&c3="+jQuery('#color3').val();
        kcyJsUrl+="&c4="+jQuery('#color4').val();
        kcyJsUrl+="&c5="+jQuery('#color5').val();
        kcyJsUrl+="&c6="+jQuery('#color6').val();
        kcyJsUrl+="&c7="+jQuery('#color7').val();
        kcyJsUrl+="&c8="+jQuery('#color8').val();
        kcyJsUrl+="&c9="+jQuery('#color9').val();
    } else {
        jQuery('.button-stuff').show();
        jQuery('.widget-stuff').hide();
        kcyJsUrl += '&button=1';
        kcyJsUrl += '&display='+ jQuery("input:radio[name=button_display]:checked").val();;
        kcyJsUrl += '&show-tooltip=1';
    }
    jQuery('#codehtml').val(new String('<?php echo str_replace("<","'+'<'+'",$WIDGET_CODE_TEMPLATE)?>').replace(/\#KCYJSURL\#/g,kcyJsUrl));

    kcyJsUrl+="&url=http://karmacracy.com";

    var s=document.createElement('script');
    s.src=kcyJsUrl;
    document.getElementsByTagName('head')[0].appendChild(s);

}

jQuery(document).ready(function() {
	 jQuery('#color1,#color2,#color3,#color4,#color5,#color6,#color7,#color8,#color9').ColorPicker( {
		 onSubmit: function(cp,hex,rgb,el) { jQuery(el).val(hex); jQuery('#show-'+(jQuery(el).attr('id').replace('color',''))).css('backgroundColor','#'+hex); jQuery(el).ColorPickerHide(); recalc(); },
		 onChange: function(cp,hex,rgb) { jQuery(jQuery(this).data('colorpicker').el).val(hex);  jQuery('#show-'+jQuery(this).data('colorpicker').el.id.replace('color','')).css('backgroundColor','#'+hex); }
	 });
	 jQuery('#colorselectors b').click(function () {
		 	var md=jQuery(this).attr('id').replace('show-','');

		 	jQuery('#color'+md).click();
	 });
	 recalc();
});

//]]>
</script>
<style type="text/css">.kcy-container li { margin-bottom:0px !important; }</style>

<div id="wchooser" style="display:<?php echo (($options["widget_active"])?"block":"none")?>">
   	<div class="explain" >                   		
		<div class="cw wrapper">
			<h4>WARNING: <?php echo _e("Some notes about styling")?></h4>
			<p><?php echo _e("Some styles of this widget may collide with those from your blog's theme. We hope they are not many, but if you see any problem that we should know, please, <a href=\"mailto:widget@karmacracy.com?subject=help+styling+widget+in+my+blog\">let us know!</a>")?></p>
		</div>

        <div class="cw wrapper">
            <h4><b>0. </b><?php echo _e("Type")?></h4>
            <div class="cw wrapper">
                <ul>
                    <li><input onclick="recalc()" type="radio" id="widget" value="widget" name="button" <?php echo (($options["widget_button"]!='button')?("checked='checked'"):"")?>> <label for="widget"><?php echo _e("Widget")?></label></li>
                    <li><input onclick="recalc()" type="radio" id="button" value="button" name="button" <?php echo (($options["widget_button"]=='button')?("checked='checked'"):"")?>> <label for="button"><?php echo _e("Button")?></label></li>                    
                </ul>
            </div>
        </div>


        <div class='widget-stuff' <?php echo (($options["widget_button"]=='button')?("style='display:none;'"):"")?>>
        	<div class="cw wrapper">
        		<h4><b>1. </b><?php echo _e("Size")?></h4>
        		<p><?php echo _e("Set a size for your widget, so it perfectly fits in your webpages")?></p>
        		<div class="cw-size wrapper">
                    <ul>
                        <li><input class="rounded5" onblur="recalc()" type="text" id="size" value="<?php echo (isset($options["widget_width"])?$options["widget_width"]:"700")?>" name="size"> <label for="size"><?php echo _e("pixels")?></label></li>
                    </ul>
                </div>
        	</div>
        </div>

        <div class='button-stuff' <?php echo (($options["widget_button"]!='button')?("style='display:none;'"):"")?>>
            <h4><b>1. </b><?php echo _e("Display")?></h4>
            <div class="cw-type wrapper">
                    <div class='cw-button-expamples'>
                        <div class='cw-button-expample'>
                            <div class='cw-button-expample-box'>
                                <div class='kbeo'>
                                    <div class="kcy_karmacracy_widget_h_ID2" style='display:inline-block'></div><script defer="defer" src="http://rodney.karmacracy.com/widget-3.0/?id=ID2&amp;button=1&amp;display=over&amp;show-tooltip=1&amp;url=http://karmacracy.com"></script>
                                </div>
                            </div>
                            <input onclick="recalc()" type="radio" value="over" name="button_display" <?php echo (($options["widget_button_display"]=='over')?("checked='checked'"):"") ?> />
                        </div>
                        
                        <div class='cw-button-expample'>
                            <div class='cw-button-expample-box'>
                                <div class='kber'>
                                    <div class="kcy_karmacracy_widget_h_ID3" style='display:inline-block'></div><script defer="defer" src="http://rodney.karmacracy.com/widget-3.0/?id=ID3&amp;button=1&amp;display=right&amp;show-tooltip=1&amp;url=http://karmacracy.com"></script>
                                </div>
                            </div>
                            <input onclick="recalc()" type="radio" value="right" name="button_display"  <?php echo (($options["widget_button_display"]!='over')?("checked='checked'"):"")?>/>
                        </div>
                    </div>
                </div>
        </div>


    	<div class="cw wrapper">
    		<h4><b>2. </b><?php echo _e("Location")?></h4>
    		<p><?php echo _e("Choose where you want to put the widget")?>:
    			<select class="rounded5" name="location">
    				<option <?php echo ($options["widget_location"]=="body")?"selected='selected'":""?> value="body">After the body</option>
    				<option <?php echo ($options["widget_location"]=="beforebody")?"selected='selected'":""?> value="beforebody">Before the body</option>
    				<!-- <option <?php echo ($options["widget_location"]=="title")?"selected='selected'":""?> value="title">After the title</option> -->
    				<option <?php echo ($options["widget_location"]=="manual")?"selected='selected'":""?> value="manual">Manual - use wp_karmacracy_wdgt_widget_html() function</option>
    			</select>
    		</p>
            <h4><b>3. </b><?=_e("Media Code")?></h4>
                <p><?php echo _e('If your media is registered at <a href="http://cads.me" target="_blank">cAds</a>, add your code here')?></p>
                    <div class="wrapper">
                        <div class="cw-size wrapper">
                            <ul>
                                <li><input class="rounded5" onblur="recalc()" type="text" id="medio_id" value="<?php echo $options['widget_medio_id']?>" name="medio_id"></li>
                            </ul>
                        </div>                                
                    </div>
    	</div>
        <div class='widget-stuff' <?php echo (($options["widget_button"]=='button')?("style='display:none;'"):"")?>>
        	<div class="cw wrapper">
        		<h4><b>4. </b><?php echo _e("Colour")?></h4>
        		<p><?php echo _e("Make your widget colourful")?></p>
                <div id="colorselectors">
                    <div class="tab-item active" id="cw-appearance">
                        <div class="t-section t-tpl-50-50">
                            <div class="t-first t-unit">
                                <input onchange="recalc()" type="text" id="color1" value="<?php echo (isset($options["widget_color1"])?$options["widget_color1"]:"f2f2f2")?>" name="color1" class="widget-colors text rounded5">
                                <b id="show-1" style="background-color: #<?php echo (isset($options["widget_color1"])?$options["widget_color1"]:"f2f2f2")?>"></b>
                                <span><?php echo _e("Border")?></span>
                            </div>
                            <div class="t-first t-unit">
                                <input onchange="recalc()" type="text" id="color2" value="<?php echo (isset($options["widget_color2"])?$options["widget_color2"]:"ffffff")?>" name="color2" class="widget-colors text rounded5">
                                <b id="show-2" style="background-color: #<?php echo (isset($options["widget_color2"])?$options["widget_color2"]:"ffffff")?> "></b>
                                <span><?php echo _e("Background")?></span>
                            </div>
                            <div class="t-first t-unit">
                                <input  onchange="recalc()" type="text" id="color3" value="<?php echo (isset($options["widget_color3"])?$options["widget_color3"]:"f2f2f2")?>" name="color3" class="widget-colors text rounded5">
                                <b id="show-3" style="background-color: #<?php echo (isset($options["widget_color3"])?$options["widget_color3"]:"f2f2f2")?>"></b>
                                <span><?php echo _e("Left background")?></span>
                            </div>
                        </div>
                        <div class="t-section t-tpl-50-50">
                            <div class="t-first t-unit">
                                <input  onchange="recalc()" type="text" id="color4" value="<?php echo (isset($options["widget_color4"])?$options["widget_color4"]:"353535")?>" name="color4" class="widget-colors text rounded5">
                                <b id="show-4" style="background-color: #<?php echo (isset($options["widget_color4"])?$options["widget_color4"]:"353535")?>"></b>
                                <span><?php echo _e("Section text")?></span>
                            </div>
                            <div class="t-first t-unit">
                                <input onchange="recalc()" type="text" id="color5" value="<?php echo (isset($options["widget_color5"])?$options["widget_color5"]:"067dba")?>" name="color5" class="widget-colors text rounded5">
                                <b id="show-5" style="background-color: #<?php echo (isset($options["widget_color5"])?$options["widget_color5"]:"067dba")?>"></b>
                                <span><?php echo _e("Button background")?></span>
                            </div>
                            <div class="t-first t-unit">
                                <input onchange="recalc()" type="text" id="color6" value="<?php echo (isset($options["widget_color6"])?$options["widget_color6"]:"ffffff")?>" name="color6"  class="widget-colors text rounded5">
                                <b id="show-6" style="background-color: #<?php echo (isset($options["widget_color6"])?$options["widget_color6"]:"ffffff")?> "></b>
                                <span><?php echo _e("Button text")?></span>
                            </div>
                        </div>
                        <div class="t-section t-tpl-50-50">
                          	<div class="t-first t-unit">
                                <input onchange="recalc()" type="text" id="color7" value="<?php echo (isset($options["widget_color7"])?$options["widget_color7"]:"353535")?>" name="color7"  class="widget-colors text rounded5">
                                <b id="show-7" style="background-color: #<?php echo (isset($options["widget_color7"])?$options["widget_color7"]:"353535")?>"></b>
                                <span><?php echo _e("kclicks")?></span>
                            </div>
                            <div class="t-first t-unit">
                                <input onchange="recalc()" type="text" id="color9" value="<?php echo (isset($options["widget_color9"])?$options["widget_color9"]:"353535")?>" name="color9"  class="widget-colors text rounded5">
                                <b id="show-9" style="background-color: #<?php echo (isset($options["widget_color9"])?$options["widget_color9"]:"353535")?>"></b>
                                <span><?php echo _e("Lower text")?></span>
                            </div>
                        </div>
                    </div>
                </div>
        	</div>
             <div class="cw wrapper">
                <h4><b>5. </b><?php echo _e("Other options")?></h4>
                <div class="cw-type wrapper">
                    <ul>
                        <li style="display:none"><input onclick="recalc()" type="checkbox" id="sc" value="1" name="sc" <?php echo (($options["widget_sc"])?("checked='checked'"):"")?>> <label for="sc"><?php echo _e("Show clicks")?></label></li>
                        <li><input onclick="recalc()" type="checkbox" id="rb" value="1" name="rb" <?php echo (($options["widget_rb"])?("checked='checked'"):"")?>> <label for="rb"><?php echo _e("Rounded borders")?></label></li>
                        <li><input onclick="recalc()" type="checkbox" id="np" value="1" name="np" <?php echo (($options["widget_np"])?("checked='checked'"):"")?>> <label for="np"><?php echo _e("Hide 'powered by' link")?></label></li>
                    </ul>
                </div>              

            </div>
            <div class="cw wrapper"><p>
        &nbsp;
        		</p><p>
        &nbsp;
        		</p>
            </div>
        </div>
        <div class="cw wrapper">
            <h4><?php echo _e("Preview")?></h4>
            <p><?php echo _e("Here you can see how your widget is going to be.")?></p>
            <div id="atw" style="height:130px">

            </div>
        </div>
    </div>
    <p><?php echo _e("By submiting this info, you agree that the widget will be shown automatically in your site.")?></p>
</div>


<div class="submit">
    <input class='button-primary' type="submit" name="widgetupdate" value="<?php esc_attr_e('Update Widget Settings', $this->get_plugin_info('locale')) ?>" />
</div>
</form>



