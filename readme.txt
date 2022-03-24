=== Daily Prayer Time ===
Contributors: mmrs151, Hjeewa, kams01
Donate link: https://donate.uwt.org/Account/Index.aspx
Tags: prayer time, ramadan time, salah time, mosque timetable, islam, muslim, salat, namaz, fasting, Quran verse
Requires at least: 4.5
Requires PHP: 5.6
Tested up to: 5.9
Stable tag: 2022.04.24
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display prayer time in any screen, in any language and many more.

== Description ==
For video tutorial please follow these links:
- (Latest) How to tutorial [youtube https://youtu.be/ka5WxQfkFww]

Alhamdulillah that you can display Yearly and Monthly prayer time with ajax month selector using shortcode [timetable]
Daily prayer time can be displayed vertically or horizontally in your preferable widget area. Designed for any Mosque or Islamic institutes.

**You need to upload your masjid's timetable from plugin admin section.**

= PULL REQUEST =
[GIT Repository] https://bitbucket.org/mmrs151/daily-prayer-time-for-mosques
Add your commit and make a pull request. Please describe your feature, I will add it to the core.
Also, feel free to release my code with a new plugin name with your exiting new ideas added to it.
My sole intention is to support the ummah.
So I have absolutely no complain as long as you have the same intention.

Mustafiz.

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

- Use custom css using the class dptUserStyles to decorate your element table elements.

- Use span class dpt_jamah and dpt_start along with [next_xxxx_prayer] and design your view however you want.

- Display Iqamah update for next day

- Display prayer time on big monitors in the masjid

- Display Quran verse in a shortcode

= shortcodes =
1. **[monthlytable]** - Display Yearly and Monthly prayer time with ajax month selector
2. **[dailytable_horizontal]** - Display daily timetable horizontally
3. **[dailytable_horizontal asr=hanafi]** - Display daily timetable horizontally with Hanafi Asr start method
4. **[dailytable_vertical]** - Display daily timetable vertically
5. **[dailytable_vertical asr=hanafi]** - Display daily timetable vertically with Hanafi Asr start method
6. **[dailytable_horizontal asr=hanafi friday_alert="First Khutbah: 1:15. Second Khutbah: 1:45"]** - Display Friday announcement
7. **[monthlytable display=iqamah_only]** - Display Iqamah only for Yearly and Monthly prayer time with ajax month selector
8. **[monthlytable display=azan_only]** - Display Azan only for Yearly and Monthly prayer time with ajax month selector
9. **[monthlytable heading="Månedlige Tidsplan"]** - Display monthly time table heading in any language, default is 'Monthly Time Table for'
10. **[digital_screen]** - Display prayer time on big monitors in the masjid
11. **[quran_verse]** - Display a random verse from the Holy Quran 
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
6. Digital screen settings
7. Jumuah, Ramadan, Asr and Iqamah notification settings
8. API docs
9. Widget settings
10. Example 1: daily prayer times
11. Example 2: monthly prayer times
12. Digital screen or mobile screen
13. Example1: Using individual shortcode
14. Example2: Using individual shortcode
15. API response
16. Iqamah change notification for tomorrow

== Changelog ==

= 2022.04.24 =
* Fix notices and warnings 
* Fix sehri end time

== Upgrade Notice ==

= 2022.04.24 =
* Fix notices and warnings 
* Fix sehri end time