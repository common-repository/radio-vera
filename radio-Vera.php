<?php
 /*
Plugin Name: Radio VERA
Plugin URI: http://support.prihod.ru/docs/vidzhety-i-sajdbary/vidzhet-radio-vera/
Description: Виджет православного радио «Вера»
Author: ORTOX
Version: 2.2
Author URI: http://prihod.ru
*/
wp_enqueue_style( 'radiovera', plugin_dir_url(__FILE__) . 'circle.skin/circle.player.css' );

///////////////////////////////////////////////////////////////////////////////////////
// подключения языковых внешних файлов

add_action('plugins_loaded', 'Player_init');

function Player_init() {
	load_plugin_textdomain("radiovera", false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
// -------------------------------------------------------------------------------------------------------
class Radio_Vera extends WP_Widget {
			public function __construct() {
			    parent::__construct(
			        'Radio_Vera',
			        __('Radio "VERA"','radiovera'),
			        array( 'description' =>  __('This widget displays radio from','radiovera').' http://radiovera.ru')
			    );
			}
// -------------------------------------------------------------------------------------------------------
			public function widget( $args, $instance ){

					echo $args['before_widget'];				
						
					// получаем сохраненные переменные	
		 			$title 			= isset( $instance[ 'title' ] )  ? $instance[ 'title' ] : '';
		 			$description 	= isset( $instance[ 'description' ] )  ? $instance[ 'description' ] : '';
					$bitrate	 	= isset( $instance[ 'bitrate' ] )  ? ($instance[ 'bitrate' ]*1) : '128';
					$show_logo		= isset( $instance[ 'show_logo' ] )  ? $instance[ 'show_logo' ] : 'no';
					$show_playlist	= isset( $instance[ 'show_playlist' ] )  ? $instance[ 'show_playlist' ] : 'yes';		 			
					$show_player	= isset( $instance[ 'show_player' ] )  ? $instance[ 'show_player' ] : 'onsite';

					echo $args['before_title'].$title.$args['after_title'];

					if($show_player=='onsite' or $show_player=='continuously' or $show_player==''){

						if($show_logo=='yes'){
							echo "<img src='".plugin_dir_url(__FILE__)."vera.png' style='width:100%;box-shadow: 0px 0px!important;border:0px!important;'><br>";						
						}
						echo $description; 

						if($show_player=='continuously'){
							$continuously = "_con";
						}else{
							$continuously = "";							
						}

						?>
						<div id='radio-vera-player'>

						<script type="text/javascript">
							if(!window.jQuery){
								document.write(unescape('<script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__); ?>js/jquery.js">">%3C/script%3E'));
							}
						</script>

						<script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__); ?>js/jquery.jplayer.min.js"></script>
						<script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__); ?>js/jquery.transform2d.js"></script>
						<script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__); ?>js/jquery.grab.js"></script>
						<script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__); ?>js/mod.csstransforms.min.js"></script>
						<script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__); ?>js/jquery.cookie.js"></script>
						<script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__); ?>js/circle.player<?php echo $continuously; ?>.js"></script>


						<script type="text/javascript">
							//<![CDATA[
							//
							// http://radiovera.hostingradio.ru:8007/radiovera_128
							// 
								var myCirclePlayer;
								jQuery(document).ready(function(){
								myCirclePlayer = new CirclePlayer("#jquery_jplayer_radiovera",
								{
					                mp3: "http://radiovera<?php echo $bitrate; ?>.streamr.ru"}, {
									cssSelectorAncestor: "#cp_container_1",
									swfPath: "<?php echo plugin_dir_url(__FILE__); ?>js",
									supplied: "mp3",
									wmode: "window",
									solution:"flash,html",
									volume: 0.5,
									keyEnabled: false
								});
							});
							//]]>
						</script>
							<div  id="jquery_jplayer_radiovera" class="cp-jplayer"></div>
							<div id="cp_container_1" class="cp-container" style="width:100px;">
								<div style="display:none;"  class="cp-buffer-holder"> 
									<div class="cp-buffer-1"></div>
									<div class="cp-buffer-2"></div>
								</div>
								<div style="display:none;" class="cp-progress-holder">
									<div class="cp-progress-1"></div>
									<div class="cp-progress-2"></div>
								</div>
								<div style="display:none;" class="cp-circle-control"></div>
								<ul class="cp-controls">
									<li style='border:0'><a class="cp-play" style="transition: none;" tabindex="1">play</a></li>
									<li style='border:0'><a class="cp-pause" style="display:none;transition: none;" tabindex="1">pause</a></li>
								</ul>
							</div>

						<div style='height:96px;'></div>

					<?php if($show_playlist=='yes'){ ?>

						<b><?php _e('Now Playing:','radiovera'); ?></b>
						<script type="text/javascript">
						jQuery(document).ready(function() {
							upldate();
							setInterval(upldate, 59000);
						});	
						function parseXML(xml) {
								    var elem = xml.children('elem');
								    if (elem.length > 0)
								    {
								        elem.each(function(){
								            var ths = jQuery(this);

								            switch( ths.attr("STATUS") ) {
								            	case "playing":

								            		jQuery("#artist span").text( ths.children("ARTIST").text() );
								            		jQuery("#name span").text( ths.children("NAME"  ).text() );

								            		break;
								            }
								        });
								    }
								}
						function upldate() {
								jQuery.ajax({
								    url:'<?php echo plugin_dir_url(__FILE__); ?>playlist.php',
								    dataType: 'xml',
								    cache: false,
								    success: function(data){
								        var xml = jQuery(data);
								        parseXML( xml.children() );
								    }
								});
						}

						</script>
						<div id="artist"><span></span></div>
						<div id="name"><span></span></div>

						</div>
					<?php 
					}
					}else{
						
						echo "<div style='display: inline-block;'>";

						if( wp_is_mobile()){
									?>
									<script>
									function radio_vera_top(){
											var newWin = window.open('<?php echo plugin_dir_url(__FILE__).'radio.html'; ?>',
						   					'<?php echo $title; ?>',
						   					'fullscreen=1');
											newWin.focus();
						   					//newWin.setActive();							
									}
									</script>					
									<?php
							}else{	
									?>
									<script>
									function radio_vera_top(){
											var left = screen.width-460;
											var newWin = window.open('<?php echo plugin_dir_url(__FILE__).'radio.html'; ?>',
						   					'<?php echo $title; ?>',
						   					'width=370,height=200,left='+left+',top=160;resizable=0,scrollbars=0,status=0,location=0');
											newWin.focus();
						   					//newWin.setActive();							
									}
									</script>					
									<?php
						}
						
						if($show_logo=='yes'){
							echo "<a href='javascript: void(0);' onclick='radio_vera_top()'><img src='".plugin_dir_url(__FILE__)."/vera.png' style='width:100%;box-shadow: 0px 0px!important;border:0px!important;'>";						
							echo $description;							
							echo "</a>";
						}else{
							echo "<a href='javascript: void(0);' onclick='radio_vera_top()'><img src='".plugin_dir_url(__FILE__)."radio.png' style='box-shadow: 0px 0px!important;border:0px!important;float:left;'>";
							echo $description."</a>";							
						}
							
					echo "</div>";		
				}


				echo $args['after_widget'];

			}
