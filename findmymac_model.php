<?php

use CFPropertyList\CFPropertyList;

class findmymac_model extends \Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'findmymac'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rs['status'] = '';
        $this->rs['ownerdisplayname'] = '';
        $this->rs['email'] = '';
        $this->rs['personid'] = '';
        $this->rs['hostname'] = '';
        $this->rs['add_time'] = null;

        if ($serial) {
            $this->retrieve_record($serial);
        }

        $this->serial = $serial;
    }

    // ------------------------------------------------------------------------

    /**
     * Process data sent by postflight
     *
     * @param string data
     *
     **/
    public function process($data)
    {

        // If data is empty, throw error
        if (! $data) {
            throw new Exception("Error Processing FindMyMac Module Request: No data found", 1);
        } else if (substr( $data, 0, 30 ) != '<?xml version="1.0" encoding="' ) { // Else if old style text, process with old text based handler
            // Translate findmymac strings to db fields
            $translate = array(
                'Status = ' => 'status',
                'OwnerDisplayName = ' => 'ownerdisplayname',
                'Email = ' => 'email',
                'personID = ' => 'personid',
                'hostname = ' => 'hostname',
                'add_time = ' => 'add_time');

            // Clear any previous data we had
            foreach ($translate as $search => $field) {
                if ($field == "add_time"){
                    $this->$field = null;
                } else {
                    $this->$field = '';
                }
            }

            // Parse data
            foreach (explode("\n", $data) as $line) {
            // Translate standard entries
                foreach ($translate as $search => $field) {
                    if (strpos($line, $search) === 0) {
                        $value = substr($line, strlen($search));
                        
                        // Set add time to null if it is blank
                        if ($field == "add_time" && $value == ""){
                            $this->$field = null;
                        } else {
                            $this->$field = $value;
                        }
                        break;
                    }
                }
            } // End foreach explode lines

            // Save the data
            $this->save();

        } else { // Else process with new XML handler

            // Process incoming findmymac.plist
            $parser = new CFPropertyList();
            $parser->parse($data, CFPropertyList::FORMAT_XML);
            $plist = $parser->toArray();

            // Process each of the items
            foreach (array('status', 'ownerdisplayname', 'email', 'personid', 'hostname', 'add_time') as $item) {

                // If key exists and is zero, set it to zero
                if (array_key_exists($item, $plist) && $plist[$item] === 0) {
                    $this->$item = 0;
                // Else if key does not exist in $plist, null it
                } else if (!array_key_exists($item, $plist) || $plist[$item] == '' || $plist[$item] == "{}" || $plist[$item] == "[]") {
                    $this->$item = null;

                // Set the db fields to be the same as those in the preference file
                } else {
                    $this->$item = $plist[$item];
                }
            }

            // Save the data, Find the Mac
            $this->save();
        }
    }
}
