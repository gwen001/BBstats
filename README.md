<h1 align="center">BBStats</h1>

<h4 align="center">A tool that display stats and graphs about your bug bounty activity.</h4>

<p align="center">
    <img src="https://img.shields.io/badge/php-%3E=5.5-blue" alt="php badge">
    <img src="https://img.shields.io/badge/license-MIT-green" alt="MIT license badge">
    <a href="https://twitter.com/intent/tweet?text=https%3a%2f%2fgithub.com%2fgwen001%2fBBStats%2f" target="_blank"><img src="https://img.shields.io/twitter/url?style=social&url=https%3A%2F%2Fgithub.com%2Fgwen001%2FBBStats" alt="twitter badge"></a>
</p>

<p align="center">
    <img src="https://img.shields.io/github/stars/gwen001/BBStats?style=social" alt="github stars badge">
    <img src="https://img.shields.io/github/watchers/gwen001/BBStats?style=social" alt="github watchers badge">
    <img src="https://img.shields.io/github/forks/gwen001/BBStats?style=social" alt="github forks badge">
</p>

---

## Requirements

A web server with PHP installed and Curl extension enabled.  

Put the code at the root of your web server:
```
git clone https://github.com/gwen001/BBstats
```

## Auth

Set environment variable `HACKERONE_USERNAME` and `HACKERONE_PASSWORD`

## Recommended usage

Grab the datas from your favorite platform for the first time: *quick-init.sh*
```
php data-grabber.php -p hackerone -a n -rr -tt -e
```

Or update your current database (once a week for example): *quick-update.sh*
```
php data-grabber.php -p hackerone -a u -r -t -e -n 50
```

Enjoy the stats!
```
firefox http://127.0.0.1/BBstats/
```

## Grabber

<!-- help -->
```
Usage: php data-grabber.php -p <platform> [OPTIONS]

Options:
	-a	action to perform (default=N)
		   N: new, add new reports
		   U: update, add new reports and update the existing ones (title, bounty, state)
		   O: overwrite, add new reports and overwrite the existing ones
		   R: rollback, got back the previous last version of the database (not platform dependant)
	-e	grab reputation as well
	-f	import from file
	-g	import program datas
	-h	print this help
	-n	update/overwrite the last n reports (default=all, only recommended for the first init)
	-p	platform to grab datas (available: hackerone)
	-r	try to auto rate the reports but keep the current value if exists
	-rr	try to auto rate the reports and overwrite the current value
	-t	try to auto tag the reports but merge the current tags if exists
	-tt	try to auto tag the reports and overwrite the current tags

Examples:
	php data-grabber.php -p hackerone -a n
	php data-grabber.php -p hackerone -a u -n 50
	php data-grabber.php -p hackerone -a o -rr -tt -e
	php data-grabber.php -p hackerone -f bounties.csv -r -t
	php data-grabber.php -p hackerone -a r
```
<!-- /help -->

## Web
You can choose which graph you want to display in `config.php`.  
You create your own autotag and autorate configuration in `config.php`.  

## Todo

__grabber__
- add more platform (Bugcrowd, YesWeHack, Intigriti, Synack...)  

__db__
- ?

__web ui__
- search engine filter: with/without bounty  
- scrollbar fot both part, left and right  
- calendar plugin for dates

__graph__
- graph: bounties per month per program  
- graph: bounties per month per type  
- graph: bounties per month per platform  
- graph: reports per month per program  
- graph: reports per month per type  
- graph: reports per month per platform  
- graph: reports per status  

__bugs__
- probably alot!

---

<table>
	<tbody>
		<tr>
			<td colspan="2"><img src="https://raw.githubusercontent.com/gwen001/BBstats/master/img/sample-grabber.png" title="Grabber" alt="Grabber" /></td>
		<tr>
			<td colspan="2"><img src="https://raw.githubusercontent.com/gwen001/BBstats/master/img/sample-listing.png" title="Listing" alt="Listing" /></td>
		</tr>
		<tr>
			<td><img src="https://raw.githubusercontent.com/gwen001/BBstats/master/img/sample-bounty.png" title="Bounties" alt="Bounties" /></td>
			<td><img src="https://raw.githubusercontent.com/gwen001/BBstats/master/img/sample-evolution.png" title="Evolution" alt="Evolution" /></td>
		</tr>
		<tr>
			<td><img src="https://raw.githubusercontent.com/gwen001/BBstats/master/img/sample-report-rating.png" title="Reports rating" alt="Reports rating" /></td>
			<td><img src="https://raw.githubusercontent.com/gwen001/BBstats/master/img/sample-program-repartition.png" title="Program repartition" alt="Program repartition" /></td>
		</tr>
	</tbody>
</table>

---

Feel free to [open an issue](/../../issues/) if you have any problem with the script.  

