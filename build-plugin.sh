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

printf "Include Grunt support (y/n): "
read GRUNT

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

git clone https://github.com/sjregan/$DEFAULT_SLUG.git $FOLDER/$SLUG

echo "Removing git files..."

mkdir -p $FOLDER
cd $FOLDER/$SLUG

rm -rf .git
rm README.md
rm build-plugin.sh
rm changelog.txt

if [ "$GRUNT" == "n" ]; then
	rm Gruntfile.js
	rm package.json
fi

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

if [ "$GRUNT" != "n" ]; then
	cp package.json package.tmp
	sed "s/__PLUGIN_URL__/$(echo $PLUGIN_URL | sed -e 's/\\/\\\\/g; s/\//\\\//g; s/&/\\\&/g')/g" package.tmp > package.json
	rm package.tmp

	cp package.json package.tmp
	sed "s/$DEFAULT_NAME/$NAME/g" package.tmp > package.json
	rm package.tmp

	cp package.json package.tmp
	sed "s/$DEFAULT_SLUG/$SLUG/g" package.tmp > package.json
	rm package.tmp
fi

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
mv class-$DEFAULT_SLUG.php class-$SLUG.php

cp class-$SLUG.php class-$SLUG.tmp
sed "s/$DEFAULT_CLASS/$CLASS/g" class-$SLUG.tmp > class-$SLUG.php
rm class-$SLUG.tmp

cp class-$SLUG.php class-$SLUG.tmp
sed "s/$DEFAULT_TOKEN/$TOKEN/g" class-$SLUG.tmp > class-$SLUG.php
rm class-$SLUG.tmp

cp class-$SLUG.php class-$SLUG.tmp
sed "s/$DEFAULT_SLUG/$SLUG/g" class-$SLUG.tmp > class-$SLUG.php
rm class-$SLUG.tmp


mv class-$DEFAULT_SLUG-settings.php class-$SLUG-settings.php

cp class-$SLUG-settings.php class-$SLUG-settings.tmp
sed "s/$DEFAULT_CLASS/$CLASS/g" class-$SLUG-settings.tmp > class-$SLUG-settings.php
rm class-$SLUG-settings.tmp

cp class-$SLUG-settings.php class-$SLUG-settings.tmp
sed "s/$DEFAULT_TOKEN/$TOKEN/g" class-$SLUG-settings.tmp > class-$SLUG-settings.php
rm class-$SLUG-settings.tmp

cp class-$SLUG-settings.php class-$SLUG-settings.tmp
sed "s/$DEFAULT_SLUG/$SLUG/g" class-$SLUG-settings.tmp > class-$SLUG-settings.php
rm class-$SLUG-settings.tmp


cd lib

mv class-$DEFAULT_SLUG-admin-api.php class-$SLUG-admin-api.php

cp class-$SLUG-admin-api.php class-$SLUG-admin-api.tmp
sed "s/$DEFAULT_CLASS/$CLASS/g" class-$SLUG-admin-api.tmp > class-$SLUG-admin-api.php
rm class-$SLUG-admin-api.tmp

cp class-$SLUG-admin-api.php class-$SLUG-admin-api.tmp
sed "s/$DEFAULT_TOKEN/$TOKEN/g" class-$SLUG-admin-api.tmp > class-$SLUG-admin-api.php
rm class-$SLUG-admin-api.tmp

cp class-$SLUG-admin-api.php class-$SLUG-admin-api.tmp
sed "s/$DEFAULT_SLUG/$SLUG/g" class-$SLUG-admin-api.tmp > class-$SLUG-admin-api.php
rm class-$SLUG-admin-api.tmp


if [ "$NEWREPO" == "y" ]; then
	echo "Initialising new git repo..."
	cd ../..
	git init
fi

echo "Complete!"
