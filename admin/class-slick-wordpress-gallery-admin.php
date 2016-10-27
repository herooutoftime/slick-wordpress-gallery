<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.herooutoftime.com
 * @since      1.0.0
 *
 * @package    Slick_Wordpress_Gallery
 * @subpackage Slick_Wordpress_Gallery/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Slick_Wordpress_Gallery
 * @subpackage Slick_Wordpress_Gallery/admin
 * @author     Andreas Bilz <andreas.bilz@gmail.com>
 */
class Slick_Wordpress_Gallery_Admin
{

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string $plugin_name The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string $version The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      string $plugin_name The name of this plugin.
   * @param      string $version The version of this plugin.
   */
  public function __construct($plugin_name, $version)
  {

    $this->plugin_name = $plugin_name;
    $this->version = $version;

  }

  /**
   * Register the stylesheets for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_styles()
  {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Slick_Wordpress_Gallery_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Slick_Wordpress_Gallery_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/slick-wordpress-gallery-admin.css', array(), $this->version, 'all');

  }

  /**
   * Register the JavaScript for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts()
  {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Slick_Wordpress_Gallery_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Slick_Wordpress_Gallery_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/slick-wordpress-gallery-admin.js', array('jquery'), $this->version, false);

  }

  public function admin_init()
  {
    add_editor_style(plugin_dir_url(__FILE__) . 'css/slick-mce-editor.css');
    add_editor_style('https://fonts.googleapis.com/css?family=Pacifico');
  }

  public function print_footer_scripts()
  {
    ?>
    <script type="text/html" id="tmpl-editor-gallery-slick">
      <# if ( data.attachments ) { #>
        <div class="gallery gallery-columns-{{ data.columns }}">
          <# if ( data.slick_props.slick_use_slick == 1 ) { #>
            <div class="slick-properties">
              <h2 class="slick-properties-title">slick</h2>
              <# for(var k in data.slick_props) { #>
                <small><strong>{{ k.replace('slick_', '') }}:</strong> {{ data.slick_props[k] }}</small>,
              <# } #>
            </div>
          <# } #>
          <# _.each( data.attachments, function( attachment, index ) { #>
            <dl class="gallery-item">
              <dt class="gallery-icon">
                <# if ( attachment.thumbnail ) { #>
                  <img src="{{ attachment.thumbnail.url }}" width="{{ attachment.thumbnail.width }}"
                       height="{{ attachment.thumbnail.height }}"/>
                  <# } else { #>
                    <img src="{{ attachment.url }}"/>
                    <# } #>
              </dt>
              <# if ( attachment.caption ) { #>
                <dd class="wp-caption-text gallery-caption">
                  {{ attachment.caption }}
                </dd>
                <# } #>
            </dl>
            <# if ( index % data.columns === data.columns - 1 ) { #>
              <br style="clear: both;">
            <# } #>
          <# } ); #>
        </div>
        <# } else { #>
          <div class="wpview-error">
            <div class="dashicons dashicons-format-gallery"></div>
            <p><?php _e('No items found.'); ?></p>
          </div>
          <# } #>
    </script>

    <script type="text/javascript">
      jQuery(document).ready(function () {
        var media = wp.media;

        function verifyHTML( string ) {
          var settings = {};

          if ( ! window.tinymce ) {
            return string.replace( /<[^>]+>/g, '' );
          }

          if ( ! string || ( string.indexOf( '<' ) === -1 && string.indexOf( '>' ) === -1 ) ) {
            return string;
          }

          schema = schema || new window.tinymce.html.Schema( settings );
          parser = parser || new window.tinymce.html.DomParser( settings, schema );
          serializer = serializer || new window.tinymce.html.Serializer( settings, schema );

          return serializer.serialize( parser.parse( string, { forced_root_block: false } ) );
        }

        var slick_gallery = _.extend( {}, {
          state: [ 'gallery-edit' ],
          template: media.template( 'editor-gallery-slick' ),
          edit: function( text, update ) {
            var type = this.type,
              frame = media[ type ].edit( text );

            this.pausePlayers && this.pausePlayers();

            _.each( this.state, function( state ) {
              frame.state( state ).on( 'update', function( selection ) {
                update( media[ type ].shortcode( selection ).string(), type === 'gallery' );
              } );
            } );

            frame.on( 'close', function() {
              frame.detach();
            } );

            frame.open();
          },
          initialize: function() {
            var attachments = media.gallery.attachments( this.shortcode, media.view.settings.post.id ),
              attrs = this.shortcode.attrs.named,
              self = this;

            attachments.more()
              .done( function() {
                attachments = attachments.toJSON();

                _.each( attachments, function( attachment ) {
                  if ( attachment.sizes ) {
                    if ( attrs.size && attachment.sizes[ attrs.size ] ) {
                      attachment.thumbnail = attachment.sizes[ attrs.size ];
                    } else if ( attachment.sizes.thumbnail ) {
                      attachment.thumbnail = attachment.sizes.thumbnail;
                    } else if ( attachment.sizes.full ) {
                      attachment.thumbnail = attachment.sizes.full;
                    }
                  }
                } );

                var slick_props = Object.keys(attrs).filter(function(k) {
                  return k.indexOf('slick') == 0;
                }).reduce(function(newData, k) {
                  newData[k] = attrs[k];
                  return newData;
                }, {});

                self.render( self.template( {
                  verifyHTML: verifyHTML,
                  attachments: attachments,
                  columns: attrs.columns ? parseInt( attrs.columns, 10 ) : media.galleryDefaults.columns,
                  slick_props: slick_props
                } ) );
              } )
              .fail( function( jqXHR, textStatus ) {
                self.setError( textStatus );
              } );
          }
        });
        wp.mce.views.unregister('gallery');
        wp.mce.views.register('gallery', slick_gallery);
      });
    </script>
    <?php
  }

  public function print_media_templates()
  {
	  echo file_get_contents(plugin_dir_path(__FILE__) . 'tmpl/custom-gallery-slick-form.html');
    ?>

    <script>
      jQuery(document).ready(function () {
        // add your shortcode attribute and its default value to the
        // gallery settings list; $.extend should work as well...
        _.extend(wp.media.gallery.defaults, {
        });

        // merge default gallery settings template with yours
        wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
          template: function (view) {
            return wp.media.template('gallery-settings')(view)
              + wp.media.template('custom-gallery-slick-form')(view);
          }
//					,events: {
//						'change #slick-select': function(event) {
//							console.log(this.model.get('slick_use_slick'));
//							if(event.currentTarget.value == 0)
//								_('#slick-settings-container').hide();
//						}
//					}
        });
      });
    </script>
    <?php
  }
}
