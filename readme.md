# Photo Gallery Post Type

This plugin adds the "Photos" custom post type and related shortcodes to your site.

## Album Taxonomy

### Taxonomies
**Album**
- This taxonomy identifies the name of the photo's album
- On the taxonomy edit page you will see a Thumbnail custom field which identifies the image preview for the album. 400px by 400px.

**Color**
- This taxonomy identifies the color used in the photo, which some users may find helpful.

**Subject**
- This taxonomy identifies the subject matter of the photo.

**Size**
- This taxonomy identifies the size keyword for the photo.

**Orientation**
- This taxonomy identifies the photo's orientation.

## Shortcodes
There are two shortcodes added by this plugin: [display_photo_gallery_zw] and [display_photo_gallery_zw_albums]. The display shortcode displays a list of post titles filtered by optional parameters. The display albums shortcode displays all albums with their name and thumbnail custom field.

## Developer Notes
Run the following command on this plugin's main directory to set up:
composer update

TODO: Theming
TODO: Ensure posts returned on filter searches include sub-hierarchy posts
TODO: Look for WP plugin or JQ plugin that provides lightbox functionality for the photos in an album