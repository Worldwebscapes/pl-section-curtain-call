<?php
/*

  Plugin Name:  PageLines Section Curtain Call
  Description:  A simple Video Section.

  Author:       World Webscapes
  Author URI:   http://www.worldwebscapes.com
  Demo:         true

  Version:      5.0.1

  PageLines:    PL_Curtain_Call_Section
  Filter:       component

  Category:     framework, sections, free

  Tags:         images, items

*/

 require 'plugin-update-checker/plugin-update-checker.php';

$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(

    'https://github.com/Worldwebscapes/pl-section-curtain-call',

    __FILE__,

    'pl-section-curtain-call.php'

);



//Optional: If you're using a private repository, specify the access token like this:

$myUpdateChecker->setAuthentication('');



//Optional: Set the branch that contains the stable release.

$myUpdateChecker->setBranch('');





if ( !class_exists( 'PL_Section' ) )
	return;

class PL_Curtain_Call_Section extends PL_Section {


	function section_persistent() {

		add_filter( 'pl_binding_' . $this->id, array( $this, 'callback' ), 10, 2 );

	}

	function section_styles() {


		pl_script( 'jquery-mobile', plugins_url( 'jquery.mobile.custom.min.js', __FILE__ ) );
		pl_script( $this->id, plugins_url( 'script.js', __FILE__ ) );

	}


	function section_opts() {

		$options = array();
		$options[] = array(

			'title' => __( 'Curtain Call Title Configuration', 'pl-section-curtain-call' ),

			'type' => 'multi',

			'opts' => array(

				array(

					'key' => 'headline',

					'type' => 'text',

					'height' => '50px',

					'controls' => false,

					'default' => 'Curtain Call',

					'label' => __( 'Main headline.', 'pl-section-sellit' ),

				),

				array(

					'key' => 'subheadline',

					'label' => __( 'Sub Headline', 'pl-section-sellit' ),

					'height' => '100px',

					'type' => 'richtext',

					'default' => 'A Customized Video Section For PageLines 5. Just Add Popcorn!'

				),

			),

		);
		$options[] = array(
			'title' => __( 'Curtain Call Backgrounds', 'pl-section-curtain-call' ),
			'guide' => __( '', 'pl-section-curtain-call' ),
			'toggle' => 'closed',
			'type' => 'multi',
			'opts' => array(


				array(
					'key' => 'top_image',
					'type' => 'image_upload',
					'label' => __( 'Top Section Background Image', 'pl-section-curtain-call' ),


				),

				array(
					'type' => 'select',
					'key' => 'cccover_contain',
					'label' => __( 'Background Image Handling', 'pl-section-curtain-call' ),
					'opts' => array(
						'contain' => array( 'name' => __( 'contain', 'pl-section-curtain-call' ) ),
						'cover' => array( 'name' => __( 'cover', 'pl-section-curtain-call' ) ),

					),
				),



				pl_std_opt( 'background_color', array(
					'key' => 'top_bg',
					'default' => 'rgba(153, 0, 0, 1)',
					'label' => __( 'Top Section Background Color', 'pl-section-curtain-call' ),

				) ),

				array(
					'key' => 'bottom_image',
					'type' => 'image_upload',
					'label' => __( 'Bottom Section Background Image', 'pl-section-curtain-call' ),


				),
				pl_std_opt( 'background_color', array(
					'key' => 'bottom_bg',
					'default' => 'rgba(51, 51, 51, 1)',
					'label' => __( 'Bottom Section Color', 'pl-section-curtain-call' ),

				) ),
			array(
					'type' => 'select',
					'key' => 'ccbcover_contain',
					'label' => __( 'Background Image Handling', 'pl-section-curtain-call' ),
					'opts' => array(
						'contain' => array( 'name' => __( 'contain', 'pl-section-curtain-call' ) ),
						'cover' => array( 'name' => __( 'cover', 'pl-section-curtain-call' ) ),

					),
				),

				pl_std_opt( 'background_color', array(
					'key' => 'plc_handle_bg',
					'default' => 'rgba(153, 0, 0, 1)',
					'label' => __( 'Handle Color', 'pl-section-curtain-call' ),

				) ),
				pl_std_opt( 'background_color', array(
					'key' => 'plc_arrows_color',
					'default' => 'rgba(255, 255, 255, 1)',
					'label' => __( 'Arrows Color', 'pl-section-curtain-call' ),

				) ),



			)
		);
		$options[] = array(
			'title' => __( 'Curtain Call Video', 'pl-section-curtain-call' ),
			'guide' => __( '', 'pl-section-curtain-call' ),
			'toggle' => 'closed',
			'type' => 'multi',
			'opts' => array(


				array(
					'key' => 'vb_video',
					'type' => 'media_select_video',
					'default' => plugins_url( '/default.mp4', __FILE__ ),
					'label' => __( 'Upload Video, add Vimeo URL, or add YouTube URL', 'pl-section-curtain-call' ),
				),
				array(
					'key' => 'vb_autoplay',
					'type' => 'check',
					'default' => 1,
					'label' => __( 'Auto Play Video?', 'pl-section-curtain-call' ),
				),
				array(
					'key' => 'vb_loop',
					'type' => 'check',
					'default' => 1,
					'label' => __( 'Loop Video?', 'pl-section-curtain-call' ),
				),
				array(
					'key' => 'vb_controls',
					'type' => 'check',
					'default' => 1,
					'label' => __( 'Show Controls? (MP4 Video Only)', 'pl-section-curtain-call' ),
				),

				array(
					'key' => 'vb_title',
					'type' => 'text',
					'label' => __( 'Video Title', 'pl-section-curtain-call' ),
					'default' => 'Video Title',
				),
			pl_std_opt('scheme', array(

            'key'     => 'video_scheme',

            'default' => 'pl-scheme-default',

            'label'   => __( 'Video Title Color Scheme', 'pl-section-curtain-call' ),

            

          )),




			)
		);



		return $options;
	}


