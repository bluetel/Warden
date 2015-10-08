<?php

namespace Warden;

use Warden\Collector\CollectorParamBag;
use Warden\Exceptions;

/**
 * The analyser class checks the collectors data along with the limits
 *
 * @package Warden
 * @author Dan Cox
 */
class Analyser
{
    /**
     * An instance of the param bag from Warden
     *
     * @var \Warden\Collector\CollectorParamBag
     */
    protected $params;

    /**
     * Set up class dependencies
     *
     * @param \Warden\Collector\CollectorParamBag $params
     */
    public function __construct(CollectorParamBag $params)
    {
        $this->params = $params;
    }

    /**
     * Checks the limits against the actual recorded values and determines whether they have exceeded
     *
     * @return void
     */
    public function checkResults()
    {
        $data = $this->params->all();

        foreach ($data as $key => $value) {

            /**
             * This will need to use the appropriate method to
             * analyse the values depending on their type
             * for now, we assume they are integers
             */
            if ($value['value'] > $value['limit']) {

                // We throw the exception because the limit has been breached.
                throw new Exceptions\LimitExceededException(
                    $value['value'],
                    $value['limit'],
                    $key
                );
            }
        }
    }

} // END class Analyser
