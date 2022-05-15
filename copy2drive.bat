REM @echo off

REM run this script to make an immediate file copy to drive -- good for unfinalised develop that's not good for git-update

REM local dir for github development & for localhost testing
SET localdir=D:\kiyoland\localhost\pocchong
REM drive dir is for back up, may not be stable, files only, NOT version controlled
SET drivedir=D:\Users\kosmo\OneDrive\pocchong

robocopy %localdir% %drivedir% /e /v /mir /xd .git /xf .git .gitignore *.bat error.log readme.md desktop.ini