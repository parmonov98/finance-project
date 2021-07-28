<div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">
  <div class="c-sidebar-brand d-lg-down-none">
    Finance App
  </div>
  <ul class="c-sidebar-nav ps">

    <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="{{ route('homeloan.show') }}">
        <svg class="c-sidebar-nav-icon">
          <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-speedometer"></use>
        </svg>Home Loan</a>
    </li>

    <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="{{ route('investpersonal.show') }}">
        <svg class="c-sidebar-nav-icon">
          <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-speedometer"></use>
        </svg>Invest Personal</a>
    </li>

    <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="{{ route('programsuper.show') }}">
        <svg class="c-sidebar-nav-icon">
          <use xlink:href="vendors/@coreui/icons/svg/f    ree.svg#cil-speedometer"></use>
        </svg>Super</a>
    </li>

    <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="{{ route('longterminvestment.show') }}">
        <svg class="c-sidebar-nav-icon">
          <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-speedometer"></use>
        </svg>Long Term Investment</a>
    </li>

    <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="{{ route('5yrnetworth.show') }}">
        <svg class="c-sidebar-nav-icon">
          <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-speedometer"></use>
        </svg>5 Year Networth</a>
    </li>

    {{-- <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="{{ route('programpay.show') }}">
        <svg class="c-sidebar-nav-icon">
          <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-speedometer"></use>
        </svg> Pay</a>
    </li> --}}

    <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="{{ route('monthlynetworth.show') }}">
        <svg class="c-sidebar-nav-icon">
          <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-speedometer"></use>
        </svg>Monthly Networth</a>
    </li>
    <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="{{ route('chart.show') }}">
        <svg class="c-sidebar-nav-icon">
          <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-speedometer"></use>
        </svg>Chart</a>
    </li>

    <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="{{ route('logout') }}"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <svg class="c-sidebar-nav-icon">
          <use id="test" xlink:href="vendors/@coreui/icons/svg/free.svg#cil-speedometer"></use>
        </svg>Logout</a>
    </li>
  </ul>
  <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent"
    data-class="c-sidebar-minimized"></button>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
  @csrf
</form>


<style>
  .c-sidebar-nav-item:hover {
    background-color: white;
  }

  .c-sidebar-nav-link:hover {
    background-color: white;
  }

  .c-sidebar-nav-link {
    background-color: white;
  }

</style>
