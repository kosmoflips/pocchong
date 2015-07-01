package Method_Config;
use strict;
use warnings;

#this file is for storing constants only.
#avoid putting one-time use vars here.
#ONCE ASSIGNED, CHANGE WITH CAUTION

our $ROOT=$ENV{DOCUMENT_ROOT};
our $META={
	SITE_TITLE=>'&#38899;&#26178;&#38632; &#65374;Fairy Aria&#65374;',
	DOMAIN=>'www.pocchong.de',
};
our $PATH={
	SAFE=>$ROOT.'/cgi-bin/nerv',
	TMP=>$ROOT.'/tmp',
	HTML=>$ROOT.'/html/main', #mainly templates
	HTML_STATIC=>$ROOT.'/html/static', #individual html/php pages
	HTML_ADMIN=>$ROOT.'/html/admin', #templates for cgi use
	ELEM=>$ROOT.'/elem',
	DATA=>$ROOT.'/elem/data',
	EXEC_TMPL=>$ROOT.'/html/page/exec/tmpl',
	REMOTE=>'/home2/ghostelf/public_html/squimp/kiyoko/pocchong',
};
{#check dir /tmp/
	if (!-d $PATH->{TMP}) {
		use File::Path qw/mkpath/;
		mkpath $PATH->{TMP};
	}
}
our $FILE={
	NOTFOUND=>$ROOT.'/404.shtml',
	PREVIEW_TMPL=>$PATH->{HTML_ADMIN}.'/preview.tmpl',
	RSS_DAT=>$PATH->{DATA}.'/rss.dat',
	RSS_XML=>'/feed.rss',
};
our $PROG={
	TARGZ=>'/bin/tar',
	'7Z'=>'"D:\Program Files\7-Zip\7z.exe"',
	SQLDUMP=>'/usr/bin/mysqldump',
};
our $DBINFO={
	db=>"ghostelf_pocchong",
	usr=>"ghostelf_kiyo",
	pw=>"q[/q[bezf7huystdues", #Makise Kurisu, Steins;Gate ep9
	host=>"localhost"
};
our $CONSTANT={ #other pre-given stuff
	GPLUS_UID=>'109648214869266774446',
	IMG_PLACEHOLDER=>'https://lh3.googleusercontent.com/-QrbPPrL3jtM/VPv193qBjrI/AAAAAAAAaiU/IOucFrQpXsM/s800/img_placeholder.png',
	YEAR_BEGIN=>2006,
};
our $ARCHIV={ #for archiv.cgi and admin's showlist
	URL=>'/archiv',
	URL_ADMIN=>'/a/showlist.cgi',
	H3=>'Archiv',
	LIMIT=>100,
	TMPL=>$PATH->{HTML}.'/archiv.tmpl',
	TMPL_ADMIN=>$PATH->{HTML_ADMIN}.'/showlist.tmpl',
	EDIT=>'/a/showlist.cgi',
	SPECIAL_TMPL=>{
		5=>$PATH->{HTML}.'/archiv_cdlist.tmpl',
	}
};
our $SECTOR={ #cgi/html locations & sql,default title assignments. make sure htaccess is sync-ed with htaccess
	5=>{ #post. tag=3,self=null
		SQL=>'post',
		URL=>'/musik/collection', #htaccess, map to post.cgi?tag=2
		H3=>'CD Collection',
		CATEGORY=>'musik',
		TAG=>3,
		LIMIT=>3,
		EDIT_TMPL=>$PATH->{HTML_ADMIN}.'/edit_post.tmpl',
		TMPL=>$PATH->{HTML}.'/cdlist.tmpl',
		EDIT=>'/a/edit_post.cgi', #tag=2
	},
	4=>{ #post. tag=2,self=null
		SQL=>'post',
		URL=>'/frag', #htaccess, map to post.cgi?tag=2
		H3=>'General Updates',
		CATEGORY=>'Update',
		TAG=>2,
		LIMIT=>15,
		EDIT_TMPL=>$PATH->{HTML_ADMIN}.'/edit_post.tmpl',
		TMPL=>$PATH->{HTML}.'/frag.tmpl',
		EDIT=>'/a/edit_post.cgi', #tag=2
	},
	3=>{ #post. tag=1,self=null
		SQL=>'post',
		URL=>'/post',
		H3=>'Diary',
		CATEGORY=>'Diary',
		TAG=>1,
		LIMIT=>3,
		EDIT_TMPL=>$PATH->{HTML_ADMIN}.'/edit_post.tmpl',
		TMPL=>$PATH->{HTML}.'/post.tmpl',
		EDIT=>'/a/edit_post.cgi',
	},
	2=>{ #photo
		SQL=>'photo',
		URL=>'/photo',
		LIMIT=>15,
		H3=>'Photos',
		CATEGORY=>'Photo',
		TMPL=>$PATH->{HTML}.'/photo.tmpl',
		EDIT=>'/a/edit_photo.cgi',
		EDIT_TMPL=>$PATH->{HTML_ADMIN}.'/edit_photo.tmpl',
		TAG=>{ #in table, column called "self"
			1=>'public',
			2=>'limited',
			3=>'unlisted',
			4=>'private',
		},
	},
	1=>{ #mygirls. tag=1/2/3 -> the29a/b/2009pre
		SQL=>'mygirls',
		URL=>'/mygirls',
		LIMIT=>15,
		H3=>'&#24187;&#24819;&#35519;&#21644;', #escaped 'gensou cyouwa'
		CATEGORY=>'Art',
		TMPL=>$PATH->{HTML}.'/mygirls.tmpl',
		EDIT=>'/a/edit_mygirls.cgi',
		EDIT_TMPL=>$PATH->{HTML_ADMIN}.'/edit_mygirls.tmpl',
	}
};

1;