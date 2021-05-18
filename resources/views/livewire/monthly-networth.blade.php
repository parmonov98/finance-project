<div>
    <div class="card">
        <div class="card-header"><i class="fa fa-align-justify"></i> Striped Table</div>
        <div class="card-body">
            <div class="tableFixHead">
                <!-- <table class="table table-responsive- table-striped">
                    <thead>
                        <tr>
                            <th scope="row">Date</th>
                            @for($i=0;$i<12;$i++) <th scope="row">2012/01/01</th>
                                @endfor

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2020-02-02</td>
                        </tr>
                        <td>2012/01/01</td>
                        <td>$235,000.00</td>
                        <td>$990.77</td>
                        <td>$1,200.00</td>
                        <td>$2,190.77</td>
                        <td>$1,630.27</td>
                        <td>$587.50</td>
                        <td>$233,369.73</td>
                        <td>No Data</td>

                    </tbody>
                </table> -->
                <style>
                    .table-header-bold{
                        font-weight: 700; 
                        border-bottom: none;
                        background-color: #f9f9f9; 
                        border-radius: 10%; 
                    }
                    .table-header{
                        border-top: none;
                        margin-left: 20px;
                        padding-left: 20px;
                        font-weight: 100;
                        border-top: none;
                        margin-left: 
                    }
                </style>

                <table class="table table-responsive"  style=" white-space: nowrap;">
                    <tr>
                        <th class="table-header-bold">Debt</th>
                    </tr>
                    <tr>
                        <th class="table-header">Date</th>
                        @for($i=0;$i<24;$i++)
                            <td>02/02/2020</td>
                        @endfor
                    </tr>
                    <tr>
                        <th class="table-header">House Loan</th>
                        @for($i=0;$i<24;$i++)
                            <td>$ 50</td>
                        @endfor
                    </tr>


                    <tr>
                        <th></th>
                        @for($i=0;$i<24;$i++)
                            <td></td>
                        @endfor
                    </tr>

                    <tr>
                        <th class="table-header-bold">Assets</th>
                    </tr>
                    <tr>
                        <th>Home</th>
                        @for($i=0;$i<24;$i++)
                            <td>$ 669</td>
                        @endfor
                    </tr>
                    <tr>
                        <th>Investment Super</th>
                        @for($i=0;$i<24;$i++)
                            <td>$ 224</td>
                        @endfor
                    </tr>
                    <tr>
                        <th>Cash</th>
                        @for($i=0;$i<24;$i++)
                            <td>$ 11.00</td>
                        @endfor
                    </tr>
                    <tr>
                        <th>Investment Personal</th>
                        @for($i=0;$i<24;$i++)
                            <td>$ 150</td>
                        @endfor
                    </tr>
                    <tr>
                        <th>Long Term Investment</th>
                        @for($i=0;$i<24;$i++)
                            <td>$ 2,696</td>
                        @endfor
                    </tr>
                    <tr>
                        <th>Other Investments</th>
                        @for($i=0;$i<24;$i++)
                            <td>$ 1,000</td>
                        @endfor
                    </tr>

                    <tr>
                        <th></th>
                        @for($i=0;$i<24;$i++)
                            <td></td>
                        @endfor
                    </tr>

                    <tr>
                        <th class="table-header-bold">Summary</th>
                    </tr>
                    <tr>
                        <th>Total Debt</th>
                        @for($i=0;$i<24;$i++)
                            <td>$ 888</td>
                        @endfor
                    </tr>

                    <tr>
                        <th>Total Assets</th>
                        @for($i=0;$i<24;$i++)
                            <td>$ 1,217</td>
                        @endfor
                    </tr>

                    <tr>
                        <th>Difference</th>
                        @for($i=0;$i<24;$i++)
                            <td>$ 383</td>
                        @endfor
                    </tr>

                    <tr>
                        <th>Difference - Super</th>
                        @for($i=0;$i<24;$i++)
                            <td>$ 200</td>
                        @endfor
                    </tr>

                    <tr>
                        <th style="background-color: #e5fbff;">Running Diff - Cash + Equity</th>
                        @for($i=0;$i<24;$i++)
                            <td>$ 142</td>
                        @endfor
                    </tr>

                    <tr>
                        <th style="background-color: #e5fbff;">Running Diff - Overrall</th>
                        @for($i=0;$i<24;$i++)
                            <td>$ 165</td>
                        @endfor
                    </tr>



                </table>

            </div>
            
        </div>
    </div>
</div>