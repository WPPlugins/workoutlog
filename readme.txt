=== Plugin Name ===
Contributors: tschoah
Tags: statistics, stats, sport, log
Requires at least: 1.5
Tested up to: 2.0.4
Stable tag: 0.1

Logs all your workouts in your personal blog and shows them your blog readers.

== Description ==

Logs all your workouts in your personal blog and shows them your blog readers.
Maybe you are a racing cycler and you want to log all your workouts. 
That's it what workoutlog does.

= Changes in version 0.2 =

* Wol has been translated by Ian Sterling from german to english.

= Features in version 0.1 =

* Any user can add and delete his workouts.
* Any user can add global available sport categories.
* Prints out a overall statistic of the current year, which cumulates all workouts of any user.
* Prints out a overall statistic of the current week, which cumulates all workouts of any user.
* Prints out a current week by user.
* All statistics should be customizable by your own style informations.

== Installation ==

1. Download the wol - workoutlog package.
1. Unzip the archive into your wordpress plugin directory. *For example: C:\htdocs\wordpress\wp-content\plugins*
1. Visit your plugin administration site of your blog.
1. Activate the new plugin wol - workoutlog.
1. That's it!

= Usage =

With the following PHP-functions you can print out some statistics:

* wol_currentYearStat(); *// overall statistic of the current year*
* wol_currentWeekStat(); *// overall statistic of the current week*
* wol_currentUserWeekStat($displayname); *// Current week statistics by user. The parameter $displayname defines the user for which the statistics shall be printed out.*

The following CSS IDs can be used to customize the style informations of the printed out statistics:

* #wol_curyearstat *CSS ID for the html output of wol_currentYearStat()*
* #wol_curweekstat *CSS ID for the html output of wol_currentWeekStat()*
* #wol_curuserweekstat *CSS ID for the html output of wol_currentUserWeekStat($displayname)*