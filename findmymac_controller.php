<?php

/**
 * FindMyMac module class
 *
 * @package munkireport
 * @author poundbangbash/clburlison
 **/
class findmymac_controller extends Module_controller
{
    /*** Protect methods with auth! ****/
    public function __construct()
    {
        // Store module path
        $this->module_path = dirname(__FILE__);
    }

  /**
   * Get findmymac widget data
   *
   * @author clburlison
   **/
    public function get_stats()
    {        
        $sql = "SELECT COUNT(CASE WHEN `status` = 'Enabled' THEN 1 END) AS 'Enabled',
                      COUNT(CASE WHEN `status` = 'Disabled' THEN 1 END) AS 'Disabled'
                      FROM findmymac
                      LEFT JOIN reportdata USING (serial_number)
                      ".get_machine_group_filter();

        $queryobj = new Findmymac_model();
        $out = [];
        foreach($queryobj->query($sql)[0] as $label => $value){
            $out[] = ['label' => $label, 'count' => $value];
        }
        
        jsonView($out);
    }

  /**
 * Get findmymac data for serial_number
 *
 * @param string $serial serial number
 **/
    public function get_data($serial_number = '')
    {
        $findmymac = new Findmymac_model($serial_number);
        jsonView($findmymac->rs);
    }
} // END class Findmymac_controller
