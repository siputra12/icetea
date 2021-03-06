<?php

namespace IceTea\Foundation\Exceptions;

use Exception;
use IceTea\Exceptions\InternalExceptionList;
use IceTea\Exceptions\Handler as InternalExceptionHandler;

class Handler
{

    /**
     * @var array
     */
    protected $dontReport = [];

    /**
     * @var \Exception
     */
    protected $exception;

    /**
     * @var string
     */
    protected $name;


    /**
     * Constructor.
     *
     * @param \Exception $e
     */
    public function __construct(Exception $e)
    {
        $this->exception = $e;
        $this->name      = get_class($this->exception);

    }//end __construct()


    public function report()
    {
        $this->buildReportContext();

        if (! $this->shouldntReport()) {
            throw $this->exception;
        }

        if ($this->isInternalException()) {
            $handler = new InternalExceptionHandler($this->exception);
            $handler->report();
        }

    }//end report()


    protected function shouldntReport()
    {
        return in_array($this->name, $this->dontReport);

    }//end shouldntReport()


    protected function buildReportContext()
    {
        $this->dontReport = array_merge(InternalExceptionList::$list, $this->dontReport);

    }//end buildReportContext()


    protected function isInternalException()
    {
        return in_array($this->name, InternalExceptionList::$list);

    }//end isInternalException()


}//end class
