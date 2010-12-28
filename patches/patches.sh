#!/bin/sh
#
# Runs all patches in the different folders
#export IMAGE_DIR=image_file_avhdd_v2_101220

for i in * ;do
	#ignoramos los directorios
	[ ! -d "$i" ] && continue
	[ ! -f "$i/patch.sh" ] && continue
	echo "Processing $i"
	# Source shell script for speed.
	(
		# trap - INT QUIT TSTP
		# set start
		cd $i
		pwd
		"./patch.sh"
		cd ..
	)
done
