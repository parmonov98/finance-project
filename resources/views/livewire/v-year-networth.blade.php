<div>
    <div class="card">
        <div class="card-header"><strong>Credit Card</strong> <small>Form</small></div>
        <div class="card-body">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="form-group">
                        <label for="name">Quick Home Loan Repay</label>
                        <input class="form-control" id="name" type="text" placeholder="Enter your name">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="form-group">
                        <label for="ccnumber">Debt</label>
                        <input class="form-control" id="ccnumber" type="text" placeholder="0000 0000 0000 0000">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="form-group">
                        <label for="ccnumber">House Loan</label>
                        <input class="form-control" id="ccnumber" type="text" placeholder="0000 0000 0000 0000">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="form-group">
                        <label for="ccnumber">Investment Super</label>
                        <input class="form-control" id="ccnumber" type="text" placeholder="0000 0000 0000 0000">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="form-group">
                        <label for="ccnumber">Cash</label>
                        <input class="form-control" id="ccnumber" type="text" placeholder="0000 0000 0000 0000">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="form-group">
                        <label for="ccnumber">Invest Personal</label>
                        <input class="form-control" id="ccnumber" type="text" placeholder="0000 0000 0000 0000">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="form-group">
                        <label for="ccnumber">Long Term Investment</label>
                        <input class="form-control" id="ccnumber" type="text" placeholder="0000 0000 0000 0000">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="form-group">
                        <label for="ccnumber">Total Debt</label>
                        <input class="form-control" id="ccnumber" type="text" placeholder="0000 0000 0000 0000">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="form-group">
                        <label for="ccnumber">Total Assets</label>
                        <input class="form-control" id="ccnumber" type="text" placeholder="0000 0000 0000 0000">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="form-group">
                        <label for="ccnumber">Difference</label>
                        <input class="form-control" id="ccnumber" type="text" placeholder="0000 0000 0000 0000">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="form-group">
                        <label for="ccnumber">Difference Super</label>
                        <input class="form-control" id="ccnumber" type="text" placeholder="0000 0000 0000 0000">
                    </div>
                </div>

            </div>

        </div>

        <div class="card-footer">
            <button class="btn btn-sm btn-primary" type="submit"> Submit</button>
            <button class="btn btn-sm btn-danger" type="reset"> Reset</button>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><i class="fa fa-align-justify"></i> Program Super Table</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-responsive-sm table-striped">
                    <thead>
                        <tr>
                            <th>Quick Home Loan Repay</th>
                            <th>Debt</th>
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
                        @for($i=0; $i<50; $i++) <tr>
                            <td>6-12-2020 (approx)</td>
                            <td></td>
                            <td>$231,789.45</td>
                            <td>$250,000.00</td>
                            <td>$451.58</td>
                            <td></td>
                            <td>$451.58</td>
                            <td>$451.58</td>
                            <td>$231,789.45</td>
                            <td>$251,354.75</td>
                            <td>19,565.30</td>
                            <td>19,113.71</td>
                            </tr>
                            @endfor
                    </tbody>
                </table>
            </div>

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