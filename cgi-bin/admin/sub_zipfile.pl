#!/usr/bin/perlml

use strict;
use warnings;

#requires tar and gzip on system
#ref: http://ss64.com/bash/tar.html
# tar -zcvf compressFileName folderToCompress

my ($infile,$outfile)=@ARGV; #self, string (has no ext), A REF
# tar czf name_of_archive_file.tar.gz name_of_directory_to_tar
$outfile.='.tgz' if $outfile !~/\.tgz/i;
@cmd=(
	'/bin/tar',
	'-zcvf',
	$outfile,
	$infile
);
eval { $stat=system("@cmd"); };
