# twitchQuoting
Used to replace the twitch.center API if you want to host it yourself and use a database.

## Requirements
A database named ```twitch```, with a table named ```quotes``` with the fields ```q_id``` (Auto-Increment), ```quote```, ```date```, ```game```, and ```stream_title```.

A user with access to the database, by default named ```twitch_daemon```.

# Commands
- !quote - without arguments, gets a random quote.
-- !quote list - returns a URL with the quote list (see list.php)
-- !quote [arg:int] - returns the quote with the matching ID, if it exists.
-- !quote [arg:string] - returns a random quote with the string as a substring, if one exists. otherwise, returns a random quote.
- !addquote [arg:string] - adds the quote to the database, along with the current game, stream name, and date.

# Nightbot Commands
- !addquote: ```$(urlfetch http://[HOST]/addquote.php?QUERY= + $(eval var qs = `$(querystring)` + "\x00" + `$(twitch [TWITCH USERNAME] "{{title}}")` + "\x00" + `$(twitch [TWITCH USERNAME] "{{game}}")` + "\x00" + `$(time [TIME ZONE] "D MMM YYYY")`; encodeURIComponent(qs);))	```
- !quote:```$(urlfetch http://[HOST]/quote.php?QUERY=$(querystring))```
- !delquote: ```$(urlfetch http://[HOST]/delquote.php?QUERY=$(querystring))	```

## Notes
The database user's password is blanked out, replace it with your user's password. I recommend using a custom user without permissions to DROP.
!addquote has a minimum length to be able to add the quote, hopefully preventing at least a bit of spam. By default this is 10 characters.
The !quote command can be negatively indexed, but it breaks at the first quote. I might fix this in the future.
There's a more elegant way to handle !addquote using Twitch's API, but I don't want to do it right now.
!delquote renumbers the quotes in the database if you delete one in the middle. e.g., if you have 19 quotes, and you delete quote 2, quotes 3-19 will all be shifted down by one so the quote numbering is contiguous.