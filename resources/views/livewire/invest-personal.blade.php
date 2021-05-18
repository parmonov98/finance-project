<div>
    <div class="card">
        <div class="card-header"><strong>Credit Card</strong> <small>Form</small></div>
        <div class="card-body">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-lg-6 col-xl-6">
                    <div class="row">
                        <div class="col-6">
                            <label for="ccnumber">Return on invest (Min)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">0</span>
                                    <span class="input-group-text">%</span>
                                </div>
                                <input type="number" class="form-control" min="-2" max="0">
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="ccnumber">Return on invest (Max)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">0</span>
                                    <span class="input-group-text">%</span>
                                </div>
                                <input type="number" class="form-control" min="0" max="10">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-lg-6 col-xl-6">
                    <div class="form-group">
                        <label for="ccnumber">Inflation</label>
                        <input class="form-control" id="ccnumber" type="text" placeholder="0000 0000 0000 0000">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="form-group">
                        <label for="ccnumber">Fees/Taxes</label>
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
                    @for($i=0; $i<50; $i++) <tr>
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