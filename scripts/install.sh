#!/bin/bash

# findmymac controller
CTL="${BASEURL}index.php?/module/findmymac/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/findmymac.py" -o "${MUNKIPATH}preflight.d/findmymac.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/findmymac.py"

	# Set preference to include this file in the preflight check
	setreportpref "findmymac" "${CACHEPATH}findmymac.plist"

	# Delete the older style txt cache file
	if [[ -f "${MUNKIPATH}preflight.d/cache/findmymac.txt" ]] ; then
		rm -f "${MUNKIPATH}preflight.d/cache/findmymac.txt"
	fi

	# Delete the older style txt cache file
	if [[ -f "${MUNKIPATH}preflight.d/cache/findmymac_raw_data.plist" ]] ; then
		rm -f "${MUNKIPATH}preflight.d/cache/findmymac_raw_data.plist"
	fi

	# Delete the older sh script file
	if [[ -f "${MUNKIPATH}preflight.d/findmymac.sh" ]] ; then
		rm -f "${MUNKIPATH}preflight.d/findmymac.sh"
	fi

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/findmymac.py"

	# Signal that we had an error
	ERR=1
fi
