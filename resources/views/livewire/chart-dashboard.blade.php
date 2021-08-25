<div class="bg-white p-4">

  <div class="row">
    <select wire:model="year" name="currect_year">
      {{-- <option>current year</option> --}}
      @foreach ($years as $item)
        <option value="{{ $item }}">{{ $item }}</option>
      @endforeach
    </select>
  </div>
  <div class="row">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <script>
      var dates = <?php echo $yearText; ?>;

      var years = dates.map(item => item.toString());

      var total_debts = <?php echo $total_debts ?? ''; ?>;
      {{--var total_assets = <?php echo $total_assets ?? ''; ?>;--}}
      {{--var differences = <?php echo $differences; ?>;--}}
      {{--var differences_vs_super = <?php echo $differences_vs_super; ?>;--}}
      {{--var runningDiff_minus_cash_plus_equity = <?php echo $runningDiff_minus_cash_plus_equity; ?>;--}}
      {{--var runningDiff_minus_overall = <?php echo $runningDiff_minus_overall; ?>;--}}


      window.barChartData = [
          {
              label: 'Total Debt',
              data: total_debts,
              borderColor: 'black',
              backgroundColor: '#ff0000',
          },
          {
              label: 'Total Assets',
              data: total_debts,
              borderColor: 'black',
              backgroundColor: '#92d050',
          },
          {
              label: 'Difference',
              data: total_debts,
              borderColor: 'black',
              backgroundColor: '#00b050',
          },
          {
              label: 'Difference - Super',
              data: total_debts,
              borderColor: 'black',
              backgroundColor: '#505050',
          }
      ];

      window.onload = function() {
        function addCommas(nStr) {
          nStr += '';
          x = nStr.split('.');
          x1 = x[0];
          x2 = x.length > 1 ? '.' + x[1] : '';
          var rgx = /(\d+)(\d{3})/;
          while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
          }
          return x1 + x2;
        }

        function toCurrency(label) {
          return '$' + label.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

      const DATA_COUNT = 7;
      const NUMBER_CFG = {count: DATA_COUNT, min: -100, max: 100};

      const data = {
          labels: years,
          datasets: window.barChartData
      };

        window.ctx = document.getElementById("canvas").getContext("2d");
        window.myLinechart = new Chart(window.ctx, {
          type: 'bar',
          data: data,
          options: {
              responsive: true,
              plugins: {
                  legend: {
                      position: 'top',
                  },
                  title: {
                      display: true,
                      text: 'Chart.js Bar Chart'
                  }
              }
          }
        });

      };
    </script>

    <canvas wire:ignore id="canvas" height="280" width="600"></canvas>
  </div>

</div>
