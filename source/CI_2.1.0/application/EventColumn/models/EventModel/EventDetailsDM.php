<?php

class EventModel_EventDetailsDM extends BaseDM {

    const ADMISSION_FREE = 'free';

	private $event_details_id;
	private $smoking;
	private $food_available;
	private $age_range;
    private $admission = self::ADMISSION_FREE;

	/**
	 * class construct method
	 *
	 * @access public
	 * @return Object
	 * @since  1.0
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * loads the event details from the EVENT_DETAILS table.
	 *
	 * @param  int $event_details_id
	 * @access public
	 * @return void
	 * @throws Exception
	 * @since  1.0
	 */
	public function load($id) {
		$query = $this->db->get_where("EVENT_DETAILS", array("event_details_id" => $id));

		if ($query->num_rows > 0) {
			$event_details = $query->row();

			foreach ($event_details as $column => $value) {
				if (property_exists($this, $column)) {
					$this->$column = $value;
				}
			}
		} else {
			throw new Exception("unable to load details for event_details_id '" . $id . "'");
		}
	}

	/**
	 * save the event details to the database.
	 *
	 * @access public
	 * @return mixed
	 * @since  1.0
	 */
	public function save() {
		/*
		 * check to see if the event details alraedy exist, if they do update $this->event_details_id,
		 * if they don't create a new entry, we won't update event details because it's not directly
		 * related to any single event or user and can be spread accross multiple events/users.
		 */
		$id = $this->eventDetailsExist();
		if ($id !== false) {
			$this->event_details_id = $id;
		} else {
			$this->insert();

            $this->event_details_id = $this->db->insert_id();
		}

		return $this->event_details_id;
	}

	/**
	 * check to see if event details exist
	 *
	 * @access public
	 * @return mixed (boolean false if they don't exist or event_details_id (int) if they do)
	 * @since  1.0
	 */
	public function eventDetailsExist() {
		$result = false;
		$details_array = array();

		$details_array['smoking'] = $this->smoking;
		$details_array['food_available'] = $this->food_available;
		$details_array['age_range'] = $this->age_range;
        $details_array['admission'] = $this->admission;

		$query = $this->db->get_where('EVENT_DETAILS', $details_array);

		if($query->num_rows > 0) {
			$result = $query->row()->event_details_id;
		}

		return $result;
	}

	/**
	 * insert a new event details row
	 *
	 * @access private
	 * @return unknown
	 * @since  1.0
	 */
	protected function insert() {
		$values = array();

		$values["smoking"] = empty($this->smoking) ? 'NO' : 'YES';
		$values["food_available"] = $this->food_available;
		$values["age_range"] = $this->age_range;
        $values["admission"] = $this->admission;

		return $this->db->insert("EVENT_DETAILS", $values);
	}

	/**
	 * this is only here to appease the parent class abstract method declaration
	 * we won't update event_details because one entry can be spread accross
	 * multiple events or users, therefore we will likely need the entry at some point
	 * and instead, when an entry needs to change we will simply insert a new one.
	 */
	protected function update() {

	}

	/**
	 * gets the event_details_id
	 *
	 * @return int
	 * @since  1.0
	 */
	public function getEventDetailsId() {
		return $this->event_details_id;
	}

	/**
	 * gets the smoking
	 *
	 * @return bool
	 * @since  1.0
	 */
	public function getSmoking() {
		return $this->smoking;
	}

	/**
	 * sets the smoking
	 *
	 * @param  bool
	 * @return Object
	 * @since  1.0
	 */
	public function setSmoking($smoking) {
		$this->smoking = strtolower($smoking);
		return $this;
	}

	/**
	 * gets the food_available
	 *
	 * @return bool
	 * @since  1.0
	 */
	public function getFoodAvailable() {
		return $this->food_available;
	}

	/**
	 * sets the food_available
	 *
	 * @param  bool
	 * @return Object
	 * @since  1.0
	 */
	public function setFoodAvailable($food_available) {
		$this->food_available = strtolower($food_available);
		return $this;
	}

	/**
	 * gets the age_range
	 *
	 * @return String
	 * @since  1.0
	 */
	public function getAgeRange() {
		return $this->age_range;
	}

	/**
	 * sets the age_range
	 *
	 * @param  String
	 * @return Object
	 * @since  1.0
	 */
	public function setAgeRange($age_range) {
		$this->age_range = strtolower($age_range);
		return $this;
	}

    /**
	 * sets the admission
	 *
	 * @param  String
	 * @return Object
	 * @since  1.0
	 */
	public function setAdmission($admission) {
        $this->admission = (is_numeric($admission)) ? $admission : self::ADMISSION_FREE;
        return $this;
    }

    /**
	 * gets the admission
	 *
	 * @return String
	 * @since  1.0
	 */
	public function getAdmission() {
        return $this->admission;
    }
}

?>
