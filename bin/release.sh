#!/bin/bash

rm -rf svn

svn co https://plugins.svn.wordpress.org/remove-noreferrer/ svn

cp -Rv assets/screenshots/* svn/assets/
cp -Rv assets/banners/* svn/assets/
cp -Rv assets/icons/* svn/assets/
cp -Rv readme.txt index.php remove-noreferrer.php admin frontend inc svn/trunk/
