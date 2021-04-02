#!/bin/sh

# FindMyMac information

# Set cache dir variables
DIR=$(dirname $0)
findmymac_manfiest_file="$DIR/cache/findmymac.txt"
findmymac_raw_data="$DIR/cache/findmymac_raw_data.plist"

#### FindMyMac Information ####
fmmdata=$(/usr/sbin/nvram -x -p | /usr/bin/awk '/fmm-mobileme-token/,/<\/data>/' | /usr/bin/awk '/<key>/ {f=0}; f && c==1; /<key>/ {f=1; c++}' | /usr/bin/grep -v 'data\|key' | /usr/bin/tr -d '\t' | /usr/bin/tr -d '\n')
if [[ -z $fmmdata ]]
then
    echo "Status = Disabled" > "$findmymac_manfiest_file"
else
    echo ${fmmdata} | /usr/bin/base64 --decode > $findmymac_raw_data
    echo "Status = Enabled" > "$findmymac_manfiest_file"

    email=$(/usr/libexec/PlistBuddy -c "Print username" $findmymac_raw_data)
    echo "Email = $email" >> "$findmymac_manfiest_file"
    
    owner_displayName=$(/usr/libexec/PlistBuddy -c "Print userInfo:InUseOwnerDisplayName" $findmymac_raw_data)
    echo "OwnerDisplayName = $owner_displayName" >> "$findmymac_manfiest_file"
    
    personID=$(/usr/libexec/PlistBuddy -c "Print personID" $findmymac_raw_data)
    echo "personID = $personID" >> "$findmymac_manfiest_file"
    
    hostname=$(/usr/libexec/PlistBuddy -c "Print dataclassProperties:com.apple.Dataclass.DeviceLocator:hostname" $findmymac_raw_data)
    echo "hostname = $hostname" >> "$findmymac_manfiest_file"

    add_time=$(/usr/libexec/PlistBuddy -c "Print addTime" $findmymac_raw_data)
    echo "add_time = $add_time" >> "$findmymac_manfiest_file"
fi

exit 0
