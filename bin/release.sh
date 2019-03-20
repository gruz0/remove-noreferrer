#!/bin/bash

rm -rf svn

svn co https://plugins.svn.wordpress.org/remove-noreferer/ svn

cp -Rv assets/screenshots/* svn/assets/
cp -Rv assets/banners/* svn/assets/
cp -Rv assets/icons/* svn/assets/
cp -Rv LICENSE readme.txt remove-noreferrer.php svn/trunk/
