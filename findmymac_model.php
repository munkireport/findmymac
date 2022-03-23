<?php
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
        // Translate findmymac strings to db fields
        $translate = array(
            'Status = ' => 'status',
            'OwnerDisplayName = ' => 'ownerdisplayname',
            'Email = ' => 'email',
            'personID = ' => 'personid',
            'add_time = ' => 'add_time',
            'hostname = ' => 'hostname');

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
    }
}
