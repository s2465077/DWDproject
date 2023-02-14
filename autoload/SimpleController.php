<?php
// Class that provides methods for working with the data at 'quizForm'.

class SimpleController {
	private $mapper;

	public function __construct($table)
    {
		global $f3;						// needed for $f3->get()
		$this->mapper = new DB\SQL\Mapper($f3->get('DB'), $table);	// create DB query mapper object
	}

	public function putIntoDatabase($data)
	{
		$this->mapper->name   = $data["name"];
		$this->mapper->MBTI = $data["MBTI"];
        $this->mapper->ratingQ1 = $data["ratingQ1"];
        $this->mapper->ratingQ2 = $data["ratingQ2"];
        $this->mapper->ratingQ3 = $data["ratingQ3"];
        $this->mapper->ratingQ4 = $data["ratingQ4"];
        $this->mapper->ratingQ5 = $data["ratingQ5"];
        $this->mapper->ratingQ6 = $data["ratingQ6"];
        $this->mapper->ratingQ7 = $data["ratingQ7"];
        $this->mapper->ratingQ8 = $data["ratingQ8"];
        $this->mapper->ratingQ9 = $data["ratingQ9"];
        $this->mapper->ratingQ10 = $data["ratingQ10"];
        $this->mapper->questionnaire1   = $data["questionnaire1"];
        $this->mapper->questionnaire2   = $data["questionnaire2"];
        $this->mapper->questionnaire3   = $data["questionnaire3"];
        $this->mapper->questionnaire4   = $data["questionnaire4"];
		$this->mapper->save();					 // save new record with these fields
	}

    public function getUserTableFromStr($str) {
        $list = $this->mapper->load(["name LIKE ?", "%" . $str . "%"]);  // load DB record matching the given name
        return $list;
    }


}