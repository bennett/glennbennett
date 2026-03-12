<?php
class Breweries {
    private $url = 'https://api.openbrewerydb.org/breweries?by_state=california&by_county=ventura';
    private $breweries = [];

    public function __construct() {
        $this->breweries = json_decode(file_get_contents($this->url), true);
    }

    public function getBreweries() {
        return $this->breweries;
    }
}

$breweries = new Breweries();
print_r($breweries->getBreweries());
