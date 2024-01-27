<?php
namespace app\helper;

trait helperfun
{
    // its check table exjist or no
    protected function tablEexjist($table)
    {
        $sql = "SHOW TABLES FROM $this->db_name LIKE '$table'";
        if ($tableindb = $this->mysqli->query($sql)) {
            if ($tableindb->num_rows == 1) {
                return true;
            } else {
                array_push(
                    $this->result,
                    "$table Table dose not exjist this database $this->db_name"
                );
                return false;
            }
        }
    }
}
