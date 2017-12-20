<?php
class parser {
	private $patterns, $replacements;

	public function __construct() {
	
		// Titles
		$this->regexes[1][]="/^==== (.+?) ====$/m";
		$this->regexes[1][]="/^=== (.+?) ===$/m";
		$this->regexes[1][]="/^== (.+?) ==$/m";
		$this->regexes[2][]="<h3>$1</h3>";
		$this->regexes[2][]="<h2>$1</h2>";
		$this->regexes[2][]="<h1>$1</h1>";
		
		// Formatting
		$this->regexes[1][]="/\'\'\'\'\'(.+?)\'\'\'\'\'/s";
		$this->regexes[1][]="/\'\'\'(.+?)\'\'\'/s";
		$this->regexes[1][]="/\'\'(.+?)\'\'/s";
		$this->regexes[2][]="<strong><em>$1</em></strong>";
		$this->regexes[2][]="<strong>$1</strong>";
		$this->regexes[2][]="<em>$1</em>";

		// Special
		$this->regexes[1][]="/^----+(\s*)$/m";						// Horizontal line
		$this->regexes[1][]="/\[\[(img):((ht|f)tp(s?):\/\/(.+?))( (.+))*\]\]/i";	// (File|img):(http|https|ftp) aka image
		$this->regexes[1][]="/\[((news|(ht|f)tp(s?)|irc):\/\/(.+?))( (.+))\]/i";	// Other urls with text
		$this->regexes[1][]="/\[((news|(ht|f)tp(s?)|irc):\/\/(.+?))\]/i";		// Other urls without text
		$this->regexes[2][]="<hr/>";
		$this->regexes[2][]="<img src=\"$2\" alt=\"$6\"/>";
		$this->regexes[2][]="<a href=\"$1\">$7</a>";
		$this->regexes[2][]="<a href=\"$1\">$1</a>";

		// Wiki
		$this->regexes[1][]="/\[\[([^ ]*)\]\]/i";					// Internal redirect
		$this->regexes[1][]="/\[\[([^ ]*) ([^\]]*)\]\]/i";				// Internal redirect Titled
		$this->regexes[2][]="<a href=\"$1\">$1</a>";
		$this->regexes[2][]="<a href=\"$1\">$2</a>";

		// Indentations
		$this->regexes[1][]="/[\n\r]: *.+([\n\r]:+.+)*/";				// Indentation first pass
		$this->regexes[1][]="/^:(?!:) *(.+)$/m";					// Indentation second pass
		$this->regexes[1][]="/([\n\r]:: *.+)+/";					// Subindentation first pass
		$this->regexes[1][]="/^:: *(.+)$/m";						// Subindentation second pass
		$this->regexes[2][]="\n<dl>$0\n</dl>"; 						// Newline is here to make the second pass easier
		$this->regexes[2][]="<dd>$1</dd>";
		$this->regexes[2][]="\n<dd><dl>$0\n</dl></dd>";
		$this->regexes[2][]="<dd>$1</dd>";

		// Ordered list
		$this->regexes[1][]="/[\n\r]?#.+([\n|\r]#.+)+/";				// First pass, finding all blocks
		$this->regexes[1][]="/[\n\r]#(?!#) *(.+)(([\n\r]#{2,}.+)+)/";			// List item with sub items of 2 or more
		$this->regexes[1][]="/[\n\r]#{2}(?!#) *(.+)(([\n\r]#{3,}.+)+)/";		// List item with sub items of 3 or more
		$this->regexes[1][]="/[\n\r]#{3}(?!#) *(.+)(([\n\r]#{4,}.+)+)/";		// List item with sub items of 4 or more
		$this->regexes[2][]="\n<ol>\n$0\n</ol>";
		$this->regexes[2][]="\n<li>$1\n<ol>$2\n</ol>\n</li>";
		$this->regexes[2][]="\n<li>$1\n<ol>$2\n</ol>\n</li>";
		$this->regexes[2][]="\n<li>$1\n<ol>$2\n</ol>\n</li>";

		// Unordered list
		$this->regexes[1][]="/[\n\r]?\*.+([\n|\r]\*.+)+/";				// First pass, finding all blocks
		$this->regexes[1][]="/[\n\r]\*(?!\*) *(.+)(([\n\r]\*{2,}.+)+)/";		// List item with sub items of 2 or more
		$this->regexes[1][]="/[\n\r]\*{2}(?!\*) *(.+)(([\n\r]\*{3,}.+)+)/";		// List item with sub items of 3 or more
		$this->regexes[1][]="/[\n\r]\*{3}(?!\*) *(.+)(([\n\r]\*{4,}.+)+)/";		// List item with sub items of 4 or more
		$this->regexes[2][]="\n<ul>\n$0\n</ul>";
		$this->regexes[2][]="\n<li>$1\n<ul>$2\n</ul>\n</li>";
		$this->regexes[2][]="\n<li>$1\n<ul>$2\n</ul>\n</li>";
		$this->regexes[2][]="\n<li>$1\n<ul>$2\n</ul>\n</li>";

		// List items
		$this->regexes[1][]="/^[#\*]+ *(.+)$/m";					// Wraps all list items to <li/>
		$this->regexes[2][]="<li>$1</li>";

		// Newlines (TODO: make it smarter and so that it groupd paragraphs)
		$this->regexes[1][]="/^(?!<li|dd).+(?=(<a|strong|em|img)).+$/mi";		// Ones with breakable elements (TODO: Fix )
		$this->regexes[1][]="/^[^><\n\r]+$/m";						// Ones with no elements
		$this->regexes[2][]="$0<br><br>";
		$this->regexes[2][]="$0<br><br>";
	}
	public function parse($input) {
		$output=(!empty($input))?preg_replace($this->regexes[1],$this->regexes[2],$input):false;
		return $output;
	}
}
