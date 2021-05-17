<div>
     <!-- First row of the two cards -->
     <div class="row">
          <!-- First Card -->
          <div class="col-sm-12 col-md-6">
               <div class="card">
                    <div class="card-header"><strong>Amortization Calculator</strong> <small>Input Data</small></div>
                    <div class="card-body">
                         <div class="row">
                              <div class="col-sm-12 col-md-6">
                                   <div class="form-group">
                                        <label for="name">Loan Amount</label>
                                        <input type="text" class="form-control numeric" placeholder="Enter your loan amount" id="cc" type="text" data-inputmask="'mask': '9999 9999 9999 9999'" >
                                   </div>
                              </div>
                              <div class="col-sm-12 col-md-6">
                                   <div class="form-group">
                                        <label for="ccnumber">Annual Interest Rate (%)</label>
                                        <input class="form-control" placeholder="3">
                                   </div>
                              </div>
                         </div>

                         <div class="row">
                              <div class="col-sm-12 col-md-6">
                                   <div class="form-group">
                                        <label for="ccnumber">Loan Period</label>
                                        <input class="form-control" placeholder="30 (In Years)">
                                   </div>
                              </div>
                              <div class="col-sm-12 col-md-6">
                                   <div class="form-group">
                                        <label for="ccnumber">Number Of Payments</label>
                                        <input class="form-control" placeholder="30 (Per year)">
                                   </div>
                              </div>
                         </div>

                         <div class="row">
                              <div class="col-sm-12 col-md-6">
                                   <div class="form-group">
                                        <div class="form-group">
                                             <label for="ccnumber">Start Date Of Loan</label>
                                             <input class="form-control" placeholder="01/01/2020">
                                        </div>
                                   </div>
                              </div>
                              <div class="col-sm-12 col-md-6">
                                   <div class="form-group">
                                        <div class="form-group">
                                             <label for="ccnumber">Extra Payments</label>
                                             <input class="form-control" placeholder="Optional">
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
                    <div class="card-footer">
                         <button class="btn btn-sm btn-primary" type="submit"> Submit</button>
                         <button class="btn btn-sm btn-danger" type="reset"> Reset</button>
                    </div>
               </div>
          </div>
          <!-- Second Card -->
          <div class="col-sm-12 col-md-6">
               <div class="card">
                    <div class="card-header"><strong>Loan Summary</strong> <small>Output Data</small></div>
                    <div class="card-body">
                         <div class="row">
                              <div class="col-sm-12">
                                   <div class="form-group">
                                        <label for="name">Scheduled Payment</label>
                                        <input class="form-control" placeholder="No data" disabled>
                                   </div>
                              </div>

                         </div>
                         <div class="row">
                              <div class="col-sm-12 col-md-6">
                                   <div class="form-group">
                                        <label for="ccnumber">Scheduled No Of Pay</label>
                                        <input class="form-control" placeholder="No Data" disabled>
                                   </div>
                              </div>
                              <div class="col-sm-12 col-md-6">
                                   <div class="form-group">
                                        <label for="ccnumber">Actual No Of Payments</label>
                                        <input class="form-control" placeholder="No Data" disabled>
                                   </div>
                              </div>

                         </div>

                         <div class="row">
                              <div class="col-sm-12 col-md-6">
                                   <div class="form-group">
                                        <label for="ccnumber">Total early Pay</label>
                                        <input class="form-control" placeholder="No Data" disabled>
                                   </div>
                              </div>
                              <div class="col-sm-12 col-md-6">
                                   <div class="form-group">
                                        <div class="form-group">
                                             <label for="ccnumber">Total Interest</label>
                                             <input class="form-control" placeholder="No Data" disabled>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
     <!-- Second row for the tables -->
     <div class="card">
          <div class="card-header"><i class="fa fa-align-justify"></i> Striped Table</div>
          <div class="card-body">
               <div class="tableFixHead">
                    <table class="table table-responsive-sm table-striped">
                         <thead>
                              <tr>
                                   <th>PMT No</th>
                                   <th>PMT Date</th>
                                   <th>Beg Balance</th>
                                   <th>SCH Pay</th>
                                   <th>EXT Pay</th>
                                   <th>TOT Pay</th>
                                   <th>Principal</th>
                                   <th>Interest</th>
                                   <th>End Balance</th>
                                   <th>CUM INTRST</th>
                              </tr>
                         </thead>
                         <tbody>
                              @for($i=0; $i<30 ; $i++) <tr>
                                   <td>1</td>
                                   <td>2012/01/01</td>
                                   <td>$235,000.00</td>
                                   <td>$990.77</td>
                                   <td>$1,200.00</td>
                                   <td>$2,190.77</td>
                                   <td>$1,630.27</td>
                                   <td>$587.50</td>
                                   <td>$233,369.73</td>
                                   <td>No Data</td>
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