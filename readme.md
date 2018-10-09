# Majors and Degrees Custom Post Type

This plugin adds the "Majors and Degrees" custom post type and related shortcodes to your site.

## Post

### Custom Fields
**Header Image**
- Image above the main post content. Minimum width 1110px, maximum height 340px.

**About the Degree**
- Description of the degree.

**Careers**
- Careers related to the degree.

**Courses**
- Course requirements for the degree.


### Taxonomies
**Department**
- This taxonomy identifies the name, ranking, and contact information of the department. This information is displayed in the right column of a degree's single post page.

**Degree Type**
- This taxonomy identifies the type of degree (Major, Minor, Certificate, etc).

**Keyword**
- This taxonomy identifies different words a visitor might associate with the degree. They are used by the search form shortcode.


## Shortcodes

There are two shortcodes added by this plugin: [display_majors_and_degrees] and [search_form_majors_and_degrees]. The display shortcode displays a list of post titles filtered by optional parameters. The search form shortcode displays a search form that allows a user to search through this plugin's custom posts by their keyword taxonomy.


### [display_majors_and_degrees]

Parameters: departments, degree_types, keywords
This shortcode displays a list of post titles from the "Majors and Degrees" custom post type. Its parameters use comma-separated lists of slugs for their corresponding post taxonomy. Example:
[display_majors_and_degrees departments="recreation-park-and-tourism-sciences,animal-science" degree_types="certificate"]


### [search_form_majors_and_degrees]

Parameters: (none)
This shortcode displays a search form that can be used to search through "Majors and Degrees" posts by their keyword taxonomy.


## Developer Notes
Run the following commands on this plugin's main directory:
composer update
composer install
