
Sarge
======================================================================

Sarge enhances ExpressionEngine's built-in Drop Down List field by
giving you the capability to group your options into `optgroup`s and
specify the values for each option.

It is developed by [Brandon Kelly](http://brandon-kelly.com/).


Installation
----------------------------------------------------------------------

1. Download and unzip the latest version
2. Upload `extensions/ext.sarge.php` to `system/extensions`
3. Upload `language/english/lang.sarge.php` to
   `system/language/english`
4. Enable Sarge in the Extensions Manager


Configuration
----------------------------------------------------------------------

From Sarge's Settings page, you can set the following preference:

####  Check for Sarge updates?  ######################################
Powered by [LG Addon Updater](http://leevigraham.com/cms-customisation/expressionengine/lg-addon-updater/),
Sarge can call home and check to see if there's a new
update available.


Usage
----------------------------------------------------------------------

All Sarge-related Drop-Down List field customization takes place
within the Select Options textarea in the Edit Custom Field form.

####  Adding `optgroups`  ############################################
Here's an example of options being grouped into optgroups:

    [optgroup] = Fruit
        Apple
        Orange
        Mango
    [/optgroup]
    [optgroup] = Veggies
        Bell Pepper
        Carrot
        Potato
    [/optgroup]

####  Specifying an option's value  ##################################
By default, an option's value is the same as its name. With Sarge, you
can take control of that value:

    Select a fruit =

    Apple = apl
    Orange = org
    Mango = mgo


Requirements
----------------------------------------------------------------------
Sarge requires ExpressionEngine 1.6 or later


Change Log
----------------------------------------------------------------------

####  1.0.0  #########################################################
- Initial release

####  1.1.0  #########################################################
- LG Addon Updater support

####  1.2.0  #########################################################
- Stand Alone Entry Form support (sponsored by
  [Masuga Design](http://masugadesign.com/))


Onward
----------------------------------------------------------------------

- [Sarge documentation](http://brandon-kelly.com/apps/sarge)
- [Sarge's thread on EE Forums](http://expressionengine.com/forums/viewthread/75923/)
- [Sarge support on Get Satisfaction](http://getsatisfaction.com/brandonkelly/products/brandonkelly_sarge)
