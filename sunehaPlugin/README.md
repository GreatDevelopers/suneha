=== Suneha ===

Contributors: Navdeep Bagga
Donate link: http://navdeepbagga.com/
Tags: API, AJAX, suneha
Requires at least: 3.5.1
Tested up to: 3.5.1
Stable tag: 3.5.1
License: GPLv2 
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Lets your wordpress website connect to suneha interface. 

== Description ==

Suneha lets you users send SMS from your website. Using suneha is very simple; you just need to embed a simple form in your webpage. 
Read the follwing instructions to install Suneha for your Web Application.   


== Installation ==

1. Upload `suneha` to the `/wp-content/plugins/` directory.
2. Edit 'wp-content/plugins/ApiInformation.php'. Look for 'your api key here' in the file and replace it with your own api key that you must have got during registration.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Copy the following code wherever you want your form to appear.   

<form name="myform" id = "submit" method = "post" >
<label for = "number">Enter Number :</label>
<input id = "number" name = "number" type = "text" />
<label for = "message">Enter Message :</label>
<textarea id= "message" name="message" ></textarea>
<input type = "button" style="color:#000" value = "Send SMS" id = "send"/>
</form>

You should now be able to send SMS through this form.

== Changelog ==

= 1.0.1 =
Add jquery library.
