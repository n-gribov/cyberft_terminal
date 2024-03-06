#!/bin/bash
 
# Recursive file convertion windows-1251 --> utf-8
# Place this file in the root of your site, add execute permission and run
# Converts *.php, *.html, *.css, *.js files.
# To add file type by extension, e.g. *.cgi, add '-o -name "*.cgi"' to the find command
 
#find ./ -type f |
find ./ -type f \( -iname "*" ! -iname "iconv.sh" \) |
while read file
do
  echo " $file"
  iconv -f WINDOWS-1251 -t UTF-8 $file > $file.icv && mv $file.icv $file
done
