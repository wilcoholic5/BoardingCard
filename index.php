<?php

abstract class BoardingCard {
    public $destination;
    public $origin;
    public $transportationType = null;

    public function __construct(
        $origin,
        $destination
    )
    {
        $this->destination = $destination;
        $this->origin = $origin;
    }

    abstract function printCardInfo();
}

class FlightCard extends BoardingCard {
    use HasSeatAssignment, HasUniqueNumber;
    // to keep file small, making these public so getters/setters arent necessary.
    public $transportationType = 'flight';
    public $gate;
    public $ticketCounter;

    public function __construct(
        $origin,
        $destination,
        $gate,
        $seatAssignment,
        $uniqueNumber,
        $ticketCounter = null
    )
    {
        parent::__construct($origin, $destination);
        $this->gate = $gate;
        $this->ticketCounter = $ticketCounter;
        $this->seatAssignment = $seatAssignment;
        $this->uniqueNumber = $uniqueNumber;
    }

    public function printCardInfo()
    {
        echo "From {$this->origin}, take flight {$this->uniqueNumber} to {$this->destination}.";
        echo " {$this->gate}, seat {$this->seatAssignment}";
        if ($this->ticketCounter) {
            echo "Baggage drop at ticket counter {$this->ticketCounter}.";
            echo PHP_EOL;
        }
    }
}

class TrainCard extends BoardingCard {
    use HasSeatAssignment, HasUniqueNumber;
    public $transportationType = 'train';

    public function __construct(
        $origin,
        $destination,
        $seatAssignment,
        $uniqueNumber
    )
    {
        parent::__construct($origin, $destination);
        $this->seatAssignment = $seatAssignment;
        $this->uniqueNumber = $uniqueNumber;
    }

    public function printCardInfo()
    {
        echo "Take {$this->transportationType} {$this->uniqueNumber} from {$this->origin} to {$this->destination}.";
        echo " Sit in seat {$this->seatAssignment}";
        echo PHP_EOL;
    }
}

class AirportBusCard extends BoardingCard {
    public $transportationType = 'airport bus';

    public function printCardInfo()
    {
        echo "Take the {$this->transportationType} from {$this->origin} to {$this->destination}. No seat assignment.";
        echo PHP_EOL;
    }
}


trait HasSeatAssignment {
    public $seatAssignment;
}

trait HasUniqueNumber {
    public $uniqueNumber;
}

class ItinerarySort {
    public $sortedItinerary = [];
    public function sort($boardingCards)
    {
        $destinations = [];
        $origins = [];
        $cardLink = [];
        foreach ($boardingCards as $boardingCard) {
            $destinations[] = $boardingCard->destination;
            $origins[] = $boardingCard->origin;
            $cardLink[$boardingCard->origin] = $boardingCard;
        }
        $startingPoint = array_diff($origins, $destinations)[0];
        $end = array_diff($destinations, $origins)[0];
        for ($i = 0; $i < count($cardLink); $i++) {
            // Ran out of time
//            $this->sortedItinerary[] = $cardLink[$startingPoint];
        }
    }

    public function printItinerary()
    {
        foreach ($this->sortedItinerary as $card) {
            $card->printCardInfo();
        }
    }
}

$boardingCard1 = new TrainCard(
    'Madrid',
    'Barcelona',
    '45B',
    '78A'
);

$boardingCard2 = new AirportBusCard(
    'Barcelona',
    'Gerona Airport'
);

$boardingCard3 = new FlightCard(
    'Gerona Airport',
    'Stockholm',
    '45B',
    '7B',
    'SK455',
    '344'

);

$boardingCard4 = new FlightCard(
    'Stockholm',
    'New York JFK',
    '22',
    '7B',
    'SK22'
);

$lib = new ItinerarySort();
$cards = [
    $boardingCard4,
    $boardingCard1,
    $boardingCard3,
    $boardingCard2,
];

$lib->sort($cards);
$lib->printItinerary();