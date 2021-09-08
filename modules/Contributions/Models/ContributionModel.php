<?php namespace Modules\Contributions\Models;

use CodeIgniter\Model;

class ContributionModel extends Model {
    protected $table = 'contributions';
    protected $primaryKey = 'id';
  
    protected $useAutoIncrement = true;
    
    protected $allowedFields = ['name', 'cost', 'created_by', 'deleted_at'];
    protected $useSoftDeletes = true;
  
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function viewAll() {
        $this->select('contributions.*, users.first_name, users.last_name');
        $this->where(['contributions.deleted_at' => NULL]);
        $this->join('users', 'contributions.created_by = users.id');
        return $this->get()->getResultArray();
    }
}