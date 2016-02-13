If you want to download custom bootstrap css from the internet, just replace any part of the filename that has the word bootstrap with the letters uc.
In other words...
bootstrap-theme.min.css 
becomes
uc-theme.min.css 

THE ONLY EXCEPTION is that main.css has been renamed uc-main.css
All your custom css goes in uc-main.css

This is done in case you were using bootstrap already on your site so you don't accidentally overwrite your settings.

If you would prefer to call your existing bootstrap css files, you can change the css includes in the models/top-nav.php file