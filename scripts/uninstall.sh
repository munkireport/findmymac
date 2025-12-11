#!/bin/bash

# Remove findmymac.sh script
rm -f "${MUNKIPATH}preflight.d/findmymac.sh"

# Remove findmymac.py script
rm -f "${MUNKIPATH}preflight.d/findmymac.py"

# Remove findmymac.txt file
rm -f "${MUNKIPATH}preflight.d/cache/findmymac.txt"

# Remove findmymac_raw_data.plist file
rm -f "${MUNKIPATH}preflight.d/cache/findmymac_raw_data.plist"

# Remove findmymac.plist file
rm -f "${MUNKIPATH}preflight.d/cache/findmymac.plist"
