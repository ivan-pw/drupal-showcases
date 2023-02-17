Using:
Composer 2.5.4
Drupal 8.9.8
Drupal Console 1.9.8



How to use: 

Put it into your custom module folder, activate.
Maybe you want to test it, so use Devel Generate for creatink a dump nodes.



Endpoints:

/showcases/list 
Return an 'items' array of 3 randow showcases, returned fields:

	nid (nid) - node Id
	title (string)- node Title
	name (string)- field Name
	featured_image - Field Featured image, include:
		target_id (int)
		alt (string)
		title (string)
		width (int)
		height (int)
		url (string)
	linked_article - linked article, include: 
		nid (int)
		linked_article_title (string)
	
Using with Featured parameter: 
/showcases/list/featured
Will return only nodes with Featured field is On 

/showcases/single/$id
Return full info about Showcase node with $id
Structure: 
	nid (int)
	title (string)
	name (string)
	featured_image
		target_id (int)
		alt (string)
		title (string)
		width (int)
		height (int)
		url (string)
	logo_image
		target_id (int)
		alt (string)
		title (string)
		width (int)
		height (int)
		url (string)
	linked_article
		nid (int)
		linked_article_title
	featured (boolean)
	address (string)
	short_description (string)
	description (string)
	facebook_url (string)
	twitter_url (string)



How to repeat:

Install Drupal 8.9.8 by Composer 2.5.4
Install Drupal console, Drush

1) Create new content type Showcase, create all fields.

2) Generate new blank module: 

php ./vendor/bin/drush generate module-standard

3) Export created content type to the new module:

php ./vendor/bin/drupal config:export:content:type showcase \
  --module="showcases" \
  --optional-config \
  --remove-uuid \
  --remove-config-hash
  
4) Activate modules: REST, Serialization

5) Edit showcases.routing.yml for new routes

6) Edit controller file: showcases/src/Controller/ShowcasesController.php

7) Activate module