// -------------------------------------------------------------------------------------------------------				
			public function update( $new_instance, $old_instance )
			{
					$instance = array();
					$instance['title']			= strip_tags( $new_instance['title'] );
					$instance['description']	= strip_tags( $new_instance['description'] );
					$instance['bitrate']		= strip_tags( $new_instance['bitrate'] );
					$instance['show_logo']		= strip_tags( $new_instance['show_logo'] );
					$instance['show_playlist']	= strip_tags( $new_instance['show_playlist'] );
					$instance['show_player']	= strip_tags( $new_instance['show_player'] );
			    return $instance;
			}
// -------------------------------------------------------------------------------------------------------				
			public function form( $instance ){

				$title 			= isset( $instance[ 'title' ] )  ? $instance[ 'title' ] : '';
				$description 	= isset( $instance[ 'description' ] )  ? $instance[ 'description' ] : '';
				$bitrate	 	= isset( $instance[ 'bitrate' ] )  ? $instance[ 'bitrate' ] : '128';
				$show_logo		= isset( $instance[ 'show_logo' ] )  ? $instance[ 'show_logo' ] : 'no';
				$show_playlist	= isset( $instance[ 'show_playlist' ] )  ? $instance[ 'show_playlist' ] : 'yes';
				$show_player	= isset( $instance[ 'show_player' ] )  ? $instance[ 'show_player' ] : 'onsite';				

				echo "<p>";
				echo "<label for='".$this->get_field_id('title')."'>".__('Title','radiovera').":</label><br>";
				echo "<input class='widefat' id='".$this->get_field_id( 'title' )."' name='".$this->get_field_name( 'title' )."' type='text' value='".esc_attr($title)."' />";
				echo "<br>";
				echo "<label for='".$this->get_field_id('description')."'>".__('Description','radiovera').":</label><br>";
				echo "<textarea class='widefat' id='".$this->get_field_id( 'description' )."' name='".$this->get_field_name( 'description' )."'>".esc_attr($description)."</textarea>";
				echo "<br>";
				echo "<label for='".$this->get_field_id('show_logo')."'>".__('Show logo','radiovera').":</label><br>";
				echo "<select class='widefat' id='".$this->get_field_id( 'show_logo' )."' name='".$this->get_field_name( 'show_logo' )."'>
						<option value='yes' ".($show_logo=='yes' ? 'selected' : '').">".__('Yes','radiovera')."</option>
						<option value='no' ".($show_logo=='no' ? 'selected' : '').">".__('No','radiovera')."</option>
						</select>";
				echo "<br>";
				echo "<label for='".$this->get_field_id('bitrate')."'>".__('Sound quality (bitrate)','radiovera').":</label><br>";
				echo "<select class='widefat' id='".$this->get_field_id( 'bitrate' )."' name='".$this->get_field_name( 'bitrate' )."'>
						<option value='32' ".($bitrate=='32' ? 'selected' : '').">32 kbps</option>
						<option value='64' ".($bitrate=='64' ? 'selected' : '').">64 kbps</option>
						<option value='128' ".($bitrate=='128' ? 'selected' : '').">128 kbps</option>
						</select>";
				echo "<br>";
				echo "<label for='".$this->get_field_id('show_playlist')."'>".__('Show playlist','radiovera').":</label><br>";
				echo "<select class='widefat' id='".$this->get_field_id( 'show_playlist' )."' name='".$this->get_field_name( 'show_playlist' )."'>
						<option value='yes' ".($show_playlist=='yes' ? 'selected' : '').">".__('Yes','radiovera')."</option>
						<option value='no' ".($show_playlist=='no' ? 'selected' : '').">".__('No','radiovera')."</option>
						</select>";
				echo "<br>";
				echo "<label for='".$this->get_field_id('show_player')."'>".__('Show player','radiovera').":<br>";
				echo "<select class='widefat' id='".$this->get_field_id( 'show_player' )."' name='".$this->get_field_name( 'show_player' )."'>
						<option value='onsite' ".($show_player=='onsite' ? 'selected' : '').">".__('On site','radiovera')."</option>
						<option value='continuously' ".($show_player=='continuously' ? 'selected' : '').">".__('On the site is continuously','radiovera')."</option>
						<option value='newwindow' ".($show_player=='newwindow' ? 'selected' : '').">".__('In a new window','radiovera')."</option>
						</select>";

				echo "<br><br>".__('This widget displays radio from','radiovera').' <a href="http://radiovera.ru">http://radiovera.ru</a><br><br></p>';

			}
}
add_action( 'widgets_init', create_function( '', 'register_widget( "Radio_Vera" );' ) );
?>