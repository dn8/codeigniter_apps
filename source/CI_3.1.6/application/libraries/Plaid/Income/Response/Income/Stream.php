<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 2/25/18
 * Time: 6:35 PM
 */

namespace Plaid\Income\Response\Income;


use Plaid\Plaid;

/**
 * Class Stream
 *
 * @package Plaid\Income\Response\Income
 */
class Stream extends Plaid {

    /**
     * @var int
     */
    private $confidence;

    /**
     * @var int
     */
    private $days;

    /**
     * @var int
     */
    private $monthly_income;

    /**
     * @var string
     */
    private $name;

    /**
     * Stream constructor.
     *
     * @param $raw_response
     */
    public function __construct($raw_response) {
        parent::__construct($raw_response);
        $this->confidence = $this->raw_response->confidence;
        $this->days = $this->raw_response->days;
        $this->monthly_income = $this->raw_response->monthly_income;
        $this->name = $this->raw_response->name;
    }

    /**
     * @return int
     */
    public function getConfidence() {
        return $this->confidence;
    }

    /**
     * @return int
     */
    public function getDays() {
        return $this->days;
    }

    /**
     * @return int
     */
    public function getMonthlyIncome() {
        return $this->monthly_income;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }
}