	/** Standard callback format */


	function get_videobox( $config = array() ) {

		$default = array(
			'vb_autoplay' => $this->opt( 'vb_autoplay' ),
			'vb_loop' => $this->opt( 'vb_loop' ),
			'vb_controls' => $this->opt( 'vb_controls' ),
			'vb_video' => $this->opt( 'vb_video', $this->base_url . '/default.mp4' )
		);

		$config = wp_parse_args( $config, $default );

		ob_start();

		$video_options = array();


		if ( 1 == $config[ 'vb_autoplay' ] ) {
			$video_options[] = 'autoplay';
		}

		if ( 1 == $config[ 'vb_loop' ] ) {
			$video_options[] = 'loop';
		}

		if ( 1 == $config[ 'vb_controls' ] ) {
			$video_options[] = 'controls';
		}

		$url = $config[ 'vb_video' ];

		$yt_rx = '/^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be))(\/(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?$/';
		$has_match_youtube = preg_match( $yt_rx, $url, $yt_matches );

		$vm_rx = '/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([‌​0-9]{6,11})[?]?.*/';
		$has_match_vimeo = preg_match( $vm_rx, $url, $vm_matches );

		if ( $has_match_youtube ) {

			$video_id = $yt_matches[ 5 ];

			$autoplay = ( $config[ 'vb_autoplay' ] == 1 ) ? 'autoplay=1' : 'autoplay=0';

			$loop = ( $config[ 'vb_loop' ] == 1 ) ? 'loop=1' : 'loop=0';

			$video_url = sprintf( '//www.youtube.com/embed/%s?%s&amp;%s&amp;showinfo=0&amp;&rel=0', $video_id, $autoplay, $loop );

			?>

			<iframe class="video" src="<?php echo $video_url; ?>" frameborder="0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>

			<?php

			} else if ( $has_match_vimeo ) {

				$video_id = $vm_matches[ 5 ];

				$autoplay = ( $config[ 'vb_autoplay' ] == 1 ) ? 'autoplay=1' : 'autoplay=0';

				$loop = ( $config[ 'vb_loop' ] == 1 ) ? 'loop=1' : 'loop=0';

				$video_url = sprintf( 'https://player.vimeo.com/video/%s?%s&amp;%s&amp;color=878a8a&amp;title=0&amp;byline=0&amp;portrait=0&amp;badge=0', $video_id, $autoplay, $loop );

				?>

			<iframe class="video" src="<?php echo $video_url; ?>" frameborder="0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>

			<?php

			} else {

				$video_id = rand();


				?>
			<video id="<?php echo $video_id;?>" <?php echo join( ' ', $video_options );?> >
			  <source src="<?php echo do_shortcode( $config['vb_video'] ); ?>?r=<?php echo $video_id;?>" type="video/mp4" />
			 	No browser html5 video.
			</video>

			<script>
				jQuery( '#<?php echo $video_id;?>' )[ 0 ].load();
			</script>
			<?php

		}

		return ob_get_clean();

	}

	function callback( $response, $data ) {

		$response[ 'template' ] = $this->do_callback( $data[ 'value' ] );


		return $response;
	}


	/**
	 * The section HTML output
	 */
	function section_template() {

		?>

		<div class="pl-show-on-load pl-trigger">
			<h1 class="pl-alignment-center" data-bind="pltext: headline"></h1>
			<h5 class="pl-alignment-center" data-bind="pltext: subheadline"></h5>
			<figure class="plc-image-container topbackcontain " data-bind="plbg: bottom_image, style: { 'background-color': bottom_bg, 'background-size': ccbcover_contain,   }">

				<div class="pl-alignment-center pl-video-box" data-bind="plcallback: { vb_autoplay: vb_autoplay(), vb_loop: vb_loop(), vb_controls: vb_controls(), vb_video: vb_video()}" data-callback="videobox">
					<?php echo $this->get_videobox(); ?>
				</div>
				<div class="pl-alignment-center">
<div data-bind="style: {}, plclassname: [video_scheme() ]">
					<h3 class="video-title"  data-bind="pltext: vb_title" ></h3>
				</div>
				</div>
				<div class="plc-resize-img  " data-bind="plbg: top_image, style: { 'background-color': top_bg, 'background-size': cccover_contain,  }">


				</div>

				<span class="plc-handle" data-bind="style: { 'background-color': plc_handle_bg }">
          <i class="pl-icon pl-icon-chevron-left" data-bind="style: { 'color': plc_arrows_color }"></i>
          <i class="pl-icon pl-icon-video-camera" data-bind="style: { 'color': plc_arrows_color }"></i>
        </span>
			



			</figure>


		</div>

		<?php
	}

}
