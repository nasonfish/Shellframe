## Shellframe
Shellframe is a small and simple framework for web applications of all sized. It's meant to let you control what you want to do with your application, while still making things simple and easy to work with.

## /webroot/
The webroot folder is the folder where you should point your web server's root folder to. This folder should contain all the scripts you don't want going through the main_tpl.php page - API files, POST receiving pages, etc. The index.php file should receive all of the traffic where a page is not found for it (almost like a 404 page).

## /system/
The index.php file grabs the data from the Config (and CustomConfig) files in the /system/ package. This is where we handle our libraries. You can specify config options in the CustomConfig file following the pattern it already uses, and even add new libraries the way it adds the Handler.

Then, you must use `global $yourvar;` in the PageHandler.class.php on line 5 to make sure these are passed to the templates.

## /libs/
This is where you can store the aforementioned libraries, and include them in the Config and PageHandler. These are passed to the template.

## /templates/
And finally, the templates folder! These are the files that contain the actual information displayed on your app. The names of the files are automatically used and pages are created just by creating files.

The things there already are 'special' - the 404_tpl.php catches all pages that we couldn't find a template for, the index_tpl.php is the index page, and the main_tpl.php is the main template that the other templates are included in.

You can create new files for new pages - `<name>_tpl[.<type>].php`. The name is the name of the page - i.e. /users/'s type is `users`. The `<type>` is if you want the template to appear somewhere else on the page - you can see where those are put by looking at the default main_tpl.php. Current types are:
 - title
 - head
 - foot
 - js
 - [page]
So, any prefix is put in the specified place on the page. If there is no type, it takes the `$this->page();`.

Arguments after the url are passed as `$pass` - for example, for the page /users/nasonfish/20/, in users_tpl[.type].php:

 - $pass[0] = nasonfish
 - $pass[1] = 20

Remember to check if the keys exist first!

Thanks for checking out Shellframe - more extensive documentation coming soon!
