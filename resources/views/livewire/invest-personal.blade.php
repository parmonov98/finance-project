<div>
    <div class="card">
        <div class="card-header"><i class="fa fa-align-justify"></i> Invest Personal Table</div>
        <div class="card-body">
            <table class="table table-responsive-sm table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Return on Invest</th>
                        <th>Fees/Taxes</th>
                        <th>Monthly Acc Fee</th>
                        <th>Inflation</th>
                        <th>Monthly Invest</th>
                        <th>Interest</th>
                        <th>Fees/Taxes</th>
                        <th>Total Invested</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i=0; $i<50; $i++)
                        <tr>
                            <td>6-12-2020</td>
                            <td>4%</td>
                            <td>0.20%</td>
                            <td>$50.00</td>
                            <td> </td>
                            <td>$500.00</td>
                            <td>$1.67</td>
                            <td>$50.08</td>
                            <td>$451.58</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="#">Prev</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">4</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </div>
    </div>
</div>