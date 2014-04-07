#!/bin/sh

printf "Plugin name: "
read NAME

printf "Destination folder: "
read FOLDER

DEFAULT_NAME="WordPress Plugin Template"
DEFAULT_CLASS=${DEFAULT_NAME// /_}
DEFAULT_TOKEN=$( tr '[A-Z]' '[a-z]' <<< $DEFAULT_CLASS)
DEFAULT_SLUG=${DEFAULT_TOKEN//_/-}

CLASS=${NAME// /_}
TOKEN=$( tr '[A-Z]' '[a-z]' <<< $CLASS)
SLUG=${TOKEN//_/-}

git clone git@github.com:hlashbrooke/$DEFAULT_SLUG.git $FOLDER/$SLUG

echo "Removing git files..."

cd $FOLDER/$SLUG

rm -rf .git
rm README.md

echo "Updating extension files..."

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

cp readme.txt readme.tmp
sed "s/$DEFAULT_NAME/$NAME/g" readme.tmp > readme.txt
rm readme.tmp

cd lang
rm $DEFAULT_TOKEN.pot

cd ../includes
mv class-$SLUG.php class-$SLUG.php

cp class-$SLUG.php class-$SLUG.tmp
sed "s/$DEFAULT_CLASS/$CLASS/g" class-$SLUG.tmp > class-$SLUG.php
rm class-$SLUG.tmp

cp class-$SLUG.php class-$SLUG.tmp
sed "s/$DEFAULT_TOKEN/$TOKEN/g" class-$SLUG.tmp > class-$SLUG.php
rm class-$SLUG.tmp

cp class-$SLUG.php class-$SLUG.tmp
sed "s/$DEFAULT_SLUG/$SLUG/g" class-$SLUG.tmp > class-$SLUG.php
rm class-$SLUG.tmp


mv class-$SLUG-settings.php class-$SLUG-settings.php

cp class-$SLUG-settings.php class-$SLUG-settings.tmp
sed "s/$DEFAULT_CLASS/$CLASS/g" class-$SLUG-settings.tmp > class-$SLUG-settings.php
rm class-$SLUG-settings.tmp

cp class-$SLUG-settings.php class-$SLUG-settings.tmp
sed "s/$DEFAULT_TOKEN/$TOKEN/g" class-$SLUG-settings.tmp > class-$SLUG-settings.php
rm class-$SLUG-settings.tmp

cp class-$SLUG-settings.php class-$SLUG-settings.tmp
sed "s/$DEFAULT_SLUG/$SLUG/g" class-$SLUG-settings.tmp > class-$SLUG-settings.php
rm class-$SLUG-settings.tmp


cd /post-types
mv class-$SLUG-post_type.php class-$SLUG-post_type.php

cp class-$SLUG-post_type.php class-$SLUG-post_type.tmp
sed "s/$DEFAULT_CLASS/$CLASS/g" class-$SLUG-post_type.tmp > class-$SLUG-post_type.php
rm class-$SLUG-post_type.tmp

cp class-$SLUG-post_type.php class-$SLUG-post_type.tmp
sed "s/$DEFAULT_TOKEN/$TOKEN/g" class-$SLUG-post_type.tmp > class-$SLUG-post_type.php
rm class-$SLUG-post_type.tmp

cp class-$SLUG-post_type.php class-$SLUG-post_type.tmp
sed "s/$DEFAULT_SLUG/$SLUG/g" class-$SLUG-post_type.tmp > class-$SLUG-post_type.php
rm class-$SLUG-post_type.tmp

echo "Creating new git repo..."

cd ../..
git init

echo "Complete!"