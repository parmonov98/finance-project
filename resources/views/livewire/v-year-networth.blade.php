<div>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-warning p-3">
                <h4>Visit here after first monthly networths calculation to fix initial data as approx data</h4>
            </div>
        </div>

    </div>

    <div class="card">
        <div class="card-header">
            Program Super Table
            <span class="float-right">
                <div class="form-group row">
                    <select wire:model="show"  class="form-control" id="ccyear">
                        <option value="0.5">6 Months</option>
                        <option value="1">1 Year</option>
                        <option value="3">3 Years</option>
                        <option value="5">5 Years</option>
                        <option value="1000">All time</option>
                    </select>
                </div>
            </span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-responsive-xl table-responsive-lg table-responsive-md table-responsive-sm table-responsive-xs  table-striped">
                    <thead>
                        <tr>
                            <th>Quick Home Loan Repay</th>
                            <th>House Loan</th>
                            <th>Home Worth</th>
                            <th>Investment Super</th>
                            <th>Cash</th>
                            <th>Investment Personal</th>
                            <th>Long Term Investment</th>
                            <th>Total Debt</th>
                            <th>Total Assets</th>
                            <th>Difference</th>
                            <th>Difference - Super</th>
                        </tr>
                    </thead>
                    <tbody>

                        @php

                        foreach($home_loans as $key => $home_loan)
                        {

                            if(isset($programVYear[$key]->home_worth) && isset($programVYear[$key]->long_term_invest) && isset($programVYear[$key]->house_loan) && isset($programVYear[$key]->invest_super))
                            {
                                $total_assets_real = $programVYear[$key]->home_worth + $programVYear[$key]->long_term_invest;
                                $difference_real = $total_assets_real - $programVYear[$key]->house_loan;
                                $difference_super_real = $total_assets_real - $programVYear[$key]->house_loan - $programVYear[$key]->invest_super;
                            }



                            echo ' <tr> ' ;

                                if(isset($programVYear[$key]->date))
                                    echo '<td> '. $programVYear[$key]->date .  ' (approx)</td>';
                                else
                                    echo '<td> No data</td>';

                                if(isset($programVYear[$key]->house_loan))
                                    echo '<td>$' . number_format($programVYear[$key]->house_loan, 2) . '</td>';
                                else
                                    echo '<td> No data</td>';

                                if(isset($programVYear[$key]->home_worth))
                                    echo '<td>$' . number_format($programVYear[$key]->home_worth, 2) . ' </td>';
                                else
                                    echo '<td> No data</td>';

                                if(isset($programVYear[$key]->invest_super))
                                    echo '<td>$' . number_format($programVYear[$key]->invest_super, 2) . ' </td>';
                                else
                                    echo '<td> No data</td>';

                                if(isset($programVYear[$key]->cash))
                                    echo '<td>$' . number_format($programVYear[$key]->cash, 2) . '   </td>';
                                else
                                    echo '<td> No data</td>';

                                if(isset($programVYear[$key]->invest_personal))
                                    echo '<td>$' . number_format($programVYear[$key]->invest_personal, 2) . '</td>';
                                else
                                    echo '<td> No data</td>';

                                if(isset($programVYear[$key]->long_term_invest))
                                    echo '<td>$' . number_format($programVYear[$key]->long_term_invest, 2) . ' </td>';
                                else
                                    echo '<td> No data</td>';

                                if(isset($programVYear[$key]->total_debt))
                                    echo '<td>$' . number_format($programVYear[$key]->total_debt, 2) .  ' </td>';
                                else
                                    echo '<td> No data</td>';

                                if(isset($programVYear[$key]->total_assets))
                                    echo '<td>$' . number_format($programVYear[$key]->total_assets, 2) .  ' </td>';
                                else
                                    echo '<td> No data</td>';

                                if(isset($programVYear[$key]->difference))
                                    echo '<td>$' . number_format($programVYear[$key]->difference, 2) .  ' </td>';
                                else
                                    echo '<td> No data </td>';

                                if(isset($programVYear[$key]->difference_minus_super))
                                    echo '<td>$' . number_format($programVYear[$key]->difference_minus_super, 2) .  ' </td>';
                                else
                                    echo '<td> No data </td>';

                            echo '</tr> ';




                            echo ' <tr> ' ;

                                if(isset($home_loans[$key]->pay_date))
                                    echo '<td><B> '. $home_loans[$key]->pay_date . '<B></td>';
                                else
                                    echo '<td> No data (approx)</td>';

                                if(isset($home_loans[$key]->beg_balance))
                                    echo '<td><B>$' . number_format($home_loans[$key]->beg_balance, 2) . '<B></td>';
                                else
                                    echo '<td> No data</td>';

                                if(isset($monthlyNetworths[$key]->home_value))
                                    echo '<td><B>$' . number_format($monthlyNetworths[$key]->home_value, 2) . '<B></td>';
                                else
                                    echo '<td> No data</td>';

                                if(isset($investSupers[$key]->total_invested))
                                    echo '<td><B>$' . number_format($investSupers[$key]->total_invested, 2) . '<B></td>';
                                else
                                    echo '<td> No data</td>';

                                if(isset($monthlyNetworths[$key]->cash))
                                    echo '<td><B>$' . number_format($monthlyNetworths[$key]->cash, 2) . '  <B></td>';
                                else
                                    echo '<td> No data</td>';

                                if(isset($investPersonals[$key]->total_invested))
                                    echo '<td><B>$' . number_format($investPersonals[$key]->total_invested, 2) . '<B></td>';
                                else
                                    echo '<td> No data</td>';

                                if(isset($longTermInvests[$key]->total_invested))
                                    echo '<td><B>$' . number_format($longTermInvests[$key]->total_invested, 2) . '<B></td>';
                                else
                                    echo '<td> No data</td>';

                                if(isset($home_loans[$key]->beg_balance))
                                    echo '<td><B>$' . number_format($home_loans[$key]->beg_balance, 2) .  '<B></td>';
                                else
                                    echo '<td> No data</td>';

                                if(isset($monthlyNetworths[$key]->assets))
                                    echo '<td><B>$' . number_format($monthlyNetworths[$key]->assets, 2) .  '<B></td>';
                                else
                                    echo '<td> No data</td>';

                                if(isset($monthlyNetworths[$key]->difference))
                                    echo '<td><B>$' . number_format($monthlyNetworths[$key]->difference, 2) .  '<B></td>';
                                else
                                    echo '<td> No data</td>';

                                if(isset($monthlyNetworths[$key]->difference_super))
                                    echo '<td><B>$' . number_format($monthlyNetworths[$key]->difference_super, 2) .  '<B></td>';
                                else
                                    echo '<td> No data</td>';

                            echo '</tr> ';



                        }

                        @endphp
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
