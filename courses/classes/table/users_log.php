<?php
/**
 * Test table class to be put in test_table.php of root of Moodle installation.
 *  for defining some custom column names and proccessing
 * Username and Password feilds using custom and other column methods.
 */
class users_log extends table_sql {

    /**
     * Constructor
     * @param int $uniqueid all tables have to have a unique id, this is used
     *      as a key when storing table properties like sort order in the session.
     */
    function __construct($uniqueid) {
        parent::__construct($uniqueid);
        // Define the list of columns to show.
        $columns = array('serialno','user','totalduration');
        $this->define_columns($columns);

        // Define the titles of columns to show in header.
        $headers = array('S No','User','Watched Duration');
        $this->define_headers($headers);
    }
    
    function col_totalduration($values){
        // Calculate hours, minutes, and seconds
        $hours = floor($values->totalduration / 3600);
        $minutes = floor(($values->totalduration % 3600) / 60);
        $seconds = $values->totalduration % 60;
    
        // Format the result
        $formatted_duration = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
    
        return $formatted_duration;
    }
    

    /**
     * This function is called for each data row to allow processing of
     * columns which do not have a *_cols function.
     * @return string return processed value. Return NULL if no change has
     *     been made.
     */
    function other_cols($colname, $value) {
        // For security reasons we don't want to show the password hash.
        if ($colname == 'password') {
            return "****";
        }
    }
}