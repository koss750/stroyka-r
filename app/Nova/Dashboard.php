<?php

use Laravel\Nova\Card;

class Dashboard extends Card
{
    public function cards(Request $request)
    {
        return [
            (new Card())->html('<h1>Custom Tab Content</h1><p>This is some hardcoded HTML content.</p>'),
        ];
    }
}
