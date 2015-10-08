<?php

namespace Warden;

use Warden\Collector\CollectorParamBag;
use Warden\Exceptions;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

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
     * An instance of the symfony expression language class
     *
     * @var \Symfony\Component\ExpressionLanguage\ExpressionLanguage
     */
    protected $expression;

    /**
     * Set up class dependencies
     *
     * @param \Warden\Collector\CollectorParamBag $params
     * @param \Symfony\Component\ExpressionLanguage\ExpressionLanguage $expression
     */
    public function __construct(CollectorParamBag $params, ExpressionLanguage $expression = NULL)
    {
        $this->params = $params;
        $this->expression = (!is_null($expression) ? $expression : new ExpressionLanguage);
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

            $failed = $this->expression->evaluate(
                $value['expression'],
                array(
                    'value'     => $value['value'],
                    'limit'     => $value['limit']
                )
            );

            if ($failed) {

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
