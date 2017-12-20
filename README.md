# AutoWiki
AutoWiki wiki-fies any simple set of text files, based on the titles of each one. 

On the example site, there are three files (without any extension): Autowiki, Syntax and Wiki. Therefore the words Autowiki, Syntax and Wiki appear as Wiki hyperlinks on any page that mentions them.

AutoWiki can be used as provided. But it is by no means a website, moreover it is an skeleton, in order to be configured and adapted easily.

AutoWiki follows the MVC paradigm (three directories, /m, /v, /c). The contents are in the model directory. Any HTML5 template would easily fit on the View directory. The Controller directory has the source code skeleton.

AutoWiki has no website editor. Pages should be edited as text. Sorry, I like using git, so I can write from my tablet. Nevertheless, it would be extremely easy to add one (on the Controller directory).

AutoWiki should be configured as this (nginx):
```
	location @rewrite {
		rewrite ^/([^/]*)$ /index.php?page=$1&$args;
	}
```
