<?php

namespace Laravel\Nova\Cards;
use Laravel\Nova\Card;

class CustomCard extends Card
{
    /**
     * The width of the card on the dashboard.
     *
     * @var int
     */
    public $width = '1/4';

    /**
     * The HTML content for the card.
     *
     * @var string
     */
    public $htmlContent;


    /**
     * Set the HTML content for the card.
     *
     * @param string $htmlContent
     * @return $this
     */
    public function withHtmlContent(string $htmlContent)
    {
        $this->htmlContent = $htmlContent;

        return $this;
    }

    /**
     * Get the component name for the card.
     *
     * @return string
     */
    public function component()
    {
        return 'custom-card';
    }
}