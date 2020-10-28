<?php

declare(strict_types=1);

namespace WTG\DTO;

use Carbon\CarbonInterface;

final class InvoiceFile
{
    protected string $filename;
    protected string $customerNumber;
    protected string $invoiceNumber;
    protected CarbonInterface $date;

    /**
     * InvoiceFile constructor.
     *
     * @param string $filename
     * @param string $customerNumber
     * @param string $invoiceNumber
     * @param CarbonInterface $date
     */
    public function __construct(string $filename, string $customerNumber, string $invoiceNumber, CarbonInterface $date)
    {
        $this->filename = $filename;
        $this->customerNumber = $customerNumber;
        $this->invoiceNumber = $invoiceNumber;
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getCustomerNumber(): string
    {
        return $this->customerNumber;
    }

    /**
     * @return string
     */
    public function getInvoiceNumber(): string
    {
        return $this->invoiceNumber;
    }

    /**
     * @return CarbonInterface
     */
    public function getDate(): CarbonInterface
    {
        return $this->date;
    }
}
