<?php
$principal = 100000.00; // initial balance
$interestRate = 0.05; // 5% interest rate
$withdrawal = 537.00; // withdrawal amount per month

// Initialize variables
$months = 0;
$earnedInterest = 0;

echo "<table border='1'>
      <tr>
      <th>Month</th>
      <th>Balance</th>
      <th>Interest Earned</th>
      <th>Withdrawal</th>
      </tr>";

while ($principal > 0) {
    $earnedInterest = round($principal * $interestRate / 12, 2);
    $principal += $earnedInterest - $withdrawal;
    $months++;

    echo "<tr>
          <td>$months</td>
          <td>$" . number_format($principal, 2) . "</td>
          <td>$" . number_format($earnedInterest, 2) . "</td>
          <td>$" . number_format($withdrawal, 2) . "</td>
          </tr>";

    if ($principal < $withdrawal) {
        echo "<tr>
              <td colspan='4'>Insufficient funds for next withdrawal. Account closed.</td>
              </tr>";
        break;
    }
}

echo "</table>";
?>
