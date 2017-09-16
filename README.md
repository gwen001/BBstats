# Bug Bounty Stats
It's a tool that would be able (in the future) to aggregrate your reports/bounties from different platforms in order to create combined stats and graphs.  


### Requirements
A web server with PHP installed and Curl extension enabled.  


### Install
Put the code at the root of your web server:
```
git clone https://github.com/gwen001/BBstats
```

Grab the datas from your favorite platform:
```
php data-grabber -p hackerone -a n -rr -tt -e
```

Enjoy the stats!
```
firefox http://127.0.0.1/BBstats/
```

### Grabber
```
Usage: php data-grabber.php -p <platform> [OPTIONS]

Options:
	-a	action to perform (default=N)
		   N: new, add new reports
		   U: update, add new reports and update the existing ones (title, bounty, state)
		   O: overwrite, add new reports and overwrite the existing ones
	-e	grab reputation as well
	-f	import from file
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
```


### Web
You can choose which graph you want to display in `config.php`.  
You create your own autotag and autorate configuration in `config.php`.  


### Todo
__grabber__
- add more platform (Bugcrowd, Cobalt)  
- find a better way to connect to Hackerone  

__db__
- manual bounty edit  

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


### Samples
[https://raw.githubusercontent.com/gwen001/BBstats/master/img/sample-listing.png?token=AFGZierP-h89-qf-9knaI_RpWG4YTcRqks5ZxjsPwA%3D%3D](Listing)
[https://raw.githubusercontent.com/gwen001/BBstats/master/img/sample-bounty.png?token=AFGZieM4SIvDbTH-TaqS15nE_pOt62wjks5Zxjt3wA%3D%3D](Bounties)
[https://raw.githubusercontent.com/gwen001/BBstats/master/img/sample-evolution.png?token=AFGZiULFAYapKK7T_piZ7ts2dicfD_BTks5ZxjuHwA%3D%3D](Evolution)
[https://raw.githubusercontent.com/gwen001/BBstats/master/img/sample-report-rating.png?token=AFGZiZ8Reh3UQKHZHEWyE1xekcUCEmJMks5ZxjuWwA%3D%3D](Reports rating)
[https://raw.githubusercontent.com/gwen001/BBstats/master/img/sample-program-repartition.png?token=AFGZiSmh23gopovmHFcnlvBaJyb7qqDpks5ZxjuwwA%3D%3D](Program repartition)

<br><br><br><br>

I don't believe in license.  
You can do want you want with this program.  
