#!/bin/bash

printf "Destination folder: "
read FOLDER

printf "Plugin name: "
read NAME

printf "Plugin URL: "
read PLUGIN_URL

printf "Author Name: "
read AUTHOR_NAME

printf "Author URL: "
read AUTHOR_URL

printf "Text Domain (for translations): "
read TEXT_DOMAIN

printf "Initialise new git repo (y/n): "
read NEWREPO

DEFAULT_NAME="WordPress Plugin Template"
DEFAULT_CLASS=${DEFAULT_NAME// /_}
DEFAULT_TOKEN=$( tr '[A-Z]' '[a-z]' <<< $DEFAULT_CLASS)
DEFAULT_SLUG=${DEFAULT_TOKEN//_/-}

CLASS=${NAME// /_}
TOKEN=$( tr '[A-Z]' '[a-z]' <<< $CLASS)
SLUG=${TOKEN//_/-}
TODAYS_DATE=$(date +%Y-%m-%d)

if [[ -z "${TEXT_DOMAIN// }" ]]; then
	TEXT_DOMAIN=$SLUG
fi

mkdir $FOLDER/$SLUG
cp -Rp . $FOLDER/$SLUG/

echo "Removing git files..."

mkdir -p $FOLDER
cd $FOLDER/$SLUG

rm -rf .git
rm -rf .idea
rm README.md
rm build-plugin.sh
rm changelog.txt

echo "Updating plugin files..."

mv $DEFAULT_SLUG.php $SLUG.php

cp $SLUG.php $SLUG.tmp
sed "s/$DEFAULT_NAME/$NAME/g" $SLUG.tmp > $SLUG.php
rm $SLUG.tmp

cp $SLUG.php $SLUG.tmp
sed "s/$DEFAULT_SLUG/$SLUG/g" $SLUG.tmp > $SLUG.php
rm $SLUG.tmp

cp $SLUG.php $SLUG.tmp
sed "s/$DEFAULT_TOKEN/$TOKEN/g" $SLUG.tmp > $SLUG.php
rm $SLUG.tmp

cp $SLUG.php $SLUG.tmp
sed "s/$DEFAULT_CLASS/$CLASS/g" $SLUG.tmp > $SLUG.php
rm $SLUG.tmp

cp $SLUG.php $SLUG.tmp
sed "s/__PLUGIN_URL__/$(echo $PLUGIN_URL | sed -e 's/\\/\\\\/g; s/\//\\\//g; s/&/\\\&/g')/g" $SLUG.tmp > $SLUG.php
rm $SLUG.tmp

cp $SLUG.php $SLUG.tmp
sed "s/__AUTHOR_NAME__/$(echo $AUTHOR_NAME | sed -e 's/\\/\\\\/g; s/\//\\\//g; s/&/\\\&/g')/g" $SLUG.tmp > $SLUG.php
rm $SLUG.tmp

cp $SLUG.php $SLUG.tmp
sed "s/__AUTHOR_URL__/$(echo $AUTHOR_URL | sed -e 's/\\/\\\\/g; s/\//\\\//g; s/&/\\\&/g')/g" $SLUG.tmp > $SLUG.php
rm $SLUG.tmp

cp $SLUG.php $SLUG.tmp
sed "s/__TEXT_DOMAIN__/$(echo $TEXT_DOMAIN | sed -e 's/\\/\\\\/g; s/\//\\\//g; s/&/\\\&/g')/g" $SLUG.tmp > $SLUG.php
rm $SLUG.tmp

cp readme.txt readme.tmp
sed "s/$DEFAULT_NAME/$NAME/g" readme.tmp > readme.txt
rm readme.tmp

cp readme.txt readme.tmp
sed "s/__AUTHOR_NAME__/$(echo $AUTHOR_NAME | sed -e 's/\\/\\\\/g; s/\//\\\//g; s/&/\\\&/g')/g" readme.tmp > readme.txt
rm readme.tmp

cp readme.txt readme.tmp
sed "s/__AUTHOR_URL__/$(echo $AUTHOR_URL | sed -e 's/\\/\\\\/g; s/\//\\\//g; s/&/\\\&/g')/g" readme.tmp > readme.txt
rm readme.tmp

cp readme.txt readme.tmp
sed "s/__TODAYS_DATE__/$TODAYS_DATE/g" readme.tmp > readme.txt
rm readme.tmp

cd lang
mv $DEFAULT_SLUG.pot $SLUG.pot

cp $SLUG.pot $SLUG.tmp
sed "s/$DEFAULT_NAME/$NAME/g" $SLUG.tmp > $SLUG.pot
rm $SLUG.tmp

cp $SLUG.pot $SLUG.tmp
sed "s/$DEFAULT_CLASS/$CLASS/g" $SLUG.tmp > $SLUG.pot
rm $SLUG.tmp

cp $SLUG.pot $SLUG.tmp
sed "s/$DEFAULT_TOKEN/$TOKEN/g" $SLUG.tmp > $SLUG.pot
rm $SLUG.tmp

cp $SLUG.pot $SLUG.tmp
sed "s/$DEFAULT_SLUG/$SLUG/g" $SLUG.tmp > $SLUG.pot
rm $SLUG.tmp

cd ../includes

cp plugin.php plugin.tmp
sed "s/$DEFAULT_CLASS/$CLASS/g" plugin.tmp > plugin.php
rm plugin.tmp

cp plugin.php plugin.tmp
sed "s/$DEFAULT_TOKEN/$TOKEN/g" plugin.tmp > plugin.php
rm plugin.tmp

cp plugin.php plugin.tmp
sed "s/$DEFAULT_SLUG/$SLUG/g" plugin.tmp > plugin.php
rm plugin.tmp

cp settings.php settings.tmp
sed "s/$DEFAULT_CLASS/$CLASS/g" settings.tmp > settings.php
rm settings.tmp

cp settings.php settings.tmp
sed "s/$DEFAULT_TOKEN/$TOKEN/g" settings.tmp > settings.php
rm settings.tmp

cp settings.php settings.tmp
sed "s/$DEFAULT_SLUG/$SLUG/g" settings.tmp > settings.php
rm settings.tmp

cd lib

cp admin-api.php admin-api.tmp
sed "s/$DEFAULT_CLASS/$CLASS/g" admin-api.tmp > admin-api.php
rm admin-api.tmp

cp admin-api.php admin-api.tmp
sed "s/$DEFAULT_TOKEN/$TOKEN/g" admin-api.tmp > admin-api.php
rm admin-api.tmp

cp admin-api.php admin-api.tmp
sed "s/$DEFAULT_SLUG/$SLUG/g" admin-api.tmp > admin-api.php
rm admin-api.tmp

if [ "$NEWREPO" == "y" ]; then
	echo "Initialising new git repo..."
	cd ../..
	git init
fi

echo "Complete!"
