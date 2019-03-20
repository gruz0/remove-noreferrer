#!/bin/bash

rm -rf svn

svn co https://plugins.svn.wordpress.org/remove-noreferrer/ svn

cp -Rv assets/screenshots/* svn/assets/
cp -Rv readme.txt remove-noreferrer.php svn/trunk/
