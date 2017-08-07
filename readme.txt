=== Daily Prayer Time ===
Contributors: mmrs151
Donate link: http://www.uwt.org/
Tags: prayer time, ramadan time, salah time, mosque timetable, islam, muslim, salat, namaz, fasting
Requires at least: 3.5
Tested up to: 4.7
Stable tag: 4.2.6
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display Yearly, Monthly and Daily prayer time vertically or horizontally, in any language.

== Description ==
Alhamdulillah that you can Display Yearly and Monthly prayer time with ajax month selector using shortcode [timetable]
Daily prayer time can be displayed vertically or horizontally in your preferable widget area. Designed for any Mosque or Islamic institutes.

**You need to upload your masjid's timetable from plugin admin section.**

= Features =
Once the installation above is done, this will allow you

- To display prayer start and jamah time

- To display ramadan timetable for daily or full month

- To display next prayer and IQAMAH notifications

- To display prayer time either vertical or horizontal widget.

- To display 'Jamah time' only if you chose.

- To chose from three different themes

- To chose Asr salah start method

- Display monthly and yearly timetable using shortcode [timetable] from any page or post

- Display Khutbah time announcement on Friday

- Display Iqamah time only for monthly timetable using shortcode

- Upload any number of days, weeks, months or a full year.

- Support all language that are readable on the web

= shortcodes =
1. **[monthlytable]** - Display Yearly and Monthly prayer time with ajax month selector
2. **[monthlytable_daily]** - Display daily timetable horizontally
3. **[monthlytable_daily asr=hanafi]** - Display daily timetable horizontally with Hanafi Asr start method
4. **[monthlytable_daily_vertical]** - Display daily timetable vertically
5. **[monthlytable_daily_vertical asr=hanafi]** - Display daily timetable vertically with Hanafi Asr start method
6. **[monthlytable_daily asr=hanafi friday_alert="First Khutbah: 1:15. Second Khutbah: 1:45"]** - Display Friday announcement
7. **[monthlytable display=iqamah_only]** - Display Iqamah only for Yearly and Monthly prayer time with ajax month selector
8. **[monthlytable display=azan_only]** - Display Azan only for Yearly and Monthly prayer time with ajax month selector
9. **[monthlytable heading="Månedlige Tidsplan"]** - Display monthly time table heading in any language, default is 'Monthly Time Table for'
... and more. Check the 'helps-and-tips' page in plugin settings once you install it.

== Installation ==
1. Download the plugin
2. Simply go under the Plugins page, then click on Add new and select the plugin's .zip file
3. Alternatively you can extract the contents of the zip file directly to your wp-content/plugins/ folder
4. Finally, just go under Plugins and activate the plugin

= Comprehensive setup =

**Please upload your mosque's timetable in .csv format from the plugin setting page.**

== Frequently Asked Questions ==

= Why my time table is showing all zeros(0)? =
You will need to  import your mosque's timetable csv from settings section.

= Why my date is showing '1, Jan 1970' =
Because you have not imported your mosque's timetable or your date format is not valid mysql format, which is (YYYY-MM-DD)

= How to display ramadan time =
Simply put '1' for the last column(is_ramadan) in the sample csv for the days belongs to ramadan before upload

= Why does it not show minutes remaining for next IQAMAH
Please check/update your timezone settings in Settings > General

= What other features coming in the next updates
Please look at https://trello.com/b/6Re5Dga7/salah-time-wordpress-plugin

== Screenshots ==
1. Upload timetable
2. Translate in any language
3. Hijri date settings
4. Change timetable look and feel
5. Update monthly prayer time from admin section
6. Ramadan and Asr salah settings
7. Widget settings
8. Dark and no border theme
9. Support for any language
10. Custom title in widget
11. Change monthly time table heading and month name
12. Highlight next prayer, time remaining for iqamah, ramadan timing
13. Ramadan calendar for current year
14. Shortcode for displaying next prayer
15. Shortcode for displaying daily ramadan times

== Changelog ==

= 4.2.6 =
* Fix for iqamah time or azan time only

= 4.2.5 =
* Horizontal timetable with div layout, easy for devs to update
* Iftar/sehri start/end using shortcode
* fix sunrise for next prayer shortcode

= 4.2.4 =
* Fix shared hosting data import
* Fix for number of rows affected
* Fix sample.csv url

= 4.2.3 =
* fix debug
* fix time remaining in shortcode
* New shortcode for showing single next prayer time
* Customisable notification background colour

= 4.1.1 =
* Display hijri date on a new line

= 4.1.0 =
* Allowing multiple date format

= 4.0.0 =
* Upgraded Admin pages
* User can select theme
* Quick time table update
* Hijri date

= 3.1.0 =
* Fix more translation bugs

== Upgrade Notice ==

= 4.2.5 =
This version allows you to display ramadan times using a shortcode. Also there is a div layout for horizontal timetable, which make developer easy to change if needed.