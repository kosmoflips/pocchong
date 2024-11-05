# pocchong.de

on github since 2021-08-25


## file structure

 - [axon] db, image, other binary stuff
	 - Note things in this folder are mostly binary and are synced to OneDrive only
 - [cgi-bin] not in use but still keep it as a tradition
 - [dendron]
	 - [deco] css/js stuff
	 - [static] individual static pages (indexed)
	 - [static2] individual static pages (self-use, not indexed)
	 - independent sub-sites
 - [nerv] core php stuff


## notes

 - commits earlier than 2022-10-28 are migrated from back up repo, refer to author date.
 - data files: db, user hash, images, etc. aren't synced to git, but are stored in my OneDrive only. remember to load them together with "git clone"
 - earlier versions in perl/CGI were archived incompletely, so they may not load correctly.
 - earlier versions where php were stored in /cgi-bin would not load with PHP 8.1.2


## major stages
- [15-06-30] [first traced version/PerlCGI](https://github.com/kosmoflips/pocchong/tree/ccdf248b3e2a378e30ad52588caf134dfaddbbf2)
- [17-08-02] [MySQL to SQLite](https://github.com/kosmoflips/pocchong/tree/34deee1d0281016de228382bac82648155a713d7)
- [19-09-09] [Perl/CGI to PHP](https://github.com/kosmoflips/pocchong/tree/216588bc33b38997477e1ae0a6ba92adddc9fc98)
- [20-08-15] ["mare" stable](https://github.com/kosmoflips/pocchong/tree/f779c00f27ec0ee09e34c190efc37e020a94aa2a)
- [22-03-29] [leave /cgi-bin since php upgrade](https://github.com/kosmoflips/pocchong/tree/f1563a37e3eb09267cde6083e3f665be66f1ece1)
- [22-10-28] [local image hosting reconstruction](https://github.com/kosmoflips/pocchong/tree/c0f8b8b4cba5388ac7fb691aa9fd2a125aead819)
- [23-03-24] [css theme selector](https://github.com/kosmoflips/pocchong/tree/63873c09a8d3045e1717eadb9f243d4044719e67)
- [24-11-03] [major structural update](https://github.com/kosmoflips/pocchong/tree/85faf974074b334d13d981c278d0840d605e265f)
