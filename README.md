## Autolink –– Automatic link creation for Contao
This extension has a backend GUI. It can search for text and automatically creates a link if search term was found. The search can be restricted to the needed Contao pages.

* compatibility: Contao >= 3.2.0, now including Contao 4.11 and PHP 7.4
* now with svg wand icon  instead of the gif

### Installation
#### Contao 3
* copy the folder "autolink" to /system/modules
* update database

#### Contao 4
* copy the folder "autolink" to /system/modules
* renew prod. cache
* update database
* copy the folder additionally to **/web**/system/modules - otherwise the icon won't appear

### Screenshots

Backend
![Backend](https://github.com/mandrael/contao-autolink/blob/main/docs/images/autolink-backend.png)

Create links
![Create links](https://github.com/mandrael/contao-autolink/blob/main/docs/images/autolink-link-creation.png)

New Icon

![New Icon](https://github.com/mandrael/contao-autolink/blob/main/docs/images/autolink-new-icon.png)

-----

This Contao extension is based on the modified extension from kikmedia, which was forked from aschempp:

#### kikmedia-contao-autolink
* Provides an autolinker to Contao 3.
* This extension is based on the mostly orphaned extension of https://github.com/aschempp (see https://github.com/aschempp/contao-autolink).
* Added some fixes for the new page picker and created files for autoloader.
* Feel free to suggest enhancements.
* Compatibility and dependencies: Contao >= 3.2.0
