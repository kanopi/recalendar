CONTENTS OF THIS FILE
---------------------
   
 * Introduction
 * Requirements
 * Installation
 * Configuration
 * Maintainers

INTRODUCTION
------------

**Re(curring dates) + calendar = Recalendar**

The **Recalendar** module integrates fullcalendar.js with Drupal 8. 

There are a number of Drupal 8 modules that have the same goal, but they do not support recurring dates.

REQUIREMENTS
------------

### PHP
Only works with PHP 7.1 and higher.

### Core Modules
The Rest and Serialization modules in Drupal core will be enabled during installation.

### Contributed Modules

The following contributed modules will be automatically installed when you use composer to install

* Recurring Dates Field - https://www.drupal.org/project/date_recur

    `composer require drupal/date_recur:2.0`

* Interactive widget for Recurring Dates Field - https://www.drupal.org/project/date_recur_interactive

    `composer require drupal/date_recur_interactive`
### Patches

The following date_recur_interactive patches are applied during installation.

* Issue #2923774: Repeat rule widget always fills default day value in Weekly options: https://www.drupal.org/files/issues/2018-12-08/2923774-date_recur-weekly_wrong_day-11.patch

* Issue #3047074: RDATE & EXDATE not time/zone aware: https://www.drupal.org/files/issues/2019-04-24/3047074-rdate-exdate-timezone-8.patch

### Libraries

The required external javascripts are included in this module.

* Fullcalendar.js (documentation can be found at https://fullcalendar.io/docs)

* Moment.js (documentation can be found at https://momentjs.com/docs/)

INSTALLATION
------------
### Composer Install

The following command installs Recalendar, its dependencies (date_recur and date_recur_interactive) and applies 2 patches to date_recur_interactive.

`composer require kanopi/recalendar`

CONFIGURATION
-------------

### Permissions
There is an "Allow administration of Recalendar settings" permission that can be granted to additional roles (admin role has this permission by default).

### Config
By default, the calendar provided by this module is available at /recalendar. This can be changed at /admin/config/recalendar. Remember to rebuild caches after making this config change.

USAGE
-----

1. Enable Recalendar module
1. Navigate to /recalendar and you will see an empty calendar
1. You will now have a taxonomy vocabulary called "Recalendar Event Type"
1. Add terms to the "Recalendar Event Type" taxonomy
1. You will now have a content type called "Recalendar Event"
1. Add and publish "Recalendar Event" nodes, tagging them with the appropriate "Recalendar Event Type" term(s)
1. Rebuild cache
1. Run cron
1. Return to /recalendar - you should now see your Recalendar Events!

Note: if you want to change the /recalendar path alias and page title to something else (e.g., /calendar or /events), you can change it at /admin/config/recalendar. Remember to rebuild caches after making this config change.


MAINTAINERS
-----------

### Current Maintainers
* Anne Bonham (https://www.drupal.org/u/banoodle)
* Jim Birch (https://www.drupal.org/u/thejimbirch)
* Sean Dietrich (https://www.drupal.org/u/sean_e_dietrich)

### Sponsored By
* Kanopi Studios (https://kanopi.com/)