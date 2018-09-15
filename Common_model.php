<?php  defined('BASEPATH') OR exit('No direct script access allowed');
  /**A common model to Insert/Update/Delete/Get Data
   * 
   */
  class Common_model extends CI_Model
  {
  	
  	function __construct()
  	{
  		parent::__construct();
      $this->load->database();
    }
    public function insertData($table)
    {
      $this->db->insert($table,$this->input->post());
    }
    public function getData($table)
    {  

      $query = $this->db->get($table);
      return $query->result_array();
    }
    /*getWhereData method //returns json data*/
    public function getWhereData($table,$where)
    {
      $query=$this->db->get_where($table,array($where => $this->input->post($where)));
      echo json_encode($query->result_array());
    }
 public function getWhereData1($table,$where)
    {
      $query=$this->db->get_where($table,array($where => $this->input->post($where)));
      echo json_encode($query->row_array());
    }

    public function getWhereRow($table,$where)
    {
      $query=$this->db->get_where($table,array($where => $this->input->post($where)));
      return $query->row_array();
    }
    public function getWhereResult($table,$where)
    {
      $query=$this->db->get_where($table,array('parentid' => $this->input->post($where)));
      return $query->result_array();
    }
    public function getWhereRow1($table,$where){
      $query=$this->db->get_where($table,array('id' => $where));
      return $query->row_array();
    }
    public function updateData($table,$where,$array)
    {  
      $this->db->set($array);
      $this->db->where($where,$this->input->post('id'));
      $this->db->update($table);
    }
    public function deleteData($table,$where)
    {
     $this->db->where($where,$this->input->post('id'));
     $this->db->delete($table);
   }
   public function getJoinRecordNew($table1,$table2) {
    $query = $this->db->query("SELECT ".$table1.".id as id, ".$table1.".name as name, ".$table2.".name as countryName from ".$table1." INNER JOIN ".$table2." on state.countryid = country.id");
    return $query->result_array();
  }
  public function getJoinData($table1,$table2,$table3){
    $query = $this->db->query("SELECT ".$table1.".id as id, ".$table1.".name as name, ".$table1.".stateid as st, ".$table2.".name as stateName, ".$table3.".name as countryName from ".$table1." INNER JOIN ".$table2." on city.stateid = state.id INNER JOIN ".$table3." on state.countryid = country.id");
    return $query->result_array();
  }
  //for country and category
   public function getAllWhere($table, $where = '', $order_fld = '', $order_type = '', $limit = '', $offset = '') {
        if ($order_fld != '' && $order_type != '') {
            $this->db->order_by($order_fld, $order_type);
        }
        if ($where != '') {
            $this->db->like('id',$where);
            $this->db->or_like('name',$where);
        }
        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }
        $query = $this->db->get($table);
        return $query->result_array();
    }
    //for state table
     public function GetJoinRecord($table,$table2,$first,$second,$field_val,$where='',$order_fld='',$order_type='', $limit = '', $offset = '') {
        $this->db->select("$field_val");
        $this->db->from("$table");
        $this->db->join("$table2", "$table.$first = $table2.$second");

         if ($where != '') {
            $this->db->like('state.id',$where);
            $this->db->or_like('state.name',$where);
            $this->db->or_like('country.name',$where);

         }
        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }
        if(!empty($order_fld) && !empty($order_type)){
            $this->db->order_by($order_fld, $order_type);
        }
        $query = $this->db->get();
        return $query->result_array();
    
}
//for city & news table
public function GetJoinRecordThree($table1,$table2,$table3, $first,$second,$three,$four,$field_val='',$where="",$order_fld='',$order_type='', $limit = '', $offset = '') {
        if(!empty($field_val)){
            $this->db->select("$field_val");
        }else{
            $this->db->select("*");
        }
        $this->db->from("$table1");
        $this->db->join("$table2", "$table2.$second = $table1.$first",'inner');
        $this->db->join("$table3", "$table2.$three = $table3.$four",'inner');
        if(!empty($where)){
            
            $this->db->like('city.id',$where);
            $this->db->or_like('city.name',$where);
            $this->db->or_like('state.name',$where);
            $this->db->or_like('country.name',$where);
        }
        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }
        
        if(!empty($order_fld) && !empty($order_type)){
            $this->db->order_by($order_fld, $order_type);
        }
        $query = $this->db->get();
        return $query->result_array();
       
    }
    public function GetJoinRecordNews($table1,$table2,$table3, $first,$second,$three,$four,$field_val='',$where="",$order_fld='',$order_type='', $limit = '', $offset = '') {
        if(!empty($field_val)){
            $this->db->select("$field_val");
        }else{
            $this->db->select("*");
        }
        $this->db->from("$table1");
        $this->db->join("$table2", "$table1.$first = $table2.$second",'inner');
        $this->db->join("$table3", "$table1.$three = $table3.$four",'inner');
        if(!empty($where)){
            
            $this->db->like('news.id',$where);
            $this->db->or_like('news.title',$where);
            $this->db->or_like('news.date',$where);
            $this->db->or_like('news.content',$where);
        }
        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }
        
        if(!empty($order_fld) && !empty($order_type)){
            $this->db->order_by($order_fld, $order_type);
        }
        $query = $this->db->get();
        return $query->result_array();
       
    }

  
}
?>