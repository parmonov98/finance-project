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
      var months = <?php echo $yearText; ?>;

      var year = months.map(item => item.toString());
      console.log(year);
      var total_debts = <?php echo $total_debts; ?>;
      var total_assets = <?php echo $total_assets; ?>;
      var differences = <?php echo $differences; ?>;
      var differences_vs_super = <?php echo $differences_vs_super; ?>;
      var runningDiff_minus_cash_plus_equity = <?php echo $runningDiff_minus_cash_plus_equity; ?>;
      var runningDiff_minus_overall = <?php echo $runningDiff_minus_overall; ?>;


      window.lineChartData = {
        labels: year,
        datasets: [{
          label: 'Total Debt',

          backgroundColor: "transparent",
          color: "blue",
          borderColor: 'rgb(75, 192, 192)',
          data: total_debts
        }, {
          label: 'Total Assets',
          backgroundColor: "transparent",
          borderColor: 'rgb(105, 0, 0)',
          color: "red",
          data: total_assets
        }, {
          label: 'Difference',
          backgroundColor: "transparent",
          borderColor: 'rgb(175, 92, 92)',
          color: "red",
          data: differences
        }, {
          label: 'Differences + Super',
          backgroundColor: "transparent",
          borderColor: 'rgb(175, 175, 175)',
          color: "red",
          data: differences_vs_super
        }, {
          label: 'Running diff - Cash + Equity',
          backgroundColor: "transparent",
          borderColor: 'rgb(5, 5, 75)',
          color: "red",
          data: runningDiff_minus_cash_plus_equity
        }, {
          label: 'Running diff - overall',
          backgroundColor: "transparent",
          borderColor: 'rgb(15, 15, 255)',
          color: "red",
          data: runningDiff_minus_overall
        }]
      };

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

        window.ctx = document.getElementById("canvas").getContext("2d");
        window.myLinechart = new Chart(window.ctx, {
          type: 'line',
          data: window.lineChartData,
          options: {
            tooltips: {
              mode: 'label',
              label: 'mylabel',
              callbacks: {
                label: toCurrency,
              },
              multiTooltipTemplate: toCurrency,
              tooltipTemplate: toCurrency,
              scaleLabel: toCurrency,
            },
            scales: {
              yAxes: [{
                ticks: {
                  callback: function(label, index, labels) {
                    return '$' + label.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                  },
                  beginAtZero: true,
                  fontSize: 10,
                },
                gridLines: {
                  display: false
                },
                scaleLabel: {
                  display: true,
                  fontSize: 10,
                }
              }],
              xAxes: [{
                ticks: {
                  beginAtZero: true,
                  fontSize: 10
                },
                gridLines: {
                  display: false
                },
                scaleLabel: {
                  display: true,
                  fontSize: 10,
                }
              }]
            },
            elements: {
              rectangle: {
                borderWidth: 2,
                borderColor: 'rgb(0, 255, 0)',
                borderSkipped: 'bottom'
              }
            },
            responsive: true,
            title: {
              display: true,
              text: 'Statistics'
            }
          }
        });

      };
    </script>

    <canvas wire:ignore id="canvas" height="280" width="600"></canvas>
  </div>

</div>
