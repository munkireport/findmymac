#!/usr/local/munkireport/munkireport-python3

import subprocess
import os
import sys
import platform

sys.path.insert(0, '/usr/local/munkireport')

from munkilib import FoundationPlist

def get_findmy_legacy():

    # Get FindMy data from nvram
    cmd = ['/usr/sbin/nvram','-x','-p']
    proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                            stdin=subprocess.PIPE,
                            stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    (output, unused_error) = proc.communicate()

    findmy_plist = FoundationPlist.readPlistFromString(output)
    findmy_data = {'status':"Disabled"}

    if "fmm-mobileme-token-FMM" in findmy_plist:
        fmm_plist = FoundationPlist.readPlistFromString(findmy_plist["fmm-mobileme-token-FMM"])
        findmy_data['status'] = "Enabled"

        for item in fmm_plist:
            if item == 'username':
                findmy_data['email'] = fmm_plist[item]
            elif item == 'userInfo':
                try:
                    findmy_data['ownerdisplayname'] = fmm_plist[item]['InUseOwnerDisplayName']
                except:
                    continue
            elif item == 'personID':
                findmy_data['personid'] = fmm_plist[item]
            elif item == 'dataclassProperties':
                try:
                    findmy_data['hostname'] = fmm_plist[item]['com.apple.Dataclass.DeviceLocator']['hostname']
                except:
                    continue
            elif item == 'addTime':
                findmy_data['add_time'] = str(int(fmm_plist[item]))

    return findmy_data


def get_findmy():

    # Get FindMy data from on disk files
    if os.path.isfile('/Library/Preferences/com.apple.FindMyMac.plist'):
        info_path = "/Library/Preferences/com.apple.FindMyMac.plist"
    else:
        return {'status':"Disabled"}

    try:
        findmy_plist = FoundationPlist.readPlist(info_path)
    except Exception:
        return {}

    if "FMMEnabled" in findmy_plist and findmy_plist["FMMEnabled"] is True:
        findmy_data = {'status':"Enabled"}

        stat = os.stat(info_path)
        findmy_data['add_time'] = str(int(stat.st_mtime))

        # Get the user account that has an active FindMy
        findmy_data = process_user_prefs(findmy_data)

    else:
        findmy_data = {'status':"Disabled"}

    return findmy_data

def get_user_prefs():

    user_prefs = ""

    # Get all users' home folders
    cmd = ['/usr/bin/dscl', '.', '-readall', '/Users', 'NFSHomeDirectory']
    proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                            stdin=subprocess.PIPE,
                            stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    (output, unused_error) = proc.communicate()
    
    for user in output.decode("utf-8", errors="ignore").split('\n'):
        if 'NFSHomeDirectory' in user and '/var/empty' not in user:
            user_pref  = user.replace("NFSHomeDirectory: ", "")+'/Library/Preferences/MobileMeAccounts.plist'
            if os.path.isfile(user_pref):
                user_prefs = user_pref + "\n" + user_prefs

    return user_prefs[:-1]

def process_user_prefs(findmy_data):

    # Get user accounts with active internet accounts
    user_prefs = get_user_prefs()

    for user_pref in user_prefs.split('\n'):
        user_data = FoundationPlist.readPlist(user_pref)
        for account in user_data['Accounts']:
            for service in account['Services']:

                # Check if the user is signed into FindMy
                if service["Name"] == "FIND_MY_MAC" and "Enabled" in service and service["Enabled"] == True:
                    findmy_data['hostname'] = service['hostname']

                    findmy_data['email'] = account['AccountID']
                    findmy_data['ownerdisplayname'] = account['DisplayName']
                    findmy_data['personid'] = account['AccountDSID']
                    return findmy_data

    return findmy_data

def getDarwinVersion():
    """Returns the Darwin version."""
    # Catalina -> 10.15.7 -> 19.6.0 -> 19
    darwin_version_tuple = platform.release().split('.')
    return int(darwin_version_tuple[0]) 

def main():
    """Main"""

    # Remove old findmymac.sh script, if it exists
    if os.path.isfile(os.path.dirname(os.path.realpath(__file__))+'/findmymac.sh'):
        os.remove(os.path.dirname(os.path.realpath(__file__))+'/findmymac.sh')

    # If greater than macOS 14 (Darwin 23), run new FindMy method
    if getDarwinVersion() > 23:
        result = get_findmy()
    else:
        result = get_findmy_legacy()

    # Write findmy results to cache file
    cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
    output_plist = os.path.join(cachedir, 'findmymac.plist')
    FoundationPlist.writePlist(result, output_plist)
#    print FoundationPlist.writePlistToString(result)

if __name__ == "__main__":
    main()
