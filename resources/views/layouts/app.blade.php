<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  @livewireStyles
  @stack('css')
  <style>
    th {
      position: -webkit-sticky;
      position: sticky;
      top: 0;
      z-index: 2;
      background-color: white;
      white-space: nowrap;
    }


    .span-error {
      color: #c22e00;
      font-size: 12px;
    }

  </style>

  <title>Finance App</title>
</head>

<body class="c-app">
  @include('partials.sidebar')
  <div class="c-wrapper c-fixed-components" style="max-height: 100%;">
    @include('partials.header')
    <div class="c-body">
      <main class="c-main">
        <div class="container-fluid">
          <div class="fade-in">
            @yield('content')
          </div>
        </div>
      </main>
    </div>
    @include('partials.footer')
  </div>

  <script src="js/main.js"></script>


  @stack('scripts')

  @livewireScripts


  <script>
    window.addEventListener('closeModalOfHomeLoan', event => {
      $("#updateHomeLoan").modal('hide');
      $(".modal-backdrop.show").remove();
    });

    window.addEventListener('closeModalOfInvestPersonal', event => {
      $("#updateInvestPersonal").modal('hide');
        $(".modal-backdrop.show").remove();
    });
    window.addEventListener('closeModalOfInvestmentSuper', event => {
      $("#updateInvestmentSuper").modal('hide');
        $(".modal-backdrop.show").remove();
    });
    window.addEventListener('closeModalOfLongTermInvestment', event => {
      $("#updateLongTermInvestment").modal('hide');
        $(".modal-backdrop.show").remove();
    });

    window.addEventListener('updatedChart', (event) => {
      console.log(event.detail);
      // var ctx = document.getElementById("canvas").getContext("2d")
      console.log(window.myLinechart);
      //   console.log(window.lineChartData.datasets);
      //   window.myLinechart.data.labels.push(label);
      window.myLinechart.config.data.labels = event.detail.months;
      window.myLinechart.config.data.datasets.forEach((dataset, index) => {
        if (dataset.label == "Total Debt") {
          window.myLinechart.config.data.datasets[index].data = event.detail.total_debts;
        }
        if (dataset.label == "Total Assets") {
          window.myLinechart.config.data.datasets[index].data = event.detail.total_assets;
        }
        if (dataset.label == "Difference") {
          window.myLinechart.config.data.datasets[index].data = event.detail.differences;
        }
        if (dataset.label == "Differences + Super") {
          window.myLinechart.config.data.datasets[index].data = event.detail.differences_vs_super;
        }
        if (dataset.label == "Running diff - Cash + Equity") {
          window.myLinechart.config.data.datasets[index].data = event.detail.differences_minus_cash_plus_equity;
        }
        if (dataset.label == "Running diff - overall") {
          window.myLinechart.config.data.datasets[index].data = event.detail.differences_minus_overall;
        }
      });;
      window.myLinechart.update();
      //   $("#updateHomeLoan").modal('hide');
    })
  </script>



</body>

</html>
