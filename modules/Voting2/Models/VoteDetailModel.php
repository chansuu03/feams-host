<?php namespace Modules\Voting2\Models;

use CodeIgniter\Model;

class VoteDetailModel extends Model {
    protected $table = 'vote_details2';
    protected $primaryKey = 'id';
  
    protected $useAutoIncrement = true;
    
    protected $allowedFields = ['votes_id', 'user_id' ,'user_type'];

    public function candidateDetails($elecID, $userID) {
        $this->select('users.first_name, users.last_name, vote_details2.id, vote_details2.votes_id, votes2.voter_id, votes2.election_id, vote_details2.user_type');
        $this->where(['votes2.voter_id' => $userID, 'votes2.election_id' => $elecID]);
        $this->join('users', 'vote_details2.user_id = users.id', 'left');
        $this->join('votes2', 'vote_details2.votes_id = votes2.id', 'left');
        return $this->get()->getResultArray();
    }

    public function voteCounts($elecID) {
        $db = \Config\Database::connect();
        $results = $db->query('SELECT COUNT(user_id) as voteCount, users.first_name, users.last_name, type, votes2.election_id, vote_details2.user_id
        FROM vote_details2 
        JOIN users ON users.id = `vote_details2`.user_id 
        JOIN votes2 ON votes2.id = vote_details2.votes_id 
        WHERE votes2.election_id = '. $elecID .' GROUP BY(user_id) 
        ORDER BY COUNT(user_id) DESC');
        // echo '<pre>';
        // print_r($results->getResultArray());
        // die();
        return $results->getResultArray();
    }

    public function viewDetail($votesID) {
        $this->select('vote_details2.id, votes_id, user_type, users.first_name, users.last_name');
        $this->where(['votes_id' => $votesID]);
        $this->join('users', 'users.id = vote_details2.user_id');
        return $this->get()->getResultArray();
    }
}