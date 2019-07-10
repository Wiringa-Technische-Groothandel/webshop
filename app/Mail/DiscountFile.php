<?php

namespace WTG\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

/**
 * Discount file mailable.
 *
 * @package     WTG\Mail
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class DiscountFile extends Mailable
{
    use Queueable;

    const FILE_NAME = 'icc_data.txt';

    /**
     * @var string
     */
    protected $fileData;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var string
     */
    public $subject = '[WTG Webshop] - Kortingsbestand';

    /**
     * DiscountFile constructor.
     *
     * @param  string  $fileData
     * @param  string  $fileName
     */
    public function __construct(string $fileData, string $fileName = self::FILE_NAME)
    {
        $this->fileName = $fileName;
        $this->fileData = $fileData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this
            ->attachData($this->fileData, $this->fileName, [
                'mime' => 'text/plain'
            ])
            ->markdown('emails.discount-file');
    }
}
