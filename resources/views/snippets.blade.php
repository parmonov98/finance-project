$months = $nb_payments*$period;
$interest = $interest_rate / 1200;
$amount = $interest * -$loan_amount * pow((1 + $interest), $months) / (1 - pow((1 + $interest), $months));
number_format($amount, 2);

dd("Your payment will ;" . round($amount,2) . " a month, for " . $months . " months");



$sch_payment = ($interest_rate*$loan_amount)/($nb_payments*(1-(pow((1+($interest_rate/$nb_payments)), -$nb_payments*$period ))));